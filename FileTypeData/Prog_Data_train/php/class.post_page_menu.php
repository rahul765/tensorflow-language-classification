<?php
class etm_tinymce_extra_button {
	var $post;
	
	function etm_tinymce_extra_button ($args=array()){
		add_action("admin_head-post.php",array(&$this,'insert_tool_head'));
		add_action("admin_head-post-new.php",array(&$this,'insert_tool_head'));		
		add_action("admin_head-index.php",array(&$this,'insert_tool_head'));	
		
				
		add_action('media_buttons_context',array(&$this,'media_buttons_context'));
		//add_action('wp_ajax_mce_list_fields', array(&$this,'mce_list_fields'));
	}
	
	function insert_tool_head(){
    	// add creation of statistics
		wp_register_style( 'etm-insert-tool', EASY_TRANSLATION_MANAGER_URL.'css/insert_tool.css', array(),'1.0.0');
		wp_print_styles('etm-insert-tool');
		wp_register_script( 'etm-insert-tool', EASY_TRANSLATION_MANAGER_URL.'js/insert_tool.js', array(),'1.0.0');
		wp_print_scripts('etm-insert-tool');
 		echo '<script>
 				var EASY_TRANSLATION_MANAGER_URL = "'.EASY_TRANSLATION_MANAGER_URL.'";
 		</script> '; 		
		
		add_action('admin_footer',array(&$this,'shortcode_dialog'),1);
	}
	
