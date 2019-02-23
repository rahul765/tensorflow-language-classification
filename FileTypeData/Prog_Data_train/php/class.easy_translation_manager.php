<?php
class easy_translation_manager_plugin { 
	var $id = 'etm';
	var $menu_name;
	var $plugin_page; 
	var $selectede_lang;
    var $etm_tools_retrive;
	var $permalink_structur = '';
	var $selectede_lang_status;
    var $show_lang_status = false;
	var $check_lang = 0;
    var $cachesPostBody = array();
	
	var $tran_terms_data = '';
	var $tran_menu_data = '';
    
    var $first_time = 0;
    var $main_page_first_time = 0;
    var $menu_creation = false;
    var $current_translatede_post_id = 0;
    var $permalink_lockdown = false;
    
	function easy_translation_manager_plugin( $args=array() ){
	
		//------------
		$defaults = array(
			'theme'					=> false,
			'layout'				=> 'horizontal'
		);
		
		foreach($defaults as $property => $default){
			$this->$property = isset($args[$property])?$args[$property]:$default;
		}
		//-----------	
		
		if ( !session_id() ){
            session_start();
        } 

        $this->etm_tools_retrive = etm_tools_retrive_options();
        $this->permalink_structur = get_option('permalink_structure','');  
        $this->check_languages();
        
		add_action('init',  array(&$this, 'init'));
		$tmp_change_wp_admin = false;
    	if(!empty($this->etm_tools_retrive['change_wp_admin'])){
    		$tmp_change_wp_admin = $this->etm_tools_retrive['change_wp_admin'];
    	}
        
   		if(!empty($tmp_change_wp_admin) && $tmp_change_wp_admin){
   			add_filter('locale', array(&$this,'wp_admin_locale') );       		
   		}
		

		add_action('plugins_loaded',array(&$this,'handle_addons_load'),5);
		
		if(is_admin()){
			if(empty($_SERVER["REQUEST_URI"]) || strpos($_SERVER["REQUEST_URI"],'update-core.php') == false){
				add_action("after_setup_theme", array(&$this,"plugins_loaded") ,16);
			}
			add_action('admin_enqueue_scripts', array(&$this,'backend_header') );
		} else {
			add_action("after_setup_theme", array(&$this,"plugins_loaded") ,16);
		}

		if(empty($this->etm_tools_retrive['deactivate_seach']) and !is_admin()){
			add_filter('posts_join', array(&$this,'search_join'), 10, 2);
			add_filter('posts_where', array(&$this,'search_where'), 10, 2);
			add_filter('posts_groupby', array(&$this,'search_groupby'), 10, 2);
		}
	
		if(!empty($this->etm_tools_retrive['tran-listing'])){
			remove_action('template_redirect', 'redirect_canonical');
		}
		
	
		if(is_admin()){
            add_action("admin_menu", array(&$this,"admin_menu") );
			require_once EASY_TRANSLATION_MANAGER_OPTIONPANEL.'options-panel/load.pop.php';
			rh_register_php('options-panel',EASY_TRANSLATION_MANAGER_OPTIONPANEL.'options-panel/class.PluginOptionsPanelModule.php', '2.3.2');		
		}
	}
	
	function backend_header(){
		wp_enqueue_style( 'new_icons_etm', EASY_TRANSLATION_MANAGER_URL.'css/backend.css', array(),'1.0.0');
		wp_enqueue_style( 'extracss-etm', EASY_TRANSLATION_MANAGER_URL.'css/etm-extra.css', array(),'1.0.0');
	}

    function init(){
		global $wp_locale;

		wp_enqueue_script('jquery');
		$tmp = etm_languages_flags($this->selectede_lang);
		if($this->show_lang_status && !empty($tmp['rtl']) && $tmp['rtl']){
			if(!is_admin()){
				$tmp_file = 'rtl.css';
				if(!empty($this->etm_tools_retrive['rtl_front_page_css'])){
	    			$tmp_file = $this->etm_tools_retrive['rtl_front_page_css'];
	    		}
	    		
	    		$tmp_path = get_template_directory().'/';
	    		$tmp_url = get_template_directory_uri().'/';
	    		
	    		$tmp_path_file = $tmp_path.$tmp_file;
	    		
				if (file_exists($tmp_path_file) && $tmp_file != 'rtl.css') {
					wp_register_style( 'rtl', $tmp_url.$tmp_file, array(),'1.0.0');
					wp_enqueue_style('rtl');
				}
			}

			$wp_locale->text_direction = 'rtl';
		} else {
			$wp_locale->text_direction = 'ltr';
		}
    } 
	

    // show admin menues 
	function admin_menu(){ 
			$this->menu_name = __('Translation','etm');       
            add_menu_page( $this->menu_name, $this->menu_name, 'etm_show_menu', ($this->id.'-opt'));
            
           // $this->plugin_page = add_submenu_page(($this->id.'-start'),__("Get Started",'etm'), __("Get Started",'etm'), 'etm_show_menu',($this->id.'-start'), array(&$this,'form_options') );etm-opt
            
        	do_action(($this->id.'-options-menu'));
        	add_action( 'admin_head-'. $this->plugin_page, array(&$this,'start_head') );
        	
            require_once EASY_TRANSLATION_MANAGER_PATH.'admin/class.post_page_menu.php';
            new etm_tinymce_extra_button();
            
        	
	}
	
    // create sub menu pages in admin
	function create_sub_menu(){
        require EASY_TRANSLATION_MANAGER_PATH.'admin/class.admin_translation.php';
        new admin_etm_admin_translation($this->id);	
        
        require EASY_TRANSLATION_MANAGER_PATH.'admin/class.admin_importexportpo.php';
        new admin_etm_importexportpo($this->id);		

		if(is_admin()){
			$dc_options = array(
				'id'			=> $this->id.'-dc',
				'plugin_id'		=> $this->id,
				'capability'	=> 'etm_options',
				'resources_path'=> 'easy-translation-manager',
				'parent_id'		=> $this->id.'-opt',
				'menu_text'		=> __('Downloads','rhl'),
				'page_title'	=> __('Downloadable content - Easy Translation Manager for WordPress','rhl'),
				'license_keys'	=> etm_tools_retrive_options('license_keys'),
				'plugin_code'	=> 'ETM',
				'product_name'	=> __('Easy Translation Manager for WordPress','rhl'),
				'options_varname' => 'etm_options',
				'tdom'			=> 'etm'
			);
		
		
			$settings = array(
				'id'					=> $this->id.'-opt',
				'plugin_id'				=> $this->id,
				'menu_id'				=> $this->id.'-opt',
				'options_panel_version'	=> '2.6.6',
				'capability'			=> 'etm_options',
				'capability_license'	=> 'etm_license',
				'options_varname'		=> 'etm_options',
				'options_parameters'	=> array(),
				'page_title'			=> __('Options','etm'),
				'menu_text'				=> __('Options','etm'),
				'option_menu_parent'	=> ($this->id.'-opt'),
				'notification'			=> (object)array(
					'plugin_version'=>  ETM_VERSION,
					'plugin_code' 	=> 'ETM',
					'message'		=> __('Easy Translation Manager update %s is available!','wlb').' <a href="%s">'.__('Please update now','wlb')
				),
				'registration' 		=> ( $this->theme ? false : true ),
				'theme'					=> $this->theme,
                'fileuploader'			=> true,
				'dc_options'			=> $dc_options,
				'option_show_in_metabox'=> true,
				'extracss'				=> 'extracss-etm',
				'import_export'  		=> false,
				'import_export_options' => false,
				'downloadables'			=> true,
				'pluginslug'	=> ETM_SLUG,
				'api_url' 		=> "http://plugins.righthere.com",
				'layout'		=> $this->layout
				);	
			
			do_action('rh-php-commons');			 			 	
			new PluginOptionsPanelModule($settings);				
			
            require_once EASY_TRANSLATION_MANAGER_PATH.'admin/class.option_panel.php';
            new etm_options($this->id);	
		}
	}
	
