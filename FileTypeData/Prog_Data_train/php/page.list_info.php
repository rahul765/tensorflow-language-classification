<?php

$sws_limit_interval = sws_languages_tools_retrive_options('limit_interval');
$sws_totalcount = (count($data_array));
$sws_limit_from = ($sws_limit_interval*$sws_current_page);

if(($sws_limit_interval*($sws_current_page+1)) > (count($data_array)-1) ){
    $sws_limit_to = $sws_totalcount;
} else {
    $sws_limit_to = $sws_limit_interval*($sws_current_page+1);
}


$content = '<table class="widefat">
	<thead>
		<tr>';
            $i = 0;
			foreach($sws_columns as $field => $label){
			 
                if($sort_type_select == $field){
                    if($sort_type_select_direction == 'asc'){
                        $icon = '<img style="margin-bottom:-2px" src="'.SWS_LAGNGUAGES_TOTAL_URL.'images/asc.png">'; 
                        $sortdirection = 'desc';
                    } else {
                        $icon = '<img style="margin-bottom:-2px" src="'.SWS_LAGNGUAGES_TOTAL_URL.'images/desc.png">';
                        $sortdirection = 'asc';
                    }
                } else {
                   $icon = ''; 
                   $sortdirection = 'desc';  
                }

                if($field == 'tools' ||  $field == 'languages'){
                    $extra_text = $label;
                } else {
                    $extra_text = '<a href="#" style="text-decoration: none;" onClick="loadingContent('.$sws_current_page.','.$sws_previussubjecpage.',\''.$sws_folder.'\',\''.$sws_title.'\', \'false\'   ,\''.$field.'\',\''.$sortdirection.'\');return false;">'.$label.'</a>';
                }

             
				$content .= '<th width="'.$sws_columnsWidth[$i].'">'.$icon.' '.$extra_text.'</th>';
				$i++;
			}											
		$content .= '</tr>
	</thead>
		'.retrive_data($_POST['type'],$data_array).'
</table>';


$content .= '<div>'.index_indicator() . '</div>';


//-------------------------------------------- functioner

	function retrive_data($name,$data_array){
		global $wpdb,$sws_columns,$sws_columnsWidth,$sws_limit_to,$sws_limit_from,$group,$sws_current_page;
		$tmp_content = "";
        if(!empty($data_array)){
           for($i=$sws_limit_from;$i<$sws_limit_to;$i++){
            
                $row = $data_array[$i];
                
                if($group == true){
                        $tmp_content .='<tr style="cursor: pointer;" onMouseOver="jQuery(this).css(\'background-color\',\'#EEEEEE\');" onMouseOut="jQuery(this).css(\'background-color\',\'#FFFFFF\');" onclick="loadingContent(0,'.$sws_current_page.',\''.$row['folder'].'\',\''.$row['title'].'\',\'true\');">';
                } else {
                    if($row['editible'] == 'true'){
                        $tmp_content .='<tr onMouseOver="jQuery(this).css(\'background-color\',\'#EEEEEE\');" onMouseOut="jQuery(this).css(\'background-color\',\'#FFFFFF\');">';
                    } else {
                            $tmp_content .='<tr style="background-color:#ffe6e6">';
                    }
                }
                    
                    foreach($sws_columns as $field => $label){
                        if($field == 'languages' or $field == 'tools'){
    					   $tmp_content .='<td>'.$row[$field].'</td>';
                        } else {
    					   $tmp_content .='<td>'.strip_tags($row[$field]).'</td>'; 
                        }

    				}
                $tmp_content .='</tr>';
            }
		} else {
			$tmp_content .='<tr>
				<td colspan="'.(count($sws_columns)).'">No records.</td>
			</tr>';
		}
		return $tmp_content;
	}


	function index_indicator(){
		global $wpdb,$sws_limit_interval,$sws_current_page,$sws_totalcount;
		$content = "";

		$i = 0;
		$loop = 0;
		if($sws_limit_interval > 0 &&  $sws_totalcount > 0){
		
			$content .= '<div style="margin-left: auto; margin-right: auto; width: auto; text-align: center; display: table;">';
			if($sws_current_page != 0){
				$content .= createlink($name,'<<',$sws_current_page-1,false,'width:10px;font-size: 12px;');
			} else {
				$content .= createlink($name,'',$sws_current_page,false,'width:10px;font-size: 12px;');			
			}
		
			while ($i <= $sws_totalcount-1) {
			
				if(($sws_totalcount-1) < ($i+$sws_limit_interval)){
					$to_max = $sws_totalcount-1;
				} else {
					$to_max = ($i+$sws_limit_interval);
				}
				if($loop == $sws_current_page){
					$content .= createlink($name,($i+1).'-'.$to_max,$loop,true,'font-size: 16px;');
				} else {
					$content .= createlink($name,($i+1).'-'.$to_max,$loop,false,'font-size: 12px;');
				}
				$i += $sws_limit_interval;
				$loop++;
			}
			
			if((($sws_current_page*$sws_limit_interval)+$sws_limit_interval) < $sws_totalcount-1){
				$content .= createlink($name,'>>',$sws_current_page+1,false,'width:10px;font-size: 12px;');
			} else {
				$content .= createlink($name,'',$sws_current_page,false,'width:10px;font-size: 12px;');
			}

			$content .= '</div>';
		}
		return $content;
	}
    
	function createlink($name,$text,$page,$marked,$style){
	   global $sws_folder,$sws_title,$sws_previussubjecpage;
		if(!empty($style)){
			$style = 'style="'.$style.'"';
		}
	
		$content .= '<div class="mall_index_elements" '.$style.'>';
		if($marked){
			$content .= '<b>';
		}
		
        if($group){
            $content .= '<a href="#" style="text-decoration: none;" onClick="loadingContent('.$page.','.$page.');return false;">'.$text.'</a>';  
        } else {
            $content .= '<a href="#" style="text-decoration: none;" onClick="loadingContent('.$page.','.$sws_previussubjecpage.',\''.$sws_folder.'\',\''.$sws_title.'\');return false;">'.$text.'</a>';
        }
		
		
		if($marked){
			$content .= '</b>';
		}
		$content .= '</div>';
		
		return $content;
	} 
?>