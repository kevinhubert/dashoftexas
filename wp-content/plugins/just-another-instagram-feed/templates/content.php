<?php 
/*
	Instalod.com - Content Template File
	
	@description: This file will be used to display the content of the current page.
	@author: Sebastian Forsberg
	@version: 0.1 
*/

if( !isset( $instalod ) ) return;

?>

<?php include_once('header.php'); ?>
<div></div>

<div id="wrap">
	<header>
	  <?php $instalod->get_search_form(); ?>
	</header>
	
	<div id="content">	
	<?php echo $instalod->the_content; ?>
	</div>
	
	<footer>
	
	</footer>
</div>

<?php include_once('footer.php'); ?>