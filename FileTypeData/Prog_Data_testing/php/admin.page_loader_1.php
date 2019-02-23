<?php
global $wpdb,$userdata,$etm_tag,$etm_folde;

$post_type_array = etm_tools_get_types($_POST['type']);
$etm_tag = $post_type_array['tag'];


if(!empty($post_type_array['descriptions_group']) )
    echo '<div style="float:left;padding-top:5px;padding-bottom:10px;font-size: 12px;">'.$post_type_array['descriptions_group'] . '</div>';

if($etm_tag == 'plugin' || $etm_tag == 'theme' || $etm_tag == 'addon'){
	echo '<span><a style="text-decoration: none;display: block;clear: both;height:0px;font-weight: bold;" href="#" onClick="updateMoFiles(this,\''.$etm_tag.'\',\''.$etm_folde.'\');return false;"> Generate new .mo file (all Plugins/Themes/Add-ons)</a></span>';

} 

    
if(!empty($post_type_array['group_list_function']) && function_exists($post_type_array['group_list_function']))
    $data_array = $post_type_array['group_list_function'];

$obj_data = (object)array(); 
$obj_data->interval = etm_tools_retrive_options('limit_interval');
$obj_data->page = empty($_POST['previussubjecpage']) ? 0 : $_POST['previussubjecpage'];
$obj_data->sort_col = empty($_POST['sort_type']) ? etm_tools_retrive_options('sort_group_list_'.$etm_tag) : $_POST['sort_type'] ;
$obj_data->sort_dir = empty($_POST['sort_dir'])? etm_tools_retrive_options('sort_group_list_direction_'.$etm_tag) : $_POST['sort_dir'];
$obj_data->retrive_fn = $data_array;
$obj_data->etm_folder = '';
$obj_data->post_tag = $etm_tag;
	
$createTable = new unrealhuman_shower();
$createTable->setup_table($obj_data);
	
if(etm_tools_retrive_options('hide_auther')){
	foreach($post_type_array['etm_columns_group'] as $key => $tmp_data){
		if($tmp_data['backtitle'] == 'auther'){
			$post_type_array['etm_columns_group'][$key]['backtitle'] = 'autherDeativate';
		}
	}
} 
    
$createTable->set_table_data($post_type_array['etm_columns_group'],$data_array);

if($etm_tag == 'page'){
	$createTable->setup_seach_bar(0,$etm_tag,'',false,empty($_POST['sort_type']) ? etm_tools_retrive_options('sort_group_list_'.$etm_tag) : $_POST['sort_type'] ,empty($_POST['sort_dir'])? etm_tools_retrive_options('sort_group_list_direction_'.$etm_tag) : $_POST['sort_dir']);
}
    
if(!empty($post_type_array['etm_button_group_function']))
	$createTable->set_button($post_type_array['etm_button_group_function']);
	
echo $createTable->init(0);
    
if($etm_tag == 'post'){
	$data_array = '';
	$obj_data = (object)array(); 
	
	$etm_folder = 'post_tag';
	
	if(!empty($post_type_array['group_list_function_tags']) && function_exists($post_type_array['group_list_function_tags']))
  	  $data_array = $post_type_array['group_list_function_tags'];

	$obj_data->interval = etm_tools_retrive_options('limit_interval');
	$obj_data->page = 0;
	$obj_data->sort_col = etm_tools_retrive_options('sort_single_list_'.$etm_tag);
	$obj_data->sort_dir = etm_tools_retrive_options('sort_single_list_direction_'.$etm_tag);
	$obj_data->retrive_fn = $data_array;
	$obj_data->etm_folder = $etm_folder;
	$obj_data->post_tag = $etm_tag;

	$createTables = new unrealhuman_shower();
	$createTables->setup_title_description('<div class="etm_icon etm_icon_9"></div><span><h2>'.__('Post Tags','etm').'</h2></span>',__('Choose the Post Tag you want to translate.','etm'));
	$createTables->setup_table($obj_data);
	
	if(etm_tools_retrive_options('hide_auther')){
		foreach($post_type_array['etm_columns_group_tags'] as $key => $tmp_data){
			if($tmp_data['backtitle'] == 'auther'){
				$post_type_array['etm_columns_group_tags'][$key]['backtitle'] = 'autherDeativate';
			}
		}
	}	
	
	$createTables->set_table_data($post_type_array['etm_columns_group_tags'],$data_array);
	echo $createTables->init(1);

	$etm_folder = 'category';
	$obj_data = (object)array(); 
	$data_array = '';
	if(!empty($post_type_array['group_list_function_tags']) && function_exists($post_type_array['group_list_function_tags']))
  	  $data_array = $post_type_array['group_list_function_tags'];

	$obj_data->interval = etm_tools_retrive_options('limit_interval');
	$obj_data->page = 0;
	$obj_data->sort_col = etm_tools_retrive_options('sort_single_list_'.$etm_tag);
	$obj_data->sort_dir = etm_tools_retrive_options('sort_single_list_direction_'.$etm_tag);
	$obj_data->retrive_fn = $data_array;
	$obj_data->etm_folder = $etm_folder;
	$obj_data->post_tag = $etm_tag;    

	if(etm_tools_retrive_options('hide_auther')){
		foreach($post_type_array['etm_columns_group_tags'] as $key => $tmp_data){
			if($tmp_data['backtitle'] == 'auther'){
				$post_type_array['etm_columns_group_tags'][$key]['backtitle'] = 'autherDeativate';
			}
		}
	}

	$createTables = new unrealhuman_shower();
	$createTables->setup_table($obj_data);
	$createTables->setup_title_description('<div class="etm_icon etm_icon_9"></div><span><h2>' .__('Post Categories','etm').'</h2></span>',__('Choose the Post Category you want to translate.','etm'));
	$createTables->set_table_data($post_type_array['etm_columns_group_tags'],$data_array);
	echo $createTables->init(2);	
	
}	
die();
?>