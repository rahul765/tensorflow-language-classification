<?php
    global $wpdb,$userdata,$etm_tag,$meta_id;
    
    $etm_folder = $_POST['folder'];
    
    $check_pieces = explode("_", $etm_folder);
    if(empty($check_pieces[0])){
    	$check_pieces[0] = '';
    }
    if(empty($check_pieces[1])){
    	$check_pieces[1] = '';
    } 
    if(empty($check_pieces[2])){
    	$check_pieces[2] = '';
    }  
    
    $meta_id = $check_pieces[1];
    
    $post_type_array = etm_tools_get_types($_POST['type']); 
    $etm_tag = $post_type_array['tag'];
    
    if(!empty($_POST['previussubjecpage'])){
        $etm_previussubjecpage = $_POST['previussubjecpage'];
    } else {
        $etm_previussubjecpage = 0;
    }
    

    echo '<div style="padding-top:5px;font-size:16px">'.$_POST['title']. '</div>'; 
    if(!empty($post_type_array['descriptions_single']) )
        echo '<div style="float:left;font-size: 12px;padding-bottom: 5px;">'.$post_type_array['descriptions_single'] . '</div>'; 
    
    
    if(count($check_pieces)>0 && $check_pieces[0] == 'meta'){
        echo '<div style="clear: both;font-size: 12px;padding-bottom: 5px;padding-top: 5px;float:left;margin-left: 10px;"><a href="#" style="text-decoration: none;float: left;" onClick="loadingContent('.$etm_previussubjecpage.',\''.$check_pieces[2].'\',\''.$check_pieces[2].'\',false,\''.$_POST['sort_type'].'\',\''.$_POST['sort_dir'].'\');return false;">< back</a>';
    } else {
        echo '<div style="clear: both;font-size: 12px;padding-bottom: 5px;padding-top: 5px;float:left;margin-left: 10px;"><a href="#" style="text-decoration: none;float: left;" onClick="loadingContent('.$etm_previussubjecpage.',\'\',\'\',false,\''.$_POST['sort_type'].'\',\''.$_POST['sort_dir'].'\');return false;">< back</a>';
    }
    
    
    if(($etm_tag == 'plugin' || $etm_tag == 'theme'  || $etm_tag == 'addon') && (current_user_can('etm_string_create') || current_user_can('manage_options')) ){
    	echo '<span style="float: left;margin-left: 10px;"> - </span>';
    	echo '<a style="float: left;text-decoration: none;margin-left:10px" href="#" onClick="openManualInput(\''.$etm_tag.'\',\''.$etm_folder.'\');return false;"> Create Manual String</a>';	
    } 
    
    if($etm_tag == 'plugin' || $etm_tag == 'theme'  || $etm_tag == 'addon'){
    	echo '<span style="float: left;margin-left: 10px;"> - </span>';
    	echo '<a style="float: left;text-decoration: none;margin-left:10px" href="#" onClick="updateListFiles(this,\''.$etm_tag.'\',\''.$etm_folder.'\');return false;"> Rescan files (only this plugin)</a>';
    	echo '<span style="float: left;margin-left: 10px;"> - </span>';
    	echo '<a style="float: left;font-weight: bold;margin-left: 10px;opacity: 1;text-decoration: none;" href="#" onClick="updateMoFiles(this);return false;"> Generate new .mo file (all Plugins/Themes/Add-ons)</a>';
    }
    
    echo '</div>';
    
    
    if(empty($firsttime)){
    	$firsttime = false;
    }
    
    $data_array = '';
    
    if(count($check_pieces)>0 && $check_pieces[0] == 'meta' && function_exists('etm_tools_get_menu_group')){
        $data_array = 'etm_tools_get_meta';
    } else if(!empty($post_type_array['single_list_function']) && function_exists($post_type_array['single_list_function'])){
        $data_array = $post_type_array['single_list_function']; 
    }
    
    
        
    $obj_data = (object)array(); 
    $obj_data->interval = etm_tools_retrive_options('limit_interval');
    $obj_data->page = 0;
    $obj_data->sort_col = etm_tools_retrive_options('sort_single_list_'.$etm_tag);
    $obj_data->sort_dir = etm_tools_retrive_options('sort_single_list_direction_'.$etm_tag);
    $obj_data->retrive_fn = $data_array;
	$obj_data->etm_folder = $etm_folder;
	$obj_data->post_tag = $etm_tag;

    $createTable = new unrealhuman_shower();
    $createTable->setup_table($obj_data);
    if(etm_tools_retrive_options('hide_auther') and !empty($post_type_array['etm_columns_single'])){
    	foreach($post_type_array['etm_columns_single'] as $key => $tmp_data){
    		if($tmp_data['backtitle'] == 'auther'){
    			$post_type_array['etm_columns_single'][$key]['backtitle'] = 'autherDeativate';
    		}
    	}
    }
    
        if(count($check_pieces)>0 && $check_pieces[0] == 'meta'){
            $createTable->set_table_data($post_type_array['etm_columns_meta'],$data_array);
        } else {
            $createTable->set_table_data($post_type_array['etm_columns_single'],$data_array);
            $createTable->setup_seach_bar(0,$check_pieces[0],$check_pieces[0],false,$_POST['sort_type'],$_POST['sort_dir']);
        }
  
    if(!empty($post_type_array['etm_button_single_function']))
    	$createTable->set_button($post_type_array['etm_button_single_function']);
    	
    die($createTable->init());
?>