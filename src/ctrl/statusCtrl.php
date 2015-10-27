<?php

namespace JenkinsHelper;

class statusCtrl extends BaseCtrl{

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

	private function statusAction(){
		$allJobs = $this->jenkinsLibW->getAllJobsDetailed();
		$query = $this->query;

		if($this->query !== ""){
			$allJobs = array_filter($allJobs, function($item) use ($query){
				return is_int(strpos($item->name, $query)); 
			});
		}

		foreach ($allJobs as $job) {
			$this->workflowH->result(

				$job->name, 
				$job->url, 
				$job->name, 
				$job->healthReport[0]->description,
				$this->jenkinsLibW->getStatusIcon($job->color, $job->healthReport)); //, .$buildingStr, 
				//$this->defaultData->icon);
		}
		

		echo $this->workflowH->toxml();
	}
}

