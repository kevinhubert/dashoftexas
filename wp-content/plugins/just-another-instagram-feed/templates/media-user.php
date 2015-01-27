<?php 
/*
  Single Media User Profile/Stream Link

  @description: Displays the current $data user profile picture and username as a link to the user's page on this site
*/

//Check if the Instagram Query $data variable is present and that the user object exists within it
if( !isset( $data->user ) ) return;
?>
<a class="jaif-user-circle" href="<?php echo 'http://instagram.com/'.$data->user->username ?>" title="@<?php echo $data->user->username; ?>" target="_blank">
  <img class="jaif-user-pic" src="<?php echo $data->user->profile_picture; ?>" />
</a>