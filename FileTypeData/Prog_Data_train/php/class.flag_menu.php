<?php
class easy_translation_manager_flag_menu {
	var $translation_dir = 'ltr';
	var $check_lang_codrops = array();
	var $array_size_number = array('-2px','2px','7px','14px');
	var $flag_size_array = array(16,24,32,48);
	var $menu_settings_names = array('desing_menu_width'=>'menu_width','desing_menu_align'=>'menu_aligment','desing_menu_info'=>'menu_display','desing_menu_flag_size'=>'menu_flag','desing_menu_type'=>'menu_layout','desing_menu_hidearrow'=>'menu_hidearrow');

	function easy_translation_manager_flag_menu(){
	    global $easy_translation_manager_plugin;
		$this->controle_menu();
		add_shortcode('etm_menu', array(&$this,'etm_shortcode_function'));
		add_action('widgets_init', array(&$this,"etm_widgets_init" ));
	}
	
    function controle_menu(){
    	global $easy_translation_manager_plugin;
    	if(is_admin() && !empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pos_dashboard']) and $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pos_dashboard'] == 1){
    	    add_action('wp_dashboard_setup', array(&$this,'add_dashboard_widgets') );
    	}
    	
    	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pos']) and $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pos'] == 1){
    		add_action('etm_languagemenu', array(&$this,'create_menu') ); 
    	}
    	
    	if(!is_admin() && !empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pos_overlay']) and $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pos_overlay'] == 1){
    		add_action( 'wp_enqueue_scripts', array(&$this,'dashboard_enqueue_script'));
    		add_action('wp_head', array(&$this,'etm_overlay_menu'), 100);    
    	}
    }
    
    // ---------------------------------- ADD WIDGET ---------------------------------------
 
 	function etm_widgets_init(){
 		register_widget( 'etm_langaush_widget' );
 	}    
    
    // ---------------------------------- ADD dashboard ---------------------------------------    
   
	function add_dashboard_widgets() {
    	global $easy_translation_manager_plugin;
		if(empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_title_dashboard'])){
			$easy_translation_manager_plugin->etm_tools_retrive['desing_menu_title_dashboard'] = 'Select language';
		
		}
		wp_add_dashboard_widget('etm_dashboard_widget', __($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_title_dashboard'],'etm'), array(&$this,'dashboard_widget_function'));	
	
		global $wp_meta_boxes;
	
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
		$example_widget_backup = array('etm_dashboard_widget' => $normal_dashboard['etm_dashboard_widget']);
		unset($normal_dashboard['etm_dashboard_widget']);
		$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}  
	
	function dashboard_widget_function() {
	    global $easy_translation_manager_plugin;
	    

	    
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_type_dashboard'])){
			$desing_menu_type = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_type_dashboard'];
		} else {
			$desing_menu_type = '0';
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_info_dashboard'])){
			$desing_menu_info = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_info_dashboard'];
		} else {
			$desing_menu_info = 0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_flag_size_dashboard'])){
			$desing_menu_flag_size = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_flag_size_dashboard'];
		} else {
			$desing_menu_flag_size = 0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_align_dashboard'])){
			$desing_menu_aligment = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_align_dashboard'];
		} else {
			$desing_menu_aligment = 0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_hidearrow_dashboard'])){
			$desing_menu_hidearrow = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_hidearrow_dashboard'];
		} else {
			$desing_menu_hidearrow = 0;
		}
		
		$tmp_array_data['menu_hidearrow'] = $desing_menu_hidearrow;
		$tmp_array_data['menu_flag'] = $desing_menu_flag_size;
		$tmp_array_data['menu_layout'] = $desing_menu_type;
		$tmp_array_data['menu_display'] = $desing_menu_info;
	    $tmp_array_data['menu_aligment'] = $desing_menu_aligment;
	    
	    
		$this->create_menu($tmp_array_data,'dashboard');
		
	}  	
	
    // ---------------------------------- ADD SHORTCODE ---------------------------------------
	function etm_shortcode_function($atts) {

		$data_array = array();

		if(empty($atts['class'])){
			$atts['class'] = '';
		}
		
		if(empty($atts['hidearrow'])){
			$atts['hidearrow'] = '';
		}
		
		if(empty($atts['style'])){
			$atts['style'] = '';
		}		
		
		if(empty($atts['aligment'])){
			$atts['aligment'] = '';
		} else {
			$data_array['menu_aligment'] = $atts['aligment'];
		}
		
		if(!empty($atts['width'])){
			$data_array['menu_width'] = $atts['width'];
		}
		if(!empty($atts['display'])){
			$data_array['menu_display'] = $atts['display'];
		}
		if(!empty($atts['flag'])){
			$data_array['menu_flag'] = $atts['flag'];
		}
		if(!empty($atts['layout'])){
			$data_array['menu_layout'] = $atts['layout'];
		}
		
		if(!empty($atts['hidearrow'])){
			$data_array['menu_hidearrow'] = $atts['hidearrow'];
		}
		
		if(!empty($atts['style']) || !empty($atts['class']) || !empty($atts['aligment'])){
		
			$content =  '<div class="'.$atts['class'].' ' .$atts['aligment'].'" ';
				if(!empty($atts['style'])){
					$content .= 'style="'.$atts['style'].'" ';
				}
			$content .= '>';
			
		}
		
		$content .= $this->create_menu($data_array,'shortcode');
		if(!empty($atts['style']) || !empty($atts['class']) || !empty($atts['aligment'])){
			$content .= '</div>';
		}
		
		return $content;
	} 
	
    // ---------------------------------- Overlay menu ---------------------------------------
    
	function dashboard_enqueue_script() {
    	global $easy_translation_manager_plugin;
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_type_overlay'])){
			$desing_menu_type = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_type_overlay'];
		} else {
			$desing_menu_type = '0';
		}
	
	
		if($desing_menu_type == 3){
			wp_enqueue_style( 'codrops_cs-skin-elastic', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-bouncing-list.css', array(),'1.0.0');
		} else if($desing_menu_type == 4){
			wp_enqueue_style( 'codrops_cs-skin-slide', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-box-slide.css', array(),'1.0.0');
		} else if($desing_menu_type == 5){		
			wp_enqueue_style( 'codrops_cs-skin-rotate', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-rotating-bars.css', array(),'1.0.0');
		} else if($desing_menu_type == 6){
			wp_enqueue_style( 'codrops_cs-skin-boxes', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-fluid-grid.css', array(),'1.0.0');		
		} else if($desing_menu_type == 7){
			wp_enqueue_style( 'codrops_cs-skin-circular', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-responsive-circle.css', array(),'1.0.0');		
		}
	}
    
	function etm_overlay_menu() {
	    global $easy_translation_manager_plugin;
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_placement_overlay'])){
			$desing_menu_placement_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_placement_overlay'];
		} else {
			$desing_menu_placement_overlay = 'topright';
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_type_overlay'])){
			$desing_menu_type_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_type_overlay'];
		} else {
			$desing_menu_type_overlay = '0';
		}
		
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_xpostion_overlay'])){
			$desing_menu_xpostion_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_xpostion_overlay'];
		} else {
			$desing_menu_xpostion_overlay = 0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_ypostion_overlay'])){
			$desing_menu_ypostion_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_ypostion_overlay'];
		} else {
			$desing_menu_ypostion_overlay = 0;
		}		
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_info_overlay'])){
			$desing_menu_info_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_info_overlay'];
		} else {
			$desing_menu_info_overlay = 0;
		}
		

		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_flag_size_overlay'])){
			$desing_menu_flag_size_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_flag_size_overlay'];
		} else {
			$desing_menu_flag_size_overlay = 0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_hidearrow_overlay'])){
			$desing_menu_hidearrow_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_hidearrow_overlay'];
		} else {
			$desing_menu_hidearrow_overlay = 0;
		}		
		
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pixel_jump_overlay'])){
			$desing_menu_pixel_jump_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_pixel_jump_overlay'];
		} else {
			$desing_menu_pixel_jump_overlay = 0;
		}		
		
		if ( is_admin_bar_showing() && $desing_menu_pixel_jump_overlay) {
			$desing_menu_ypostion_overlay += 32;
		}

		if(($desing_menu_placement_overlay == 'topright' || $desing_menu_placement_overlay == 'topcenter' || $desing_menu_placement_overlay == 'topleft' || $desing_menu_placement_overlay == 'sidetopright' || $desing_menu_placement_overlay == 'sidetopleft') && $desing_menu_ypostion_overlay < 32){
		echo '<style type="text/css">
				body.admin-bar #wphead {
					padding-top: 0;
				}
				body.admin-bar #footer {
					padding-bottom: 28px;
				}
				#wpadminbar {
					top: auto !important;
					bottom: 0;
				}
				#wpadminbar .quicklinks .menupop ul {
					bottom: 28px;
				}
				
				#wpadminbar .ab-top-menu li .ab-sub-wrapper {
					bottom: 32px;
				}
				
				#wpadminbar .ab-top-menu li .ab-sub-wrapper ul {
					bottom:0px;
				}
			</style>';
		}
		
		$css = '';
		
		if($desing_menu_placement_overlay == 'topleft' || $desing_menu_placement_overlay == 'topright'){
			$css .= 'top:'.$desing_menu_ypostion_overlay.'px;';
		} else if($desing_menu_placement_overlay == 'bottomleft' || $desing_menu_placement_overlay == 'bottomright'){
			$css .= 'bottom:'.$desing_menu_xpostion_overlay.'px;';
		} else if($desing_menu_placement_overlay == 'sidetopleft' || $desing_menu_placement_overlay == 'sidetopright'){
			$css .= 'top:'.$desing_menu_ypostion_overlay.'px;';
		} else if($desing_menu_placement_overlay == 'sidebottomleft' || $desing_menu_placement_overlay == 'sidebottomright'){
			$css .= 'bottom:'.$desing_menu_ypostion_overlay.'px;';
		}
		
		

		if($desing_menu_placement_overlay == 'topleft' || $desing_menu_placement_overlay == 'bottomleft'){
			$css .= 'left:'.$desing_menu_xpostion_overlay.'px;';
		} else if($desing_menu_placement_overlay == 'topright' || $desing_menu_placement_overlay == 'bottomright'){
			$css .= 'right:'.$desing_menu_xpostion_overlay.'px;';
		} else if($desing_menu_placement_overlay == 'sidetopleft' || $desing_menu_placement_overlay == 'sidebottomleft'){
			$css .= 'left:'.$desing_menu_xpostion_overlay.'px;';
		} else if($desing_menu_placement_overlay == 'sidetopright' || $desing_menu_placement_overlay == 'sidebottomright'){
			$css .= 'right:'.$desing_menu_xpostion_overlay.'px;';
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_lockdonw_overlay'])){
			$css .= 'position:'.$easy_translation_manager_plugin->etm_tools_retrive['desing_menu_lockdonw_overlay'] . ';';
		} else {
			$css .= 'position: absolute;';
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_backgroundalpha_overlay'])){
			$desing_menu_backgroundalpha_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_backgroundalpha_overlay'];
		} else {
			$desing_menu_backgroundalpha_overlay = 0.0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_backgroundcolor_overlay'])){
			$desing_menu_backgroundcolor_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_backgroundcolor_overlay'];
		} else {
			$desing_menu_backgroundcolor_overlay = '#ffffff';
		}
		
		if(!empty($desing_menu_backgroundcolor_overlay) and !empty($desing_menu_backgroundalpha_overlay)){
			$temp = $this->HexToRGB($desing_menu_backgroundcolor_overlay);
			$css .= 'background: rgba('.$temp['r'].','.$temp['g'].','.$temp['b'].','. $desing_menu_backgroundalpha_overlay.') !important;';
			
		}
		
		//addborder
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_boxalpha_overlay'])){
			$desing_menu_boxalpha_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_boxalpha_overlay'];
		} else {
			$desing_menu_boxalpha_overlay = 0;
		}
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_boxcolor_overlay'])){
			$desing_menu_boxcolor_overlay = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_boxcolor_overlay'];
		} else {
			$desing_menu_boxcolor_overlay = '#000000';
		}
		
		if(!empty($desing_menu_boxcolor_overlay) and !empty($desing_menu_boxalpha_overlay)){
			$temp = $this->HexToRGB($desing_menu_boxcolor_overlay);
			$css .= 'box-shadow : 0 0 5px 1px rgba('.$temp['r'].','.$temp['g'].','.$temp['b'].','. $desing_menu_boxalpha_overlay.') !important;';
		}
	
		if((!empty($desing_menu_boxalpha_overlay) && $desing_menu_boxalpha_overlay != 0.0) || (!empty($desing_menu_backgroundalpha_overlay) && $desing_menu_backgroundalpha_overlay != 0.0)){
			$css .= 'padding:10px;';
		}
		
		global $post;
		$postid = get_the_ID();
		
		$showonlyonpostIDarray = '';
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['showonlyonpostID'])){
			$showonlyonpostID = $easy_translation_manager_plugin->etm_tools_retrive['showonlyonpostID'];
			$showonlyonpostIDarray = explode(',', $showonlyonpostID);
		}
		
		
		
		if(empty($showonlyonpostIDarray) or (!empty($showonlyonpostIDarray) and in_array($postid, $showonlyonpostIDarray))){
			echo '<div style="'.$css.'" class="etmoverlay_all etmoverlay_'.$desing_menu_placement_overlay.'">';
			$tmp_array_data['menu_flag'] = $desing_menu_flag_size_overlay;
			$tmp_array_data['menu_layout'] = $desing_menu_type_overlay;
			$tmp_array_data['menu_display'] = $desing_menu_info_overlay;
			$tmp_array_data['menu_placement'] = $desing_menu_placement_overlay;
			$tmp_array_data['menu_hidearrow'] = $desing_menu_hidearrow_overlay;
			
			$this->create_menu($tmp_array_data,'overlay');
			echo '</div>';
			echo '<link rel="stylesheet" type="text/css" href="'.EASY_TRANSLATION_MANAGER_URL.'css/overlay.css"></link>';
		}
	}
	
 // --------------------------------- CREATE MENU -------------------------------- 
  function create_menu($tmp_menu_data = array(),$tmp_type='do_action'){
        global $post,$wpdb,$etm_check,$easy_translation_manager_plugin;

		// menu setup
		$easy_translation_manager_plugin->menu_creation = true;
		$easy_translation_manager_plugin->permalink_lockdown = true;
		$return_lang = '';
		
		// extra feauter to problem sites
		if(!empty($etm_check) && is_numeric($etm_check)){
			$easy_translation_manager_plugin->current_translatede_post_id = $etm_check;
		}
		
		foreach($this->menu_settings_names as $_key => $_data){
			if(empty($tmp_menu_data[$_data]) ){
	        	if(!empty($easy_translation_manager_plugin->etm_tools_retrive[$_key]) && $tmp_type == 'do_action'){
	        		$tmp_menu_data[$_data] = $easy_translation_manager_plugin->etm_tools_retrive[$_key];
	        	} else {
		        	$tmp_menu_data[$_data] = '';
		        	
		        	if($_data == 'menu_flag' || $_data == 'menu_display' || $_data == 'menu_layout' || $_data == 'menu_hidearrow'){
			        	$tmp_menu_data[$_data] = 0;
		        	}
		        	
	        	}
			}
		}
		
		// Get menu flag
        $tmp_lang = $this->get_active_flags();
        
    	// Get all information about the flags
    	$tmp_lang = $this->check_for_translations_permalinks($tmp_lang,$tmp_menu_data['menu_flag']);
 
		if(empty($tmp_lang) || (count($tmp_lang) == 1 && !empty($easy_translation_manager_plugin->etm_tools_retrive['default_language']) && $tmp_lang[0]['code'] == $easy_translation_manager_plugin->etm_tools_retrive['default_language'])) {
			return '';
		}
 
 
		if(empty($tmp_menu_data['menu_layout'])){
			$return_lang = $this->generate_dropdown_old($tmp_lang,$tmp_menu_data);
		} else if(!empty($tmp_menu_data['menu_layout']) && ($tmp_menu_data['menu_layout'] == 1 or $tmp_menu_data['menu_layout'] == 2)){
			$return_lang = $this->generate_side_by_side_old($tmp_lang,$tmp_menu_data,$tmp_type);
		} else if($tmp_menu_data['menu_layout'] == 3 || $tmp_menu_data['menu_layout'] == 4 || $tmp_menu_data['menu_layout'] == 5 || $tmp_menu_data['menu_layout'] == 6 || $tmp_menu_data['menu_layout'] == 7){
			if(empty($tmp_lang) || count($tmp_lang) == 1) {
				return '';
			}
			$return_lang = $this->generate_new_codrops($tmp_lang,$tmp_menu_data);
		} 

    	$easy_translation_manager_plugin->permalink_lockdown = false;
    	$easy_translation_manager_plugin->menu_creation = false;
    	if($tmp_type == 'shortcode'){
    		return ($return_lang != '' ? $return_lang : '' );
    	} else {
    		echo ($return_lang != '' ? $return_lang : '' );
    		echo '<div style="clear:both"></div>';
        	return '';
    	}
    }

 // --------------------------------- CREATE MENU --------------------------------     
    
    function generate_new_codrops($tmp_langs,$tmp_menu_data){
    	global $easy_translation_manager_plugin;
		$return_lang = $type_var = '';
    	
		if($tmp_menu_data['menu_layout'] == 3){
			$type_var = 'cs-skin-elastic';
			wp_enqueue_style( 'flagmenu-bouncing-list', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-bouncing-list.css', array(),'1.0.0');
		} else if($tmp_menu_data['menu_layout'] == 4){
			$type_var = 'cs-skin-slide';
			wp_enqueue_style( 'flagmenu-box-slide', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-box-slide.css', array(),'1.0.0');
		} else if($tmp_menu_data['menu_layout'] == 5){
			$type_var = 'cs-skin-rotate';		
			wp_enqueue_style( 'flagmenu-rotating-bars', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-rotating-bars.css', array(),'1.0.0');
		} else if($tmp_menu_data['menu_layout'] == 6){
			$type_var = 'cs-skin-boxes';	
			wp_enqueue_style( 'flagmenu-fluid-grid', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-fluid-grid.css', array(),'1.0.0');		
		} else if($tmp_menu_data['menu_layout'] == 7){
			wp_enqueue_style( 'flagmenu-responsive-circle', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-responsive-circle.css', array(),'1.0.0');	
			$type_var = 'cs-skin-circular';		
		}
		

		
		if(empty($this->check_lang_codrops['js'])){
			$this->check_lang_codrops['js'] = 1;
		} else {
			$this->check_lang_codrops['js'] += 1;
		}

        if($this->check_lang_codrops['js'] == 1 ){
			wp_enqueue_script( 'classie', EASY_TRANSLATION_MANAGER_URL.'js/classie.js', array(),'1.0.0');
			wp_enqueue_script( 'selectFx', EASY_TRANSLATION_MANAGER_URL.'js/flagmenu.js', array(),'1.0.0');
			wp_enqueue_style( 'flagmenu', EASY_TRANSLATION_MANAGER_URL.'css/flagmenu.css', array(),'1.0.0');
		}	
		
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['old_flags'])){
			if(empty($this->check_lang_codrops[$type_var])){
				$this->check_lang_codrops[$type_var] = array(0,0,0,0);
			}
		
	        if($this->check_lang_codrops[$type_var][$tmp_menu_data['menu_flag']] != 1 && !empty($tmp_langs)){
				foreach($tmp_langs as $key_tmp => $tmp_lang){
	
					if($tmp_menu_data['menu_layout'] == 6 || $tmp_menu_data['menu_layout'] == 7){
						$tmp_lang['icon'] = substr($tmp_lang['icon'], 0,-7);
						$tmp_lang['icon'] = $tmp_lang['icon'].'.svg';
						$tmp_langs[$key_tmp]['icon'] = $tmp_lang['icon'];
					}
	
					if($tmp_lang['selected_lang']){
						if($tmp_menu_data['menu_layout'] == 7){
							$return_lang .= ' .etm_menu.size'.$tmp_menu_data['menu_flag'].' .'.$type_var.'.cs-select .cs-placeholder {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';	
						} else {
							$return_lang .= ' .etm_menu.size'.$tmp_menu_data['menu_flag'].':not(.menu_display2) .'.$type_var.'.cs-select .cs-placeholder {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';		
						}

						$return_lang .= ' .etm_menu.size'.$tmp_menu_data['menu_flag'].' .'.$type_var.'.cs-select .cs-options li.etmicon-'.$tmp_lang['code'].' {';
						$return_lang .= 'display:none;';
						$return_lang .= '}';
								
					} else {
						if($tmp_menu_data['menu_layout'] == 6 || $tmp_menu_data['menu_layout'] == 7){
							$return_lang .= ' .etm_menu.size'.$tmp_menu_data['menu_flag'].' .'.$type_var.'.cs-select .cs-options li.etmicon-'.$tmp_lang['code'].' {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';
						} else if($tmp_menu_data['menu_display'] != 2){
							$return_lang .= ' .etm_menu.size'.$tmp_menu_data['menu_flag'].':not(.menu_display2) .'.$type_var.'.cs-select .cs-options li.etmicon-'.$tmp_lang['code'].' span {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';
						}
						
					}
				} 
				
	        }
			
			if($tmp_menu_data['menu_layout'] == 4){
				if($this->check_lang_codrops[$type_var][$tmp_menu_data['menu_flag']] != 1 ){
					$count_tmp = 1;
					if(count($tmp_langs) > 0){ 
						$count_tmp = (count($tmp_langs));
					}
					$return_lang .= '.etm_menu.size'.$tmp_menu_data['menu_flag'].'  .cs-skin-slide.cs-active::before {-webkit-transform: perspective(1px) scale3d(1,'.$count_tmp.',1);transform: perspective(1px) scale3d(1,'.$count_tmp.',1);}';
				}
			}
			
			$this->check_lang_codrops[$type_var][$tmp_menu_data['menu_flag']] = 1;
		} else {
			if(empty($this->check_lang_codrops[$type_var])){
				$this->check_lang_codrops[$type_var] = 0;
			}
		
	        if(empty($this->check_lang_codrops[$type_var]) && !empty($tmp_langs)){
				foreach($tmp_langs as $tmp_lang){
					if($tmp_lang['selected_lang']){
						if($tmp_menu_data['menu_layout'] == 7){
							$return_lang .= ' .etm_menu .'.$type_var.'.cs-select .cs-placeholder {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';
						} else {
							$return_lang .= '.etm_menu:not(.menu_display2) .'.$type_var.'.cs-select .cs-placeholder {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';		
						}
					
						$return_lang .= ' .etm_menu .'.$type_var.'.cs-select .cs-options li.etmicon-'.$tmp_lang['code'].' {';
						$return_lang .= 'display:none;';
						$return_lang .= '}';
					} else {
						if($tmp_menu_data['menu_layout'] == 6 || $tmp_menu_data['menu_layout'] == 7){
							$return_lang .= ' .etm_menu .'.$type_var.'.cs-select .cs-options li.etmicon-'.$tmp_lang['code'].' {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';
						} else {
							$return_lang .= ' .etm_menu:not(.menu_display2) .'.$type_var.'.cs-select .cs-options li.etmicon-'.$tmp_lang['code'].' span {';
							$return_lang .= 'background-image: url(\''.$tmp_lang['icon'].'\');';
							$return_lang .= '}';
						}
					}
				} 
				
	        }
			
			if($tmp_menu_data['menu_layout'] == 4){
				if(empty($this->check_lang_codrops[$type_var][$tmp_menu_data['menu_flag']])){
					$count_tmp = 1;
					if(count($tmp_langs) > 0){ 
						$count_tmp = (count($tmp_langs));
					}
					$return_lang .= '.etm_menu.size'.$tmp_menu_data['menu_flag'].' .cs-skin-slide.cs-active::before {-webkit-transform: perspective(1px) scale3d(1,'.$count_tmp.',1);transform: perspective(1px) scale3d(1,'.$count_tmp.',1);}';
				}
			}
			if($tmp_menu_data['menu_layout'] == 4){
				if(empty($this->check_lang_codrops[$type_var])){
					$this->check_lang_codrops[$type_var] = array();
				}
				$this->check_lang_codrops[$type_var][$tmp_menu_data['menu_flag']] = 1;
			} else {
				$this->check_lang_codrops[$type_var] = 1;
			}
		}
		
		if(!empty($return_lang)){
			$return_lang = '<style>'.$return_lang.'</style>';
		}
			
		
		$flag_type = '';
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['old_flags']) && $tmp_menu_data['menu_layout'] != 6 && $tmp_menu_data['menu_layout'] != 7){
			$flag_type = 'flagpng';
		}
		
		$hidearrow = '';
		if(!empty($tmp_menu_data['menu_hidearrow'])){
			$hidearrow = 'menu_hidearrow';
		}
		

		
    	if((count($tmp_langs) > 0 && $tmp_menu_data['menu_layout'] != 7) || (count($tmp_langs) > 1 && $tmp_menu_data['menu_layout'] == 7)){ 
    		$return_lang .= '<div class="etm_menu '.$this->translation_dir.' '.$flag_type.' '.$hidearrow.' size'.$tmp_menu_data['menu_flag'].' menu_display'.$tmp_menu_data['menu_display'].' '.$tmp_menu_data['menu_aligment'].'"><select style="display: none;" class="cs-select '.$type_var.'">';
			foreach($tmp_langs as $tmp_lang){
			
				if(($tmp_menu_data['menu_display'] == 1 && $tmp_menu_data['menu_layout'] != 6 )|| $tmp_menu_data['menu_layout'] == 7){
					$tmp_lang['title'] = '&nbsp;';
				}
				if($tmp_menu_data['menu_display'] == 2){
					$tmp_lang['icon'] = '';
				}
			
			
				$return_lang .= '<option data-link="'.$tmp_lang['link'].'" value="'.$tmp_lang['icon'].'" ';
				
				if($tmp_lang['selected_lang']){
					$return_lang .= 'selected="selected" ';
				}
				
				$return_lang .= 'data-class="etmicon etmicon-'.$tmp_lang['code'].' '.($tmp_lang['selected_lang'] == true ? 'removethis' : '').'">'.$tmp_lang['title'].'</option>'; 
			}
			$return_lang .= '</select></div>';
    	}
    
		return $return_lang;
    }
    
    function generate_dropdown_old($tmp_langs,$tmp_menu_data){
    	global $easy_translation_manager_plugin;
		$easy_translation_manager_plugin->check_lang += 1;
		$return_lang = '';
		$generaterandom = rand();
		
		$flag_type = '';
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['old_flags'])){
			$flag_type = 'flagpng';
		}
		
		$hidearrow = '';
		if(!empty($tmp_menu_data['menu_hidearrow'])){
			$hidearrow = 'menu_hidearrow';
		}
		
		$return_lang .='<div class="etm_menu_old size'.$tmp_menu_data['menu_flag'].' menu_display'.$tmp_menu_data['menu_display'].' '.$tmp_menu_data['menu_aligment'].' '.$flag_type.' '.$hidearrow.'">';
			
        if($easy_translation_manager_plugin->check_lang == 1 ){
			$return_lang .= '<script type="text/javascript" src="'.EASY_TRANSLATION_MANAGER_URL.'js/jquery.dd.js"></script>';
			if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_css'])){
				$return_lang .= '<link rel="stylesheet" type="text/css" href="'.$easy_translation_manager_plugin->etm_tools_retrive['desing_menu_css'].'"></link>';
			} else {
				$return_lang .= '<link rel="stylesheet" type="text/css" href="'.EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-dropdown.css"></link>';
			}
		}
    	if(count($tmp_langs) > 0){ 
			$dropdow_width_size_array = array('40px','50px','63px','85px');
			
			if (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {
				$dropdow_width_size_array = array('65px','75px','85px','100px');
			} 
			
			if($tmp_menu_data['menu_display'] == 1){
				$dropdow_width_size = $dropdow_width_size_array[$tmp_menu_data['menu_flag']];
			} else if($tmp_menu_data['menu_display'] == 2){
				$dropdow_width_size = '208px';
				$tmp_menu_data['menu_flag'] = 0;
			} else {
				$dropdow_width_size = '258px';
			}
			
   			if(!empty($tmp_menu_data['menu_width'])){
    			$dropdow_width_size = $tmp_menu_data['menu_width'];
    		}

			$return_lang .= '<select onChange="window.location = this.value" name="easy-translation-manger_menu'.$easy_translation_manager_plugin->check_lang.'_'.$generaterandom.'" id="easy-translation-manger_menu'.$easy_translation_manager_plugin->check_lang.'_'.$generaterandom.'" style="width:'.$dropdow_width_size.';" >';

			foreach($tmp_langs as $tmp_lang){	
				$return_lang .= '<option ';
				$return_lang .= 'value="'.$tmp_lang['link'].'" ';
				
				if($tmp_menu_data['menu_display'] != 2){
					$return_lang .= 'title="'.$tmp_lang['icon'].'" ';
				}
				if( !empty($tmp_lang['selected_lang'])){
					$return_lang .= 'selected="selected"';
				}

                if($tmp_lang['fade']){
                        $return_lang .= ' disabled="disabled" ';         
                }
                
				$return_lang .= '>';
				if($tmp_menu_data['menu_display'] != 1){
					
					$return_lang .= $tmp_lang['title'];
				} else {
					$return_lang .= '	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;  &ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;	&ensp;'.$tmp_lang['title'];
				
				}
				$return_lang .= '</option>';
				
			}

			$return_lang .= '</select><div style="clear:both"></div>';
			$return_lang .= '<script>
				jQuery(document).ready(function($){
				try {
					var oHandler = jQuery("#easy-translation-manger_menu'.$easy_translation_manager_plugin->check_lang.'_'.$generaterandom.'").msDropDown({mainCSS:\'etm'.$tmp_menu_data['menu_flag'].' '.$tmp_menu_data['menu_aligment'].' ' . $this->translation_dir . '\'}).data("etm");
					jQuery("#ver").html(jQuery.msDropDown.version);';
			if(!empty($tmp_menu_data['menu_aligment'])){
    			$return_lang .= 'jQuery("#easy-translation-manger_menu'.$easy_translation_manager_plugin->check_lang.'_'.$generaterandom.'").addClass(\''.$tmp_menu_data['menu_aligment'].'\');';
    		}	
			$return_lang .= '} catch(e) {
					alert("Error: "+e.message);
				}
			});
			</script></div>';
    	}
			
		return $return_lang;
			
    }
    
    function generate_side_by_side_old($tmp_langs,$tmp_menu_data,$tmp_type){

    	global $easy_translation_manager_plugin;
		$return_lang = '';
		
		$flag_type = '';
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['old_flags'])){
			$flag_type = 'flagpng';
		}

    	if(count($tmp_langs) > 0){ 
			$return_lang .= '<link rel="stylesheet" type="text/css" href="'.EASY_TRANSLATION_MANAGER_URL.'css/flagmenu-side-by-side.css"></link>';
			$return_lang .='<div class="etm_menu_old_side size'.$tmp_menu_data['menu_flag'].' menu_display'.$tmp_menu_data['menu_display'].' '.$tmp_menu_data['menu_aligment'].' '.$flag_type.'" style="';	

			if(!empty($tmp_menu_data['menu_width'])){
				$return_lang .= 'width:'.$tmp_menu_data['menu_width'].' ';
			}
			
			$return_lang .= '" >';
			
			$flag_size = $this->flag_size_array[$tmp_menu_data['menu_flag']];
			
			$line_height = $flag_size;
			
			if(empty($tmp_menu_data['menu_flag'])){
				$line_height = 19;
			}
			
			
			
			if($tmp_menu_data['menu_display'] == 1){
				$margin_right_spacing = '1px';
			} else if($tmp_menu_data['menu_display'] == 2){	
				$margin_right_spacing = '10px';
			} else {
				$margin_right_spacing = '20px';
			}
			
			$css_object = 'float: left;margin-right:'.$margin_right_spacing.';';
			
			
			if(!empty($tmp_menu_data['menu_placement']) && ($tmp_menu_data['menu_placement'] == 'bottomright' || 
															 $tmp_menu_data['menu_placement'] == 'topright')){
				$css_object = 'float: right;margin-left:'.$margin_right_spacing.';';											 
				
			}
			
			
			if(!empty($tmp_menu_data['menu_placement']) && ($tmp_menu_data['menu_placement'] == 'sidetopleft' || 
															 $tmp_menu_data['menu_placement'] == 'sidetopright' || 
															 $tmp_menu_data['menu_placement'] == 'sidebottomleft' || 
															 $tmp_menu_data['menu_placement'] == 'sidebottomright')  ){
				$css_object = 'display:table;clear:both;';
				

				if(!empty($tmp_menu_data['menu_placement']) && ( $tmp_menu_data['menu_placement'] == 'sidebottomleft' || 
																 $tmp_menu_data['menu_placement'] == 'sidebottomright')  ){
					$css_object .= 'margin-top:5px';											 
				} else {
					$css_object .= 'margin-bottom:5px';	
				}
			}
			
			
            foreach($tmp_langs as $tmp_lang){
					$tmp_image = '';
					$tmp_text = '';
						
					if(!empty($tmp_lang['fade'])){
					   $tmp_image = '<span style="float: left;line-height: 0;"><img style="box-shadow: none;" height="'.$flag_size.'" style="filter: alpha(opacity=50);opacity: 0.5;" src="'.$tmp_lang['icon'].'" title="'.$tmp_lang['title'] . '"></span>';
					   $tmp_text = '<span style="float: left;color: #999;margin-left: 5px;line-height: '.$line_height.'px;">'.$tmp_lang['title'] . '</span>';
                    } else {
					   $tmp_image = '<span style="float: left;line-height: 0;"><img style="box-shadow: none;" height="'.$flag_size.'" src="'.$tmp_lang['icon'].'" title="'.$tmp_lang['title'] . '"></span>';
					   $tmp_text = '<span style="float: left;margin-left: 5px;line-height: '.$line_height.'px;">'.$tmp_lang['title'] . '</span>';  
					}

					if(!empty($tmp_lang['fade']) || !empty($tmp_lang['selected_lang'])){
					   $return_lang .= '<span class="selected_lang" style="'.$css_object.'"><b>';
                    } else {
					   $return_lang .= '<span class="buttoneffect" style="'.$css_object.'" onclick="window.location.href = \''.$tmp_lang['link'].'\';">';
                    }
					
					
					if($tmp_menu_data['menu_layout'] == 2 && !empty($tmp_lang['selected_lang'])){
					} else if($tmp_menu_data['menu_display'] == 1){
						$return_lang .= $tmp_image;
					} else if($tmp_menu_data['menu_display'] == 2){
						$return_lang .= $tmp_text;
					} else {
						$return_lang .= $tmp_image;
						$return_lang .= $tmp_text;	
					}
				
                    if(!empty($tmp_lang['fade']) || !empty($tmp_lang['selected_lang'])){
                        $return_lang .= '</b></span>';
                    } else {
                        $return_lang .= '</span>';
                    }
        	}

			$return_lang .= '<div style="clear:both"></div>';
			$return_lang .= '</div>';
    	}

		return $return_lang;
    }
    
    
	// --------------------------------- CREATE Extra info -------------------------------- 
    
    function get_active_flags(){
	    global $easy_translation_manager_plugin;
	    $active_langs = etm_tools_retrive_aktiv_languages('',false);
    	
    	// if default is not active in flags then add
    	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['default_language']) && empty($active_langs[$easy_translation_manager_plugin->etm_tools_retrive['default_language']])){
    		$active_langs[$easy_translation_manager_plugin->etm_tools_retrive['default_language']] = 2;
    	}
    	
    	// Sort the flags
    	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['flag_sort'])){
	    	$flag_sorts = $easy_translation_manager_plugin->etm_tools_retrive['flag_sort'];

	    	foreach($flag_sorts as $_key => $_data){
		    	if(empty($active_langs[$_data])){
			    	unset($flag_sorts[$_key]);
		    	}
	    	}
	    	$flag_sorts = array_values($flag_sorts);
	    	$flag_sorts = etm_tools_retrive_languages_data($flag_sorts,false,false);
	    	
	    	return $flag_sorts;	
    	}
	    
	    // reset index and get alternativ data
	    $active_langs = array_keys($active_langs);
		$active_langs = etm_tools_retrive_languages_data($active_langs,false,true);
		$active_langs = etm_tools_subval_sort($active_langs,'org_name','desc');
		return $active_langs;
    }
    
    function check_for_translations_permalinks($arrays,$desing_menu_flag_size){
    	global $post,$wpdb,$etm_check,$easy_translation_manager_plugin;
    	
        $url_tag_array = $url_tag_array_tmp = $url_tag_string = $permalink_array = $fade_trans = '';

        if(!empty($arrays)){
			// Hide flags if not translations
	       	if((is_singular()) && !is_admin() && !empty($easy_translation_manager_plugin->etm_tools_retrive['fade_none_translation_menu']) && !empty($easy_translation_manager_plugin->current_translatede_post_id)){
	        	$fade_trans = $wpdb->get_col("SELECT SUBSTR(meta_key,-2) as lang FROM {$wpdb->prefix}postmeta WHERE (substring(meta_key,1,17) = 'ect_tran_content_' or substring(meta_key,1,15) = 'ect_tran_title_') and meta_key != '' and post_id=".$easy_translation_manager_plugin->current_translatede_post_id .' Group by lang');
	        	
	        	if(!empty($fade_trans)){
	        		$fade_trans = array_flip($fade_trans);
	        		foreach($fade_trans as $t_k => $t_d){
		        		$fade_trans[$t_k] = true;
	        		}
	        	}
	    	}
            	
        	// Get permalinks data
	        if(!empty($easy_translation_manager_plugin->current_translatede_post_id) && !empty($easy_translation_manager_plugin->etm_tools_retrive['use_permalink']) && !is_admin() && !empty($easy_translation_manager_plugin->permalink_structur )){
	        	$tran_permalink = $wpdb->get_results("SELECT SUBSTR(meta_key,-2) as lang,meta_value FROM {$wpdb->prefix}postmeta WHERE  substring(meta_key,1,19) = 'ect_tran_permalink_' and meta_value != '/' and post_id=".$easy_translation_manager_plugin->current_translatede_post_id ." Group by lang");
	        	if(!empty($tran_permalink)){
		        	foreach($tran_permalink as $_data_array){
			        	$permalink_array[$_data_array->lang] = $_data_array->meta_value;
		        	}
	        	}
        	}
        	
        	// Get url tag data
	        if(!empty($easy_translation_manager_plugin->etm_tools_retrive['GP_name'])){
	            $url_tag_array = explode('|',$easy_translation_manager_plugin->etm_tools_retrive['GP_name']);
	        } else {
	        	$url_tag_array = array('la');
	        }
		        
	        // Generate url for non permalink
	        $url_tag_array_tmp = array_fill_keys($url_tag_array, '[LANG]');
	        $url_tag_string = add_query_arg($url_tag_array_tmp,$easy_translation_manager_plugin->curPageURL());
	        $url_tag_string_no = add_query_arg(array(),$easy_translation_manager_plugin->curPageURL());
	        
			$global_terms = '';
			if(is_archive()){
				global $wp_query;
				$global_terms = $wp_query->get_queried_object();
					    
			}



			// title
        	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['desing_menu_lang_string'])){
        		$desing_menu_lang_string = $easy_translation_manager_plugin->etm_tools_retrive['desing_menu_lang_string'];
        	} else {
	        	$desing_menu_lang_string = '[ORG] ([ENG])';
        	}

				
			// Add all data to array
            foreach($arrays as $_key_array => $_data_array ){
            	// check if flag has to be hidden
           		if((is_singular()) && !is_admin() && $easy_translation_manager_plugin->etm_tools_retrive['fade_none_translation_menu'] && !empty($easy_translation_manager_plugin->current_translatede_post_id)){  
					if(!empty($fade_trans[$_data_array['code']])){
						$arrays[$_key_array]['fade'] = false;
					} else {
						if((!empty($easy_translation_manager_plugin->selectede_lang) && $_data_array['code'] == $easy_translation_manager_plugin->selectede_lang) || (!empty($easy_translation_manager_plugin->etm_tools_retrive['default_language']) && $easy_translation_manager_plugin->etm_tools_retrive['default_language'] == $_data_array['code'])){
							$arrays[$_key_array]['fade'] = false;
						} else {
							$arrays[$_key_array]['fade'] = true;
						}	
					}
				} else {
					$arrays[$_key_array]['fade'] = false;
				}
				
		

				//generate title
            	$tmp_string_text = str_replace('[ORG]',$arrays[$_key_array]['org_name'],$desing_menu_lang_string);
            	$tmp_string_text = str_replace('[ENG]',$arrays[$_key_array]['english_name'],$tmp_string_text);
				$arrays[$_key_array]['title'] = $tmp_string_text;
				
				
				
				// get icon url
				$arrays[$_key_array]['icon'] = etm_tools_create_icons_url($arrays[$_key_array]['icon'],$desing_menu_flag_size);
				
				// add link data
				
				if(is_admin()){
					$arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$url_tag_string);
				} else if(empty($easy_translation_manager_plugin->permalink_structur) || is_front_page() || is_home()){
			       $arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$url_tag_string); 
		        } else {
			        if(is_archive() && (is_category() || is_tag()) && !empty($global_terms)){
			        
						$tran_terms_data = get_option('ect_tran_terms_'.$_data_array['code']);
						if(!empty($tran_terms_data[$global_terms->term_id])){
							$arrays[$_key_array]['link'] = trailingslashit(get_option('siteurl')) . $global_terms->taxonomy .'/'.$tran_terms_data[$global_terms->term_id]->slug;
						} else {
							$arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$url_tag_string); 	
						} 
			        } else if(is_singular()){
			        	if(!empty($easy_translation_manager_plugin->current_translatede_post_id)){
				        	$translatede_permalink = get_post_meta($easy_translation_manager_plugin->current_translatede_post_id, 'ect_tran_permalink_'.$_data_array['code'], true);
				    		if(!empty($translatede_permalink) && $translatede_permalink != '/'){
				    			$arrays[$_key_array]['link'] = trailingslashit(get_option('siteurl')) . $translatede_permalink;
				    		} else {
					    		$arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$url_tag_string); 
				    		}
			        	} else {
				        	$arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$url_tag_string); 	
			        	}
			        }
		        }
		
				
				if(!empty($easy_translation_manager_plugin->etm_tools_retrive['domain_list'][$_data_array['code']])){
					$testdata = str_replace(array('http://','https://'), array('',''), $easy_translation_manager_plugin->etm_tools_retrive['domain_list'][$_data_array['code']]);
					$testdata2 = str_replace(array('http://','https://'), array('',''), $_SERVER['HTTP_HOST']);
					
					
					$arrays[$_key_array]['link'] = str_replace(get_option('siteurl'),$easy_translation_manager_plugin->etm_tools_retrive['domain_list'][$_data_array['code']],$arrays[$_key_array]['link']);


					if(!empty($testdata) && !empty($testdata2) && $testdata == $testdata2 && strpos( $arrays[$_key_array]['link'], '?') > 0){
						$arrays[$_key_array]['link'] = add_query_arg($url_tag_array_tmp,$arrays[$_key_array]['link']);
						$arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$arrays[$_key_array]['link']); 	
					} else if(!empty($testdata) && !empty($testdata2) && $testdata != $testdata2 && strpos( $arrays[$_key_array]['link'], '?') > 0 && !empty($easy_translation_manager_plugin->permalink_structur)){
						$arrays[$_key_array]['link'] = substr($arrays[$_key_array]['link'],0,strpos( $arrays[$_key_array]['link'], '?'));
					} else if(!empty($testdata) && !empty($testdata2) && $testdata != $testdata2 && strpos( $arrays[$_key_array]['link'], '?') > 0){
						$arrays[$_key_array]['link'] = substr($arrays[$_key_array]['link'],0,strpos( $arrays[$_key_array]['link'], '?'));
					
						foreach($url_tag_array as $tmp_d_d){
							if(!empty($_GET[$tmp_d_d])){
								unset($_GET[$tmp_d_d]);
							}
						}
						
						$http_build_query = http_build_query($_GET);
						if(!empty($http_build_query)){
							$arrays[$_key_array]['link'] = $arrays[$_key_array]['link'] . '?'.$http_build_query;
						}
					}
					
					
				} 
				
				if(empty($arrays[$_key_array]['link'])){
					$arrays[$_key_array]['link'] = str_replace('[LANG]',$_data_array['code'],$url_tag_string);
				}
				
				// add current flag data
            	if(!empty($easy_translation_manager_plugin->selectede_lang) && $_data_array['code'] == $easy_translation_manager_plugin->selectede_lang){
            		$arrays[$_key_array]['selected_lang'] = true;
            		if(!empty($arrays[$_key_array]['rtl'])){
	            		$this->translation_dir = 'rtl';
            		}
            	} else {
                	$arrays[$_key_array]['selected_lang'] = false;
            	}
            }  
		}
		
        return $arrays;
    }
    
	function HexToRGB($hex) {
		$hex = str_replace("#", "", $hex);
		$color = array();
 
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		} else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}
		return $color;
	}
}
?>