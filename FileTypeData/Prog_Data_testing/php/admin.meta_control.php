<?php
global $wpdb,$userdata,$content_info,$total_steps,$dir,$easy_translation_manager_plugin;
$total_steps = array();

if((empty($_REQUEST['tmp_lang']) or empty($_REQUEST['tmp_id'])) and $_REQUEST['status'] != 'create' and $_REQUEST['status'] != 'deleteAll'){
	etm_send_error_die('Error data.');
}


if(!empty($_REQUEST['status']) && $_REQUEST['status']=='save'){
    $sqllangcheckdata_save = $wpdb->get_results("SELECT post_id,meta_key FROM  {$wpdb->prefix}postmeta WHERE meta_id='".$_REQUEST['tmp_id']." LIMIT 1'");
	
    
    if(!empty($sqllangcheckdata_save[0])){
    
    	if(!empty($_GET['multidata'])){
    	
	    	if(!empty($_GET)){
	    		$save_array = array();
		    	foreach($_GET as $_tmp_k => $_tmp_d){
			    	
			    	if(!empty($_tmp_k) && substr($_tmp_k, 0, 13) == 'translations_'){
				    	$_position =  substr($_tmp_k, 13);
				    	$_position =  explode('||', $_position);

				    	if(count($_position) == 1){
					    	$save_array[$_position[0]] = $_tmp_d;
				    	} else if(count($_position) == 2){
					    	$save_array[$_position[0]][$_position[1]] = $_tmp_d;
				    	} else if(count($_position) == 3){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]] = $_tmp_d;
				    	} else if(count($_position) == 4){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]] = $_tmp_d;
				    	} else if(count($_position) == 5){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]][$_position[4]] = $_tmp_d;
				    	} else if(count($_position) == 6){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]][$_position[4]][$_position[5]] = $_tmp_d;
				    	} else if(count($_position) == 7){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]][$_position[4]][$_position[5]][$_position[6]] = $_tmp_d; 	
				    	} else if(count($_position) == 8){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]][$_position[4]][$_position[5]][$_position[6]][$_position[7]] = $_tmp_d; 
				    	} else if(count($_position) == 9){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]][$_position[4]][$_position[5]][$_position[6]][$_position[7]][$_position[8]] = $_tmp_d; 
				    	} else if(count($_position) == 10){
					    	$save_array[$_position[0]][$_position[1]][$_position[2]][$_position[3]][$_position[4]][$_position[5]][$_position[6]][$_position[7]][$_position[8]][$_position[9]] = $_tmp_d; 
				    	}
			    	}
		    	}
	    	}	
	    	
	    	update_post_meta($sqllangcheckdata_save[0]->post_id,$sqllangcheckdata_save[0]->meta_key.'_'.$_REQUEST['tmp_lang'],$save_array);
	    	
	    	$_REQUEST['tmp_translations'] = 1;
    	} else {
	        update_post_meta($sqllangcheckdata_save[0]->post_id,$sqllangcheckdata_save[0]->meta_key.'_'.$_REQUEST['tmp_lang'],$_REQUEST['tmp_translations']); 
    	}
    }
    
    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Your translation has been saved.',
        'INFOCON' => ($_REQUEST['tmp_translations'] != '' ? '1':'0')
    );	 

} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
    
    $sqllangcheckdata_delete = $wpdb->get_results("SELECT post_id,meta_key FROM  {$wpdb->prefix}postmeta WHERE meta_id='".$_REQUEST['tmp_id']." LIMIT 1'");
	
    
    if(!empty($sqllangcheckdata_delete[0])){
        delete_post_meta($sqllangcheckdata_delete[0]->post_id,$sqllangcheckdata_delete[0]->meta_key.'_'.$_REQUEST['tmp_lang']); 
    }

    $response = array(
    	'R'	=> 'OK',
        'MSG' => 'Translation has been deleted');     
} else {
    

	$sqldatalang = etm_languages_flags($_REQUEST['tmp_lang']);
    
    $langed_string = '<img width="70px" style="padding-left: 10px; float: left;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"> Translate to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</div>';

    $sqllangcheckdata_check = $wpdb->get_results("SELECT post_id,meta_key,meta_value FROM  {$wpdb->prefix}postmeta WHERE meta_id='".$_REQUEST['tmp_id']." LIMIT 1'");
	

    if(!empty($sqllangcheckdata_check[0])){
        $translations_string = get_post_meta($sqllangcheckdata_check[0]->post_id, $sqllangcheckdata_check[0]->meta_key.'_'.$_REQUEST['tmp_lang'], true); 
        $default_string = get_post_meta($sqllangcheckdata_check[0]->post_id, $sqllangcheckdata_check[0]->meta_key, true);
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
    
    
    if(!is_array($default_string)){
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
    } else {
	    $content_info .= '<tr style="text-align: left; height: 100px; line-height: 10px;" height="100%"><td colspan="3" width="100%" height="100%" valign="top">';
		    $content_info  .= '<div style="position: relative; height: 100%; display: block; overflow: auto;"><form id="etm_multi_array_data"><table style="position: absolute;" width="100%">';
		    
			$content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">'.__('Default','etm').'</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">'.__('Translation','etm').'</td>
	                      </tr>';		
	                      
		    etm_generate_boxes($default_string,$translations_string);
               
		    $content_info .= '</table></form></div>';
	    
	    $content_info .= '</td></tr><tr style="height: 35px;"><td>';
	    
	    
	    
    } 
    

    
    
    

    

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


function etm_generate_boxes($array_data,$translations_string){
	global $content_info,$total_steps,$dir;

	if(!empty($array_data)){
		foreach($array_data as $_tmp_k => $tmp){
			if(is_array($tmp)){
				$total_steps[] = $_tmp_k;
				etm_generate_boxes($tmp,$translations_string);
			} else {
				$create_tmp_variable = '';
				$tmp_data = $translations_string;

				if(!empty($total_steps)){
					foreach($total_steps as $tmp_d){
						if($create_tmp_variable != ''){
							$create_tmp_variable .= '||';
						}
						
						if(!empty($tmp_data[$tmp_d])){
							$tmp_data = $tmp_data[$tmp_d];
						} else {
							$tmp_data = '';
						}
						
						$create_tmp_variable .= $tmp_d;
					}
				}

				
				if($create_tmp_variable != ''){
					$create_tmp_variable .= '||';
				}
				
				if(!empty($tmp_data[$_tmp_k])){				
					$tmp_data = $tmp_data[$_tmp_k];
				} else {
					$tmp_data = '';
				}
				$create_tmp_variable .= $_tmp_k;
	
				$content_info .= '<tr height="100" style="height: 100px"><td height="100" width="49%" valign="top"><textarea enabled="false" style="height:100%;width:100%;resize:none" readonly class="'.$dir.'">'.$tmp.'</textarea></td><td height="100" width="2%">&nbsp;</td><td height="100" width="49%" valign="top"><textarea name="translations_'.$create_tmp_variable.'" id="translations_'.$create_tmp_variable.'" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$tmp_data.'</textarea></td></tr>';
			}
		}
	}
	
	if(!empty($total_steps)){
		$total_steps = array_values($total_steps);
		unset($total_steps[count($total_steps)-1]);	
		$total_steps = array_values($total_steps);
	}
	
	
}





die(json_encode($response));
?>