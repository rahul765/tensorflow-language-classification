<?
/**********************************************************************************
* DClassifieds                                                                    *
* Open-Source Project Inspired by Dinko Georgiev (webmaster@dclassifieds.eu)      *
* =============================================================================== *
* Software Version:           0.1b                                           	  *
* Software by:                Dinko Georgiev     								  *
* Support, News, Updates at:  http://www.dclassifieds.eu                       	  *
***********************************************************************************
* This program is free software; you may redistribute it and/or modify it under   *
* the terms of the provided license.          									  *
*                                                                                 *
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
*                                                                                 *
* See the "license.txt" file for details of the license.                          *
* The latest version can always be found at http://www.gnu.org/licenses/gpl.txt   *
**********************************************************************************/
class DCUtil
{
	/**
	 * generate seo title
	 *
	 * @param string $string
	 * @return string
	 */
	static function getSeoTitle( $string )
	{
		$book = array(	'я' => 'я',
						'в' => 'в',
						'е' => 'е',
						'р' => 'р',
						'т' => 'т',
						'ъ' => 'ъ',
						'у' => 'у',
						'и' => 'и',
						'о' => 'о',
						'п' => 'п',
						'ш' => 'ш',
						'щ' => 'щ',
						'а' => 'а',
						'с' => 'с',
						'д' => 'д',
						'ф' => 'ф',
						'г' => 'г',
						'х' => 'х',
						'й' => 'й',
						'к' => 'к',
						'л' => 'л',
						'з' => 'з',
						'ь' => 'ь',
						'ц' => 'ц',
						'ж' => 'ж',
						'б' => 'б',
						'н' => 'н',
						'м' => 'м',
						'ч' => 'ч',
						'ю' => 'ю',
						'Я' => 'я',
						'В' => 'в',
						'Е' => 'е',
						'Р' => 'р',
						'Т' => 'т',
						'Ъ' => 'ъ',
						'У' => 'у',
						'И' => 'и',
						'О' => 'о',
						'П' => 'п',
						'Ш' => 'ш',
						'Щ' => 'ш',
						'А' => 'а',
						'С' => 'с',
						'Д' => 'д',
						'Ф' => 'ф',
						'Г' => 'г',
						'Х' => 'х',
						'Й' => 'й',
						'К' => 'к',
						'Л' => 'л',
						'З' => 'з',
						'Ь' => 'ь',
						'Ц' => 'ц',
						'Ж' => 'ж',
						'Б' => 'б',
						'Н' => 'н',
						'М' => 'м',
						'Ч' => 'ч',
						'ю' => 'ю',
						' ' => '-', 
						'.'	=> '-',
						','	=> '-',
						'_' => '-',
						'q' => 'q',
						'w' => 'w',
						'e' => 'e',
						'r' => 'r',
						't' => 't',
						'y' => 'y',
						'u' => 'u',
						'i' => 'i',
						'o' => 'o',
						'p' => 'p',
						'a' => 'a',
						's' => 's',
						'd' => 'd',
						'f' => 'f',
						'g' => 'g',
						'h' => 'h',
						'j' => 'j',
						'k' => 'k',
						'l' => 'l',
						'z' => 'z',
						'x' => 'x',
						'c' => 'c',
						'v' => 'v',
						'b' => 'b',
						'n' => 'n',
						'm' => 'm',
						'Q' => 'Q',
						'W' => 'W',
						'E' => 'E',
						'R' => 'R',
						'T' => 'T',
						'Y' => 'Y',
						'U' => 'U',
						'I' => 'I',
						'O' => 'O',
						'P' => 'P',
						'A' => 'A',
						'S' => 'S',
						'D' => 'D',
						'F' => 'F',
						'G' => 'G',
						'H' => 'H',
						'J' => 'J',
						'K' => 'K',
						'L' => 'L',
						'Z' => 'Z',
						'X' => 'X',
						'C' => 'C',
						'V' => 'V',
						'B' => 'B',
						'N' => 'N',
						'M' => 'M',
						'0'	=> '0',
						'1'	=> '1',
						'2'	=> '2',
						'3'	=> '3',
						'4'	=> '4',
						'5'	=> '5',
						'6'	=> '6',
						'7'	=> '7',
						'8'	=> '8',
						'9'	=> '9',
						'џ' => 'џ',
                        'ќ' => 'ќ',
                        'њ' => 'њ',
                        'љ' => 'љ',
                        'ѓ' => 'ѓ',
                        'ѕ' => 'ѕ',
                        'ј' => 'ј');
						
		$temp_holder_array      = explode(' ', $string);
		//$temp_holder_array      = explode(' ', $string);
	   	$new_holder_array       = array();
	   	if(!empty( $temp_holder_array )){
	         foreach($temp_holder_array as $k => $v){
	               if(mb_strlen(trim($v), 'utf-8')){
	               		 $strlen 	= mb_strlen( $v , 'utf-8' );
	               		 $new_string = '';
	               		 for( $i = 0; $i <= $strlen - 1; $i++ ){
							$letter = mb_substr($v, $i, 1 , 'utf-8');
							if(isset( $book[$letter] )){
								$new_string .= $book[$letter];
							} elseif (isset( $book[strtolower($letter)] )){
								$new_string .= $book[strtolower($letter)];
							} else {
								$new_string .= '';
							}
						 }
						 if(!empty($new_string)){
	                     	$new_holder_array[] = $new_string;
						 }
	               }
	         }
	   	}
		   
		$new_string = join('-' , $new_holder_array);
		
		return $new_string;
		
	}

	static function sanitize( $string )
	{
		$string = strip_tags(trim($string));
		if(!get_magic_quotes_gpc() || !get_magic_quotes_runtime()){
			$string = addslashes($string);
		}
		return $string;
	}
	
	static function ucfirst( $string , $encoding = 'utf-8' )
	{
		return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding) . mb_strtolower(mb_substr($string, 1, mb_strlen($string, $encoding), $encoding), $encoding);
	}
	
	static function genRefresh($after_seconds, $to_url = '')
	{
		return '
		<script language="JavaScript">
		function relocate()
		{
			var url = "'.$to_url.'";
			url = url.replace(/\&amp\;/g, \'&\');
		
			eval("document.location.href=\""+url+"\";");
		}
		setTimeout("relocate()", '.$after_seconds.'*1000);
		</script>'."\n";
	}
	
	static function getShortDescription( $_description )
	{
		$ret = $_description;
		if(mb_strlen( $_description, 'utf-8' ) > 250){
			$ret = mb_substr($_description, 0, 250, 'utf-8') . ' ...';
		}
		return $ret;
	}
}
