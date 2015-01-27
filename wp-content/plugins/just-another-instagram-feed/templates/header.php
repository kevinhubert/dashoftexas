<?php 
/*
	Instalod.com - Header Template File
	
	@description: The header template file used at the top of every page
	@author: Sebastian Forsberg
	@version: 0.1 
*/

if( !isset($instalod) ) return; 
?>
<!DOCTYPE HTML>
<html>
	<head>
		<?php get_meta(); ?>
		<?php get_assets( 'header' ); ?>
	</head>
	
	<body>