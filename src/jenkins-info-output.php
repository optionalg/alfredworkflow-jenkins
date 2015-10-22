<?php

require( 'workflows.php' );
$w = new Workflows();

exec( 'touch settings.plist' );
$tokens = explode("  ", $query);

$in = "$tokens[0]";
$type = "$tokens[1]";

if ( $type == 'hostname' ):
	$w->set( 'hostname', $in, 'settings.plist' );
	echo 'Set hostname to '.$in;
elseif ($type == 'username'):
	$w->set( 'username', $in, 'settings.plist' );
	echo 'Set username to '.$in;
elseif ($type == 'password'):
	$w->set( 'password', $in, 'settings.plist' );
	echo 'Set password to '.$in;
elseif ($type == 'protocol'):
	$w->set( 'protocol', $in, 'settings.plist' );
	echo 'Set protocol to '.$in;
elseif ($type == 'token'):
	$w->set( 'token', $in, 'settings.plist' );
	echo 'Set token to '.$in;
endif;