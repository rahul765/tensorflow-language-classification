<?php
global $wpdb,$userdata,$easy_translation_manager_plugin;
 
if((empty($_REQUEST['tmp_lang']) or empty($_REQUEST['tmp_id'])) and $_REQUEST['status'] != 'create' and $_REQUEST['status'] != 'update' and $_REQUEST['status'] !='deleteManuals'){
	etm_send_error_die('Error data.');
}



if(!empty($_REQUEST['status']) && $_REQUEST['status']=='update'){

	if(empty($_REQUEST['tmp_motext']) or empty($_REQUEST['id']) or empty($_REQUEST['tmp_defaulttext'])){
		etm_send_error_die('Error data. Missing data');
	}
    
    $sql = "UPDATE {$wpdb->prefix}etm_plugin_index SET mo_tag='".$_REQUEST['tmp_motext']."',
                												   default_string='".$_REQUEST['tmp_defaulttext']."',
                												   file='".$_REQUEST['tmp_filetext']."',
                												   manual_added='1' WHERE id='".$_REQUEST['id']."'";
    $wpdb->query($sql);   	  	

    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Translation has been update'
    );

} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='create'){

	if(empty($_REQUEST['tag']) or empty($_REQUEST['folder']) or empty($_REQUEST['tmp_defaulttext']) or empty($_REQUEST['tmp_motext'])){
		etm_send_error_die('Error data.');
	}
	
		$sqlinsert = "INSERT INTO {$wpdb->prefix}etm_plugin_index (default_string,folder_name,file,category_type,mo_tag,create_user,create_ip,manual_added) 
        	  VALUES ('".$_REQUEST['tmp_defaulttext']."','".$_REQUEST['folder']."','".$_REQUEST['tmp_filetext']."','".$_REQUEST['tag']."','".$_REQUEST['tmp_motext']."',".$userdata->ID.",'".$_SERVER['REMOTE_ADDR']."',1)";
        $wpdb->query($sqlinsert); 
    
    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Translation has been added'
    );
    
} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='deleteManuals'){

        $sqlupdate = "DELETE FROM {$wpdb->prefix}etm_plugin_index WHERE id ='".$_REQUEST['tmp_id']."'";
        $wpdb->query($sqlupdate); 

        $sqlupdate = "DELETE FROM {$wpdb->prefix}etm_plugin_string WHERE lang_index_id ='".$_REQUEST['tmp_id']."'";
        $wpdb->query($sqlupdate); 
        
	    $response = array(
    		'R'	=> 'OK',
        	'MSG' => 'Manual translation has been deleted');    
    
} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
        $sqlupdate = "DELETE FROM {$wpdb->prefix}etm_plugin_string WHERE lang_code = '".$_REQUEST['tmp_lang']."' and lang_index_id ='".$_REQUEST['tmp_id']."'";
        $wpdb->query($sqlupdate); 
        
	    $response = array(
    		'R'	=> 'OK',
        	'MSG' => 'Translation has been deleted');   
} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='save'){
    $user_count = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}etm_plugin_string WHERE lang_code = '".$_REQUEST['tmp_lang']."' and lang_index_id ='".$_REQUEST['tmp_id']."'");
    
    if(empty($user_count)){
		if(!isset($_REQUEST['tmp_translations_ex1'])){
        	$sqlinsert = "INSERT INTO {$wpdb->prefix}etm_plugin_string (lang_code,lang_index_id,translatede_string,create_user,create_ip) 
        	  VALUES ('".$_REQUEST['tmp_lang']."','".$_REQUEST['tmp_id']."','".$_REQUEST['tmp_translations']."',".$userdata->ID.",'".$_SERVER['REMOTE_ADDR']."')";
        } else {
        	$sqlinsert = "INSERT INTO {$wpdb->prefix}etm_plugin_string (lang_code,lang_index_id,translatede_string,translatede_string2,create_user,create_ip) 
        	  VALUES ('".$_REQUEST['tmp_lang']."','".$_REQUEST['tmp_id']."','".$_REQUEST['tmp_translations']."','".$_REQUEST['tmp_translations_ex1']."',".$userdata->ID.",'".$_SERVER['REMOTE_ADDR']."')";
        }	  

        $wpdb->query($sqlinsert);             
    } else {
    	if(!isset($_REQUEST['tmp_translations_ex1'])){
        	$sqlupdate = "UPDATE {$wpdb->prefix}etm_plugin_string SET translatede_string='".$_REQUEST['tmp_translations']."' WHERE lang_code = '".$_REQUEST['tmp_lang']."' and lang_index_id ='".$_REQUEST['tmp_id']."'";
        	} else {
        	$sqlupdate = "UPDATE {$wpdb->prefix}etm_plugin_string SET translatede_string='".$_REQUEST['tmp_translations']."',translatede_string2='".$_REQUEST['tmp_translations_ex1']."' WHERE lang_code = '".$_REQUEST['tmp_lang']."' and lang_index_id ='".$_REQUEST['tmp_id']."'";
        	}
        $wpdb->query($sqlupdate);   
    }    
    
    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Your translation has been saved.',
        'INFOCON' => ($_REQUEST['tmp_translations'] != '' ? '1':'0')
    );
} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
        $sqlupdate = "DELETE FROM {$wpdb->prefix}etm_plugin_string WHERE lang_code = '".$_REQUEST['tmp_lang']."' and lang_index_id ='".$_REQUEST['tmp_id']."'";
        $wpdb->query($sqlupdate); 
        
	    $response = array(
    		'R'	=> 'OK',
        	'MSG' => 'Translation has been deleted');     
} else {
    $translations_string = ''; 
    $translations_string2 = '';
    $default_string = '';
    $default_string2 = '';
    $sqldatalang = etm_languages_flags($_REQUEST['tmp_lang']);
    
    
    $langed_string = '<img width="70px" style="padding-left: 10px; float: left;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"> Translate to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</div>';

    $sql = "SELECT id,translatede_string,translatede_string2 FROM  {$wpdb->prefix}etm_plugin_string WHERE lang_code = '".$_REQUEST['tmp_lang']."' and lang_index_id ='".$_REQUEST['tmp_id']."'";
    $sqldata = $wpdb->get_results($sql);
    
    $sql1 = "SELECT default_string,default_string2 FROM  {$wpdb->prefix}etm_plugin_index WHERE id ='".$_REQUEST['tmp_id']."'";
    $sqldata1 = $wpdb->get_results($sql1);
    
    if(count($sqldata1) > 0){

    	if(!empty($sqldata[0]->translatede_string)){
        	$translations_string = $sqldata[0]->translatede_string; 
        } 
        
    	if(!empty($sqldata[0]->translatede_string2)){
        	$translations_string2 = $sqldata[0]->translatede_string2; 
        }
        

    	if(!empty($sqldata1[0]->default_string)){
        	$default_string = $sqldata1[0]->default_string; 
        } 
    	if(!empty($sqldata1[0]->default_string2)){
        	$default_string2 = $sqldata1[0]->default_string2; 
        } 
        
        if(empty($translations_string)){
            $translations_string = '';
        }
        if(empty($translations_string2)){
            $translations_string2 = '';
        } 
    }
    
    $tmp = etm_languages_flags($_REQUEST['tmp_lang']);
		
	if(!empty($tmp['rtl']) && $tmp['rtl']){
		$dir = 'rtl';
	} else {
		$dir = 'ltr';
	}
    
    
    
    $content_info  = '<table style="clear:both;" height="100%" width="100%">';
    $content_info .= '<tr><td width="100%" colspan="3" valign="top"><h2 style="padding-top:0px"><h2>'.$langed_string.'</h2></td></tr>'; 
    
    
    if(empty($default_string2)){
	    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">'.__('Default','etm').'</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">'.__('Translation','etm').'</td>
	                      </tr>';
	                      
	
	    $content_info .= '<tr height="100%">
	                        <td width="49%" valign="top"><textarea id="etm_default_inputtext" enabled="false" style="height:100%;width:100%;resize:none" class="'.$dir.'" readonly>'.$default_string.'</textarea></td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="top"><textarea id="translations_inputtext" class="translations_inputtext  '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_string.'</textarea></td>
	                      </tr><tr style="height: 35px;"><td>';
    } else {
	    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">'.__('Singular Default','etm').'</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">'.__('Singular Translation','etm').'</td>
	                      </tr>';
	                      
	
	    $content_info .= '<tr height="40%">
	                        <td width="49%" valign="top"><textarea id="etm_default_inputtext" enabled="false" style="height:100%;width:100%;resize:none" class="'.$dir.'" readonly>'.$default_string.'</textarea></td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="top"><textarea id="translations_inputtext" class="translations_inputtext  '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_string.'</textarea></td>
	                      </tr><tr style="height: 35px;"><td>';
	                      
	                          
	    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">'.__('Plural Default','etm').'</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">'.__('Plural Translation','etm').'</td>
	                      </tr>';
	                      
	
	    $content_info .= '<tr height="40%">
	                        <td width="49%" valign="top"><textarea id="etm_default_inputtext_extra1" enabled="false" style="height:100%;width:100%;resize:none" class="'.$dir.'" readonly>'.$default_string2.'</textarea></td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="top"><textarea id="translations_inputtext_extra1" class="translations_inputtext  '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_string2.'</textarea></td>
	                      </tr><tr style="height: 35px;"><td>'; 
    }
    
    

    
    
    
    
    
    
    
    
    if(!empty($sqldata[0]->id) && (current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
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
    	
    if((current_user_can('etm_translate_'.$_REQUEST['tmp_type'])) || current_user_can('manage_options')){
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