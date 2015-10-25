<?php

namespace JenkinsHelper;

class BaseCtrl {
	
	protected $workflowH = null;

	protected $query = "";

	function __construct($query){
		$this->workflowH = new Workflows();
		$this->query = $query;
	}

	protected function unknownAction(){
		echo "Unknown Action !";
	}
}