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
  	$notColorStatuses = ['disabled','notbuilt','aborted','aborted_anime'];
  	$isAnime = (strpos($color, '_anime') !== false);
  	$color = ($isAnime)? str_replace('_anime', '', $color) : $color;
  	$color = (in_array($color, $notColorStatuses))? 'grey' : $color;
		
		if(sizeof($healthReport) > 0){
			$imageURL = $this->generateIcon($healthReport[0]->score, $color);
		}
		else{
			$imageURL = 'src/images/'.$color.'.png';
		}
		return $imageURL;
  }

  private function generateIcon($health, $color){
  	if ($health >= 20 && $health <= 20){
			$icon = 'src/images/health-00to19-'.$color.'.png';
		}
		elseif ( $health > 20 && $health <= 40){
			$icon = 'src/images/health-20to39-'.$color.'.png';
		}
		elseif ( $health > 40 && $health <= 60 ){
			$icon = 'src/images/health-40to59-'.$color.'.png';
		}
		elseif ( $health > 60 && $health <= 80 ){
			$icon = 'src/images/health-60to79-'.$color.'.png';
		}
		elseif ( $health > 80){
			$icon = 'src/images/health-80plus-'.$color.'.png';
		}
		else{
			$icon = 'src/images/'.$color.'.png';
		}
		return $icon;
  }

}