    // start when plugins are loaded
    function plugins_loaded(){
    	global $wpseo_sitemaps,$wpseo_front,$post;
        $check_install = get_option('etm_version');
    	if(version_compare($check_install, ETM_VERSION,'<')){
    		etm_sub_page_installation($check_install);
    	} 

        $this->create_sub_menu();
        $this->aktivate_langushes();
          
        if(is_admin()){
	       $this->intercept_data();
        }

		if(class_exists( 'WooCommerce' )){
			add_action('woocommerce_cart_totals_before_shipping', array(&$this,'woocommerce_cart_totals_before_shipping'),2, 1);
			add_action('woocommerce_checkout_init', array(&$this,'woocommerce_cart_totals_before_shipping'),2, 1);
			add_filter('woocommerce_cart_item_product', array(&$this,'woocommerce_cart_item_product'),2, 1000);
		}


		add_filter( 'pre_option_name', array(&$this,'_my_custom_option') );
	


		
        // SEO controles
    	if(!is_admin() && defined('WPSEO_VERSION') && !empty($this->etm_tools_retrive['seo_plugin_by_yoast'])){
    		remove_action( 'template_redirect', array( $wpseo_sitemaps, 'redirect' ) );
    		require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/yoast-class-sitemaps.php');

    		if(WPSEO_VERSION < '1.2.8.7'){
    			require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/yoast-class-frontend.php');	
    		} else if(WPSEO_VERSION < '1.5.2.5'){
    			require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/yoast-class-frontend1524.php');
    		} else {
	    		require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/yoast-class-frontend1287.php');
    		} 
    	} 
    }
    
    function _my_custom_option( $option )
	{
	    remove_filter( 'pre_option_name', '_my_custom_option' );
	
	    print_r($option);
	    echo 'ergergergergergerge';
	
	    add_filter( 'pre_option_name', '_my_custom_option' );
	    return $option;
	} 
    
	function woocommerce_script() {
		wp_enqueue_script( 'woocommerce_script_mini_cart', EASY_TRANSLATION_MANAGER_URL . '/js/frontend.js', array(), '1.0.0', true );
	}

    // check languages
	function check_languages(){
        $selectede_lang = '';
        $lang_tag_array = '';
        $etm_url_old = '';
        $current_user_ip = $this->getIP();
        
        if(!empty($this->etm_tools_retrive['GP_name'])){
			$lang_tag_array = $this->etm_tools_retrive['GP_name'];
        }

        if(!empty($lang_tag_array)){
            $lang_tag_array = explode('|',$lang_tag_array);
            
            foreach ($lang_tag_array as $tmp_tag){
                if(isset($_GET[$tmp_tag]) && !empty($_GET[$tmp_tag])){
                	if(is_admin()){
                		$_SESSION['etm_lang_wpadmin'] = $_GET[$tmp_tag];
                	} else {
                		$_SESSION['etm_lang'] = $_GET[$tmp_tag];
                		add_action( 'wp_enqueue_scripts', array(&$this,'woocommerce_script') );

                	}
                    
                    break;
                } else if(isset($_POST[$tmp_tag]) && !empty($_POST[$tmp_tag])){
                	if(is_admin()){
                		$_SESSION['etm_lang_wpadmin'] = $_POST[$tmp_tag];
                	} else {
                    	$_SESSION['etm_lang'] = $_POST[$tmp_tag];
                		add_action( 'wp_enqueue_scripts', array(&$this,'woocommerce_script') );
                    }
                    break;
                }
            }
            
            $current_url_multi_domain = '';
            $current_page_url_host = $_SERVER['HTTP_HOST'];

            if(!empty($this->etm_tools_retrive['domain_list_fast'][$current_page_url_host])){
           	 	$current_url_multi_domain = $this->etm_tools_retrive['domain_list_fast'][$current_page_url_host];
           	} else if(!empty($this->etm_tools_retrive['domain_list_fast']['http://'.$current_page_url_host])){
           	 	$current_url_multi_domain = $this->etm_tools_retrive['domain_list_fast']['http://'.$current_page_url_host];
           	} else if(!empty($this->etm_tools_retrive['domain_list_fast']['https://'.$current_page_url_host])){ 	
           	 	$current_url_multi_domain = $this->etm_tools_retrive['domain_list_fast']['https://'.$current_page_url_host];	 	
		   	} else if(!empty($this->etm_tools_retrive['domain_list_fast'][str_replace(array('http://','https://'), array('',''), $current_page_url_host)])){
           	 	$current_url_multi_domain = $this->etm_tools_retrive['domain_list_fast'][str_replace(array('http://','https://'), array('',''), $current_page_url_host)];
            }
            
            if(!empty($current_url_multi_domain)){
	            $etm_url_old = get_option( '_tmp_etm_'.$current_user_ip, '');
            }
            
            if((!empty($current_url_multi_domain) && empty($etm_url_old)) || (!empty($current_url_multi_domain) && $etm_url_old != $_SERVER['HTTP_HOST'])){
	            $selectede_lang = $current_url_multi_domain;
	            if(is_admin()){
		        	$_SESSION['etm_lang_wpadmin'] = $current_url_multi_domain;
	            } else {
		        	$_SESSION['etm_lang'] = $current_url_multi_domain; 
	            }
	            
            } else if((!empty($_SESSION['etm_lang']) && !is_admin()) or (!empty($_SESSION['etm_lang']) && !empty($_POST['action']) && strpos($_POST['action'],'woocommerce') !== false )){
                $selectede_lang = $_SESSION['etm_lang'];
            } else if(!empty($_SESSION['etm_lang_wpadmin']) && is_admin()){
                $selectede_lang = $_SESSION['etm_lang_wpadmin'];
            } else {
            	$browser_languash = $this->etm_tools_retrive['browser_languash'];

            	if(!empty($browser_languash)){
            		 $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            		 if(is_admin()){
            		 	$_SESSION['etm_lang_wpadmin'] = $lang;
            		 } else {
            		 	$_SESSION['etm_lang'] = $lang;
            		 }
            		 
            		 $selectede_lang = $lang;
            	} else {
            	
            		if(is_admin()){
            			if(!empty($this->etm_tools_retrive['default_language_wp_etm'])){
	            			$default_languashwp =  $this->etm_tools_retrive['default_language_wp_etm'];
            			}
            			if(!empty($default_languashwp)){
            				$_SESSION['etm_lang_wpadmin'] = $default_languashwp;
            		 		$selectede_lang = $default_languashwp;
            			}	 
            		} else {
            			if(!empty($this->etm_tools_retrive['default_language'])){
	            			$default_languash =  $this->etm_tools_retrive['default_language']; 
            			}
            			         		 
            			if(!empty($default_languash)){
            				$_SESSION['etm_lang'] = $default_languash;
            		 		$selectede_lang = $default_languash;
            			}
            		}           		 
            	}
            	
            }
        }
        $this->selectede_lang = $selectede_lang;
        
        if(!empty($this->etm_tools_retrive['lang_'. $this->selectede_lang])){
        	$this->selectede_lang_status =  $this->etm_tools_retrive['lang_'. $this->selectede_lang];
        }

		$test_ip_array = array();
		if(!empty($this->etm_tools_retrive['test_ip'])){
    		$test_ip_array = str_replace (" ", "", $this->etm_tools_retrive['test_ip']);
    		$test_ip_array = explode('|',$test_ip_array);
		}

		if(!empty($this->selectede_lang_status) && ($this->selectede_lang_status == 2 || ( $this->selectede_lang_status == 1 && !empty($test_ip_array) && in_array($_SERVER['REMOTE_ADDR'], $test_ip_array, true)) )){
			$this->show_lang_status = true;
		} else {
            if(is_admin()){
            	$default_languashwp = '';
            	if(!empty($this->etm_tools_retrive['default_language_wp_etm'])){
            		$default_languashwp =  $this->etm_tools_retrive['default_language_wp_etm'];
            	}
           	 	if(!empty($default_languashwp)){
            		$_SESSION['etm_lang_wpadmin'] = $default_languashwp;
            		$this->selectede_lang = $default_languashwp;
            		$this->show_lang_status = true;
            	} else {
            		$this->show_lang_status = false;
            	}
            } else {
            	$default_languash =  '';
            	if(!empty($this->etm_tools_retrive['default_language'])){
            		$default_languash =  $this->etm_tools_retrive['default_language'];
            	}

           	 	if(!empty($default_languash)){
            		$_SESSION['etm_lang'] = $default_languash;
            		$this->selectede_lang = $default_languash;
            		$this->show_lang_status = true;
            	} else {
            		$this->show_lang_status = false;
            	}
            }
		}
		
		$this->tran_terms_data = get_option('ect_tran_terms_'.$this->selectede_lang);
		$this->tran_menu_data = get_option('ect_tran_menu_'.$this->selectede_lang);	
       
       if($etm_url_old != $_SERVER['HTTP_HOST']){
			update_option( '_tmp_etm_'.$current_user_ip, $_SERVER['HTTP_HOST']);  
        }
		
	}
	