	function shortcode_dialog(){
?>
<div id="etm-insert-tool" class="etm-dialog-cont">
	<div class="etm-dialog-overlay"></div>
	<div class="etm-dialog">
		<div class="etm-dialog-head">
			<div class="etm-dialog-head-text"><?php _e("Easy Translation Manager Menu", 'etm')?></div>
			<div class="etm-close-icon">
				<a class="etm-close-icon-a" title="Close" href="javascript:void(0);" alt="Close"><img src="<?php echo EASY_TRANSLATION_MANAGER_URL; ?>images/tb-close.png" /></a>			
			</div>
		</div>	
		<div class="etm-dialog-body" style="width:340px;">
				<label class="etm-mce-label"><?php _e( 'Width in px or % ( 0 : auto ):', 'etm' ); ?></label>
				<div class="etm-mce-input"><input class="widefat" id="etm_desing_menu_width" type="text" /></div>

				<label class="etm-mce-label"><?php  _e('Select layout style','etm'); ?></label>
				<div class="etm-mce-input">
					<select class="widefat" id="etm_desing_menu_type" type="text">
					<?php $desing_types = array(
					'3'=>__('Bouncing List','etm'),
					'4'=>__('Box Slide','etm'),	
					'5'=>__('Rotating Bars','etm'),
					'6'=>__('Fluid Grid','etm'),
					'7'=>__('Responsive Circle','etm'),
					'0'=>__('Basic Drop-down list','etm'),
					'1'=>__('Side-by-Side','etm'),
					'2'=>__('Side-by-Side (Remove current flag)','etm'));
				
					foreach($desing_types as $key => $desing_types){
						echo '<option value="'.$key.'" ';
						echo '>'.$desing_types.'</option>';		
					}?>
				
					</select>
			</div>

				<label class="etm-mce-label"><?php  _e('Select flag size','etm'); ?></label>
				<div class="etm-mce-input"><select class="widefat" id="etm_desing_menu_flag_size" type="text">
				<?php $flag_sizes = array(
					'0'=>__('Small','etm'),
					'1'=>__('Medium','etm'),
					'2'=>__('Large','etm'),
					'3'=>__('X-Large','etm')
				);
				
				foreach($flag_sizes as $key => $flag_size){
					echo '<option value="'.$key.'" ';
					echo '>'.$flag_size.'</option>';		
				}?>
				
				</select></div>	
				
				<label class="etm-mce-label"><?php  _e('Select Display Type','etm'); ?></label>
				<div class="etm-mce-input"><select class="widefat" id="etm_desing_menu_info" type="text">
				<?php $flag_types = array('0'=>__('Show flag and text','etm'),
					'1'=>__('Show only flag','etm'),
					'2'=>__('Show only text','etm'));
				
				foreach($flag_types as $key => $flag_type){
					echo '<option value="'.$key.'" ';
					echo '>'.$flag_type.'</option>';		
				}?>
				
				</select></div>
				
			
				<label class="etm-mce-label" style="padding-right: 10px;"><?php  _e('Hide arrow','etm'); ?></label><input type="checkbox" id="etm_hidearrow" value="1">
			
			
			<div id="etm_shortcode" class="etm-mce-input" style="display:none;">[etm_menu]</div>
				<div class="etm-mce-buttons">
				<input type="button" OnClick="javascript:insert_etm_shortcode();" class="button-primary" value="<?php _e("Insert Menu", 'etm')?>" />
			</div>
        </div>
		<div class="etm-dialog-body">
        	<div id="preview_etm">
					<label class="etm-mce-label"><?php _e("CSS Class", 'cgw')?></label>
					<div class="etm-mce-input"><input style="width:350px;" type="text" id="etm_class"></div>
        	
					<label class="etm-mce-label"><?php _e("Style", 'etm')?></label>
					<div class="etm-mce-input"><input style="width:350px;" type="text" id="etm_style"></div>
					
					<label class="etm-mce-label"><?php _e("CSS properties", 'etm')?></label>
					<div class="etm-mce-input" style="margin-top: 3px;">
					
					<label for="border">Border</label>
					<input type="text" onKeyUp="etm_input_change_value()" value="" style="width: 30px; margin-right: 10px;" name="etm_border" id="etm_border" maxlength="5">

					<label for="vspace">Vertical space</label>
					<input type="text" onKeyUp="etm_input_change_value()" value="" style="width: 30px; margin-right: 10px;" name="etm_vspace" id="etm_vspace" maxlength="5">

					<label for="hspace">Horizontal space</label>
					<input type="text" onKeyUp="etm_input_change_value()" value="" style="width: 30px; margin-right: 10px;" name="etm_hspace" id="etm_hspace" maxlength="5">
				</div>
				
				<label class="etm-mce-label"><?php _e("Alignment", 'etm')?></label>
				<div class="etm-mce-input" style="margin-top: 3px;" id="etm_radio_list">
					<input type="radio" value="alignnone" checked="checked"id="etm_alignnone" name="etm_align">
					<label class="etm-image-align-label etm-image-align-none-label" for="alignnone">None</label>
					<input type="radio" value="alignleft" id="etm_alignleft" name="etm_align">
					<label class="etm-image-align-label etm-image-align-left-label" for="alignleft">Left</label>
					<input type="radio" value="aligncenter" id="etm_aligncenter" name="etm_align">
					<label class="etm-image-align-label etm-image-align-center-label" for="aligncenter">Center</label>
					<input type="radio" value="alignright" id="etm_alignright" name="etm_align">
					<label class="etm-image-align-label etm-image-align-right-label" for="alignright">Right</label>
				</div>  
			</div>
        </div>
	</div>   
</div>
<?php	
	}
	
	function media_buttons_context($context){
		$screen = get_current_screen();
		if(true){
		 	$out = '<a class="button" id="etm-insert-tool-trigger" href="javascript:void(0);" title="'. __("Add Easy Translation Manager Menu", 'cgm').'">'.__("ETM", 'cgm').'</a>';	
		} else {
        	$out = '<a id="etm-insert-tool-trigger" href="javascript:void(0);" title="'. __("Add Easy Translation Manager Menu", 'etm').'"><img src="'.EASY_TRANSLATION_MANAGER_URL."/images/etm16.png".'" alt="'. __("Add Easy Translation Manager Menu", 'etm') . '" /></a>';
		}
        return $context . $out;
	}
}
?>