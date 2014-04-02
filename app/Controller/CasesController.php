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
			$cols = 'ixBug,sTitle,dtResolved,sProject,events';
			$users = $this->User->find('list');
			$resolvedByQuery = array();
			$assignedToQuery = array();
			foreach ($users as $id => $name) {
				$resolvedByQuery[] = 'resolvedBy:"' . $name . '"';
				$assignedToQuery[] = 'assignedTo:"' . $name . '"';
			}
			$resolvedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $resolvedByQuery) . ' resolved:"-8d.." orderBy:"resolved"&cols=' . $cols;
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
			$completed = array();
			for ($i = 7; $i >= 0; $i--) {
				$date = CakeTime::format('-' . $i . 'days');
				$completed[$date] = array('projects' => array());
				if (CakeTime::format($date) !== CakeTime::format('now')) {
					$completed[$date]['dateFormat'] = '%A <small>%B %e, %Y</small>';
				} else {
					$completed[$date]['dateFormat'] = 'Today <small>%B %e, %Y</small>';
				}
			}
			if (!empty($resolvedCases)) {
				foreach ($resolvedCases as $resolvedCase) {
					$date = CakeTime::format($resolvedCase['dtResolved']);
					if (isset($completed[$date])) {
						$completed[$date]['projects'][$resolvedCase['sProject']][] = array(
							'id' => $resolvedCase['ixBug'],
							'title' => $resolvedCase['sTitle'],
							'project' => $resolvedCase['sProject'],
							'events' => $resolvedCase['events']['event']
						);
					}
				}
			}
			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=' . implode(' OR ', $assignedToQuery) . ' status:"active (dev)" orderBy:"ixBug"&cols=' . $cols;
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
			$workingOn = array('projects' => array());
			if (!empty($activeDevCases)) {
				foreach ($activeDevCases as $activeDevCase) {
					$workingOn['projects'][$activeDevCase['sProject']][] = array(
						'id' => $activeDevCase['ixBug'],
						'title' => $activeDevCase['sTitle'],
						'project' => $activeDevCase['sProject'],
						'events' => $activeDevCase['events']['event']
					);
				}
			}
			$this->set(compact('users', 'completed', 'workingOn'));
			$this->set('_serialize', array('completed', 'workingOn'));
		}

	}
