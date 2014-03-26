<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeTime', 'Utility');
	App::uses('Xml', 'Utility');
	App::uses('HttpSocket', 'Network/Http');

	class CasesController extends AppController {

		public function beforeFilter() {
			parent::beforeFilter();
		}

		public function index($userId = null) {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}

			$auth = $this->Session->read('Auth');

			if (!$userId) {
				return $this->redirect(array('controller' => 'cases', 'action' => 'index', $auth['id']));
			}

			$usersRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=listPeople';
			$usersResponseXml = Xml::build($usersRequestUrl);
			$usersResponse = Xml::toArray($usersResponseXml);
			$users = array();
			foreach ($usersResponse['response']['people']['person'] as $user) {
				$users[$user['ixPerson']] = $user['sFullName'];
			}

			$resolvedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=resolvedby:"' . $users[$userId] . '" resolved:"-7d.." orderBy:"resolved"&cols=sTitle,dtResolved';
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
			if (!empty($resolvedCases)) {
				foreach ($resolvedCases as $resolvedCase) {
					$completed[CakeTime::format($resolvedCase['dtResolved'])][$resolvedCase['@ixBug']] = $resolvedCase['sTitle'];
				}
			}

			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=assignedTo:"' . $users[$userId] . '" status:"active (dev)" orderBy:"ixBug"&cols=sTitle';
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

			$workingOn = array();
			if (!empty($activeDevCases)) {
				foreach ($activeDevCases as $activeDevCase) {
					$workingOn[$activeDevCase['@ixBug']] = $activeDevCase['sTitle'];
				}
			}

			$this->request->data['filter']['user_id'] = $userId;

			$this->set(compact('users', 'completed', 'workingOn'));
			$this->set('_serialize', array('completed', 'workingOn'));
		}

	}