    function aktivate_langushes(){
    	global $wpdb;
		$options_tmp = array('admin_email','blogname','blogdescription','date_format','time_format','start_of_week');
    	$tmp_change_wp_admin = false;
    	
    	if(!empty($this->etm_tools_retrive['change_wp_admin'])){
    		$tmp_change_wp_admin = $this->etm_tools_retrive['change_wp_admin'];
    	}
    
    
		if((((empty($_SERVER["REQUEST_URI"]) || strpos($_SERVER["REQUEST_URI"],'options-general.php') == false) && is_admin() && !empty($this->etm_tools_retrive['change_wp_admin'])) || !is_admin())){
			if(!empty($options_tmp)){
				foreach($options_tmp as $_tmp_k){
					add_filter('option_'.$_tmp_k,array(&$this,'getcurrent_option_'.$_tmp_k) ,2,1);
				}
			}
		}

    
		if($this->show_lang_status && ($tmp_change_wp_admin || !is_admin())){
            if(!is_admin() || (!empty($_POST['action']) && strpos($_POST['action'],'woocommerce') !== false)){
				add_filter('the_posts',array(&$this,'the_posts'),1,1);
				add_filter('get_pages',array(&$this,'get_pages'),0,2);  
				add_filter('get_the_terms',array(&$this,'the_terms'),1,1);
				add_filter('get_terms', array(&$this,'get_terms'), 1, 1);
				add_filter('get_term', array(&$this,'get_term'), 1, 1);           
				add_filter('wp_get_nav_menu_items', array(&$this,'remove_menu_args'), 0, 4);
				add_filter('get_post_metadata', array(&$this,'get_metadata'), 4, 4);
				add_filter('wp_get_attachment_image_attributes',array(&$this,'post_attachment'),0,2);
				add_action('the_posts', array(&$this,'getcurrent_post_id') ,1);	
				
				

				
				
				
				if(!empty($this->etm_tools_retrive['use_permalink']) && !empty($this->permalink_structur)){

					add_filter('query_string', array(&$this,'change_url'), 1);  
					add_filter('page_link',array(&$this,'page_link'),10,2);  
					add_filter('post_link',array(&$this,'page_link'),10,2);
				}
            }
             
			$check_if_plugins = get_option('etm_options_plugin_tran');	
			if($check_if_plugins == 'true'){
       		    $sqllang = "SELECT option_value FROM  {$wpdb->prefix}options WHERE option_name = 'etm_mo_files'";
    			$etm_mo_files = $wpdb->get_var($sqllang);
				$etm_mo_files = unserialize($etm_mo_files);		
			} else {
       		    $sqllang = "SELECT option_value FROM  {$wpdb->base_prefix}options WHERE option_name = 'etm_mo_files'";
    			$etm_mo_files = $wpdb->get_var($sqllang);
				$etm_mo_files = unserialize($etm_mo_files);	
			}
			
			if(!empty($etm_mo_files)){
           		foreach($etm_mo_files as $tmp_data){
                	$this->setup_lang($tmp_data);
           		}
       		}

			add_filter('the_content',array(&$this,'post_plugin_body'),0,2);
			add_filter('the_title',array(&$this,'post_plugin_title'),0,2);   
            add_filter('get_the_excerpt',array(&$this,'post_the_excerpt'),0,2);
			/*add_filter('single_tag_title',array(&$this,'single_term_title'),1,1);			
			add_filter('single_cat_title',array(&$this,'single_term_title'),1,1);*/
		} 
    }
       
    // ----------------------------------------------------------------------------------      
    // -------------------------------Controle translation systems-----------------------  
    // ----------------------------------------------------------------------------------  
    
	function getcurrent_option_admin_email($data1){
		return $this->getcurrent_option($data1,'admin_email');
	}
	
	function getcurrent_option_blogname($data1){
		return $this->getcurrent_option($data1,'blogname');
	}
	
	function getcurrent_option_blogdescription($data1){
		return $this->getcurrent_option($data1,'blogdescription');
	}
	
	function getcurrent_option_date_format($data1){
		return $this->getcurrent_option($data1,'date_format');
	}
	
	function getcurrent_option_time_format($data1){
		return $this->getcurrent_option($data1,'time_format');
	}
	
	function getcurrent_option_start_of_week($data1){
		return $this->getcurrent_option($data1,'start_of_week');
	}
	
	function getcurrent_option($data1,$access){
		$check = get_option('etm_'.$access.'_'.$this->selectede_lang, '');
		if(!empty($check) && !is_array($data1)){
			return $check;
		}
		return $data1;
	}
    
