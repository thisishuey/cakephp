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
				$this->Session->setFlash(__('You must enter a command'), 'Cherry.flash/danger');
			}
			$this->set(compact('cmdResponse'));
			$this->set('_serialize', 'cmdResponse');
		}

	}
