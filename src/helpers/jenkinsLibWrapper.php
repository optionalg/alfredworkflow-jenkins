<?php

namespace JenkinsHelper;

class JenkinsLibWrapper{

	private $jenkinsH;
	private $userinfoH;



	function __construct($userinfoH){
		$this->userinfoH = $userinfoH;
		$this->jenkinsH = new \JenkinsKhan\Jenkins($this->userinfoH->getJenkinsUrl());
	}

  public function getAllJobsDetailed(){
    $url  = sprintf('%s/api/json?tree=jobs[name,url,color,healthReport[description,score,iconUrl]]', $this->jenkinsH->getUrl());
    $curl = curl_init($url);

    curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);

    $response_info = curl_getinfo($curl);

    if (200 != $response_info['http_code']) {
        return false;
    }

    if (curl_errno($curl)) {
        throw new \RuntimeException(
            sprintf('Error during getting information on all jobs with details')
        );
    }
    $infos = json_decode($response);
    if (!$infos instanceof \stdClass) {
        throw new \RuntimeException('Error during json_decode');
    }

    return $infos->jobs;
  } 

  public function getStatusIcon($color, $healthReport){
  	$isAnime = (strpos($color, '_anime') !== false);
  	$color = ($isAnime)? str_replace('_anime', '', $color) : $color;
  	$imgPath = 'images/%s.png';
  	$score = $healthReport[0]->score;

  	if($score >= 0 && $score <= 100){
  		$range = floor($score / 20);
	  	$rangeStart = ($range * 20);
	  	$rangeEnd = ($range * 20) + 19;
	  	$imgPath = sprintf('images/health-%sto%s-%s.png', $rangeStart, $rangeEnd, $color);
  	}
  	else{
  		$imgPath = sprintf($imgPath, $color);
  	}
  	var_dump($imgPath);
  	return $imgPath;
  }

}