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
				$this->Session->setFlash($cmdRequestUrl, 'Cherry.flash/info');
				$cmdResponseXml = Xml::build($cmdRequestUrl);
				$cmdResponse = Xml::toArray($cmdResponseXml);
			} else {
				$flash = __('<p><strong>You must enter a command in the url, e.g.</strong></p>');
				$flash .= '<ol>';
				$flash .= '<li><em>' . FULL_BASE_URL . $this->request->here . '&lt;command&gt;/&lt;argument_1&gt;:&lt;value_1&gt;/&lt;argument_2&gt;:&lt;value_2&gt;/.../&lt;argument_n&gt;:&lt;value_n&gt;</em></li>';
				$flash .= '<li><em>' . FULL_BASE_URL . $this->request->here . 'search/q:&lt;case_id&gt;/cols:sTitle,sStatus,events</em></li>';
				$flash .= '<li><em>' . FULL_BASE_URL . $this->request->here . 'viewPerson/ixPerson:&lt;person_id&gt;</em></li>';
				$flash .= '</ol>';
				$flash .= '<p><a href="https://help.fogcreek.com/8202/xml-api" target="_blank" class="alert-link">View FogBugz XML API Documentation</a></p>';
				$this->Session->setFlash($flash, 'Cherry.flash/info');
			}
			$this->set(compact('cmdResponse'));
			$this->set('_serialize', 'cmdResponse');
		}

	}
