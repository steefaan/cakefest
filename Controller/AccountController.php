<?php
App::uses('AppController', 'Controller');

class AccountController extends AppController {

	public $uses = array('User');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('login', 'logout', 'register', 'activate', 'lost_pwd', 'change_pwd');
	}

	/**
	 * AccountController::login()
	 *
	 * @param string $username
	 * @return void
	 */
	public function login($username = null) {

		if ($this->Common->isPosted()) {
			if ($this->Auth->login()) {
				$this->Common->flashMessage(__('loggedInMessage'), 'success');

				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->request->data['User']['password'] = '';

		} else {
			if (!empty($username)) {
				$this->request->data['User']['login'] = $username;
			}
		}
	}

	/**
	 * AccountController::logout()
	 *
	 * @return void
	 */
	public function logout() {
		$whereTo = $this->Auth->logout();
		# delete cookie
		if (Configure::read('Config.remember_me')) {
			$this->Comon->loadComponent('Tools.RememberMe');
			$this->RememberMe->delete();
		}
		$this->Common->flashMessage(__('loggedOutMessage'), 'success');
		return $this->redirect($whereTo);
	}

	/**
	 * AccountController::register()
	 *
	 * @return void
	 */
	public function register() {
		$this->User->Behaviors->load('Tools.Passwordable', array());
		if ($this->Common->isPosted()) {
			$this->request->data['User']['role_id'] = Configure::read('Role.user');
			if ($user = $this->User->save($this->request->data)) {
				$this->Common->flashMessage(__('Account created'), 'success');
				if (!$this->Auth->login($user['User'])) {
					throw new CakeException('Cannot log user in');
				}
				return $this->redirect(array('controller' => 'overview', 'action' => 'index'));
			}
			$this->Common->flashMessage(__('formContainsErrors'), 'error');

			# pw should not be passed to the view again for security reasons
			unset($this->request->data['User']['pwd']);
			unset($this->request->data['User']['pwd_repeat']);
		}
	}

	public function edit() {
		$uid = $this->Session->read('Auth.User.id');
		$user = $this->User->get($uid);
		$this->User->Behaviors->attach('Tools.Passwordable', array('require' => false));

		if ($this->Common->isPosted()) {
			$this->request->data['User']['id'] = $uid;
			if ($this->User->save($this->request->data, true, array('id', 'username', 'email', 'irc_nick', 'pwd', 'pwd_repeat'))) {
				$this->Common->flashMessage(__('Account modified'), 'success');
				if (!$this->Auth->login($user['User'])) {
					throw new CakeException('Cannot log user in');
				}
				return $this->redirect(array('controller' => 'overview', 'action' => 'index'));
			}
			$this->Common->flashMessage(__('formContainsErrors'), 'error');

			# pw should not be passed to the view again for security reasons
			unset($this->request->data['User']['pwd']);
			unset($this->request->data['User']['pwd_repeat']);
		} else {
			$this->request->data = $user;
		}
	}

	public function delete($id = null) {
		$this->request->onlyAllow('post', 'delete');
		$uid = $this->Session->read('Auth.User.id');
		if (!$this->User->delete($uid)) {
			throw new InternalErrorException();
		}
		$this->Common->flashMessage('Account deleted', 'success');
		return $this->redirect(array('action' => 'logout'));
	}

}
