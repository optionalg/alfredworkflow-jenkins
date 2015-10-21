<?php
class Jenkins {

	private $cache;
	private $data;
	private $bundle;
	private $path;
	private $home;
	private $results;

	public function generateIcon( $health, $color )
	{	
		if ($health >= 20 && $health <= 20):
			$icon = 'images/health-00to19-'.$color.'.png';
		elseif ( $health > 20 && $health <= 40):
			$icon = 'images/health-20to39-'.$color.'.png';
		elseif ( $health > 40 && $health <= 60 ):
			$icon = 'images/health-40to59-'.$color.'.png';
		elseif ( $health > 60 && $health <= 80 ):
			$icon = 'images/health-60to79-'.$color.'.png';
		elseif ( $health > 80):
			$icon = 'images/health-80plus-'.$color.'.png';
		else:
			$icon = 'images/'.$color.'.png';
		endif;

		return $icon;
	}
}