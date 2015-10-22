<?php

namespace JenkinsHelper;

class infoCtrl extends BaseCtrl{

	private $infoData = null; 
	private $defaultResult = null;
	private $userinfoH = null;

	function __construct($query, $action, $workflows){
		parent::__construct($query);
		$this->userinfoH = new Userinfo($this->workflowH);
		$this->initInfoData();
		$this->{$action."Action"}();
	}

	private function initInfoData(){
		$this->infoData 			= new \stdClass();
		$this->defaultResult 	= new \stdClass();

		$this->infoData->hostname = array(
			'label' => "Set your base hostname (ex. jenkins.mydomain.com) new:$this->query || old:{$this->userinfoH->getInfo('hostname')}"
		);
		$this->infoData->username = array(
			'label' => "Set your username to new:$this->query || old:{$this->userinfoH->getInfo('username')}"
		);
		$this->infoData->password = array(
			'label' => "Set your password to new:$this->query || old:{$this->userinfoH->getInfo('password')}"
		);
		$this->infoData->protocol = array(
			'label' => "Set your protocol (default is https) new:$this->query || old:{$this->userinfoH->getInfo('protocol')}"
		);
		$this->infoData->token = array(
			'label' => "Set your jenkins api token new:$this->query || old:{$this->userinfoH->getInfo('token')}"
		);

		$this->defaultResult->icon = 'icon.png';
		$this->defaultResult->valid = 'yes';
		$this->defaultResult->autocomplete = $this->query;
	}

	private function inputAction(){
		foreach ($this->infoData as $key => $value) {
			$this->workflowH->result( 
				$key, 
				"$this->query  $key", 
				"$this->query", 
				$value['label'], 
				$this->defaultData->icon, 
				$this->defaultData->valid, 
				$this->defaultData->autocomplete );
		}
	}

	private function outputAction(){

	}

	private function unknownAction(){
		
	}
}

