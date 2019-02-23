<?php

class admin_etm_importexportpo {
	var $parent_id;
    var $page;
    var $pages;
    var $plugin_page;
    var $parent_menu_id;

	function admin_etm_importexportpo($parent_id){
        $this->parent_id = $parent_id; 
        $this->parent_menu_id = $parent_id.'-opt';      
		add_action($parent_id.'-options-menu', array(&$this,'admin_menu'), 50, 0);		
	}

	// Create the difference menue
	function admin_menu(){
		$this->plugin_page = add_submenu_page($this->parent_menu_id,__('Import / Export Po','evt'),__('Import / Export Po','evt'),'manage_options','etm_importexportpo_translations',array(&$this,'chek_current_page') );
	}
	
	function curPageURL() {
	 $pageURL = 'http';
	 if (!empty($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
	
	// aktive pages body
	function chek_current_page(){
		require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/fn.php_mo.php');
	
		global $wpdb,$userdata;
	
		$Error = '';
	
		if(!empty($_POST['status']) and $_POST['status'] == 'import'){
			$tmp_textdomain = '';
			$tmp_lang = '';
		
			if(!empty($_FILES["file"])){
			
				$_FILES["file"]["name"] = substr($_FILES["file"]["name"], 0, -3);
				$pieces = explode("-", $_FILES["file"]["name"]);
				
				if(count($pieces)>0){
					$tmp_textdomain = $pieces[0];
					$tmp_lang = $pieces[(count($pieces)-1)];
					if(count($pieces)>2){
						$tmp_textdomain = '';
						for($i=0;$i<(count($pieces)-1); $i++){
						
							if(!empty($tmp_textdomain)){
								$tmp_textdomain .= '-';
							}
							$tmp_textdomain .= $pieces[$i];
						}
					}
				}
			}
		
		
			
			if (empty($_POST['plugintheme'])){
				$Error = "Error: Please select a Theme and Plugin";
			} else if (empty($tmp_lang) or (strlen($tmp_lang)!= 2 and strlen($tmp_lang)!= 5)){
				$Error = "Error: No languash is found";
			} else if (empty($tmp_textdomain)){
				$Error = "Error: No textdomain is found";
			} else if ($_FILES["file"]["error"] > 0){
				$Error = "Error: " . $_FILES["file"]["error"];
			} else if($_FILES["file"]["type"] != 'application/octet-stream'){
				$Error = "Error: wrong format (.po)";
			} else {
				$pies = explode("|||", $_POST['plugintheme']);
				$etm_folder = $pies[0];
				$etm_tag = $pies[1];
			
			
				if(empty($_POST['overwrite'])){
					$_POST['overwrite'] = 'off';
				}
				
				if(strlen($tmp_lang)> 2){
					$lang_total = etm_languages_flags();
					if(!empty($lang_total)){
						foreach($lang_total as $key => $lang_t){
							if($lang_t['default_locale'] == $tmp_lang){
								$tmp_lang = $key;
								break;
							}
						}
					}
				}

				$has_input_data_checkup = false;
				//preg_match_all('/msgid (\")(.*?)(\")\s*msgstr (\")(.*?)(\")/', $str, $matches, PREG_SET_ORDER);
				$matches = phpmo_parse_po_file($_FILES["file"]["tmp_name"]);
		
				if(!empty($matches)){
					echo '<table cellpadding="5"><tr><td>';
					echo   '<h3  style="margin-bottom: 0px;">'.__('Importede strings','evt').'</h3>';
					echo '</td><td></td></tr>';
					echo '</table>';

					echo '<div style="overflow: auto; height: 400px; width: 800px; border: 1px solid lightgray;"><table cellpadding="5"><tr><td>';
					echo '<tr><td>';
					echo 	'Status';
					echo '</td><td>';
					echo 	'Translate string';
					echo '</td></tr>';
					foreach($matches as $matche){
						if(!empty($matche['msgid'])){

				            $sql_tmp = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}etm_plugin_index WHERE default_string=\"".$matche['msgid']."\" and default_string2=\"".$matche['msgid_plural']."\" and default_placeholder=\"".$matche['msgctxt']."\" and mo_tag=\"".$tmp_textdomain."\" and category_type =\"".$etm_tag."\" and folder_name=\"".$etm_folder."\" LIMIT 1");
				            
				            $string_id = 0;
				            
				            if(!empty($sql_tmp)){
				            	$string_id = $sql_tmp;
				            
					            if(!empty($matche['msgstr'][0]) and !empty($tmp_lang) and !empty($string_id)){
					            	$sql_tmp3 = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}etm_plugin_string WHERE lang_code = \"".$tmp_lang."\" and lang_index_id =\"".$string_id."\""); 
								    if(empty($sql_tmp3)){
								    	$has_input_data_checkup = true;
								        $sqlinsert = "INSERT INTO {$wpdb->prefix}etm_plugin_string (lang_code,lang_index_id,translatede_string,translatede_string2,create_user,create_ip) 
								        	  VALUES (\"".$tmp_lang."\",\"".$string_id."\",\"".$matche['msgstr'][0]."\",\"".$matche['msgstr'][1]."\",".$userdata->ID.",\"".$_SERVER['REMOTE_ADDR']."\")";
								        $wpdb->query($sqlinsert); 
								        
										echo '<tr style="font-size:10px;"><td>';
										echo 	'Insert';
										echo '</td><td>';
										
										if(!empty($matche['msgctxt'])){
											echo 'placeholder ('.$matche['msgctxt'].')<br>';
										}
										
										echo 	$matche['msgid'].' -- '.$matche['msgstr'][0];
										
										if(!empty($matche['msgstr'][1])){
											echo '<br>';
											echo $matche['msgid_plural'].' -- '.$matche['msgstr'][1];
										}
										
										echo '</td></tr>';
								        
								                    
								    } else {
								    	if($_POST['overwrite'] == 'on'){
								    		$has_input_data_checkup = true;
									        $sqlupdate = "UPDATE {$wpdb->prefix}etm_plugin_string SET translatede_string=\"".$matche['msgstr'][0]."\",translatede_string2=\"".$matche['msgstr'][1]."\" WHERE lang_code = \"".$tmp_lang."\" and lang_index_id =\"".$string_id."\"";
									        $wpdb->query($sqlupdate); 
									        
											echo '<tr  style="font-size:10px;"><td>';
											echo 	'Update';
											echo '</td><td>';
											if(!empty($matche['msgctxt'])){
												echo 'placeholder ('.$matche['msgctxt'].')<br>';
											}
											
											echo 	$matche['msgid'].' -- '.$matche['msgstr'][0];
											
											if(!empty($matche['msgstr'][1])){
												echo '<br>';
												echo $matche['msgid_plural'].' -- '.$matche['msgstr'][1];
											}
											echo '</td></tr>';
									        
								        }  
								    }   
					            }
				            } else {
					            $sql_tmp2 = "INSERT INTO {$wpdb->prefix}etm_plugin_index (default_string,default_string2,default_placeholder,folder_name,mo_tag,category_type,file,manual_added,create_user,create_ip) 
                	  VALUES (\"".$matche['msgid']."\",\"".$matche['msgid_plural']."\",\"".$matche['msgctxt']."\",\"".$etm_folder."\",\"".$tmp_textdomain."\",\"".$etm_tag."\",' ','0',".$userdata->ID.",\"".$_SERVER['REMOTE_ADDR']."\")";
					            $wpdb->query($sql_tmp2);  
					            $string_id = $wpdb->insert_id;
					            
					            if(!empty($matche['msgstr'][0]) and !empty($tmp_lang) and !empty($string_id)){
					            	$sql_tmp3 = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}etm_plugin_string WHERE lang_code = \"".$tmp_lang."\" and lang_index_id =\"".$string_id."\"");
					            	 
								    if(empty($sql_tmp3)){
								    	$has_input_data_checkup = true;
								        $sqlinsert = "INSERT INTO {$wpdb->prefix}etm_plugin_string (lang_code,lang_index_id,translatede_string,translatede_string2,create_user,create_ip) 
								        	  VALUES (\"".$tmp_lang."\",\"".$string_id."\",\"".$matche['msgstr'][0]."\",\"".$matche['msgstr'][1]."\",".$userdata->ID.",\"".$_SERVER['REMOTE_ADDR']."\")";
								        $wpdb->query($sqlinsert);  
								        
										echo '<tr  style="font-size:10px;"><td>';
										echo 	'Insert';
										echo '</td><td>';
											if(!empty($matche['msgctxt'])){
												echo 'placeholder ('.$matche['msgctxt'].')<br>';
											}
											
											echo 	$matche['msgid'].' -- '.$matche['msgstr'][0];
											
											if(!empty($matche['msgstr'][1])){
												echo '<br>';
												echo $matche['msgid_plural'].' -- '.$matche['msgstr'][1];
											}
										echo '</td></tr>';
								                   
								    } else {
								    	if($_POST['overwrite'] == 'on'){
								    		$has_input_data_checkup = true;
									        $sqlupdate = "UPDATE {$wpdb->prefix}etm_plugin_string SET translatede_string=\"".$matche['msgstr'][0]."\",translatede_string2=\"".$matche['msgstr'][1]."\" WHERE lang_code = \"".$tmp_lang."\" and lang_index_id =\"".$string_id."\"";
									        $wpdb->query($sqlupdate); 
									        
											echo '<tr  style="font-size:10px;"><td>';
											echo 	'Update';
											echo '</td><td>';
											if(!empty($matche['msgctxt'])){
												echo 'placeholder ('.$matche['msgctxt'].')<br>';
											}
											
											echo 	$matche['msgid'].' -- '.$matche['msgstr'][0];
											
											if(!empty($matche['msgstr'][1])){
												echo '<br>';
												echo $matche['msgid_plural'].' -- '.$matche['msgstr'][1];
											}
											echo '</td></tr>';
								        }  
								    }   
					            } 
				            }	
						}

					}
					
					if(empty($has_input_data_checkup)){
						echo '<tr><td colspan="2">';
						echo 	'<span style="color:red;">No data has been Insert/Update</span>';
						echo '</td></tr>';
					}
					
					
					echo '</table>';
					echo '</div><table cellpadding="5"><tr><td>';
					echo '<tr><td>Done</td><td>';
					echo   '<a href="'.$this->curPageURL().'"><- Back</a>';		
					echo '</td></tr>';
					echo '</table>';
					if(!empty($has_input_data_checkup)){
						echo "<script>jQuery( function( $ ) {jQuery.post('".admin_url()."',{etm_data: true,'etm_fn':'mo_generator' })});</script>";
					}
					die();
				} else {
					$Error = 'Cannot find any strings';
				}
  			}
		} 	
		
			

		if(!empty($Error)){
			echo '<table cellpadding="5"><tr><td>';
			echo   '<h3  style="margin-bottom: 0px;">'.__('Error','evt').'</h3>';
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo   $Error;		
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo   '<a href="'.$this->curPageURL().'"><- Back</a>';		
			echo '</td></tr>';
			echo '</table>';
		} else {
			echo '<table cellpadding="5"  width="100%"><tr><td width="50%">';
			echo   '<h3  style="margin-bottom: 0px;">'.__('Import .po file','evt').'</h3>';
			echo '</td><td  width="50%">';
			echo   '<h3  style="margin-bottom: 0px;">'.__('Export .po file','evt').'</h3>';
			echo '</td></tr>';
			
			echo '</tr><td width="50%" valign="top">';
			echo   '<p style="padding-right: 150px;font-size:12px">Import .po file to a plugin or theme. Remember the file name must follow this syntax "[textdomain]-[language].po" eg. our textdomain is <b>"EVT"</b> and the language of the file is English <b>"en"</b> or <b>"en_UK"</b>. In this case will it look like this <b>EVT-en.po</b> or <b>EVT-en_UK.po</b></p>';
			
			
			$plugins = get_plugins();
			$themes = wp_get_themes(); 

			echo   '<form method="post" enctype="multipart/form-data">';
			echo   '<select name="plugintheme" style="font-size:12px;width:450px;">';
			echo      '<option value="" style="font-size:12px;">'.__('Select plugin / theme','evt').'</option>';
			echo      '<option value="" disabled="disabled">----------------'.__('Plugin','evt').'----------------</option>';

			if(!empty($plugins)){
				foreach($plugins as $key => $plugin){
					$folder = explode("/",$key);
					echo '<option style="font-size:12px;" value="'.$folder[0].'|||plugin">'.utf8_decode(strip_tags($plugin['Title'])).' ('.utf8_decode(strip_tags($plugin['Version'])).')</option>';
				}
			}
			
			echo      '<option value="" disabled="disabled">----------------'.__('Themes','evt').'----------------</option>';
			
			if(!empty($themes)){
				foreach($themes as $key => $theme){
					echo '<option style="font-size:12px;" value="'.$theme['Template'].'|||theme">'.utf8_decode(strip_tags($theme['Title'])).' ('.utf8_decode(strip_tags($theme['Version'])).')</option>';
				}
			}

			echo   '</select><br>';

			echo   '<input type="file" name="file" id="file" style="font-size:12px;;width:450px;">';
			echo   '<input type="hidden" name="status" value="import"><br>';

			echo   '<label for="file" style="font-size: 12px;margin-left:8px;">Overwrite the existing translations:</label> <input name="overwrite" type="checkbox" value="on"><br>';
			echo   '<input type="submit" name="submit" style="font-size: 12px;margin-left:400px;" value="Submit"><br>';
			echo   '</form>';
			
			echo '</td><td width="50%" valign="top">';
			
			echo   '<p style="font-size:12px">If you cannot find your textdomain then go to Theme translation or Plugin translation and rescan the folder. Once you know the textdomain return to this page.</p>';
			
			
			$sql_tmp4 = "SELECT count(mo_tag) as counttag , mo_tag FROM {$wpdb->prefix}etm_plugin_index
where mo_tag != '' and mo_tag != 'Variable textdomain' group by mo_tag order by mo_tag";
			   
			$sqldata_tmp4 = $wpdb->get_results($sql_tmp4);
			
			echo   '<select id="etm_texdomains" style="font-size:12px;width:450px;">';
			echo      '<option value="" style="font-size:12px;">'.__('Select Textdomain','evt').'</option>';

			if(!empty($sqldata_tmp4)){
				foreach($sqldata_tmp4 as $key => $sqltmp4){
					echo '<option style="font-size:12px;" value="'.$sqltmp4->mo_tag.'">'.$sqltmp4->mo_tag.' ('.$sqltmp4->counttag.')</option>';
				}
			}

			echo   '</select><br>';
			
			$current_active_tmp_lang = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages('',false),true);

			echo   '<select id="etm_languages" style="font-size:12px;width:450px;">';
			echo      '<option value="" style="font-size:12px;">'.__('Select Languages','evt').'</option>';

			if(!empty($current_active_tmp_lang)){
				foreach($current_active_tmp_lang as $key => $current_active_tmp){
					echo '<option style="font-size:12px;" value="'.$current_active_tmp['code'].'|||'.$current_active_tmp['default_locale'].'">'.$current_active_tmp['english_name'].' ('.$current_active_tmp['org_name'].')</option>';
				}
			}
			echo   '</select><br>';
			
			echo   '<input type="submit" name="submit" onClick="etm_download_click();return false;" style="font-size: 12px;margin-left:400px;" value="Submit"><br>';
	
	
				echo "<script>
				jQuery(document).ready(function($){
					jQuery.download = function(url, data, method){
						if( url && data ){ 
							data = typeof data == 'string' ? data : jQuery.param(data);
	
							var inputs = '';
							jQuery.each(data.split('&'), function(){ 
								var pair = this.split('=');
								inputs+='<input type=\"hidden\" name=\"'+ pair[0] +'\" value=\"'+ pair[1] +'\" />'; 
							});
							jQuery('<form action=\"'+ url +'\" method=\"'+ (method||'post') +'\">'+inputs+'</form>')
							.appendTo('body').submit().remove();
						};
					};
				

				});
				function etm_download_click(){	
					if(jQuery('#etm_languages').val() != '' && jQuery('#etm_texdomains').val() != '' ){
					
						jQuery.download('".admin_url()."','lang='+jQuery('#etm_languages').val()+'&mo='+jQuery('#etm_texdomains').val()+'&etm_data=true&etm_fn=mo_export');
					}

				}
				</script>";
	
	
	
			echo '</td></tr></table>';	
		}	
    }
	
	// aktive page header
	function create_header(){

	}
}
?>