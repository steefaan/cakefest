<?php
App::uses('AppController', 'Controller');
/**
 * Overview Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class OverviewController extends AppController {

	public $uses = array('User');

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');

	/**
	 * OverviewController::beforeFilter()
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

	/**
	 * Homepage.
	 *
	 * @return void
	 */
	public function index() {
		$this->User->Attendee->recursive = 0;
		// For now just the newest one
		$event = $this->User->Attendee->Event->find('first', array('order' => array('from' => 'DESC')));

		$attendees = $this->User->Attendee->find('all', array('conditions' => array('Attendee.event_id' => $event['Event']['id'])));

		$this->set(compact('event', 'attendees'));
	}

	/**
	 * Admin backend.
	 *
	 * @return void
	 */
	public function admin() {
		//TODO
	}

}
