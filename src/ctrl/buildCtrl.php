<?php

namespace JenkinsHelper;

class buildCtrl extends BaseCtrl{

	private $userinfoH = null;
	private $jenkinsLibW = null;

	function __construct($query, $action, $workflows){
		$currentAction = $action."Action";

		parent::__construct($query);
		$this->userinfoH = new Userinfo($this->workflowH);
		$this->jenkinsLibW = new JenkinsLibWrapper($this->userinfoH);

		if(method_exists($this, $action."Action")){
			$this->{$currentAction}();
		}
		else{
			$this->unknownAction();
		}
		
	}

	private function buildWithParamsAction(){
		$jobSlug = array_pop(array_slice(explode("/", $this->query), -2, 1));
		$job = $this->jenkinsLibW->getJob($jobSlug);
		$lastBuild = array_shift($job->getBuilds());

		$this->jenkinsLibW->launchJob($job->getName(), $lastBuild->getInputParameters());

		echo $job->getName();
	}
}