    function getcurrent_post_id( $query ) {
    	global $post;
    	if(empty($this->current_translatede_post_id) && !empty($query[0]->ID) && !is_home()){
	    	$this->current_translatede_post_id = $query[0]->ID;
    	} else if(empty($this->current_translatede_post_id) && !empty($post) && !is_home()){
	    	$this->current_translatede_post_id = $post->ID;
    	}
    	
    	return $query;
	}

    
    
    
	function change_url($string) {
		if(!empty($string) && !empty($this->etm_tools_retrive['use_permalink'])  && empty($main_page_first_time)){
			$splitaray = array();
			$main_page_first_time = 1;
			$checkup_string =  array('pagename','name','category_name','tag','attachment','error');
			
			$splitaray = explode('&', $string);
			foreach($splitaray as $_key => $_data){
				$splitaray[$_key] = explode('=', $_data);
					
				if(in_array($splitaray[$_key][0], $checkup_string)){
					return $this->checkpermalink($splitaray[$_key][0],$splitaray[$_key][1],$string);
					break;
				}
			}
		}
		return $string;
	}  
    
    function get_pages($maindata) { 
        global $wpdb;

        foreach($maindata as $key => $tmp_data){
            $translatede_body = '';
            $translatede_excerpt = '';
            $translatede_title = '';
            
            if($tmp_data->ID > 0){
                $translatede_body = get_post_meta($tmp_data->ID, 'ect_tran_content_'.$this->selectede_lang, true);
          		$translatede_excerpt = get_post_meta($tmp_data->ID, 'etm_content_excerpts_'.$this->selectede_lang, true);
		        $translatede_title = get_post_meta($tmp_data->ID, 'ect_tran_title_'.$this->selectede_lang, true);

        		if(!empty($translatede_body) ){
        			$maindata[$key]->post_content = $translatede_body;
        		}
                
        		if(!empty($translatede_excerpt) ){
        			$maindata[$key]->post_excerpt = $translatede_excerpt;
        		}
                
        		if(!empty($translatede_title) ){
        			$maindata[$key]->post_title = $translatede_title;
        		}
                
                if($this->etm_tools_retrive['default_language'] != $this->selectede_lang || (!empty($this->etm_tools_retrive['hide_elements_default']) && $this->etm_tools_retrive['hide_elements_default'])){
                    if(!is_admin()){
                        if(!empty($this->etm_tools_retrive['hide_elements_pages']) && $this->etm_tools_retrive['hide_elements_pages'] && $tmp_data->post_type == "page" && empty($translatede_title) && empty($translatede_body) && empty($translatede_excerpt)){
                            unset($maindata[$key]);    
                        }   
                    }    
                }
    		}
        }
            
    	return $maindata;	
    }  
    
    function the_posts($maindata) { 
        global $wpdb;

        if(empty($maindata) && $this->current_translatede_post_id > 0 && $this->etm_tools_retrive['use_permalink']){
        	$maindata = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID=".$this->current_translatede_post_id." ORDER BY post_date DESC");
        }
        
        foreach($maindata as $key => $tmp_data){
            $translatede_body = '';
            $translatede_excerpt = '';
            $translatede_title = '';
            
            if($tmp_data->ID > 0){
                $translatede_body = get_post_meta($tmp_data->ID, 'ect_tran_content_'.$this->selectede_lang, true);
          		$translatede_excerpt = get_post_meta($tmp_data->ID, 'etm_content_excerpts_'.$this->selectede_lang, true);
		        $translatede_title = get_post_meta($tmp_data->ID, 'ect_tran_title_'.$this->selectede_lang, true);


        		if(!empty($translatede_body) ){
        			$maindata[$key]->post_content = $translatede_body;
        		}
                
        		if(!empty($translatede_excerpt) ){
        			$maindata[$key]->post_excerpt = $translatede_excerpt;
        		}
                
        		if(!empty($translatede_title) ){
        			$maindata[$key]->post_title = $translatede_title;
        		}
                
                if($this->etm_tools_retrive['default_language'] != $this->selectede_lang || @$this->etm_tools_retrive['hide_elements_default']){
                    if(!is_admin()){
                        if(!empty($this->etm_tools_retrive['hide_elements_posts']) && $this->etm_tools_retrive['hide_elements_posts'] && $tmp_data->post_type == "post" && empty($translatede_title) && empty($translatede_body) && empty($translatede_excerpt)){
                            unset($maindata[$key]);   
                        }
                        if(!empty($this->etm_tools_retrive['hide_elements_pages']) && $this->etm_tools_retrive['hide_elements_pages'] && $tmp_data->post_type == "page" && empty($translatede_title) && empty($translatede_body) && empty($translatede_excerpt)){
                            unset($maindata[$key]);    
                        }   
                    }    
                }
    		}
        }
            
    	return $maindata;	
    }

 	function the_terms($before){
 		global $wpdb;
        if(!empty($before)){
	 		foreach($before as $key => $data){
	 			$translatede_tmp = (object) array();
	 			if(!empty($this->tran_terms_data) && !empty($this->tran_terms_data[$data->term_id])){
		 			$translatede_tmp = $this->tran_terms_data[$data->term_id];
	 			}
	
	            if(!empty($translatede_tmp->slug) && $this->etm_tools_retrive['use_permalink']){
	            	$before[$key]->slug2 = $before[$key]->slug;
	               $before[$key]->slug = $translatede_tmp->slug; 
	            }
	            
	            if(!empty($translatede_tmp->description)){
	               $before[$key]->description = $translatede_tmp->description; 
	            }           
	            
	 			if(!empty($translatede_tmp->name)){
	 				$before[$key]->name = $translatede_tmp->name;
	 			} else {
	                if(!is_admin() && ($this->etm_tools_retrive['default_language'] != $this->selectede_lang || $this->etm_tools_retrive['hide_elements_default'])){
	                    if($this->etm_tools_retrive['hide_elements_categories'] && $before[$key]->taxonomy == 'category'){
	                        unset($before[$key]);  
	                    } else if($this->etm_tools_retrive['hide_elements_tags'] && $before[$key]->taxonomy == 'post_tag'){
	                        unset($before[$key]);  
	                    }
	                } 
	    	   }  	 
	 		}
        }
        
 		return $before;
 	}  
    
    function get_term( $term, $taxonomies = null, $args = null )
    {
 		global $wpdb;
 		
 		if($this->permalink_lockdown){
	 		return $term;
 		}
        
		$translatede_tmp = '';
		
		if(!empty($this->tran_terms_data) && !empty( $this->tran_terms_data[$term->term_id])){
			$translatede_tmp = $this->tran_terms_data[$term->term_id];
		}
		
        if(!empty($translatede_tmp->slug) && $this->etm_tools_retrive['use_permalink']){
           $term->slug2 = $term->slug;
           $term->slug = $translatede_tmp->slug; 
        }
        
        if(!empty($translatede_tmp->description)){
           $term->description = $translatede_tmp->description; 
        }           
        
		if(!empty($translatede_tmp->name)){
			$term->name  = $translatede_tmp->name;
		} else {
            if(!is_admin() && ($this->etm_tools_retrive['default_language'] != $this->selectede_lang || @$this->etm_tools_retrive['hide_elements_default'])){
                if($this->etm_tools_retrive['hide_elements_categories'] && $term->taxonomy == 'category'){
                    return '';  
                } else if($this->etm_tools_retrive['hide_elements_tags'] && $term->taxonomy == 'post_tag'){
                     return '';   
                }
            } 
		}  
        
 		return $term;
    }
    
