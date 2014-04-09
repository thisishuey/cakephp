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
			$cols = 'ixBug,sTitle,dtResolved,sProject,events,ixPersonResolvedBy,ixPersonAssignedTo';
			$users = $this->User->find('all');
			$editedByQuery = array();
			$assignedToQuery = array();
			$scrum = array();
			foreach ($users as $user) {
				$editedByQuery[] = 'editedBy:"' . $user['User']['name'] . '"';
				$assignedToQuery[] = 'assignedTo:"' . $user['User']['name'] . '"';
				$scrum[$user['User']['fogbugz_id']] = array('User' => $user['User'], 'Completed' => array(), 'WorkingOn' => array());
			}
			if (isset($this->request->named['days'])) {
				$days = $this->request->named['days'];
			} else if ((int) CakeTime::format('now', '%u') === 1) {
				$days = 3;
			} else {
				$days = 1;
			}
			$resolvedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $editedByQuery) . ' edited:"-' . ($days + 1) . 'd.." orderBy:"project"&cols=' . $cols;
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
						$resolvedByDate = CakeTime::format($event['dt'], '%y%m%d') >= CakeTime::format('- ' . $days . ' days', '%y%m%d');
						$resolvedEvent = $event['sVerb'] === 'Resolved';
						$resolvedByUser = isset($scrum[$event['ixPerson']]);
						if ($resolvedByDate && $resolvedEvent && $resolvedByUser) {
							$scrum[$event['ixPerson']]['Completed'][$resolvedCase['sProject']][$resolvedCase['ixBug']] = array(
								'id' => $resolvedCase['ixBug'],
								'title' => $resolvedCase['sTitle'],
								'project' => $resolvedCase['sProject'],
								'date' => $resolvedCase['dtResolved'],
								'events' => $resolvedCase['events']['event']
							);
						}
					}
				}
			}
			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $assignedToQuery) . ' status:"active (dev)" OR status:"resolved (qa review)" orderBy:"ixBug"&cols=' . $cols;
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
						'events' => $activeDevCase['events']['event']
					);
				}
			}
			$this->set(compact('users', 'scrum'));
			$this->set('_serialize', array('scrum'));
		}

	}
