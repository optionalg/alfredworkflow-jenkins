<?php

/**
* 
*/
class infoCtrl {

	private $workflows = null;

	private $query = "";

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

	function __construct($action, $workflows, $query){
		$this->workflows = $workflows;
		$this->query = $query;
	}

	private function inputAction(){

	}

	private function outputAction(){

	}

	private function unknownAction(){
		
	}

	private function render(){
		$w->result( 'hostname', "$query  hostname", "$query", 'Set your base hostname (ex. jenkins.mydomain.com) '.$query, 'icon.png', 'yes', "$query" );
		$w->result( 'username', "$query  username", "$query", 'Set your username to '.$query, 'icon.png', 'yes', "$query" );
		$w->result( 'password', "$query  password", "$query", 'Set your password to '.$query, 'icon.png', 'yes', "$query" );
		$w->result( 'protocol', "$query  protocol", "$query", 'Set your protocol (default is https) '.$query, 'icon.png', 'yes', "$query" );
		$w->result( 'token', "$query  token", "$query", 'Set your jenkins api token '.$query, 'icon.png', 'yes', "$query" );

		echo $w->toxml();		
	}
}