    function single_term_title($before = ''){
		global $wp_query,$wpdb;
 		
 		$tag = $wp_query->get_queried_object();
		$translatede_tmp = $this->tran_terms_data[$tag->term_id];

 		if(!empty($translatede_tmp)){
 			return $translatede_tmp;
 		} else {
            if(!is_admin() && ($this->etm_tools_retrive['default_language'] != $this->selectede_lang || $this->etm_tools_retrive['hide_elements_default'])){
                if($this->etm_tools_retrive['hide_elements_categories'] && $tag->taxonomy == 'category'){
                    return ''; 
                } else if($this->etm_tools_retrive['hide_elements_tags'] && $tag->taxonomy == 'post_tag'){
                    return '';  
                }
            } 
			return $before;
 		}
  	}

    function get_terms( $terms)
    {
 		global $wpdb;
        if(!empty($terms)){
	 		foreach($terms as $key => $data){
	 			$translatede_tmp = (object) array();
	 			if(!empty($this->tran_terms_data[$data->term_id])){
		 			$translatede_tmp = $this->tran_terms_data[$data->term_id];
	 			}
				
	            if(!empty($translatede_tmp->slug) && $this->etm_tools_retrive['use_permalink']){
	               $terms[$key]->slug2 = $terms[$key]->slug;
	                $terms[$key]->slug = $translatede_tmp->slug; 
	            }
	            
	            if(!empty($translatede_tmp->description)){
	               $terms[$key]->description = $translatede_tmp->description; 
	            }           
	
	 			if(!empty($translatede_tmp->name)){
	 				$terms[$key]->name  = $translatede_tmp->name;
	 			} else {
	                if(!is_admin() && ($this->etm_tools_retrive['default_language'] != $this->selectede_lang || $this->etm_tools_retrive['hide_elements_default'])){
	                    if($this->etm_tools_retrive['hide_elements_categories'] && $terms[$key]->taxonomy == 'category'){
	                        unset($terms[$key]);  
	                    } else if($this->etm_tools_retrive['hide_elements_tags'] && $terms[$key]->taxonomy == 'post_tag'){
	                        unset($terms[$key]);  
	                    }
	                } 
	 			}  
	 		}
 		}
 		return $terms;
    }
 
    function remove_menu_args($data=null,$data2=null,$data3=null,$data4=null){   
        global $wpdb;
        
        foreach($data as $key => $tmp_data){
    		$translatede_title = '';
            
            if($tmp_data->post_type == 'nav_menu_item'){
            
            	if(!empty($tmp_data->ID) && !empty($this->tran_menu_data) && !empty($this->tran_menu_data[$tmp_data->ID])){
	           		$translatede_title = $this->tran_menu_data[$tmp_data->ID];	
            	}

                if(!empty($translatede_title->attr_title)){
                    $data[$key]->attr_title = $translatede_title->attr_title;  
                }
                if(!empty($translatede_title->title)){
                    $data[$key]->title = $translatede_title->title;  
                }
                if(!empty($translatede_title->url)){
                    $data[$key]->url = $translatede_title->url;  
                }    
                
                if(!empty($translatede_title->description)){
                    $data[$key]->description = $translatede_title->description;  
                }    
                
                if(!empty($translatede_title->title)){
		            $translatede_title = $translatede_title->title;   
                }
                
                if(($tmp_data->object == 'category' || $tmp_data->object == 'post_tag' ) && empty($translatede_title)){
        			$translatede_title = '';
        			if(!empty($this->tran_terms_data) && !empty($this->tran_terms_data[$tmp_data->object_id])){
        				$translatede_title = $this->tran_terms_data[$tmp_data->object_id];
        			}

                    if(!empty($translatede_title->name)){
                        $data[$key]->title = $translatede_title->name;  
                    }
                    if(!empty($translatede_title->name)){
	                  $translatede_title = $translatede_title->name;  
                    }
                }
    		} else {
    			$translatede_title = get_post_meta($tmp_data->ID, 'ect_tran_title_'.$this->selectede_lang, true);
    		}
            
            if(!empty($this->etm_tools_retrive['use_permalink']) && $this->etm_tools_retrive['use_permalink']){				
            	$translatede_permalink = '';
                if($tmp_data->object == 'page' || $tmp_data->type == 'post'){
                    $translatede_permalink = get_post_meta($tmp_data->object_id, 'ect_tran_permalink_'.$this->selectede_lang, true); 
                }
                
                if(!empty($translatede_permalink) && $translatede_permalink !='/'){
                    $data[$key]->url = '/'.$translatede_permalink;
                }
            }
            
            if($this->etm_tools_retrive['default_language'] != $this->selectede_lang || @$this->etm_tools_retrive['hide_elements_default']){
                if(!is_admin() && $this->etm_tools_retrive['hide_elements_menus']){
                    if(empty($translatede_title)){
                        unset($data[$key]);  
                    }     
                } 
            }
        }
        return $data;    
    }    
 
    function get_metadata($metadata, $object_id = null, $meta_key = null, $single = null){
        if(!is_admin() && $meta_key != '_edit_lock' && $meta_key != '_edit_last' && substr($meta_key,0,4) != 'ect_' && substr($meta_key,0,4) != 'etm_' && substr($meta_key,0,7) != '_yoast_' && substr($meta_key,-3,1) != '_'){
           	global $wpdb;
           	$translations_body = '';
           	if(!empty($object_id) && !empty($meta_key) && !empty($this->selectede_lang)){
				$translations_body = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta where post_id='$object_id' and meta_key='".$meta_key."_".$this->selectede_lang."' LIMIT 1" );	
           	}
            if(!empty($translations_body)){
            
				$checked_test = @unserialize($translations_body);
				if ($checked_test !== false) {
				    $translations_body = $checked_test;
				}
            
            	if(is_array($translations_body)){
	            	$this->tmp_array_controle_tmp = $this->tmp_array_controle = array();
	            	$this->tmp_array_controle = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta where post_id='$object_id' and meta_key='$meta_key' LIMIT 1" );
	            	$this->tmp_array_controle = unserialize($this->tmp_array_controle);
	            	
	            	if(is_array($this->tmp_array_controle)){
		            	$this->etm_g_boxes($translations_body);
		            	
		            	
	            	} else {
		            	$this->tmp_array_controle = $translations_body;
		            	$this->tmp_array_controle_tmp = $this->tmp_array_controle = array();
	            	}

	            	$translations_body_backup[0] = $this->tmp_array_controle;
            	} else {
	            	$translations_body_backup =  $translations_body;
            	}
                return $translations_body_backup;  
            }
        }
        return $metadata;
    }
 
    function setup_lang($lang_domain){
        if(!empty($lang_domain->url) && !empty($lang_domain->path)){
    		$mofileurl = $this->selectede_lang.'.mo' ; 
    		if (!file_exists($lang_domain->url.$mofileurl)) {
    			@chmod($lang_domain->path.$mofile, 755);
    			load_textdomain( $lang_domain->domain, $lang_domain->path.$mofileurl );
    		}  
        }
	}
 
    function wp_admin_locale( $locale ) {
    	$tmp = etm_languages_flags($this->selectede_lang);
    		
    	$return_lang = '';
    	if($this->show_lang_status && !empty($tmp['default_locale'])){
    			$return_lang = $tmp['default_locale'];
    	} else {
    		$return_lang =WPLANG;
    	}

    	return $return_lang;
    }
 
