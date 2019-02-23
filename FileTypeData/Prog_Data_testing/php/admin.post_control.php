<?php
if((empty($_REQUEST['tmp_lang']) or empty($_REQUEST['tmp_id'])) and $_REQUEST['status'] != 'create' and $_REQUEST['status'] != 'deleteAll'){
	etm_send_error_die('Error data.');
}

add_action('wp_loaded','etm_when_wp_is_loaded');
function etm_when_wp_is_loaded(){
global $wpdb,$userdata,$wpseo_sitemaps;
$wp_version_is_3_3 = etm_tools_version_check();

$Header2 = '';
$button2 = '';

if(!empty($_REQUEST['status']) && $_REQUEST['status']=='save'){
	update_post_meta($_REQUEST['tmp_id'], 'ect_tran_content_'.$_REQUEST['tmp_lang'], $_REQUEST['translatede_body']);
	update_post_meta($_REQUEST['tmp_id'], 'ect_tran_title_'.$_REQUEST['tmp_lang'], $_REQUEST['translatede_header']);  
	update_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_focuskw_'.$_REQUEST['tmp_lang'], $_REQUEST['tran_focuskw']);
    update_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_title_'.$_REQUEST['tmp_lang'], $_REQUEST['tran_title']); 	
    update_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_metadesc_'.$_REQUEST['tmp_lang'], $_REQUEST['tran_metadesc']); 

    if(substr( $_REQUEST['tran_permalink'], -1) != '/'){
        $_REQUEST['tran_permalink'] .= '/'; 
    };
                
    update_post_meta($_REQUEST['tmp_id'], 'ect_tran_permalink_'.$_REQUEST['tmp_lang'], $_REQUEST['tran_permalink']);
    update_post_meta($_REQUEST['tmp_id'], 'etm_content_excerpts_'.$_REQUEST['tmp_lang'], $_REQUEST['content_excerpts']);
    update_post_meta($_REQUEST['tmp_id'], 'etm_attachment_image_alt_'.$_REQUEST['tmp_lang'], $_REQUEST['attachment_image_alt']);

    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Your translation has been saved.',
        'INFOCON' => 1
    );
} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
	delete_post_meta($_REQUEST['tmp_id'], 'ect_tran_content_'.$_REQUEST['tmp_lang']);
	delete_post_meta($_REQUEST['tmp_id'], 'ect_tran_title_'.$_REQUEST['tmp_lang']);  
	delete_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_focuskw_'.$_REQUEST['tmp_lang']);
    delete_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_title_'.$_REQUEST['tmp_lang']); 	
    delete_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_metadesc_'.$_REQUEST['tmp_lang']);
    delete_post_meta($_REQUEST['tmp_id'], 'ect_tran_permalink_'.$_REQUEST['tmp_lang']);
    delete_post_meta($_REQUEST['tmp_id'], 'etm_content_excerpts_'.$_REQUEST['tmp_lang']);
    delete_post_meta($_REQUEST['tmp_id'], 'etm_attachment_image_alt_'.$_REQUEST['tmp_lang']);

	$response = array(
    	'R'	=> 'OK',
       	'MSG' => 'Translation has been deleted'
    );     
} else { 
	$content = '';   
    $sqldatalang = etm_languages_flags($_REQUEST['tmp_lang']);
    
    $langed_string = '<img width="70px" style="float: left;padding-left: 10px;padding-top: 4px;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"><h2 style="padding-top:0px">Translate to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</h2></div>';

    $translations_header = get_post_meta($_REQUEST['tmp_id'], 'ect_tran_title_'.$_REQUEST['tmp_lang'], true);   
    $translations_body = get_post_meta($_REQUEST['tmp_id'], 'ect_tran_content_'.$_REQUEST['tmp_lang'], true); 
    $default_permalink = get_permalink($_REQUEST['tmp_id']);
    $ect_tran_permalink = get_post_meta($_REQUEST['tmp_id'], 'ect_tran_permalink_'.$_REQUEST['tmp_lang'], true);           
          
    if(empty($translations_header))
    	$translations_header = '';
        
    if(empty($translations_body))
    	$translations_body = '';
    
    
    if($wp_version_is_3_3){
	    $post_data = get_post($_REQUEST['tmp_id']);
    } else {
	   $post_data = wp_get_single_post($_REQUEST['tmp_id']);  
    }
   
  
    $default_header = $post_data->post_title;  
    $default_body = $post_data->post_content;
    	
    if((!empty($default_header) || !empty($default_body)) && (current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
    	$content .= '<div style="float:left;padding-top:10px"><input type="submit" onClick="deletePopOpControl(\''.$_REQUEST['tmp_id'].'\',\''.$_REQUEST['tmp_lang'].'\',\''.$_REQUEST['tmp_type'].'\')" value="Delete" class="button-secondary" name="Delete"></div>';
    }
    
    
    
    
    $content .= '<div style="float:right;padding-top:10px">';
    
    if(!etm_tools_version_check()){
    	$content .= '<input type="submit" value="HTML MODE" onclick="switch_html_preview(\'pp_readonly_content\',\'pp_translate_content\',this);" class="button-secondary">';
    }
    
    
    //Seo system
    
    $seo_plugin_by_yoast = etm_tools_retrive_options('seo_plugin_by_yoast');

    if(defined('WPSEO_VERSION') && !empty($seo_plugin_by_yoast)){
    	$content .= '<input style=" margin-left: 5px;margin-right: 20px;" type="submit" onClick="etm_switch_seo(\'#etm_table_1\',\'#etm_table_2\')" value="SEO" class="button-primary">';
    	
    	$button2 = '<input style="float:right;margin-left: 5px;" type="submit" onClick="etm_switch_seo(\'#etm_table_2\',\'#etm_table_1\')" value="Back" class="button-primary">';
    	
    	
    	$Header2 = '<img width="70px" style="float: left;padding-left: 10px;padding-top: 4px;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"><h2 style="padding-top:0px">SEO to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</h2></div>';
    };
    
		$_yoast_wpseo_focuskw = get_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_focuskw', true);
		$_yoast_wpseo_title = get_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_title', true);
		$_yoast_wpseo_metadesc = get_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_metadesc', true);
		
        $_tran_focuskw = get_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_focuskw_'.$_REQUEST['tmp_lang'], true);
    	$_tran_title = get_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_title_'.$_REQUEST['tmp_lang'], true); 	
    	$_tran_metadesc = get_post_meta($_REQUEST['tmp_id'], '_yoast_wpseo_metadesc_'.$_REQUEST['tmp_lang'], true); 
    
    
    
    // Extra system
    
    
    	$content .= '<input style=" margin-left: 5px;margin-right: 20px;" type="submit" onClick="etm_switch_seo(\'#etm_table_1\',\'#etm_table_3\')" value="Extra " class="button-primary">';
    	
    	$button3 = '<input style="float:right;margin-left: 5px;" type="submit" onClick="etm_switch_seo(\'#etm_table_3\',\'#etm_table_1\')" value="Back" class="button-primary">';
    	
    	
    	$Header3 = '<img width="70px" style="float: left;padding-left: 10px;padding-top: 4px;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"><h2 style="padding-top:0px">Extra to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</h2></div>';

    
		$post_default_content_excerpts = $post_data->post_excerpt;
		$post_default_media_alternate_text = get_post_meta($_REQUEST['tmp_id'], '_wp_attachment_image_alt', true);
		
        $post_translatede_content_excerpts = get_post_meta($_REQUEST['tmp_id'], 'etm_content_excerpts_'.$_REQUEST['tmp_lang'], true);
    	$post_translatedet_media_alternate_text = get_post_meta($_REQUEST['tmp_id'], 'etm_attachment_image_alt_'.$_REQUEST['tmp_lang'], true); 	

    
    
    
    
    
    
    
    
    
    
    
    
    $content .= '<input style=" margin-left: 5px;margin-right: 20px;" type="submit" onClick="copiePopOpControl(\'#post_default_header\',\'#post_translatede_header\')" value="Copy text" class="button-secondary" name="Cancel"><input type="submit" onClick="cancelPopOpControl()" value="Cancel" class="button-secondary" name="Cancel">';
    
    if(current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options')){
    	$content .= '<input onClick="savePopOpControl(\''.$_REQUEST['tmp_id'].'\',\''.$_REQUEST['tmp_lang'].'\',\'#translations_inputtext\',\''.$_REQUEST['tmp_type'].'\',\'#post_translatede_header\',\'#translatede_body\')" type="submit" class="button-primary" value="Save" name="Save">';
    };
    $content .= '</div>';
   
   
   
   	$tmp = etm_languages_flags($_REQUEST['tmp_lang']);
		
	if(!empty($tmp['rtl']) && $tmp['rtl']){
		$dir = 'rtl';
	} else {
		$dir = 'ltr';
	}
    
    $response = array(
    	'R'	=> 'OK',
    	'DIR' => $dir,
    	'RETURNDATA'=> $content,
		'langed_string' => $langed_string,
		'default_header' => $default_header,
		'translations_header' => $translations_header,
    	'default_body' => $default_body,
    	'translations_body' => $translations_body,
    	'buttons' => $content,
    	'buttons2' => $button2,
    	'yoast_wpseo_focuskw' => $_yoast_wpseo_focuskw,
    	'yoast_wpseo_title' => $_yoast_wpseo_title,    	
    	'yoast_wpseo_metadesc' => $_yoast_wpseo_metadesc,    	
    	'tran_focuskw' => $_tran_focuskw,
    	'tran_title' => $_tran_title,    	
    	'tran_metadesc' => $_tran_metadesc, 
    	'default_heade2' => $Header2,
        'tran_permalink' => $ect_tran_permalink,
        'default_permalink' => $default_permalink,
        'header_extra_post'=>$Header3,
        'post_default_content_excerpts'=>$post_default_content_excerpts,
        'post_translatede_content_excerpts'=>$post_translatede_content_excerpts,
        'post_default_media_alternate_text'=>$post_default_media_alternate_text,
        'post_translatedet_media_alternate_text'=>$post_translatedet_media_alternate_text,
        'post_buttons3' => $button3
    );
}


die(json_encode($response));
}
?>