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
			if(!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$auth = $this->Session->read('Auth');
			$completed = array();
			for ($i = 7; $i > 0; $i--) {
				$date = CakeTime::format('-' . $i . ' days');
				$completedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=resolvedby:"me" resolved:"' . $date . '"&cols=sTitle';
				$completedResponseXml = Xml::build($completedRequestUrl);
				$completedResponse = Xml::toArray($completedResponseXml);
				$completed[$date] = $completedResponse['response'];
			}
			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=assignedTo:"me" status:"active (dev)"&cols=sTitle';
			$activeDevResponseXml = Xml::build($activeDevRequestUrl);
			$activeDevResponse = Xml::toArray($activeDevResponseXml);
			$workingOn = $activeDevResponse['response'];
			$this->set(compact('completed', 'workingOn'));
			$this->set('_serialize', array('completed', 'workingOn'));
		}

	}
