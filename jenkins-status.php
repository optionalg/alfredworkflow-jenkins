<?php

require_once('workflows.php');
require_once('jenkins.php');
$wf = new Workflows();
$jenkins = new Jenkins();

$query = str_replace(' ', '%20', $orig);
$hostname = $wf->get( 'hostname', 'settings.plist' );
$user = $wf->get( 'username', 'settings.plist' );
$password = $wf->get( 'password', 'settings.plist' );
$protocol = $wf->get( 'protocol', 'settings.plist' );
$token = $wf->get( 'token', 'settings.plist' );
$curlOpts = array();

if($token){
	$url = $protocol."://".$hostname;	
	$curlOpts[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
	$curlOpts[CURLOPT_USERPWD] = $user.':'.$token;
}
else{
	$url = $protocol."://".$user.":".urlencode($password)."@".$hostname;	
}


$xml = $wf->request( $url."/api/json?tree=jobs[name,url,color,healthReport[description,score,iconUrl]]", $curlOpts);
$xml = json_decode($xml,true);
$int = 1;

if($query){
	$xml['jobs'] = array_filter($xml['jobs'], function($item) use ($query){
		return is_int(strpos($item['name'], $query)); 
	});
}

foreach ($xml['jobs'] as $item) 
{
	$buildingStr = '';
	if (strpos($item['color'],'_anime') !== false) {
    		$buildingStr = ' Currently Running.';
	}

	$color = str_replace('_anime', '', $item['color']);
		
	if($color == 'disabled' || $color == 'notbuilt' || $color == 'aborted' || $color == 'aborted_anime'):
		$color = 'grey';
	endif;

	if(sizeof($item['healthReport']) > 0):
		$imageURL = $jenkins->generateIcon($item['healthReport'][0]['score'], $color);
	else:
		$imageURL = 'images/'.$color.'.png';
	endif;

	$wf->result( $item['name'], $item['url'], $item['name'], $item['healthReport'][0]['description'].$buildingStr, $imageURL );
};

$results = $wf->results();

if ( count( $results ) == 0 ):
	$wf->result( 'jenkins', 'No Suggestions', 'No Suggestions', 'No Status found.', 'icon.png' );
endif;

echo $wf->toxml();