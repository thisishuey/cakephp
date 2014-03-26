<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeTime', 'Utility');
	App::uses('Xml', 'Utility');
	App::uses('HttpSocket', 'Network/Http');

	class CasesController extends AppController {

		public function beforeFilter() {
			parent::beforeFilter();
		}

		public function index() {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}

			if (isset($this->request->named['name'])) {
				$name = $this->request->named['name'];
			} else {
				$name = 'me';
			}

			$auth = $this->Session->read('Auth');
			$resolvedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=resolvedby:"' . $name . '" resolved:"-7d.." orderBy:"resolved"&cols=sTitle,dtResolved';
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

			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=assignedTo:"' . $name . '" status:"active (dev)" orderBy:"ixBug"&cols=sTitle';
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

			$peopleRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=listPeople';
			$peopleResponseXml = Xml::build($peopleRequestUrl);
			$peopleResponse = Xml::toArray($peopleResponseXml);
			$people = array();
			foreach ($peopleResponse['response']['people']['person'] as $person) {
				$people[$person['sFullName']] = $person['sFullName'];
			}

			if (isset($this->request->named['name'])) {
				$this->request->data['filter']['name'] = $this->request->named['name'];
			} else {
				$this->request->data['filter']['name'] = $auth['name'];
			}

			$this->set(compact('completed', 'workingOn', 'people'));
			$this->set('_serialize', array('completed', 'workingOn'));
		}

	}
