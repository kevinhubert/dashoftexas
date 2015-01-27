<?php 
/*
	Error Page Template
*/

if( !isset($error) ) return;
?>
<div class="auth-dialog">
  <h2><?php echo $error->code ?> - <?php echo $error->error_type; ?></h2>
  <p><?php echo $error->error_message; ?></p>
</div>