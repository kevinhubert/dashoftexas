var jaifs = {}; 
var jaif_fancified = false;

( function($){  
  /* Just Another Instagram Feed Init */
  function jaif_init(){
    if( jaif_fancified == true ){
      var jaif_fancybox = $('.jaif-media-link-fancybox');
      jaif_fancify( jaif_fancybox );
    }
  }

  /* Fancybox Init */
  function jaif_fancify( fancify_this ){
    var w = $( window ).width();
    var h = $( window ).height();
    var hw = ( h > w ) ? w-100 : h-100;
    if( hw > 640 )
      hw = 640;

    var vars = {
      loop: false,
      titlePosition: 'inside',
      overlayColor:'#000',
      overlayOpacity:0.8,
    };
    $('#fancybox-overlay').width( w );
    $('#fancybox-overlay').height( h );
    fancify_this.each( function(){
      vars.href = $(this).data('fancybox-href');
      var parent = $(this).closest( 'li.jaif-loop-item' );
      var caption = parent.find('.jaif-media-caption');
      if( parent.hasClass('video') ) {
	      vars.type = 'iframe';
	      vars.height = hw;
	      vars.width = hw;
      }else{
	      vars.type = 'image';
      }
      if (caption.length) {
	      vars.title = caption.html();
      }
      $(this).fancybox( vars );
    } )
  }
  
  /* Makes a query request */
  function make_query( vars ){
    data_str.jaif_ajax = true;
    return $.ajax( vars );
  }
  
  function preload(sources){
    $.each( sources, function(i,source) { $.get(source); } );
  }
  
  function preloadImage( _imgUrl, _container ){
    var image = new Image();
    image.src = _imgUrl;
    image.onload = function(){
      $(_container).fadeTo(500, 1);
    };
}
  
  function check_pagination( type, elem ){
    switch(type){
      case 'older':
	if( jaif_vars.og_vars.max_id != undefined ){
	  data_str.max_id = jaif_vars.og_vars.max_id;
	  return true;
	}else {
	  if(elem != undefined && !elem.hasClass('no-more-posts')){
	      elem.addClass('no-more-posts');
	      elem.html( 'No More Posts' );
	  }
	  return false;
	}
      break;
      
      case 'newest':
	if( jaif_vars.og_vars.min_id !== undefined ){
	  data_str.min_id = jaif_vars.og_vars.min_id;
	  return true;
	}else{
	  return false;
	}
      break;
      
      default :
	return false;
    }
  }

  function get_vars( form ){
    form.find('input[type=hidden]').each( function() {
      var name = $(this).attr('name');
      var value = $(this).val();
      jaifs[name] = value;
    } ); 
    return jaifs;
  }

  /* Resets the data_str variable */
  function reset_vars(){
    data_str = jaif_vars.og_vars;
  } 
  

  var delay = ( function(){
    var timer = 0;
    return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
    };
  } )();

  $(document).ready( function(){
    
    jaif_init();
    
    $(window).resize( function(){ 
      delay( function(){ jaif_init(); }, 500 );
    } )

    /* Setup click handler for the load more button */
    $(document).on( 'submit', 'form.jaif-pagination', function(event){
      
      event.preventDefault();
      
      jaif_vars.og_vars = get_vars( $(this) );
      reset_vars();

      if( check_pagination( 'older', $(this) ) ){
	var form = $(this);
  var disabled = form.hasClass('disabled');
	var parent = form.parent().find( '.jaif-media-loop-list' );
	var button = form.find('button[type="submit"]');
	
	if( disabled === true || button.hasClass( 'loading' ) ){
	  return false;
	}
	
	data_str.jaif_ajax = true;
	data_str.jaif_action = 'get';
	data_str.action = 'jaif';
	ajax_var = {
		type:'GET',
		data:data_str,
		cache:true,
		dataType:'json',
		url:jaif_vars.ajaxurl
	};
	button.addClass( 'loading' );
	button.html( 'Loading...' );

	var older = make_query(ajax_var);

	if( older !== undefined ){
	    older.success(function(data){
      /*form.fadeOut( function(){
		  form.remove();
	    } );*/
	      
	      //Set the input vars for the form for the next page
	      var max_id = form.find('input[name="max_id"]')[0];
	      max_id.value = data.pagination['next_max_id'];

        button.removeClass( 'loading' );
        if(max_id.value == "undefined"){
          button.html( 'No More Posts' );
          button.attr("disabled", "disabled");
          form.addClass("disabled");
        }else{
          button.html( 'Load More' );
        }
	      
          //Append the html content to the curent DOM list
	        $(data.html).appendTo( parent );
	      
	        //Hide each of the ajax-loaded-items 
	        $('.ajax-loaded-item').find('img').hide();
	      
	        //Loop through and load the images, then show the elements
	        $('.ajax-loaded-item').each(function(){
		      if( jaif_fancified == true ){
		        var current = $(this).find('.jaif-media-link-fancybox');
		        jaif_fancify( current );
		      } 
		      preloadImage( $(this).find('img').attr('src'), $(this).find('img') );
		      $(this).removeClass('ajax-loaded-item');
	        } );
      } );
	    console.log( jaifs );
	}else{
	  button.removeClass( 'loading' );
	  button.html('Load More');
	}
      }
    } );
    
    $('.jaif-user-action').on('click',function(event){
      event.preventDefault();
      var button = $(this);
      $parent = $(this).parent();
      var input_id = $parent.find('input[name="id"]');
      var input_action = $parent.find('input[name="jaif_action"]');
      var id = input_id.val();
      var action = input_action.val();
      var classList = button[0].className.split(/\s+/);
      for(var k = 0; k<classList.length;k++){
	if(classList[k].substring(0,7) === 'action-'){
	  var removeClass = classList[k];
	}
      }
      ajax_var = {
	type:'POST',
	data:{
	  'id':id, 
	  'jaif_action':action,
	  'jaif_ajax':true
	},
	dataType:'json',
	cache:true,
	url:jaif_vars.ajaxurl
      };
      var user_result = make_query( ajax_var );	
      if( user_result != undefined ){
	user_result.success(function(data){
	  console.log(data);
	  input_action.val(data.new_action.rev_action);
	  button.html(data.new_action.name);
	  button.removeClass(removeClass);
	  button.addClass('action-'+data.new_action.action);
	} );
      }
    } );
  } )
} )(jQuery);