	function post_attachment($content,$attachment = null){
        if($attachment->ID > 0){
            
            $translatede_image_alt = get_post_meta($attachment->ID, 'etm_attachment_image_alt_'.$this->selectede_lang, true);
            $translatede_title = get_post_meta($attachment->ID, 'ect_tran_title_'.$this->selectede_lang, true);
            
            if(!empty($translatede_image_alt)){
                $content['alt'] = $translatede_image_alt;
            }
            
            if(!empty($translatede_title)){
                $content['title'] = $translatede_title;
            }
        }
        return $content;
	}  

    function page_link($maindata,$id = '',$test2 = ''){
    	if(!empty($id) && !empty($this->etm_tools_retrive['use_permalink']) && !is_admin()  && !$this->menu_creation){
    		$id_tmp = 0;
    		if(is_numeric($id)){
	    		$id_tmp = $id;
    		} else {
	    		$id_tmp = $id->ID;
    		}
    		
    		$translatede_permalink = get_post_meta($id_tmp, 'ect_tran_permalink_'.$this->selectede_lang, true);

    		if(!empty($translatede_permalink) && $translatede_permalink != '/'){
    			$maindata = trailingslashit(get_option('siteurl')) . $translatede_permalink;
    		}
    	}
    
    	return $maindata;
    }    

	function post_plugin_title($title,$id = ''){
	
		global $wpdb;
		
		$type = get_post_type($id);
		$translatede_title = '';
		
		if($type == 'nav_menu_item'){
			if(!empty($this->tran_menu_data) && !empty($this->tran_menu_data[$id]) && !empty($this->tran_menu_data[$id]->title))			{
				$translatede_title = $this->tran_menu_data[$id]->title;	
				if(!empty($translatede_title)){
					$translatede_title = str_replace('\"','"',$translatede_title);
					$translatede_title = str_replace("\'","'",$translatede_title);	
				
					preg_match('/\[loginout (login|logout)=(\'|\")(.*)(\'|\") (login|logout)=(\'|\")(.*)(\'|\")/',$translatede_title,$testreturn);

					if(!empty($testreturn) && count($testreturn) > 4){
						if ( is_user_logged_in()){
							if(!empty($testreturn[1]) && $testreturn[1] == 'logout'){
								$translatede_title = $testreturn[3];
							} else if(!empty($testreturn[5]) && $testreturn[5] == 'logout'){
								$translatede_title = $testreturn[7];
							}
						} else {
							if(!empty($testreturn[1]) && $testreturn[1] == 'login'){
								$translatede_title = $testreturn[3];
							} else if(!empty($testreturn[5]) && $testreturn[5] == 'login'){
								$translatede_title = $testreturn[7];
							}	
						}	
					}
				}
			}
		} else {
			$translatede_title = get_post_meta($id, 'ect_tran_title_'.$this->selectede_lang, true);
		}

		if(!empty($translatede_title) && $id > 0){
			return $translatede_title;
		} else {
			return $title;
		}
	
	}
    
    function post_the_excerpt($content){
		global $post,$wpdb;
		
		$translatede_excerpt = get_post_meta($post->ID, 'etm_content_excerpts_'.$this->selectede_lang, true);
		
		if(!empty($translatede_excerpt) && $post->ID > 0){
			return $translatede_excerpt;
		} else {
			return $content;
		}  
    }
    
	function post_plugin_body($content){
		global $post,$wpdb;
		

		if($post->post_type == 'events'){
	             return $content;
		}
        
        if($post->ID > 0 ){
            $translatede_body = get_post_meta($post->ID, 'ect_tran_content_'.$this->selectede_lang, true);
		}
        
		if(!empty($translatede_body) ){
			return $translatede_body;
		} else {
			return $content;
		}
        return $content;
	}
    
    // ----------------------------------------------------------------------------------  
    //------------------------------------ extra functions ------------------------------
    // ----------------------------------------------------------------------------------   
    
	function checkpermalink($tmp_name,$tmp_string,$tmp_defualt){
		$return_str = '';
        $id_lang = array();
	
		if($tmp_name == 'pagename' || $tmp_name == 'name' || $tmp_name == 'attachment' || ($tmp_name == 'error' && $tmp_string == '404')){
			global $wpdb;
			$get_array = explode('%2F', $tmp_string);
			
			if($tmp_name == 'attachment' || $tmp_name == 'error'){
				$tmp_name = 'pagename';
				
				$url = substr($_SERVER["REQUEST_URI"],1);
				if(!empty($url) && $url != '/'){
					$url_pice = explode("?", $url,2);
	           		if(substr( $url_pice[0], -1) == '/')
	                	$url_pice[0] = substr( $url_pice[0],0, -1); 
	     
	            	$get_array = $url_pice;
				}
			}			

			$post_id = array();
			if(!empty($get_array)){
				$post_id = $wpdb->get_results("SELECT post_id,meta_key FROM {$wpdb->prefix}postmeta WHERE meta_key like '%ect_tran_permalink_%' and meta_value in ('".implode("/','", $get_array) ."/')");
			}
        	        	   	
        	if(!empty($post_id)){  
        		$post_id = $post_id[0];
            	$lang = substr($post_id->meta_key, -2);    
            	if(strlen($lang)>0 and strlen($lang) < 4){
            		$id_lang[0] = $post_id->post_id;
            		$id_lang[1] = $lang;
            		$type_tmp = get_post_type( $post_id->post_id );
					$this->current_translatede_post_id = $post_id->post_id;

					if($type_tmp == 'page'){
						$return_str = 'page_id='.$id_lang[0];
					} else {
						$return_str = 'p='.$id_lang[0];
					}
            	}
        	}
		} else if($tmp_name == 'tag'){
            $tran_terms_list = '';
            $id_lang = '';
            $tran_terms_list = get_option('ect_tran_terms_checkuplist_post_tag');
            
            if(!empty($tran_terms_list)){
                foreach($tran_terms_list as $key => $tmp_data){
                    if($tmp_string == $tmp_data){
                        $id_lang = explode("_", $key);
						$test = get_tag($id_lang[0]);
						
						if(!empty($test->slug2)){
							$return_str = 'tag='.$test->slug2;
						}

                        break;
                    }
                }  
            }
                      
		
		} else if($tmp_name == 'category_name'){
            $tran_terms_list = '';
            $tran_terms_list = get_option('ect_tran_terms_checkuplist_category');
            
            if(!empty($tran_terms_list)){
                foreach($tran_terms_list as $key => $tmp_data){
                    if($tmp_data == $tmp_string){
                        $id_lang = explode("_", $key);
						$return_str = 'cat='.$id_lang[0];
                        break;
                    }
                }  
            }
		}
		
        if(!empty($id_lang)){
        	if(is_admin()){
        		$_SESSION['etm_lang_wpadmin'] = $id_lang[1];
        	} else {
        		$_SESSION['etm_lang'] = $id_lang[1];
        	}
        
            $this->selectede_lang = $id_lang[1];
            $this->check_languages(); 
        }		
		
		
		if(empty($return_str)){
			$return_str = $tmp_defualt;
		}

		return $return_str;
	}
    
