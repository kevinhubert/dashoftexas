<?php 
/*
  Follow link
*/

if( !isset( $jaif ) ) return;

$url = 'http://instagram.com/';
$user = !empty($jaif->follow_user) ? $jaif->follow_user : $jaif->settings['follow_user'];
$url .= $user; 
?>

<?php if($jaif->follow_link == true) : 
$follow_text = sprintf(__(JAIF::$messages['follow-message'],'just-another-insta-feed'), $user);
?>

<a class="jaif-button" href="<?php echo $url; ?>" target="_blank"><?php echo $follow_text; ?></a>
<?php endif; ?>