<?php
global $wpdb,$userdata,$content_info,$total_steps,$dir,$easy_translation_manager_plugin;
$total_steps = array();

if((empty($_REQUEST['tmp_lang']) or empty($_REQUEST['tmp_id'])) and $_REQUEST['status'] != 'create' and $_REQUEST['status'] != 'deleteAll'){
	etm_send_error_die('Error data.');
}


if(!empty($_REQUEST['status']) && $_REQUEST['status']=='save'){
	$sqllangcheckdata_save = $wpdb->get_var("SELECT option_name FROM  {$wpdb->prefix}options WHERE option_id='".$_REQUEST['tmp_id']." LIMIT 1'");
	
	if(!empty($sqllangcheckdata_save)){
		update_option('etm_'.$sqllangcheckdata_save.'_'.$_REQUEST['tmp_lang'],$_REQUEST['tmp_translations']); 	
	}

    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Your translation has been saved.',
        'INFOCON' => ($_REQUEST['tmp_translations'] != '' ? '1':'0')
    );	 

} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
	$sqllangcheckdata_save = $wpdb->get_var("SELECT option_name FROM  {$wpdb->prefix}options WHERE option_id='".$_REQUEST['tmp_id']." LIMIT 1'");
    if(!empty($sqllangcheckdata_delete[0])){
    	delete_option( 'etm_'.$sqllangcheckdata_save.'_'.$_REQUEST['tmp_lang'] );
    }

    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Translation has been deleted');     
} else {
    

	$sqldatalang = etm_languages_flags($_REQUEST['tmp_lang']);
    
    $langed_string = '<img width="70px" style="padding-left: 10px; float: left;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"> Translate to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</div>';

    $sqllangcheckdata_check = $wpdb->get_var("SELECT option_name FROM  {$wpdb->prefix}options WHERE option_id='".$_REQUEST['tmp_id']." LIMIT 1'");

    if(!empty($sqllangcheckdata_check)){
        $translations_string = get_option('etm_'.$sqllangcheckdata_check.'_'.$_REQUEST['tmp_lang'],''); 
        $default_string = get_option($sqllangcheckdata_check,'');

    }
    
    if(empty($translations_string)){
		$translations_string = '';
    }
    
    $tmp = etm_languages_flags($_REQUEST['tmp_lang']);
		
	if(!empty($tmp['rtl']) && $tmp['rtl']){
		$dir = 'rtl';
	} else {
		$dir = 'ltr';
	}
    
    

    $content_info  = '<table style="clear:both;" height="100%" width="100%">';
    $content_info .= '<tr><td width="100%" colspan="3" valign="top"><h2 style="padding-top:0px"><h2>'.$langed_string.'</h2></td></tr>'; 
    
 
	    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">'.__('Default','etm').'</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">'.__('Translation','etm').'</td>
	                      </tr>';
	    
	    
	    $content_info .= '<tr height="100%">
	                        <td width="49%" valign="top"><textarea id="etm_default_inputtext" enabled="false" style="height:100%;width:100%;resize:none" readonly class="'.$dir.'">'.$default_string.'</textarea></td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="top"><textarea id="translations_inputtext" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_string.'</textarea></td>
	                      </tr><tr style="height: 35px;"><td>'; 

    if(!empty($translations_string) && (current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
    	$content_info .= '<div style="float:left;"><input type="submit" onClick="deletePopOpControl(\''.$_REQUEST['tmp_id'].'\',\''.$_REQUEST['tmp_lang'].'\',\''.$_REQUEST['tmp_type'].'\')" value="Delete" class="button-secondary" name="Delete"></div>';
    }
    
    
    $content_info .= '</td><td></td><td><div style="float:right;">';
    
    
    if(!empty($easy_translation_manager_plugin->etm_tools_retrive['translator_yandex'])){
    	$default_lang = '';
    	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['default_language'])){
	    	$default_lang = $easy_translation_manager_plugin->etm_tools_retrive['default_language'];
    	}
    	
		if(etm_languages_translation($_REQUEST['tmp_lang'])){
			$content_info .= '<input onClick="googleTranslateEtm(\''. $_REQUEST['tmp_lang'] .'\',\''. $default_lang .'\')" type="submit" style="margin-right: 25px;" class="button-secondary" value="Translate">';
		} else {
			$content_info .= '<input disabled="disabled"  type="submit" style="margin-right: 25px;" class="button-secondary" value="No translation available">';
		}
    }
    
    
    
    
    
    $content_info .= '<input type="submit" onClick="cancelPopOpControl()" value="Cancel" class="button-secondary" name="Cancel">';
    
    if((current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
    	$content_info .= '<input onClick="savePopOpControl(\''.$_REQUEST['tmp_id'].'\',\''.$_REQUEST['tmp_lang'].'\',\'#translations_inputtext\',\''.$_REQUEST['tmp_type'].'\')" type="submit" class="button-primary" value="Save" name="Save">';
    }
    
    $content_info .= '</div></td></tr></table>';
   
    
    $response = array(
    	'R'	=> 'OK',
    	'RETURNDATA'=>$content_info
    );

}


die(json_encode($response));
?>