	function etm_g_boxes($array_data){
	
		if(!empty($array_data)){
			foreach($array_data as $_tmp_k => $tmp){
				if(is_array($tmp)){
					$this->tmp_array_controle_tmp[] = $_tmp_k;
					$this->etm_g_boxes($tmp);
				} else {	
					
					if(!empty($tmp)){
						if(empty($this->tmp_array_controle_tmp)){
							$this->tmp_array_controle[$_tmp_k] = $tmp;
						} else if(count($this->tmp_array_controle_tmp) == 1){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$_tmp_k] = $tmp;
				    	} else if(count($this->tmp_array_controle_tmp) == 2){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$_tmp_k] = $tmp;
				    	} else if(count($this->tmp_array_controle_tmp) == 3){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$_tmp_k] = $tmp;
				    	} else if(count($this->tmp_array_controle_tmp) == 4){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$_tmp_k] = $tmp;
				    	} else if(count($this->tmp_array_controle_tmp) == 5){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$this->tmp_array_controle_tmp[4]][$_tmp_k] = $tmp;
				    	} else if(count($this->tmp_array_controle_tmp) == 6){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$this->tmp_array_controle_tmp[4]][$this->tmp_array_controle_tmp[5]][$_tmp_k] = $tmp;
				    	} else if(count($this->tmp_array_controle_tmp) == 7){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$this->tmp_array_controle_tmp[4]][$this->tmp_array_controle_tmp[5]][$this->tmp_array_controle_tmp[6]][$_tmp_k] = $tmp; 	
				    	} else if(count($this->tmp_array_controle_tmp) == 8){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$this->tmp_array_controle_tmp[4]][$this->tmp_array_controle_tmp[5]][$this->tmp_array_controle_tmp[6]][$this->tmp_array_controle_tmp[7]][$_tmp_k] = $tmp; 
				    	} else if(count($this->tmp_array_controle_tmp) == 9){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$this->tmp_array_controle_tmp[4]][$this->tmp_array_controle_tmp[5]][$this->tmp_array_controle_tmp[6]][$this->tmp_array_controle_tmp[7]][$this->tmp_array_controle_tmp[8]][$_tmp_k] = $tmp; 
				    	} else if(count($this->tmp_array_controle_tmp) == 10){
					    	$this->tmp_array_controle[$this->tmp_array_controle_tmp[0]][$this->tmp_array_controle_tmp[1]][$this->tmp_array_controle_tmp[2]][$this->tmp_array_controle_tmp[3]][$this->tmp_array_controle_tmp[4]][$this->tmp_array_controle_tmp[5]][$this->tmp_array_controle_tmp[6]][$this->tmp_array_controle_tmp[7]][$this->tmp_array_controle_tmp[8]][$this->tmp_array_controle_tmp[9]][$_tmp_k] = $tmp; 
				    	}
			    	}
				}
			}
		}
		
		if(!empty($this->tmp_array_controle_tmp)){
			$this->tmp_array_controle_tmp = array_values($this->tmp_array_controle_tmp);
			unset($this->tmp_array_controle_tmp[count($this->tmp_array_controle_tmp)-1]);	
			$this->tmp_array_controle_tmp = array_values($this->tmp_array_controle_tmp);
		}
	}
    
    function activ_lang_array(){
    	$tmp_lang = etm_tools_retrive_aktiv_languages('',false);
    
    	if(!empty($this->etm_tools_retrive['default_language'])){
    		$tmp_lang[$this->etm_tools_retrive['default_language']] = 2;
    	}
    
    	$languashed = etm_tools_retrive_languages_data($tmp_lang,true);
    
        $return_array = '';
    
        foreach($languashed as $tmp){
            $return_array[$tmp['code']] = $tmp['english_name'] . ' ('.$tmp['org_name'].')';    
        }
        
        return $return_array;
    }  
    
    function get_the_content($more_link_text, $stripteaser){
        global $post, $more, $page, $pages, $multipage, $preview;
	
	        if ( null === $more_link_text )
	                $more_link_text = __( '(more...)','etm' );
	
	        $output = '';
	        $hasTeaser = false;
	
	        // If post password required and it doesn't match the cookie.
	        if ( post_password_required($post) ) {
	                $output = get_the_password_form();
	                return $output;
	        }
	
	        if ( $page > count($pages) ) // if the requested page doesn't exist
	                $page = count($pages); // give them the highest numbered page that DOES exist
	
	        $content = $pages[$page-1];
	        if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
	                $content = explode($matches[0], $content, 2);
	                if ( !empty($matches[1]) && !empty($more_link_text) )
	                        $more_link_text = strip_tags(wp_kses_no_null(trim($matches[1])));
	
	                $hasTeaser = true;
	        } else {
	                $content = array($content);
	        }
	        if ( (false !== strpos($post->post_content, '<!--noteaser-->') && ((!$multipage) || ($page==1))) )
	                $stripteaser = true;
	        $teaser = $content[0];
	        if ( $more && $stripteaser && $hasTeaser )
	                $teaser = '';
                      
	        $output .= $teaser;
	        if ( count($content) > 1 ) {
	                if ( $more ) {
	                        $output .= '<span id="more-' . $post->ID . '"></span>' . $content[1];
	                } else {
	                        if ( ! empty($more_link_text) )
	                                $output .= apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-{$post->ID}\" class=\"more-link\">$more_link_text</a>", $more_link_text );
	                        $output = force_balance_tags($output);
	                }
	
	        }
	        if ( $preview ) // preview fix for javascript bug with foreign languages
	                $output = preg_replace_callback('/\%u([0-9A-F]{4})/', '_convert_urlencoded_to_entities', $output);
	
            $output = apply_filters('the_content',$output);
	        return $output;
    }
    
    function curPageURL() {
		$pageURL = 'http';
		
		if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		
		$pageURL .= "://";
		$server_name = $_SERVER["SERVER_NAME"];
    // error_log('==addr '.$_SERVER["SERVER_ADDR"]);
    if($server_name == 'localhost' && isset($_SERVER["SERVER_ADDR"]) && $_SERVER["SERVER_ADDR"] != null && $_SERVER["SERVER_ADDR"] != '::1'){
      $server_name = $_SERVER["SERVER_ADDR"];
    }
		if (!empty($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
      //SERVER_NAME == localhost 
			$pageURL .= $server_name.":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $server_name.$_SERVER["REQUEST_URI"];
		}
		
		return $pageURL;
	}
	
	function handle_addons_load(){
		//-- nexgt gen gallery compat fix.

		if( defined('NGG_PLUGIN') ){
			rh_register_php('options-panel',EASY_TRANSLATION_MANAGER_PATH.'options-panel/class.PluginOptionsPanelModule.php', '2.6.6');
		}
		//---
		$upload_dir = wp_upload_dir();
		$addons_path = $upload_dir['basedir'].'/easy-translation-manager/';	
		$addons_url = $upload_dir['baseurl'].'/easy-translation-manager/';	
		$addons = etm_tools_retrive_options('addons');
		if(is_array($addons)&&!empty($addons)){
			define('ETM_ADDON_PATH',$addons_path);
			define('ETM_ADDON_URL',$addons_url);
			foreach($addons as $addon){
				try {
					@include_once $addons_path.$addon;
				}catch(Exception $e){
					$current = etm_tools_retrive_options($this->options_varname);
					$current = is_array($current) ? $current : array();
					$current['addons'] = is_array($current['addons']) ? $current['addons'] : array() ;
					//----
					$current['addons'] = array_diff($current['addons'], array($addon))  ;
					update_option($this->options_varname, $current);					
				}
			}
		}
	}
	
	function woocommerce_cart_item_product($test,$test2){
		if(!empty($test) && !empty($test->id) && !empty($test->post)){
            $translatede_body = get_post_meta($test->id, 'ect_tran_content_'.$this->selectede_lang, true);
      		$translatede_excerpt = get_post_meta($test->id, 'etm_content_excerpts_'.$this->selectede_lang, true);
	        $translatede_title = get_post_meta($test->id, 'ect_tran_title_'.$this->selectede_lang, true);

			if(!empty($translatede_title)){
				$test->post->post_title = $translatede_title;
			}
			if(!empty($translatede_excerpt)){
				$test->post->post_excerpt = $translatede_excerpt;
			}
			if(!empty($translatede_body)){
				$test->post->post_content = $translatede_body;
			}
		}
	
		return $test;

	}

    
	function woocommerce_cart_totals_before_shipping($test,$test2){
		$packages = WC()->shipping->get_packages();
		
		if(!empty($packages)){
			foreach($packages as $key_step1 => $data_step1){
				if(!empty($packages[$key_step1]['rates'])){
					foreach($packages[$key_step1]['rates'] as $key_step2 => $data_step2){
						if(!empty($data_step2->label)){
							$packages[$key_step1]['rates'][$key_step2]->label = __($data_step2->label,'woocommerce');
						}
					}
				}
			}
			WC()->shipping->shipping_methods = $packages;
			$packages2 = WC()->shipping->get_packages();
		}
	} 
    
    // ----------------------------------------------------------------------------------  
	// --------------------------------- GET STARTED ------------------------------------	
    // ----------------------------------------------------------------------------------  
    
    function start_head(){
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo EASY_TRANSLATION_MANAGER_URL; ?>css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo EASY_TRANSLATION_MANAGER_URL; ?>css/get_started.css" />
        <?php
    } 
    
	function getIP() {
		$ip = '';
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "UNKNOWN";
	
		return $ip;
	} 
    
    // -----------------------------------------------------------------------------------  
	// ---------------------------------- Redirect ajax loading functions ----------------
    // -----------------------------------------------------------------------------------

 	function intercept_data(){
		if(isset($_REQUEST['etm_data'])){
			
			if(!is_user_logged_in()){
				etm_send_error_die('You are not logged in.');
			}
			
			if(in_array($_REQUEST['etm_fn'], array('mo_save_manual','mo_reloader','mo_controle','meta_controle','menu_controle','post_controle','terms_controle','page_loader_data','page_loader_1','page_loader_2'))){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/fn.admin_tools.php');
			}
			
			
			/* Controles the page loading system */
			if($_REQUEST['etm_fn']=='page_loader_1'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/uhJQtable/version2.php');
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.page_loader_1.php');
			}
			
			if($_REQUEST['etm_fn']=='page_loader_2'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/uhJQtable/version2.php');
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.page_loader_2.php');
			}
			
			if($_REQUEST['etm_fn']=='page_loader_data'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.page_loader_data.php');
			}
			
			
			/* Controle plugins and themes mo files */
			if($_REQUEST['etm_fn']=='mo_controle'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.mo_control.php');
			}
			
			if($_REQUEST['etm_fn']=='mo_export'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/fn.php_mo.php');
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.mo_export.php');
			}

			if($_REQUEST['etm_fn']=='mo_reloader'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.mo_reloader.php');
			}
			
			if($_REQUEST['etm_fn']=='mo_generator'){				
				require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/fn.php_mo.php');
				include_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.mo_generator.php');
			}
			
			
			/* Controle post and page */
			if($_REQUEST['etm_fn']=='post_controle'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.post_control.php');
			}
			
			if($_REQUEST['etm_fn']=='terms_controle'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.terms_control.php');
			}
			
			
			if($_REQUEST['etm_fn']=='meta_controle'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.meta_control.php');
			}
			
			if($_REQUEST['etm_fn']=='site_controle'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.site_control.php');
			}			
			
			
			
			
			/* Controle menu */
			if($_REQUEST['etm_fn']=='menu_controle'){
				require_once(EASY_TRANSLATION_MANAGER_PATH.'frames/admin.menu_control.php');
			}
			
		}
	}

    // ---------------------------------------------------------------------------------- 
    // ---------------------------------- SEACH SYSTEM ----------------------------------
    // ----------------------------------------------------------------------------------    

	function search_where($where, &$wp_query)
	{
	    global $wpdb,$current_user;
	    get_currentuserinfo();
	    
		if($wp_query->is_search && !empty($wp_query->query['s'])){
			$this->check_languages();
			
			if(!empty($this->etm_tools_retrive['default_language'])){
				$defualt_lang_tmp = $this->etm_tools_retrive['default_language'];
			} else {
				$defualt_lang_tmp = 'en';
			}

			if(!empty($this->selectede_lang)){
			
				if($defualt_lang_tmp == $this->selectede_lang){
					$tmp_extra = " OR (wp_posts.post_title LIKE '%".$wp_query->query['s']."%') OR (wp_posts.post_content LIKE '%".$wp_query->query['s']."%')";
				} else {
					$tmp_extra = "";
				}
	
				if(!empty($current_user->ID) and $current_user->ID > 0){
					$where = "AND ((({$wpdb->postmeta}.meta_key = 'ect_tran_title_".$this->selectede_lang."' and {$wpdb->postmeta}.meta_value like '%".$wp_query->query['s']."%') OR ({$wpdb->postmeta}.meta_key = 'ect_tran_content_".$this->selectede_lang."' and {$wpdb->postmeta}.meta_value like '%".$wp_query->query['s']."%')".$tmp_extra.")) AND wp_posts.post_type IN ('post', 'page') AND (wp_posts.post_status = 'publish' OR (wp_posts.post_author = ".$current_user->ID." AND wp_posts.post_status = 'private'))";
				} else {
					$where = "AND ((({$wpdb->postmeta}.meta_key = 'ect_tran_title_".$this->selectede_lang."' and {$wpdb->postmeta}.meta_value like '%".$wp_query->query['s']."%') OR ({$wpdb->postmeta}.meta_key = 'ect_tran_content_".$this->selectede_lang."' and {$wpdb->postmeta}.meta_value like '%".$wp_query->query['s']."%')".$tmp_extra.")) AND wp_posts.post_type IN ('post', 'page') AND wp_posts.post_status = 'publish'";	
				}
			}
			
		}
	    return $where;
	}

	function search_join($join, &$wp_query)
	{
	    global $wpdb;
	
	    if($wp_query->is_search && !empty($wp_query->query['s'])) {
			$this->check_languages();
		
			if(!empty($this->selectede_lang)){
	        	$join .= " left join $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id ";
	        }
	    }
	    return $join;
	}
	
	function search_groupby($groupby, &$wp_query)
	{
	    global $wpdb;
	
	    $mygroupby = "{$wpdb->posts}.ID";
	
	    if(preg_match( "/$mygroupby/", $groupby)) {
	        return $groupby;
	    }
	
	    if(!strlen(trim($groupby))) {
	        return $mygroupby;
	    }
	
	    return $groupby . ", " . $mygroupby;
	}
}
?>