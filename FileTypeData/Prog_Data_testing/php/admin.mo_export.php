<?php
$lang_duel = explode("|||", $_POST['lang']);

$file_name = $_POST['mo'].'-'.$lang_duel[1].'.po';

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".$file_name."\"");

global $wpdb,$userdata;
$arraycheck = array();

$sql = "SELECT epi.default_string as default_string,epi.default_string2 as default_string2, epi.default_placeholder as default_placeholder, ifnull((SELECT translatede_string
FROM {$wpdb->prefix}etm_plugin_string where lang_code='".$lang_duel[0]."' and lang_index_id = epi.id LIMIT 1),'') as translatestring , ifnull((SELECT translatede_string2
FROM {$wpdb->prefix}etm_plugin_string where lang_code='".$lang_duel[0]."' and lang_index_id = epi.id LIMIT 1),'') as translatestring2  FROM {$wpdb->prefix}etm_plugin_index as epi  WHERE epi.mo_tag = '".$_POST['mo']."' group by epi.default_string, epi.default_string2,epi.default_placeholder ";
       
  
$sqldata_tmp = $wpdb->get_results($sql);


echo 'msgid ""
';
echo 'msgstr ""

';

if($lang_duel[0] != 'ru'){
	echo '"Plural-Forms: nplurals=2; plural=(n != 1);\n"
	
	';	
}
echo '"X-Poedit-KeywordsList: __;_e;__ngettext:1,2;_n:1,2;__ngettext_noop:1,2;_n_noop:1,2;_c,_nc:4c,1,2;_x:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;_ex:1,2c;esc_attr__;esc_attr_e;esc_attr_x:1,2c;esc_html__;esc_html_e;esc_html_x:1,2c\n"

';

echo '"X-Poedit-Basepath: .\n"

';

echo '"X-Poedit-SearchPath-0: .\n"

';

echo '"X-Poedit-SearchPath-1: ..\n"

';

echo '"Project-Id-Version: '.$_POST['mo'].'\n"

';
echo '"Language: '.$lang_duel[1].'\n"

';

echo '"Content-Type: text/plain; charset=UTF-8\n"

';

echo '"Content-Transfer-Encoding: 8bit\n"

';

if(!empty($sqldata_tmp)){
	foreach($sqldata_tmp as $s_tmp){
		if(!empty($s_tmp->default_string)){
		
        	if(!empty($s_tmp->default_placeholder)){
	        	echo 'msgctxt "'.mb_ereg_replace('"',"'",$s_tmp->default_placeholder).'"
';
        	}
		
			echo 'msgid "'.mb_ereg_replace('"',"'",$s_tmp->default_string).'"
';

        	if(!empty($s_tmp->default_string2)){
	        	echo 'msgid_plural "'.mb_ereg_replace('"',"'",$s_tmp->default_string2).'"
';
        	}
        	if(!empty($s_tmp->default_string2) ){
				if(!empty($s_tmp->translatestring)){
					echo 'msgstr[0] "'. mb_ereg_replace('"',"'",$s_tmp->translatestring).'"

';
				} else {
					echo 'msgstr[0] ""
	
	';
				}
				
				if(!empty($s_tmp->translatestring2)){
					echo 'msgstr[1] "'. mb_ereg_replace('"',"'",$s_tmp->translatestring2).'"

';
				} else {
					echo 'msgstr[1] ""
	
	';
				}
        	} else {
				if(!empty($s_tmp->translatestring)){
					echo 'msgstr "'. mb_ereg_replace('"',"'",$s_tmp->translatestring).'"

';
				} else {
					echo 'msgstr ""
	
	';
				}
        	}
		
		}	
	}
	
}
 
die();
?>