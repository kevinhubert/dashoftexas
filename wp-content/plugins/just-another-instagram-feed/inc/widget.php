<?php 
class jaif_widget extends WP_Widget{
  function __construct(){
    parent::__construct(
      'jaif_widget',
      'Just Another Instagram Feed',
      array( 'description' => __( 'A custom Instagram feed', 'jaif' ) )
    );
  }

  function widget( $args, $instance ){
    global $jaif;

    extract( $args );
    
    echo $before_widget;
    
    if( !empty( $instance['title'] ) ){
      echo $before_title;
      echo ( $instance['title_link'] != '' ) ? 
      sprintf( '<a href="%s">%s</a>', $instance['title_link'], $instance['title'] ) : $instance['title'];
      echo $after_title;
    }
    
    $instance = array_merge( $instance, $_GET );
    
    echo jaif( $instance );
    
    if( $instance['description'] != '' )
      echo '<p class="jaif-description">'.$instance['description'].'</p>';	
    
    if( $instance['follow_link'] == true ){
      $url = ( $instance['follow_user'] ) 
      ? 'http://instagram.com/'.$instance['follow_user'] 
      : 'http://instagram.com/'.$jaif->settings['follow_user'];
      echo '<a class="jaif-follow-button" href="'.$url.'" target="_blank">'.$instance['follow_text'].'</a>';
    }
    
    echo $after_widget;
  }

  function form( $instance ){
    global $jaif;
	  
    $fields = array( 
      'title' => array(
	'type' => 'text',
	'form' => array( 
	  'label' => 'Title',
	)
      ),
      'title_link' => array(
	'type' => 'text',
	'form' => array( 
	  'label' => 'Title link',
	)
      ),
      'description' => array(
	'type' => 'textarea',
	'form' =>	array( 
	  'label' => 'Description',
	)
      ),
      'follow_link' => array(
	'type' => 'select',
	'form' => array( 
	  'label' => 'Add Follow Link',
	  'values' => array( 'No', 'Yes' ),
	  'val_is_index' => true
	)
      ),
      'follow_user' => array(
	'type' => 'text',
	'form' => array( 
	  'label' => 'Follow User',
	)
      ),
      'follow_text' => array(
	'type' => 'text',
	'form' => array( 
	  'label' => 'Follow Text',
	)
      ),
      'page_limit' => array(
	'type' => 'text',
	'form' => array( 
	  'label' => 'Results to show',
	  'class' => 'small-input'
	)
      ),
      'search_type' => array(
	'type' => 'select',
	'form' =>	array( 
	  'label' => 'Search type',
	  'values' => array( 'user' => 'User', 'tag' => 'Tag' ),
	  'val_is_index' => true
	)
      ),
      'search' => array(
	'type' => 'text',
	'form' =>	array( 
	  'label' => 'Search value',
	)
      ),
      'media_size' => array(
	'type' => 'select',
	'form' =>	array( 
	  'label' => 'Image size',
	  'values' => array( 
	    'thumbnail' => '(150 x 150)', 
	    'low_resolution' => '(306 x 306)', 
	    'standard_resolution' => '(640 x 640)' 
	  ),
	  'val_is_index' => true
	)
      ),
      'media_link' => array(
	'type' => 'text',
	'form' => array( 
	  'label' => 'Media link',
	)
      ),
      'link_target' => array(
	'type' => 'select',
	'form' => array( 
	  'label' => 'New window link?',
	  'values' => array( 'No', 'Yes' ),
	  'val_is_index' => true
	)
      ),
      'columns' => array(
	'type' => 'select',
	'form' => array( 
	  'label' => 'Number of columns',
	  'values' => array( 'Custom', 1, 2, 3, 4, 5, 6 ),
	  'val_is_index' => true
	)
      ),
      'pagination' => array(
	'type' => 'select',
	'form' => array( 
	  'label' => 'Enable pagination?',
	  'values' => array( 'No', 'Yes' ),
	  'val_is_index' => true
	)
      ),
      'slider' => array(
	'type' => 'select',
	'form' => array( 
	  'label' => 'Enable slider?',
	  'values' => array( 'No', 'Yes' ),
	  'val_is_index' => true
	)
      ),
    );
    
    $instance = wp_parse_args( $instance, $jaif->get_defaults() ); ?>
    
    <table>
    <?php foreach( $fields as $name => $arg ){
      extract( $arg );
      $value = $instance[$name];

      if( $type == 'text' && 'array' == gettype( $value ) ){
	$value = ( !empty($value) ) ? implode( ',', $value ) : null;
      }
      
      $form['value'] = $value;
      $form['name'] = $name;
      
      echo '<tr>' . $this->form_field( $type, $form ) . '</tr>';
    } ?>
    </table>
<?php }

  function update( $new_instance, $old_instance ){
    return $new_instance;
  }

  /*
    Generates the HTML markup for the given type of input field, including labels and proper input names and ids

    @return String
  */
  function form_field( $type = null, $args = array() ){
    extract( $args );
    
    $class = isset( $class ) ? $class : '';
    $colspan = isset( $colspan ) ? $colspan : 1;
    $label_cs =  isset( $label_cs ) ? $label_cs : 1;
    $val_is_index = isset( $val_is_index ) ? $val_is_index : false;

    $widget = is_a( $this, 'WP_Widget' );
    
    $input_id = $this->get_field_id( $name );

    $input_name = $this->get_field_name( $name );
    
    $return = '';

    $return .= ( $widget == true ) ? sprintf( '<td colspan="%d">', $label_cs ) : '';
    
    $return .= sprintf( '<label for="%s" class="op-label">%s:</label>', $input_name, $label  );
    
    $return .=  ( $widget == true ) ? sprintf( '</td><td colspan="%s">', $colspan ) : '';

    switch( $type ){
      case 'select':
	$return .= '<select id="'. $input_id .'" name="'. $input_name .'">';
	$return .= '<option value="">--</option>';
	foreach( $values as $i => $val ){
	  if( is_object( $val ) && is_a( $val, 'op_termGroup' ) ){
	    $selected = ( $val->ID == $id ) ? 'selected' : '';
	    $return .= sprintf( '<option value="%s" %s>%s</option>', $val->ID, $selected, $val->ID.' - '.$val->name ); 
	  }elseif( !is_object( $val ) && false == $val_is_index ){
	    $selected = ( $value == $val ) ? 'selected' : '';		
	    $return .= sprintf( '<option value="%s" %s>%s</option>', $val, $selected, $val ); 
	  }else{
	    $selected = ( $value == $i ) ? 'selected' : '';		
	    $return .= sprintf( '<option value="%s" %s>%s</option>', $i, $selected, $val ); 
	  }
	}
	$return .= '</select>';
      break;

      case 'checkbox':
	foreach( $values as $i => $val ){
	  if( is_object( $val ) ){
	    $checked = ( in_array( $val->name, $value ) ) ? 'checked' : '';
	    $return .= sprintf( '<label class="op-label"><input type="%s" name="%s" id="%s" value="%s" %s>%s</label>',
	    $type, $input_name.'[]', $input_id.'[]', $val->name, $checked, $val->label );
	  }
	}
      break;

      case 'textarea':
      $return .= sprintf( '<textarea id="%s" name="%s" class="%s">%s</textarea>', 
      $input_id, $input_name, $class, $value );
      break;

      case 'text':
      case 'hidden':
      default:
	$return .= sprintf( '<input type="%s" value="%s" id="%s" name="%s" class="%s">', 
	$type, $value, $input_id, $input_name, $class );
      break;
    }
    $return .= ( $widget == true ) ? '</td>' : '';		

    return $return;
  }
}
?>