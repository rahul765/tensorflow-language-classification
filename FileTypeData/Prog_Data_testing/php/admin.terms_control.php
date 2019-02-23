<?php
global $wpdb,$userdata,$easy_translation_manager_plugin;

if((empty($_REQUEST['tmp_lang']) or empty($_REQUEST['tmp_id'])) and $_REQUEST['status'] != 'create' and $_REQUEST['status'] != 'deleteAll'){
	etm_send_error_die('Error data.');
}

add_action('wp_loaded','etm_when_wp_is_loaded2');
function etm_when_wp_is_loaded2(){
	global $wpdb,$userdata,$easy_translation_manager_plugin;

	if(!empty($_REQUEST['status']) && $_REQUEST['status']=='save'){
		$getval = get_option('ect_tran_terms_'.$_REQUEST['tmp_lang']); 
		$getval[$_REQUEST['tmp_id']]->name = $_REQUEST['tmp_translations'];
		$getval[$_REQUEST['tmp_id']]->slug = $_REQUEST['tmp_translations_ex1'];    
		$getval[$_REQUEST['tmp_id']]->description = $_REQUEST['tmp_translations_ex2'];
		$getval[$_REQUEST['tmp_id']]->type = $_REQUEST['tmp_type'];    
		update_option('ect_tran_terms_'.$_REQUEST['tmp_lang'],$getval);
	    
	    if($_REQUEST['tmp_type'] == 'category'){
		   $getval1 = get_option('ect_tran_terms_checkuplist_category'); 
	       $getval1[$_REQUEST['tmp_id'].'_'.$_REQUEST['tmp_lang']] = $_REQUEST['tmp_translations_ex1'];
		   update_option('ect_tran_terms_checkuplist_category',$getval1); 
	    } else if($_REQUEST['tmp_type'] == 'post_tag'){
		   $getval2 = get_option('ect_tran_terms_checkuplist_post_tag'); 
	       $getval2[$_REQUEST['tmp_id'].'_'.$_REQUEST['tmp_lang']] = $_REQUEST['tmp_translations_ex1'];
		   update_option('ect_tran_terms_checkuplist_post_tag',$getval2);   
	    }
	    
	    
	    $response = array(
	    	'R'	=> 'OK',
	        'MSG' => 'Your translation has been saved.',
	        'INFOCON' => ($_REQUEST['tmp_translations'] != '' ? '1':'0')
	    );	 
	
	} else if(!empty($_REQUEST['status']) && $_REQUEST['status']=='delete'){
		$getval = get_option('ect_tran_terms_'.$_REQUEST['tmp_lang']); 
		unset($getval[$_REQUEST['tmp_id']]);
		update_option('ect_tran_terms_'.$_REQUEST['tmp_lang'],$getval);
	
	    $response = array(
	    	'R'	=> 'OK',
	        'MSG' => 'Translation has been deleted');     
	} else {
		$sqldatalang = etm_languages_flags($_REQUEST['tmp_lang']);
	    
	    $langed_string = '<img width="70px" style="padding-left: 10px; float: left;" src="'.etm_tools_create_icons_url($sqldatalang['icon'],2).'" ><div style="float: left; padding-left: 10px; padding-top: 3px;"> Translate to '.$sqldatalang['org_name'] . ' ('. $sqldatalang['english_name'] . ')</div>';
	
		$translations_string = '';
		$translations_string1 = '';
		$translations_string2 = '';
		
		$getval = get_option('ect_tran_terms_'.$_REQUEST['tmp_lang']);

		if(!empty($getval)){ 
			// error_log('tmp id '.$getval[$_REQUEST['tmp_id']]->name);
			// echo join(',', $getval);
			if(!empty($getval[$_REQUEST['tmp_id']]->name)){
				$translations_string = $getval[$_REQUEST['tmp_id']]->name;	
			}
			if(!empty($getval[$_REQUEST['tmp_id']]->slug)){
				$translations_string1 = $getval[$_REQUEST['tmp_id']]->slug;	
			}  
			if(!empty($getval[$_REQUEST['tmp_id']]->description)){
				$translations_string2 = $getval[$_REQUEST['tmp_id']]->description;	
			} 
	    } 
	    $default_string = get_term($_REQUEST['tmp_id'], $_REQUEST['taxonomy']);
	
	    if(!empty($default_string) && !empty($default_string->errors)){
	       $default_string  = '';
	    }    
	    
	    if(empty($default_string)){
	       $default_string  = get_term_by('id', $_REQUEST['tmp_id'], $_REQUEST['taxonomy']);
	    }
	
	    $default_string_ex2 = '';
	    $default_string_ex1 = '';
	    
	    
	    if(!empty($default_string)){
		    if(!empty($default_string->description)){
				$default_string_ex2 = $default_string->description; 
		    }	    
		    if(!empty($default_string->slug)){
				$default_string_ex1 = $default_string->slug;
		    }
		    if(!empty($default_string->name)){
				$default_string = $default_string->name; 
		    }
	    }
	
	    
	    
	         
	    if(empty($translations_string)){
			$translations_string = '';
	    }
	    
	    if(empty($translations_string1)){
			$translations_string1 = '';
	    }
	    
	    if(empty($translations_string2)){
			$translations_string2 = '';
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
	                        <td width="49%"  valign="bottom">Default Name</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">Translated Name</td>
	                      </tr>';
	     
	    $content_info .= '<tr><td width="49%" valign="top"><input type="text" enabled="false" disabled="disabled" value="'.$default_string.'" id="etm_default_inputtext" style="clear:both;width:100%" id="post_default_header"></td><td width="2%">&nbsp;</td><td width="49%" valign="top"><input type="text" style="width:100%;clear:both;" value="'.$translations_string.'" id="translations_inputtext" class="translations_inputtext"></td></tr>';
	                       
	    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">Default Slug</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">Translated Slug</td>
	                      </tr>';                     
	                                       
	    $content_info .= '<tr>
	                        <td width="49%"  valign="top"><input type="text" enabled="false" disabled="disabled" value="'.$default_string_ex1.'" style="clear:both;width:100%" id="post_default_header"></td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="top"><input type="text" style="width:100%;clear:both;" value="'.$translations_string1.'" id="translations_inputtext_extra1" class="translations_inputtext"></td>
	                      </tr>';     
	                      
	                      
	                      
	    $content_info .= '<tr style="text-align: left; height: 20px; line-height: 10px;">
	                        <td width="49%"  valign="bottom">Default Description</td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="bottom">Translated Description</td>
	                      </tr>';  
	                      
	                                      
	    $content_info .= '<tr height="80%">
	                        <td width="49%"  valign="top"><textarea disabled="disabled" id="etm_default_inputtext_extra2" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$default_string_ex2.'</textarea></td>
					        <td width="2%">&nbsp;</td>
					        <td width="49%" valign="top"><textarea id="translations_inputtext_extra2" class="translations_inputtext '.$dir.'" style="height:100%;width:100%;resize:none">'.$translations_string2.'</textarea></td>
	                      </tr><tr style="height: 35px;"><td>';                   
	                        
	                      
	    if(!empty($translations_string) && (current_user_can('etm_translate_'.$_REQUEST['tmp_type']) || current_user_can('manage_options'))){
	    	$content_info .= '<div style="float:left;"><input type="submit" onClick="deletePopOpControl(\''.$_REQUEST['tmp_id'].'\',\''.$_REQUEST['tmp_lang'].'\',\''.$_REQUEST['tmp_type'].'\')" value="Delete" class="button-secondary" name="Delete"></div>';
	    }
	    $content_info .= '</td><td></td><td style="text-align: right">';
	    
	    
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
}
?>