<?php

namespace JenkinsHelper;

class Router {
	
	private $ctrl = null;

	function __construct($query, $context, $action){
		$className = "JenkinsHelper\\".$context."Ctrl";
		$workflowHelper = new Workflows();
		$ctrl = new $className($query, $action, $workflowHelper);
		$ctrl->render();
	}

}