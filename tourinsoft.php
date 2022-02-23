<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");// Les variables
include_once($_SERVER['DOCUMENT_ROOT']."/api/db.php");// Connexion à la db
include_once($_SERVER['DOCUMENT_ROOT']."/api/function.php");// Fonction

// @todo voir erreur mysql
// https://mairie-ciboure.fr/fetes-et-manifestations/marche-de-produits-regionaux-8/

//echo php_sapi_name();
//echo login('medium', 'add-event', 'true');
//echo $_SESSION['token'].'<br>';
//echo token_check($_SESSION['token']).'<br>';

// Si execution par cron ou admin avec droit création d'évènement
if(php_sapi_name() !== 'cli')
	if(!token_check($_SESSION['token'])) 
		die('die');

// supprime les caractères utf8 invalide des urls
ini_set('mbstring.substitute_character', 'none');
$sql = null;

// Url du fichier source
$url = "https://api-v3.tourinsoft.com/api/syndications/cdt64.tourinsoft.com/95d33256-58ec-471e-b9f3-30f23dcbfd04?format=json";

// Récupère le fichier json distant
$json = file_get_contents($url);
$array = json_decode($json, true);


//highlight_string(print_r($array, true));

// Construction de la requete mysql
$sql_list="REPLACE LOW_PRIORITY INTO `".$db_prefix."content` (`id`, `state`, `lang`, `robots`, `type`, `tpl`, `url`, `title`, `description`, `content`, `user_update`, `date_update`, `user_insert`, `date_insert`) VALUES ";


if(is_array($array['value']))
foreach($array['value'] as $key => $val) 
{
	//[ObjectTypeName] => Fêtes et manifestations // TAG

	// @todo ajouter le alt sur l'image $val['PHOTOSs'][0]['Photo']['Titre']
	// @todo date $val['DATESs'][0]['Datededebut'] $val['DATESs'][0]['Heuredouverture1'] $val['DATESs'][0]['Heuredefermeture1']

	// Contenu de l'article
	$content = array (
		'title' => $val['SyndicObjectName'],
		'visuel' => @$val['PHOTOSs'][0]['Photo']['Url'],
		'texte' => $val['DESCRIPTIFSs'][0]['Descriptioncommerciale'],
		'dates' => $val['DATESs'][0]['Datededebut'],
		'contact' => @$val['MOYENSCOMs'][0]['CoordonneesTelecom'],
		//'adresse' => '<p>'.$val['ADRESSEs'][0]['Adresse2'].'<br>'.$val['ADRESSEs'][0]['CodePostal'].' '.$val['ADRESSEs'][0]['Commune'].'</p>',
		'latitude' => $val['GmapLatitude'],
		'longitude' => $val['GmapLongitude'],
	);

    $json_content = json_encode($content, JSON_UNESCAPED_UNICODE);


	// construction de l'url
	$url = encode($val['SyndicObjectName']);
	$url =mb_convert_encoding($url, 'UTF-8', 'UTF-8');// Supprime les caractères utf8 de l'url
	
	if(@$list_url[$url]) $url = $url.'-'.$key;// Si l'url existe on ajoute le numéro de la fiche en fin d'url
	$list_url[$url] = true;

	$url = str_replace('--','-', $url);// Supprime les double tiré lié à la suppression des caractères utf8


	// Donnée de l'évènement
	$fiche = array(
		'id' => 1000000+$key,
		'state' => "'active'",
		'lang' => "'fr'",
		'robot' => "''",
		'type' => "'event'",
		'tpl' => "'event'",
		'url' => "'".$url."'",
		'title' => "'".$GLOBALS['connect']->real_escape_string($val['SyndicObjectName'])."'",
		'description' => "''",
		'content' => "'".$GLOBALS['connect']->real_escape_string($json_content)."'",
		'user_update' => 1,
		'date_update' => "'".date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $val['Updated'])))."'",
		'user_insert' => 1,
		'date_insert' => "'".date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $val['Published'])))."'"
	);


    $sql.="(".implode(",", $fiche)."),";

	//$GLOBALS['connect']->query(trim($sql_list.$sql,','));
	//if($GLOBALS['connect']->error) echo $sql_list.$sql.'<br><font color="red">'.$GLOBALS['connect']->error.'</font><br><br>';

    // @todo: Ajout des tag lié ?
    // Suppression des tag lié au évènement tourinsoft ?
}


// Insertion dans la table
$GLOBALS['connect']->query(trim($sql_list.$sql,','));
echo $GLOBALS['connect']->error;

//echo '<br><br>'.str_replace('),','),<br><br>', $sql);


//file_put_contents($file, $current);

?>