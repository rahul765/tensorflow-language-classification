<?php

class etm_langaush_widget extends WP_Widget {
function etm_langaush_widget() {
	
		$widget_ops = array( 'classname' => 'etm_widgets_select_menu', 'description' => __( 'This will allow you to select language. Set available languages in Translation > Options.', 'etm' ) );
		$this->WP_Widget( 'etm_widgets_select_menu', __( 'ETM Select Language', 'etm' ), $widget_ops );
		$this->alt_option_name = 'etm_widgets_select_menu';

		add_action( 'save_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache' ) );

	}
	function widget( $args, $instance ) {
		global $easy_translation_manager_flag_menu;

		echo '<aside style="padding-bottom:20px">';
		if(!empty($instance['title'])){
			echo '<h3 style="padding-bottom:5px" class="widget-title">'.__($instance['title'],'etm').'</h3>';
		}
		$tmp_array_data = array();
		$tmp_array_data['menu_width'] = $instance['width'];		
		$tmp_array_data['menu_flag'] = $instance['desing_menu_flag_size'];
		$tmp_array_data['menu_layout'] = $instance['desing_menu_type'];
		$tmp_array_data['menu_display'] = $instance['desing_menu_info'];
	    $tmp_array_data['menu_aligment'] = $instance['align'];
		$tmp_array_data['menu_hidearrow'] = $instance['hidearrow'];
		
		$easy_translation_manager_flag_menu->create_menu($tmp_array_data,'widget'); 
		echo '</aside>';
	}
	
	function flush_widget_cache() {
		wp_cache_delete( 'etm_widgets_select_menu', 'widget' );
	}	
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['desing_menu_info'] = strip_tags( $new_instance['desing_menu_info'] );
		$instance['desing_menu_flag_size'] = strip_tags( $new_instance['desing_menu_flag_size'] );
		$instance['desing_menu_type'] = strip_tags( $new_instance['desing_menu_type'] );
		$instance['align'] = strip_tags( $new_instance['align'] );		
		$instance['width'] = strip_tags( $new_instance['width'] );		
		$instance['hidearrow'] = strip_tags( $new_instance['hidearrow'] );	
		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['etm_widgets_select_menu'] ) )
			delete_option( 'etm_widgets_select_menu' );

		return $instance;
	}	
	
	function form( $instance ) {
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
		$desing_menu_info = isset( $instance['desing_menu_info']) ? esc_attr( $instance['desing_menu_info'] ) : '';
		$desing_menu_flag_size = isset( $instance['desing_menu_flag_size']) ? esc_attr( $instance['desing_menu_flag_size'] ) : '';		
		$desing_menu_type = isset( $instance['desing_menu_type']) ? esc_attr( $instance['desing_menu_type'] ) : '';		
		$width = isset( $instance['width']) ? esc_attr( $instance['width'] ) : '';
		$align = isset( $instance['align']) ? esc_attr( $instance['align'] ) : '';
		$menu_hidearrow = (isset( $instance['hidearrow']) && $instance['hidearrow']) ? 'checked="checked"' : '';
		
		
		
		
?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'etm' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title ?>" /></p>
				
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'desing_menu_type' ) ); ?>"><?php  _e('Select layout style','etm'); ?></label><select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'desing_menu_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desing_menu_type' ) ); ?>" type="text">
			<?php $desing_types = array(
				'3'=>__('Bouncing List','etm'),
				'4'=>__('Box Slide','etm'),	
				'5'=>__('Rotating Bars','etm'),
				'6'=>__('Fluid Grid','etm'),
				'7'=>__('Responsive Circle','etm'),
				'0'=>__('Basic Drop-down list','etm'),
				'1'=>__('Side-by-Side','etm'),
				'2'=>__('Side-by-Side (Remove current flag)','etm')			
			);
			
			foreach($desing_types as $key => $desing_types){
				echo '<option value="'.$key.'" ';
				
				if($key == $desing_menu_type && !empty($desing_menu_type)){
					echo ' selected="selected" ';
				}
				
				echo '>'.$desing_types.'</option>';		
			}?>
			
			</select></p>
				
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'desing_menu_flag_size' ) ); ?>"><?php  _e('Select flag size','etm'); ?></label><select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'desing_menu_flag_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desing_menu_flag_size' ) ); ?>" type="text">
			<?php $flag_sizes = array(
				'0'=>__('Small','etm'),
				'1'=>__('Medium','etm'),
				'2'=>__('Large','etm'),
				'3'=>__('X-Large','etm'));
			
			foreach($flag_sizes as $key => $flag_size){
				echo '<option value="'.$key.'" ';
				
				if($key == $desing_menu_flag_size && !empty($desing_menu_flag_size)){
					echo ' selected="selected" ';
				}
				
				echo '>'.$flag_size.'</option>';		
			}?>
			
			</select></p>	
			
				
				
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'desing_menu_info' ) ); ?>"><?php  _e('Select Display Type','etm'); ?></label><select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'desing_menu_info' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desing_menu_info' ) ); ?>" type="text">
				<?php $flag_types = array('0'=>__('Show flag and text','etm'),
					'1'=>__('Show only flag','etm'),
					'2'=>__('Show only text','etm'));
				
				foreach($flag_types as $key => $flag_type){
					echo '<option value="'.$key.'" ';
					
					if($key == $desing_menu_info && !empty($desing_menu_info)){
						echo ' selected="selected" ';
					}
					
					echo '>'.$flag_type.'</option>';		
				}?>
				
			</select></p>	
				
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php  _e('Aligment','etm'); ?></label><select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" type="text">
			<?php $aligns_list = array('alignleft'=>__('Left','etm'),
				'aligncenter'=>__('Center','etm'),
				'alignright'=>__('Right','etm'));
			
			foreach($aligns_list as $key => $align_list){
				echo '<option value="'.$key.'" ';
				
				if($key == $align){
					echo ' selected="selected" ';
				}
				
				echo '>'.$align_list.'</option>';		
			}?>
			
			</select></p>
			
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php _e( 'Width in px or %:', 'etm' ); ?></label><input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="text" value="<?php echo $width ?>" /></p>
			
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'hidearrow' ) ); ?>"><?php _e( 'Hide arrow', 'etm' ); ?></label><input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hidearrow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hidearrow' ) ); ?>" type="checkbox" style="margin-left: 10px;" value="1" <?php echo $menu_hidearrow ?> /></p>
			
				
		<?php
	}
	
}
?>