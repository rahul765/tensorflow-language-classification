<?php
if(isset($_GET['id']))
	{
	$id    = htmlspecialchars($_GET['id']);
	}
else
	{
	$id ='';
	}
if(!isset($url))
	{
	$url    = '';
	}
if(!isset($District_Name))
	{
	$District_Name    = '';
	}
/* if the field have number+letter */
if($id=='2a' || $id=='2b') {
  $pid=$id;
} else {
  $pid=round($id);
}
if($id) $District_Name=$id;{
 		$District_Name = addslashes($District_Name);
 		$url = str_replace("-"," ",$url);
 		$url = stripslashes($url);
 		$url = utf8_decode($url);
$result_url_page = $mysql->query("
SELECT
				A.State_Name_English,A.State_Name_Hindi,A.State_Code_Iso_3166,A.State_Or_Territory,A.State_Code,A.State_founded,A.State_Administrative_capital,A.State_number_of_districts,A.State_area_km2,A.State_text_description_and_details,A.State_Population_2012,A.State_Population_density,A.State_Literacy_Rate_pourcent,A.State_pourcent_urban_population,A.State_Official_language,A.State_Number_Town_and_Village,A.State_number_of_Towns,A.State_number_of_Towns_Electrified,A.State_number_of_Villages,A.State_number_of_Villages_Electrified,A.State_number_of_Villages_Electrified_Percentage,A.State_Gouvernor_Fonction,A.State_Gouvernor_Name,A.State_Gouvernor_date_of_birth,A.State_Gouvernor_Age,A.State_address,A.governor_residence_address,A.State_Office_phone,A.state_Office_Fax,A.state_Residence_Phone,A.governor_mobile_no,A.State_email,A.State_facebook_page,A.state_twitter_page,A.State_Gouvernor_Images,A.State_Official_Website,A.governor_personal_email,A.governor_personal_twitter,A.State_Version,A.Census_2001_Code,A.Census_2011_Code,
				B.State_Code,B.State_Name,B.District_Code,B.District_Name,B.Census_2011_Code,
				C.District_Code,C.Sub_District_Code,C.Sub_District_Version,C.Sub_District_Name,C.Census_2001_Code,C.Census_2011_Code
FROM
				All_States A,
				All_Districts_data_gouv B,
				All_Sub_Districts C
WHERE
   			B.District_Name='".addslashes($url)."'
AND
		A.State_Name_English = B.State_Name
       ");
  if($result_url_page && mysql_num_rows($result_url_page)>0) {
    $infos = mysql_fetch_array($result_url_page);
   // show_conseilregional($infos);
//  $infos need this function below from helpers.php
//function show($infos) {
//global $page,$mysql;
//}
	}
	}
	$page->settitle('State '.$infos['State_Name_English'].' district '.$infos['District_Name'].'');
	$page->setdescription('META State '.$infos['State_Name'].' district '.$infos['District_Name'].'' );
	$page->seturlpage('http://india-administrations.com/cities-villages-state-'.nettoie_url($infos['State_Name']).'-district-'.nettoie_url($infos['District_Name']).'.html');


$page->addhtml('
													<!-- START CONTENT -->


<article class="article_main_content">
<div class="road road_top">

<h1>State : '.$infos['State_Name'].'</h1>
<h1>District '.$infos['District_Name'].'</h1>



');










//////////////////////////////////////////////////////////////////////////////////////
//                 ALL DISTRICTS WITHOUT SUB DISTRICTS            /////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
$result = $mysql->query("
SELECT
				A.State_Name_English,A.State_Name_Hindi,A.State_Code_Iso_3166,A.State_Or_Territory,A.State_Code,A.State_founded,A.State_Administrative_capital,A.State_number_of_districts,A.State_area_km2,A.State_text_description_and_details,A.State_Population_2012,A.State_Population_density,A.State_Literacy_Rate_pourcent,A.State_pourcent_urban_population,A.State_Official_language,A.State_Number_Town_and_Village,A.State_number_of_Towns,A.State_number_of_Towns_Electrified,A.State_number_of_Villages,A.State_number_of_Villages_Electrified,A.State_number_of_Villages_Electrified_Percentage,A.State_Gouvernor_Fonction,A.State_Gouvernor_Name,A.State_Gouvernor_date_of_birth,A.State_Gouvernor_Age,A.State_address,A.governor_residence_address,A.State_Office_phone,A.state_Office_Fax,A.state_Residence_Phone,A.governor_mobile_no,A.State_email,A.State_facebook_page,A.state_twitter_page,A.State_Gouvernor_Images,A.State_Official_Website,A.governor_personal_email,A.governor_personal_twitter,A.State_Version,A.Census_2001_Code,A.Census_2011_Code,
				B.State_Code,B.State_Name,B.District_Code,B.District_Name,B.Census_2011_Code,
				C.District_Code,C.Sub_District_Code,C.Sub_District_Version,C.Sub_District_Name,C.Census_2001_Code,C.Census_2011_Code
FROM
				All_States A,
				All_Districts_data_gouv B,
				All_Sub_Districts C
WHERE
   			B.State_Name='BIHAR'
AND
A.State_Code=B.State_Code
GROUP BY
B.District_Name ASC;
       ");

  if($result) {
    while(list(
				$State_Name_English,$State_Name_Hindi,$State_Code_Iso_3166,$State_Or_Territory,$State_Code,$State_founded,$State_Administrative_capital,$State_number_of_districts,$State_area_km2,$State_text_description_and_details,$State_Population_2012,$State_Population_density,$State_Literacy_Rate_pourcent,$State_pourcent_urban_population,$State_Official_language,$State_Number_Town_and_Village,$State_number_of_Towns,$State_number_of_Towns_Electrified,$State_number_of_Villages,$State_number_of_Villages_Electrified,$State_number_of_Villages_Electrified_Percentage,$State_Gouvernor_Fonction,$State_Gouvernor_Name,$State_Gouvernor_date_of_birth,$State_Gouvernor_Age,$State_address,$governor_residence_address,$State_Office_phone,$state_Office_Fax,$state_Residence_Phone,$governor_mobile_no,$State_email,$State_facebook_page,$state_twitter_page,$State_Gouvernor_Images,$State_Official_Website,$governor_personal_email,$governor_personal_twitter,$State_Version,$Census_2001_Code,$Census_2011_Code,
				$State_Code,$State_Name,$District_Code,$District_Name,$Census_2011_Code,
				$District_Code,$Sub_District_Code,$Sub_District_Version,$Sub_District_Name,$Census_2001_Code,$Census_2011_Code
    )=mysql_fetch_row($result)) {
     //$nom_dept2=strtolower(urlencode($nom_dept));

$page->addhtml('
<h2>District Name '.$District_Name.'</h2>
');
}}











$page->addhtml('
<br>
');











//////////////////////////////////////////////////////////////////////////////////////
//                 SUB Districts            /////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
$result = $mysql->query("
SELECT
				A.State_Name_English,A.State_Name_Hindi,A.State_Code_Iso_3166,A.State_Or_Territory,A.State_Code,A.State_founded,A.State_Administrative_capital,A.State_number_of_districts,A.State_area_km2,A.State_text_description_and_details,A.State_Population_2012,A.State_Population_density,A.State_Literacy_Rate_pourcent,A.State_pourcent_urban_population,A.State_Official_language,A.State_Number_Town_and_Village,A.State_number_of_Towns,A.State_number_of_Towns_Electrified,A.State_number_of_Villages,A.State_number_of_Villages_Electrified,A.State_number_of_Villages_Electrified_Percentage,A.State_Gouvernor_Fonction,A.State_Gouvernor_Name,A.State_Gouvernor_date_of_birth,A.State_Gouvernor_Age,A.State_address,A.governor_residence_address,A.State_Office_phone,A.state_Office_Fax,A.state_Residence_Phone,A.governor_mobile_no,A.State_email,A.State_facebook_page,A.state_twitter_page,A.State_Gouvernor_Images,A.State_Official_Website,A.governor_personal_email,A.governor_personal_twitter,A.State_Version,A.Census_2001_Code,A.Census_2011_Code,
				B.State_Code,B.State_Name,B.District_Code,B.District_Name,B.Census_2011_Code,
				C.District_Code,C.Sub_District_Code,C.Sub_District_Version,C.Sub_District_Name,C.Census_2001_Code,C.Census_2011_Code
FROM
				All_States A,
				All_Districts_data_gouv B,
				All_Sub_Districts C
WHERE
   			B.District_Name='".addslashes($url)."'
AND
A.State_Code=B.State_Code
AND
B.District_Code = C.District_Code
ORDER BY
C.Sub_District_Name ASC;
       ");

 $page->addhtml('
<br>
<h2>District Name '.$url.'</h2>
<br>
');

  if($result) {
    while(list(
				$State_Name_English,$State_Name_Hindi,$State_Code_Iso_3166,$State_Or_Territory,$State_Code,$State_founded,$State_Administrative_capital,$State_number_of_districts,$State_area_km2,$State_text_description_and_details,$State_Population_2012,$State_Population_density,$State_Literacy_Rate_pourcent,$State_pourcent_urban_population,$State_Official_language,$State_Number_Town_and_Village,$State_number_of_Towns,$State_number_of_Towns_Electrified,$State_number_of_Villages,$State_number_of_Villages_Electrified,$State_number_of_Villages_Electrified_Percentage,$State_Gouvernor_Fonction,$State_Gouvernor_Name,$State_Gouvernor_date_of_birth,$State_Gouvernor_Age,$State_address,$governor_residence_address,$State_Office_phone,$state_Office_Fax,$state_Residence_Phone,$governor_mobile_no,$State_email,$State_facebook_page,$state_twitter_page,$State_Gouvernor_Images,$State_Official_Website,$governor_personal_email,$governor_personal_twitter,$State_Version,$Census_2001_Code,$Census_2011_Code,
				$State_Code,$State_Name,$District_Code,$District_Name,$Census_2011_Code,
				$District_Code,$Sub_District_Code,$Sub_District_Version,$Sub_District_Name,$Census_2001_Code,$Census_2011_Code
    )=mysql_fetch_row($result)) {
     //$nom_dept2=strtolower(urlencode($nom_dept));

$page->addhtml('
<h2>Sub district Name '.$Sub_District_Name.'</h2>
');
}}


$page->addhtml('
</div>
</article>
');

?>