<?php
	App::uses('AppController', 'Controller');
	App::uses('Xml', 'Utility');
	App::uses('HttpSocket', 'Network/Http');

	class ApisController extends AppController {

		public function beforeFilter() {
			parent::beforeFilter();
		}

		public function index() {
			if(!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
		}

		public function cmd($cmd = null) {
			if(!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$cmdResponse = array();
			if ($cmd) {
				$auth = $this->Session->read('Auth');
				$cmdRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=' . $cmd;
				foreach ($this->request->named as $key => $value) {
					$cmdRequestUrl .= '&' . $key . '=' . $value;
				}
				$cmdResponseXml = Xml::build($cmdRequestUrl);
				$cmdResponse = Xml::toArray($cmdResponseXml);
			} else {
				$flash = __('<p>You must enter a command in the url, e.g.</p>');
				$flash .= '<ol>';
				$flash .= '<li><em>' . FULL_BASE_URL . $this->request->here . 'search/q:&lt;case_id&gt;/cols:sTitle,sStatus,events</em></li>';
				$flash .= '<li><em>' . FULL_BASE_URL . $this->request->here . 'viewPerson/ixPerson:&lt;person_id&gt;</em></li>';
				$flash .= '</ol>';
				$this->Session->setFlash($flash, 'Cherry.flash/danger');
			}
			$this->set(compact('cmdResponse'));
			$this->set('_serialize', 'cmdResponse');
		}

	}
