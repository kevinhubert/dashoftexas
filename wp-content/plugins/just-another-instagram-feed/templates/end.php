<?php 
  /*
    After loop template
  */
  if( !isset( $jaif ) ) return;
?>
<?php if( $jaif->slider == true ) : //Add Carousel Inits ?>
<!--<script type="text/javascript">
(function ($){
$(document).ready( function(){
  var wrap = $('.<?php echo "carousel-{$jaif->layout}"; ?>');
  wrap.carousel( {
    orientation: 'vertical',
    pagination: false,
    itemsPerTransition: 2,
    insertPrevAction: function () { return $('<a href="#" class="rs-carousel-action rs-carousel-action-prev"></a>').appendTo(this); },
    insertNextAction: function () { return $('<a href="#" class="rs-carousel-action rs-carousel-action-next"></a>').appendTo(this); },
  } );
} )
})(jQuery);
</script>-->
<?php endif; ?>

<?php if( $jaif->fancybox == true ) : //Add Fancybox Inits ?>
<script type="text/javascript">
jaif_fancified = true;
</script>
<?php endif; ?>

<br class="clear"/>
</div>