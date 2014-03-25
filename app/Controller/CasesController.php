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
			$resolvedDates = array();
			for ($i = 7; $i > 0; $i--) {
				$date = CakeTime::format('-' . $i . ' days');
				$resolvedDateRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=resolvedby:"me" resolved:"' . $date . '"&cols=sTitle';
				$resolvedDateResponseXml = Xml::build($resolvedDateRequestUrl);
				$resolvedDateResponse = Xml::toArray($resolvedDateResponseXml);
				$resolvedDates[$date] = $resolvedDateResponse['response'];
			}
			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=assignedTo:"me" status:"active (dev)"&cols=sTitle';
			$activeDevResponseXml = Xml::build($activeDevRequestUrl);
			$activeDevResponse = Xml::toArray($activeDevResponseXml);
			$workingOn = $activeDevResponse['response'];
			// http://fogbugz.signup4.local/api.asp?token=ptusv371si41qin8q543pgntei69f9&cmd=search&q=resolvedby:"me" resolved:"last thursday"&cols=sTitle
			$this->set(compact('resolvedDates', 'workingOn'));
			$this->set('_serialize', array('resolvedDates', 'workingOn'));
		}

	}
