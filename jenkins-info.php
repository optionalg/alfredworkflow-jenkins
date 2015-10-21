<?php

require( 'workflows.php' );
$w = new Workflows();

$w->result( 'hostname', "$query  hostname", "$query", 'Set your base hostname (ex. jenkins.mydomain.com) '.$query, 'icon.png', 'yes', "$query" );
$w->result( 'username', "$query  username", "$query", 'Set your username to '.$query, 'icon.png', 'yes', "$query" );
$w->result( 'password', "$query  password", "$query", 'Set your password to '.$query, 'icon.png', 'yes', "$query" );
$w->result( 'gitrepo', "$query  gitrepo", "$query", 'Set your git repo (relative to home directory) '.$query, 'icon.png', 'yes', "$query" );
$w->result( 'protocol', "$query  protocol", "$query", 'Set your protocol (default is https) '.$query, 'icon.png', 'yes', "$query" );
$w->result( 'token', "$query  token", "$query", 'Set your jenkins api token '.$query, 'icon.png', 'yes', "$query" );

echo $w->toxml();