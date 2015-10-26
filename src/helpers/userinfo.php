<?php

namespace JenkinsHelper;

class Userinfo{

	private $workflowH;

	private $settingsPath;

	private $infosKeys = ['hostname', 'username', 'protocol', 'token'];

	private $userInfos = array();

	function __construct($workflow){
		$this->workflowH = $workflow;
		$this->settingsPath = 'settings.plist';
		$this->loadUserInfos();
	}

	private function loadUserInfos(){
		if(!file_exists($this->settingsPath)){
			touch($this->settingsPath);
		}

		foreach ($this->infosKeys as $infoKey) {
			$this->userInfos[$infoKey] = $this->workflowH->get($infoKey, $this->settingsPath);
		}
	}

	public function getInfo($infoKey){
		return ($this->infoExist($infoKey))? $this->userInfos[$infoKey] : NULL; 
	}

	public function setInfo($infoKey, $newValue){
		$hasBeenSet = false;
		if($this->infoExist($infoKey)){
			$this->workflowH->set($infoKey, $newValue, $this->settingsPath);
			$hasBeenSet = true;
		}
		return $hasBeenSet;
	}

	public function infoExist($infoKey){
		return (array_search($infoKey, $this->infosKeys) !== false); 
	}

	public function getJenkinsUrl(){
		$protocol = is_null($this->getInfo('protocol'))? 'https' : $this->getInfo('protocol');
		if(!is_null($this->getInfo('token'))){
			return $protocol . '://'.$this->getInfo('username') . ':' . $this->getInfo('token') . '@' . $this->getInfo('hostname');
		}
		else {
			return "";
		}
	}
}