<?php
global $wpdb,$userdata;
$arraycheck = array();


$sql = "SELECT eps.lang_code as lang_code,eps.translatede_string as translatede_string,eps.translatede_string2 as translatede_string2,epi.mo_tag as mo_tag,epi.default_string as default_string,epi.default_string2 as default_string2, epi.default_placeholder as default_placeholder FROM {$wpdb->prefix}etm_plugin_string as eps , {$wpdb->prefix}etm_plugin_index as epi  WHERE eps.translatede_string != '' and eps.lang_index_id =epi.id and epi.mo_tag != ''";
    
$sqldata_tmp = $wpdb->get_results($sql);
    if(count($sqldata_tmp) > 0)
    {  
        foreach($sqldata_tmp as $tmp)
        {
        	$extra = '';
        	if(!empty($tmp->default_placeholder)){
	        	$extra = '('.$tmp->default_placeholder.')';
        	}
        
        
        	$arraycheck[$tmp->lang_code][$tmp->mo_tag][$tmp->default_string.$extra]['msgid'] = $tmp->default_string;
        	if(!empty($tmp->default_string2)){
	        	$arraycheck[$tmp->lang_code][$tmp->mo_tag][$tmp->default_string.$extra]['msgid_plural'] = $tmp->default_string2;
        	}
        	
        	if(!empty($tmp->default_placeholder)){
	        	$arraycheck[$tmp->lang_code][$tmp->mo_tag][$tmp->default_string.$extra]['msgctxt'] = $tmp->default_placeholder;
        	}
        	
        	$arraycheck[$tmp->lang_code][$tmp->mo_tag][$tmp->default_string.$extra]['msgstr'][] = $tmp->translatede_string;
        	if(!empty($tmp->translatede_string2) ){
	        	$arraycheck[$tmp->lang_code][$tmp->mo_tag][$tmp->default_string.$extra]['msgstr'][] = $tmp->translatede_string2;
        	}	
        }
	}
	
 	if(!file_exists(EASY_TRANSLATION_MANAGER_UPLOAD_PATH_LANG)){
 		mkdir(EASY_TRANSLATION_MANAGER_UPLOAD_PATH_LANG, 0700);
 	}	
	
 	if(!file_exists(EASY_TRANSLATION_MANAGER_UPLOAD_PATH)){
 		mkdir(EASY_TRANSLATION_MANAGER_UPLOAD_PATH, 0700);
 	}
 	
 	$save_files_name = array();

    foreach($arraycheck as $key_lang => $tmp_lang)
    {
		foreach($tmp_lang as $key_mo =>  $tmp_mo)
        {
			phpmo_write_mo_file2($tmp_mo, EASY_TRANSLATION_MANAGER_UPLOAD_PATH.'/'.$key_mo.'-'.$key_lang.'.mo');  		
        	$save_files_name[$key_mo] = (object) array('path' => EASY_TRANSLATION_MANAGER_UPLOAD_PATH.'/'.$key_mo.'-' , 'url' => EASY_TRANSLATION_MANAGER_UPLOAD_URL.'/'.$key_mo.'_','domain'=>$key_mo);
        } 
	}

    update_option('etm_mo_files',$save_files_name); 
 
$response = array(
    'R'	=> 'OK'
);

die(json_encode($response));
?>