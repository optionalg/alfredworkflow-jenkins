<?php

namespace JenkinsHelper;

class infoCtrl extends BaseCtrl{

	private $infoData = null; 
	private $defaultResult = null;
	private $userinfoH = null;

	function __construct($query, $action, $workflows){
		$currentAction = $action."Action";

		parent::__construct($query);
		$this->userinfoH = new Userinfo($this->workflowH);
		$this->initInfoData();

		if(method_exists($this, $action."Action")){
			$this->{$currentAction}();
		}
		else{
			$this->unknownAction();
		}
		
	}

	private function initInfoData(){
		$this->infoData 			= new \stdClass();
		$this->defaultResult 	= new \stdClass();

		$this->infoData->hostname = array('label' => "Set your base hostname (ex. jenkins.mydomain.com) new:$this->query");
		$this->infoData->username = array('label' => "Set your username to new:$this->query");
		$this->infoData->password = array('label' => "Set your password to new:$this->query");
		$this->infoData->protocol = array('label' => "Set your protocol (default is https) new:$this->query");
		$this->infoData->token = array('label' => "Set your jenkins api token new:$this->query");

		foreach ($this->infoData as $infoKey => $info) {
			$this->infoData->{$infoKey}['label'] = $this->prepareInfoLabel($infoKey, $info['label']);
		}

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

		echo $this->workflowH->toxml();
	}

	private function outputAction(){
		$newInfoDatas = explode('  ', $this->query);
		$newDataSaved = $this->userinfoH->setInfo($newInfoDatas[1], $newInfoDatas[0]);
		$output = ($newDataSaved)? "Set $newInfoDatas[1] to $newInfoDatas[0]" : "Unknown info !";
		echo $output;
	}

	private function prepareInfoLabel($infoKey, $label){
		$oldInfo = $this->userinfoH->getInfo($infoKey);
		return ($oldInfo !== NULL)? ($label. " || old:$oldInfo") : $label;
	}
}

