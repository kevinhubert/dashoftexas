<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width">
	
	<!-- CDN's -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

	<title><?php $blog_title = get_bloginfo(); ?></title>
	<link rel="stylesheet" href="<?php site_url(); ?>/wp-content/themes/dashoftexas/style.css">
	<?php include_once( "analyticstracking.php"); ?>
	<?php wp_head(); ?>
</head>
<body>
	
	<!-- BEGIN HEADER -->	
	<header class="l-header">
		<div class="container">
			<div class="title">
				<a href="<?php bloginfo('url'); ?>">
					<h1 class="title__text">A DASH OF <span class="title__text--strong">TEXAS</span></h1>
				</a>
				<h2 class="title__text title__text--tagline"><?php echo get_bloginfo ( 'description' );  ?></h2>	
				<span class="menu">
					<div class="banner__slide"></div>
					<div class="banner__slide"></div>
					<div class="banner__slide"></div> 
				</span>			
			</div>
		</div>		
	</header>
	<!-- END HEADER -->