<?php

namespace JenkinsHelper;

class Userinfo{

	private $workflowH;

	private $settingsPath;

	private $infosKeys = ['hostname', 'username', 'password', 'protocol', 'token'];

	private $userInfos = array();

	function __construct($workflow){
		$this->workflowH = $workflow;
		$this->settingsPath = $this->workflowH->path().'/settings.plist';
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
		return (array_search($infoKey, $this->infosKeys) !== false)? $this->infosKeys[$infoKey] : false; 
	}
}