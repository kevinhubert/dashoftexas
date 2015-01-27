<?php 

if( !isset( $this ) ) return;

?>
<div class="auth-dialog">
	<p><?php echo $this->messages['notAuth']; ?></p>
	<a href="<?php echo $this->loginURL; ?>"><?php echo $this->messages['auth']; ?></a>
</div>
