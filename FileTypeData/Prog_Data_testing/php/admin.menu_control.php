<?php
global $wpdb,$userdata,$easy_translation_manager_plugin;

if(empty($_REQUEST['status'])){
	$_REQUEST['status'] = '';
}


add_action('wp_loaded','etm_when_wp_is_loaded');
function etm_when_wp_is_loaded(){
global $wpdb,$userdata,$easy_translation_manager_plugin;
if((empty($_REQUEST['tmp_lang']) or empty($_REQUEST['tmp_id'])) and $_REQUEST['status'] != 'create' and $_REQUEST['status'] != 'deleteAll'){
	etm_send_error_die('Error data.');
}
$delete_return = $_REQUEST['tmp_id'];

$id_data_split = explode('_', $_REQUEST['tmp_id']);
$_REQUEST['tmp_id'] = $id_data_split[0];
if(count($id_data_split) > 0 and !empty($id_data_split[1])){
	$_REQUEST['tmp_object_id'] = $id_data_split[1];	
}

if(count($id_data_split) > 1 and !empty($id_data_split[2])){
	$_REQUEST['etm_folder'] = $id_data_split[2];	
}



if(!empty($_REQUEST['status']) && $_REQUEST['status']=='save'){
	$getval = get_option('ect_tran_menu_'.$_REQUEST['tmp_lang']); 
	$getval[$_REQUEST['tmp_id']]->title = $_REQUEST['tmp_translations'];
	
	if(isset($_REQUEST['tmp_translations_ex1'])){
		$getval[$_REQUEST['tmp_id']]->attr_title = $_REQUEST['tmp_translations_ex1'];	
	}
	if(isset($_REQUEST['tmp_translations_ex2'])){
		$getval[$_REQUEST['tmp_id']]->url = $_REQUEST['tmp_translations_ex2'];	
	}
	if(isset($_REQUEST['tmp_translations_ex3'])){
		$getval[$_REQUEST['tmp_id']]->description = $_REQUEST['tmp_translations_ex3'];	
	}
	
	update_option('ect_tran_menu_'.$_REQUEST['tmp_lang'],$getval);
    
    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Your translation has been saved.',
        'INFOCON' => ($_REQUEST['tmp_translations'] != '' ? '1':'0')
    );
} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
	$getval = get_option('ect_tran_menu_'.$_REQUEST['tmp_lang']); 
    
	unset($getval[$_REQUEST['tmp_id']]);

	update_option('ect_tran_menu_'.$_REQUEST['tmp_lang'],$getval);
        
	    $response = array(
    		'R'	=> 'OK',
        	'MSG' => 'Translation has been deleted');     
} else {
 	$sqldatalang = etm_languages_flags($_REQUEST['tmp_lang']);
    
    
    
    $langed_string = '<img width="70px" style="padding-left: 10px; float: left;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"> Translate to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</div>';

    $postobjs = wp_get_nav_menu_items($_REQUEST['etm_folder'] );
    
    
    foreach($postobjs as $postobj){
    	if($postobj->ID == $_REQUEST['tmp_id']){
    		$default_string = $postobj->title; 
            $default_string1 = $postobj->attr_title;
            $default_string2 = $postobj->url;
            $default_string3 = $postobj->description;
            $default_type = $postobj->type;
    	}
    }
    
    

    
	$getval = get_option('ect_tran_menu_'.$_REQUEST['tmp_lang']);
    
    if(!empty($getval) and !empty($_REQUEST['tmp_id']) and !empty($getval[$_REQUEST['tmp_id']])){
		if(!empty($getval[$_REQUEST['tmp_id']]->title)){
			$translations_string = $getval[$_REQUEST['tmp_id']]->title;	
		}
		
		if(!empty($getval[$_REQUEST['tmp_id']]->attr_title)){
			$translations_title_attr = $getval[$_REQUEST['tmp_id']]->attr_title;	
		}
		
		if(!empty($getval[$_REQUEST['tmp_id']]->url)){
			$translations_url = $getval[$_REQUEST['tmp_id']]->url;	
		}
		
		if(!empty($getval[$_REQUEST['tmp_id']]->description)){
			$translations_description = $getval[$_REQUEST['tmp_id']]->description;	
		}
    }
    
    if(empty($translations_string)){
        $translations_string = '';
    }
	
    if(empty($translations_title_attr)){
        $translations_title_attr = '';
    }
    
    if(empty($translations_description)){
	    $translations_description = '';
	} 
	
    if(empty($translations_url)){
        $translations_url = '';
    }
        
    
        
    $tmp = etm_languages_flags($_REQUEST['tmp_lang']);
		
	if(!empty($tmp['rtl']) && $tmp['rtl']){
		$dir = 'rtl';
	} else {
		$dir = 'ltr';
	}
    
    $size_height = '25%';
    
    if($default_type == 'custom'){  
    	$size_height = '18%';
    }
    
    

    $content_info  = '<table style="clear:both;" height="100%" width="100%">';
    $content_info .= '<tr><td width="100%" colspan="3" valign="top"><h2 style="padding-top:0px"><h2>'.$langed_string.'</h2></td></tr>';  
    
    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
                        <td width="49%" valign="bottom">Default title</td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="bottom">Translated title</td>
                      </tr>';
    
    $content_info .= '<tr height="'.$size_height.'">
                        <td width="49%"  valign="top"><textarea id="etm_default_inputtext" disabled="disabled" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$default_string.'</textarea></td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="top"><textarea id="translations_inputtext" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_string.'</textarea></td>
                      </tr>';
                      
    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
                        <td width="49%"  valign="bottom">Default Title Attribute</td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="bottom">Translated Title Attribute</td>
                      </tr>'; 
                      
    $content_info .= '<tr height="'.$size_height.'">
                        <td width="49%"  valign="top"><textarea id="etm_default_inputtext_extra1" disabled="disabled" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$default_string1.'</textarea></td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="top"><textarea id="translations_inputtext_extra1" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_title_attr.'</textarea></td>
                      </tr>';

    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
                        <td width="49%"  valign="bottom">Default Description</td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="bottom">Translated Description</td>
                      </tr>';
       
    $content_info .= '<tr height="'.$size_height.'">
                        <td width="49%"  valign="top"><textarea id="etm_default_inputtext_extra3" disabled="disabled" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$default_string3.'</textarea></td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="top"><textarea id="translations_inputtext_extra3" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_description.'</textarea></td>
                      </tr>';
       
       
                      
    if($default_type == 'custom'){     
    
    	$content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
                        <td width="49%" valign="bottom">Default URL</td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="bottom">Translated URL</td>
                      </tr>'; 
             
    	$content_info .= '<tr height="'.$size_height.'">
                        <td width="49%"  valign="top"><textarea disabled="disabled" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$default_string2.'</textarea></td>
				        <td width="2%">&nbsp;</td>
				        <td width="49%" valign="top"><textarea id="translations_inputtext_extra2" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_url.'</textarea></td>
                      </tr>';       
    }                  
    $content_info .= '<tr style="height: 35px;"><td>';     

    if(!empty($translations_string) && (current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
    	$content_info .= '<div style="float: left;"><input type="submit" onClick="deletePopOpControl(\''.$delete_return.'\',\''.$_REQUEST['tmp_lang'].'\',\''.$_REQUEST['tmp_type'].'\')" value="Delete" class="button-secondary" name="Delete"></div>';
    }
    $content_info .= '</td><td></td><td  style="text-align: right">';
    
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
    
    
    $content_info .= '<div style="float:right;"><input type="submit" onClick="cancelPopOpControl()" value="Cancel" class="button-secondary" name="Cancel">';
    
    
    if((current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
    	$content_info .= '<input onClick="savePopOpControl(\''.$_REQUEST['tmp_id'].'\',\''.$_REQUEST['tmp_lang'].'\',\'#translations_inputtext\',\''.$_REQUEST['tmp_type'].'\',\''.$_REQUEST['tmp_id'].'_'.$_REQUEST['tmp_object_id'].'_'.$_REQUEST['etm_folder'].'\')" type="submit" class="button-primary" value="Save" name="Save">';
    }
    
    $content_info .= '</div></td></tr></table>';
   
    
    $response = array(
    	'R'	=> 'OK',
    	'RETURNDATA'=>$content_info
    );

}


die(json_encode($response));
}
?>