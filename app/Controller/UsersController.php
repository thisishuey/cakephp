<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeTime', 'Utility');
	App::uses('Xml', 'Utility');
	App::uses('HttpSocket', 'Network/Http');

	class UsersController extends AppController {

		public function beforeFilter() {
			parent::beforeFilter();
		}

		public function login() {
			if ($this->Session->check('Auth')) {
				$auth = $this->Session->read('Auth');
				$this->Session->setFlash(__('You are already logged in.'), 'Cherry.flash/info');
				return $this->redirect(array('controller' => 'cases', 'action' => 'index'));
			}
			if (!empty($this->request->data)) {
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
						$redirectUrl = array('controller' => 'cases', 'action' => 'index');
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

		public function index() {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$users = $this->paginate();
			$this->set(compact('users'));
		}

		public function view($id = null) {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$auth = $this->Session->read('Auth');
			$redirectUser = $this->User->find('first', array('conditions' => array('User.fogbugz_id' => $auth['id'])));
			if (!$id) {
				return $this->redirect(array('controller' => 'users', 'action' => 'view', $redirectUser['User']['id']));
			}
			if (!$this->User->exists($id)) {
				throw new NotFoundException(__('Invalid user'));
			}
			$user = $this->User->find('first', array('conditions' => array('User.' . $this->User->primaryKey => $id)));
			$users = $this->User->find('list');
			if (isset($this->request->named['days'])) {
				$days = $this->request->named['days'];
			} else {
				$days = 7;
			}
			$cols = 'ixBug,sTitle,dtResolved,sProject,events';
			$resolvedRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=editedBy:"' . $user['User']['name'] . '" edited:"-' . ($days + 1) . 'd.." orderBy:"resolved"&cols=' . $cols;
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
			for ($i = $days; $i >= 0; $i--) {
				$date = CakeTime::format('-' . $i . ' days');
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
						$resolvedBy = false;
						foreach ($resolvedCase['events']['event'] as $event) {
							$resolvedByDate = CakeTime::format($event['dt'], '%y%m%d') >= CakeTime::format('- ' . $days . ' days', '%y%m%d');
							$resolvedEvent = $event['sVerb'] === 'Resolved';
							$resolvedByUser = $event['ixPerson'] === $user['User']['fogbugz_id'];
							if ($resolvedByDate && $resolvedEvent && $resolvedByUser) {
								$resolvedBy = true;
							}
						}
						if ($resolvedBy) {
							$completed[$date]['projects'][$resolvedCase['sProject']][] = array(
								'id' => $resolvedCase['ixBug'],
								'title' => $resolvedCase['sTitle'],
								'project' => $resolvedCase['sProject'],
								'events' => $resolvedCase['events']['event']
							);
						}
					}
				}
			}
			$activeDevRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=search&q=assignedTo:"' . $user['User']['name'] . '" status:"active (dev)" orderBy:"ixBug"&cols=' . $cols;
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
			$this->request->data['filter']['user_id'] = $id;
			$this->set(compact('user', 'users', 'completed', 'workingOn'));
			$this->set('_serialize', array('completed', 'workingOn'));
		}

		public function add() {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$auth = $this->Session->read('Auth');
			if ($this->request->is('post')) {
				$userRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=viewPerson&ixPerson=' . $this->request->data['User']['user_id'];
				$userResponseXml = Xml::build($userRequestUrl);
				$userResponse = Xml::toArray($userResponseXml);
				$user = $userResponse['response']['person'];
				$this->request->data['User']['fogbugz_id'] = $user['ixPerson'];
				$this->request->data['User']['name'] = $user['sFullName'];
				$this->request->data['User']['email'] = $user['sEmail'];
				$this->User->create();
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved'), 'Cherry.flash/success');
					$this->redirect(array('action' => 'view', $this->User->id));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'Cherry.flash/danger');
				}
			}
			$usersRequestUrl = $auth['fogbugz_url'] . '/api.asp?token=' . $auth['token'] . '&cmd=listPeople';
			$usersResponseXml = Xml::build($usersRequestUrl);
			$usersResponse = Xml::toArray($usersResponseXml);
			$users = array();
			foreach ($usersResponse['response']['people']['person'] as $person) {
				$users[$person['ixPerson']] = $person['sFullName'];
			}
			$this->set(compact('users'));
		}

		public function edit($id = null) {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			if (!$this->User->exists($id)) {
				throw new NotFoundException(__('Invalid user'));
			}
			if ($this->request->is('post') || $this->request->is('put')) {
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved'), 'Cherry.flash/success');
					$this->redirect(array('action' => 'view', $this->User->id));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'Cherry.flash/danger');
				}
			} else {
				$this->request->data = $this->User->find('first', array('conditions' => array('User.' . $this->User->primaryKey => $id), 'contain' => false));
			}
		}

		public function delete($id = null) {
			if (!$this->Session->read('Auth')) {
				$this->Session->write('LoginRedirect', '/' . $this->request->url);
				$this->Session->setFlash(__('You must login to access that page.'), 'Cherry.flash/danger');
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$this->User->id = $id;
			if (!$this->User->exists()) {
				throw new NotFoundException(__('Invalid user'));
			}
			$this->request->onlyAllow('post', 'delete');
			if ($this->User->delete()) {
				$this->Session->setFlash(__('User deleted'), 'Cherry.flash/success');
				$this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('User was not deleted'), 'Cherry.flash/danger');
			$this->redirect(array('action' => 'index'));
		}

	}
