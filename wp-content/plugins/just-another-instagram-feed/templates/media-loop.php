<?php  
/*
  Media Loop Template

  @description The main loop template for displaying media searches
*/

if( !isset( $data_obj ) ) return;  

?>
<!--START LOOP-->
<?php if( isset( $this->ajax ) && false == $this->ajax ) : ?>
<?php do_action( 'jaif-before-loop', $this );  ?>
<?php endif; ?>

<?php if( count($data_obj) == 0 ) : ?>
  <li class="jaif-auth-dialog">
    <h3><?php echo $this->messages['nothing']; ?></h3>
    <p><?php echo $this->messages['noMedia']; ?></p>
  </li>
<?php else : ?>
<?php 
  foreach($data_obj as $index => $data) {
    jaif_media_loop_item( $data );
  } 
?>
<?php endif; ?>

<?php if( isset( $this->ajax ) && false == $this->ajax ) : ?>
<?php do_action( 'jaif-after-loop', $this );  ?>
<?php do_action( 'jaif-end', $this );  ?>
<?php endif; ?>

<!--END LOOP <?php echo $this->insta_query->pagination->next_max_id; ?> -->