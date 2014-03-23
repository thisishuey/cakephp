<?php
	App::uses('AppController', 'Controller');
	App::uses('Xml', 'Utility');
	App::uses('HttpSocket', 'Network/Http');

	class UsersController extends AppController {

		public function beforeFilter() {
			parent::beforeFilter();
		}

		public function login() {
			if($this->Session->read('Auth')) {
				$this->Session->setFlash(__('You are already logged in.'), 'Cherry.flash/info');
				return $this->redirect('/');
			}
			if(!empty($this->request->data)) {
				$user = $this->request->data['User'];
				if (strpos($user['fogbugz_url'], 'http://') !== 0) {
					$user['fogbugz_url'] = 'http://' . $user['fogbugz_url'];
				}
				$loginRequestUrl = $user['fogbugz_url'] . '/api.asp?cmd=logon&email=' . $user['email'] . '&password=' . $user['password'];
				$loginResponseXml = Xml::build($loginRequestUrl);
				$loginResponse = Xml::toArray($loginResponseXml)['response'];
				if (isset($loginResponse['token'])) {
					$personRequestUrl = $user['fogbugz_url'] . '/api.asp?token=' . $loginResponse['token'] . '&cmd=viewPerson';
					$personResponseXml = Xml::build($personRequestUrl);
					$personResponse = Xml::toArray($personResponseXml)['response'];
					$auth = array(
						'fogbugz_url' => $user['fogbugz_url'],
						'id' => $personResponse['person']['ixPerson'],
						'name' => $personResponse['person']['sFullName'],
						'email' => $user['email'],
						'token' => $loginResponse['token']
					);
					$this->Session->write('Auth', $auth);
					$this->Session->setFlash(__('You have been logged in.'), 'Cherry.flash/success');
					if ($this->Session->check('LoginRedirect')) {
						$redirectUrl = $this->Session->read('LoginRedirect');
						$this->Session->delete('LoginRedirect');
					} else {
						$redirectUrl = array('controller' => 'apis', 'action' => 'index');
					}
					return $this->redirect($redirectUrl);
				} else {
					$this->Session->setFlash(__('Invalid Username or Password, please try again.'), 'Cherry.flash/danger');
				}
			}
		}

		public function logout() {
			$auth = $this->Session->read('Auth');
			$requestUrl = $auth['fogbugz_url'] . '/api.asp?cmd=logoff&token=' . $auth['token'];
			$HttpSocket = new HttpSocket();
			$HttpSocket->get($requestUrl);
			$this->Session->delete('Auth');
			$this->Session->setFlash(__('You have been logged out.'), 'Cherry.flash/success');
			return $this->redirect(array('action' => 'login'));
		}

	}
