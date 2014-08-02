<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeTime', 'Utility');
	App::uses('Xml', 'Utility');
	App::uses('HttpSocket', 'Network/Http');

	class CasesController extends AppController {

		var $uses = array('User');

		public function beforeFilter() {
			parent::beforeFilter();
		}

		public function index() {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$auth = $this->Session->read('Auth');
			$cols = 'ixBug,sTitle,dtResolved,sProject,events,ixPersonResolvedBy,ixPersonAssignedTo,hrsElapsed,hrsCurrEst';
			$resolvedStatuses = array(
				'Resolved (Completed)',
				'Resolved (Deployed to QA)',
				'Resolved (QA Review)',
				'Resolved (Verified on QA)',
				'Resolved (Development Complete)',
				'Resolved (Duplicate)',
				'Resolved (Not Reproducible)',
				'Resolved (By Design)',
				'Call Client & Close'
			);
			$users = $this->User->find('all');
			$editedByQuery = array();
			$assignedToQuery = array();
			$scrum = array();
			if ($this->request->is('post')) {
				$data = $this->request->data;
				$date = str_replace(array('scrum-', '.json'), '', $data['Archive']['file']['name']);
				if (($archive = file_get_contents($data['Archive']['file']['tmp_name'])) !== false) {
					$archiveDecoded = json_decode($archive, true);
					$scrum = $archiveDecoded['scrum'];
				}
			} else {
				$date = 'now';
				foreach ($users as $user) {
					$editedByQuery[] = 'editedBy:"' . $user['User']['name'] . '"';
					$assignedToQuery[] = 'assignedTo:"' . $user['User']['name'] . '"';
					$scrum[$user['User']['fogbugz_id']] = array('User' => $user['User'], 'Completed' => array(), 'WorkingOn' => array(), 'Blockers' => array());
				}
				if (isset($this->request->named['days'])) {
					$days = $this->request->named['days'];
				} else if ((int) CakeTime::format('now', '%u') === 1) {
					$days = 3;
				} else {
					$days = 1;
				}
				$resolvedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $editedByQuery) . ' edited:"-' . ($days + 1) . 'd.." orderBy:"project,resolved"&cols=' . $cols;
				$resolvedResponseXml = Xml::build($resolvedRequestUrl);
				$resolvedResponse = Xml::toArray($resolvedResponseXml);
				if (isset($resolvedResponse['response']['cases'])) {
					$resolvedCount = $resolvedResponse['response']['cases']['@count'];
				} else {
					$resolvedCount = 0;
				}
				if ((int) $resolvedCount === 0) {
					$resolvedCases = array();
				} else if ((int) $resolvedCount === 1) {
					$resolvedCases = array($resolvedResponse['response']['cases']['case']);
				} else {
					$resolvedCases = $resolvedResponse['response']['cases']['case'];
				}
				if (!empty($resolvedCases)) {
					foreach ($resolvedCases as $resolvedCase) {
						foreach ($resolvedCase['events']['event'] as $event) {
							$resolvedByDate = CakeTime::format($event['dt'], '%G%m%d%H%M') >= CakeTime::format('- ' . $days . ' days', '%G%m%d1030');
							$resolvedEvent = $event['sVerb'] === 'Resolved';
							$resolvedByUser = isset($scrum[$event['ixPerson']]);
							if ($resolvedByDate && $resolvedEvent && $resolvedByUser) {
								$status = 'Resolved (Completed)';
								foreach ($resolvedStatuses as $resolvedStatus) {
									if (strpos($event['sChanges'], 'to \'' . $resolvedStatus . '\'') !== false) {
										$status = $resolvedStatus;
									}
								}
								$scrum[$event['ixPerson']]['Completed'][$resolvedCase['sProject']][$resolvedCase['ixBug']] = array(
									'id' => $resolvedCase['ixBug'],
									'title' => $resolvedCase['sTitle'],
									'project' => $resolvedCase['sProject'],
									'date' => $event['dt'],
									'elapsed' => $resolvedCase['hrsElapsed'],
									'estimate' => $resolvedCase['hrsCurrEst'],
									'events' => $resolvedCase['events']['event'],
									'status' => $status
								);
							}
						}
					}
				}
				$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $assignedToQuery) . ' status:"active (dev)" OR status:"resolved (qa review)" orderBy:"project,ixBug"&cols=' . $cols;
				$activeDevResponseXml = Xml::build($activeDevRequestUrl);
				$activeDevResponse = Xml::toArray($activeDevResponseXml);
				if (isset($activeDevResponse['response']['cases'])) {
					$activeDevCount = $activeDevResponse['response']['cases']['@count'];
				} else {
					$activeDevCount = 0;
				}
				if ((int) $activeDevCount === 0) {
					$activeDevCases = array();
				} else if ((int) $activeDevCount === 1) {
					$activeDevCases = array($activeDevResponse['response']['cases']['case']);
				} else {
					$activeDevCases = $activeDevResponse['response']['cases']['case'];
				}
				if (!empty($activeDevCases)) {
					foreach ($activeDevCases as $activeDevCase) {
						$scrum[$activeDevCase['ixPersonAssignedTo']]['WorkingOn'][$activeDevCase['sProject']][] = array(
							'id' => $activeDevCase['ixBug'],
							'title' => $activeDevCase['sTitle'],
							'project' => $activeDevCase['sProject'],
							'date' => $activeDevCase['dtResolved'],
							'elapsed' => $activeDevCase['hrsElapsed'],
							'estimate' => $activeDevCase['hrsCurrEst'],
							'events' => $activeDevCase['events']['event']
						);
					}
				}
				$activeBlockerRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $assignedToQuery) . ' status:"active (blocker)" orderBy:"ixBug"&cols=' . $cols;
				$activeBlockerResponseXml = Xml::build($activeBlockerRequestUrl);
				$activeBlockerResponse = Xml::toArray($activeBlockerResponseXml);
				if (isset($activeBlockerResponse['response']['cases'])) {
					$activeBlockerCount = $activeBlockerResponse['response']['cases']['@count'];
				} else {
					$activeBlockerCount = 0;
				}
				if ((int) $activeBlockerCount === 0) {
					$activeBlockerCases = array();
				} else if ((int) $activeBlockerCount === 1) {
					$activeBlockerCases = array($activeBlockerResponse['response']['cases']['case']);
				} else {
					$activeBlockerCases = $activeBlockerResponse['response']['cases']['case'];
				}
				if (!empty($activeBlockerCases)) {
					foreach ($activeBlockerCases as $activeBlockerCase) {
						$scrum[$activeBlockerCase['ixPersonAssignedTo']]['Blockers'][$activeBlockerCase['sProject']][] = array(
							'id' => $activeBlockerCase['ixBug'],
							'title' => $activeBlockerCase['sTitle'],
							'project' => $activeBlockerCase['sProject'],
							'date' => $activeBlockerCase['dtResolved'],
							'elapsed' => $activeBlockerCase['hrsElapsed'],
							'estimate' => $activeBlockerCase['hrsCurrEst'],
							'events' => $activeBlockerCase['events']['event']
						);
					}
				}
			}
			$this->set(compact('users', 'date', 'scrum'));
			$this->set('_serialize', array('scrum'));
		}

	}
