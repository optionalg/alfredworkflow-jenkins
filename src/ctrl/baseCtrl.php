<?php

namespace JenkinsHelper;

class BaseCtrl {
	
	protected $workflowH = null;

	protected $query = "";

	function __construct($query){
		$this->workflowH = new Workflows();
		$this->query = $query;
	}

	public function render(){
		echo $this->workflowH->toxml();
	}
}