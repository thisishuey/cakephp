<?php
	App::uses('AppModel', 'Model');

	class User extends AppModel {

		public $validate = array(
			'fogbugz_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => 'numeric'
				),
			),
			'name' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => 'notEmpty'
				),
			),
			'email' => array(
				'email' => array(
					'rule' => array('email'),
					'message' => 'email'
				),
			),
		);

	}
