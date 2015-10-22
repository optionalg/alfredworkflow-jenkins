<?php

namespace JenkinsHelper;

class infoCtrl extends BaseCtrl{

	private $infoData = array(
		'hostname' => array(
			'label' => 'Set your base hostname (ex. jenkins.mydomain.com) {{query}}'
		),
		'username' => array(
			'label' => 'Set your username to {{query}}'
		),
		'password' => array(
			'label' => 'Set your password to {{query}}'
		),
		'protocol' => array(
			'label' => 'Set your protocol (default is https) {{query}}'
		),
		'token' => array(
			'label' => 'Set your jenkins api token {{query}}'
		)
	);

	private $defaultResult = array(
		'icon' 	=> 'icon.png',
		'valid' => 'yes',
		'autocomplete' => '{{query}}'
	);

	function __construct($query, $action, $workflows){
		parent::__construct($query);
		$this->{$action."Action"}();
	}

	private function inputAction(){
		$this->workflowH->result( 'hostname', "$query  hostname", "$query", 'Set your base hostname (ex. jenkins.mydomain.com) '.$this->query, 'icon.png', 'yes', "$query" );
		$this->workflowH->result( 'username', "$query  username", "$query", 'Set your username to '.$this->query, 'icon.png', 'yes', "$query" );
		$this->workflowH->result( 'password', "$query  password", "$query", 'Set your password to '.$this->query, 'icon.png', 'yes', "$query" );
		$this->workflowH->result( 'protocol', "$query  protocol", "$query", 'Set your protocol (default is https) '.$this->query, 'icon.png', 'yes', "$query" );
		$this->workflowH->result( 'token', "$query  token", "$query", 'Set your jenkins api token '.$this->query, 'icon.png', 'yes', "$query" );
	}

	private function outputAction(){

	}

	private function unknownAction(){
		
	}
}

