<?php 

require('workflows.php');
require('jenkins.php');
$wf = new Workflows();
$jenkins = new Jenkins();

$query = str_replace(' ', '%20', $orig);

$hostname = $wf->get( 'hostname', 'settings.plist' );
$user = $wf->get( 'username', 'settings.plist' );
$password = $wf->get( 'password', 'settings.plist' );
$gitrepo = $wf->get( 'gitrepo', 'settings.plist' );
$protocol = $wf->get( 'protocol', 'settings.plist' );

if(empty($gitrepo) == FALSE):
	chdir(getenv("HOME").$gitrepo);
	$currentBranch = exec("git rev-parse --abbrev-ref HEAD");
endif;

$url = $protocol."://".$user.":".urlencode($password)."@".$hostname;
$xml = $wf->request( $url."/api/json?tree=jobs[name,url,color,healthReport[description,score,iconUrl]]");
$xml = json_decode($xml,true);
$int = 1;

foreach ($xml['jobs'] as $item) 
{
	$color = str_replace('_anime', '', $item['color']);
		
	if($color == 'disabled' || $color == 'notbuilt' || $color == 'aborted' || $color == 'aborted_anime'):
		$color = 'grey';
	endif;

	if(sizeof($item['healthReport']) > 0):
		$imageURL = $jenkins->generateIcon($item['healthReport'][0]['score'], $color);
	else:
		$imageURL = 'images/'.$color.'.png';
	endif;

	$joburl = "http://".$user.":".urlencode($password)."@".$hostname;
	$jobxml = $wf->request( $url."/job/".str_replace(' ', '%20', $item['name'])."/config.xml" );
	$jobxml = simplexml_load_string( utf8_encode($jobxml) );
	
	$token = $jobxml->authToken;
	print(is_null($token));
	if(empty($token) == FALSE):

		$stringstack = array();
		$booleanstack = array();

		$params = $jobxml->properties;
	
		foreach ($params as $prop) 
		{
			$hudson = $prop->{'hudson.model.ParametersDefinitionProperty'};
			$paramDefs = $hudson->parameterDefinitions;
			$StrDef = $paramDefs->{'hudson.model.StringParameterDefinition'};
			foreach ($StrDef as $StrDef2) 
			{
				array_push($stringstack, $StrDef2->name);
			}
			$boolDef = $paramDefs->{'hudson.model.BooleanParameterDefinition'};
			foreach ($boolDef as $boolDef2) 
			{
				array_push($booleanstack, $boolDef2->name);
			}
		}
	
		$queryParams = explode("  ", $orig);
		$infoStr = '';
		$urlStr = '';	
		
		for ($i = 0; $i < count($stringstack); ++$i) {
			if(empty($currentBranch) == FALSE && empty($queryParams[$i]) && $i == 0):
				$infoStr = $infoStr.$stringstack[$i].': '.$currentBranch.' ';
				$urlStr = $urlStr.$stringstack[$i].'='.$currentBranch.'&';
			else:
				$infoStr = $infoStr.$stringstack[$i].': '.$queryParams[$i].' ';
				$urlStr = $urlStr.$stringstack[$i].'='.$queryParams[$i].'&';
			endif;
		}
		
		for ($i = 0; $i < count($booleanstack); ++$i) {
			$booleanValue = 'false';
			if(strcasecmp($queryParams[$i + count($stringstack)], 'TRUE') == 0):
				$booleanValue = 'true';
			endif;
        		$infoStr = $infoStr.$booleanstack[$i].': '.$booleanValue.' ';
			$urlStr = $urlStr.$booleanstack[$i].'='.$booleanValue.'&';
		}

		$urlStr = substr($urlStr, 0, -1);
		$urlParams = $item['name'].'/buildWithParameters?token='.$token.'&'.$urlStr.'&delay=0&cause=Started By: '.$user;
	
		$wf->result( $item['name'], $url.'/job/'.str_replace(' ', '%20', $urlParams), $item['name'], $infoStr, $imageURL );
	endif;
};

$results = $wf->results();
if ( count( $results ) == 0 ):
	$wf->result( 'jenkins', 'No Suggestions', 'No Suggestions', 'No Status found.', 'icon.png' );
endif;

echo $wf->toxml();