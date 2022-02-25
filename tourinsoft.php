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
$sql_content = $sql_meta = null;
$id_start = -1000000000;
$limit = 50;


// Récupère le fichier json distant
$json = file_get_contents($GLOBALS['flux_tourinsoft']);
$array = json_decode($json, true)['value'];




// Construction des requetes mysql
$sql_init_content="REPLACE LOW_PRIORITY INTO `".$tc."` (`id`, `state`, `lang`, `robots`, `type`, `tpl`, `url`, `title`, `description`, `content`, `user_update`, `date_update`, `user_insert`, `date_insert`) VALUES ";
$sql_init_meta="REPLACE LOW_PRIORITY INTO `".$tm."` (`id`, `type`, `cle`) VALUES ";


// Suppression des tag lié au évènement tourinsoft
$GLOBALS['connect']->query("DELETE FROM ".$tt." WHERE zone='agenda' AND id<=0");

// Suppression des dates dans les méta avant ajout ?
$GLOBALS['connect']->query("DELETE FROM ".$tm." WHERE type='aaaa-mm-jjTh:m:s' AND id<=0");

// Suppression des contenus
$GLOBALS['connect']->query("DELETE FROM ".$tc." WHERE type='event-tourinsoft' AND id<=0");


if(is_array($array))
{
	// TRI PAR DATE
	// Mets la date à un niveau plus haut pour le tri simplifier
	foreach($array as $key => $val) 
	{
		if(@$val['DATESs'][0]['Heuredouverture1'])
			$array[$key]['datedebut'] = rtrim($val['DATESs'][0]['Datededebut'], '00:00:00').$val['DATESs'][0]['Heuredouverture1'];
		else 
			$array[$key]['datedebut'] = $val['DATESs'][0]['Datededebut'];
	}

	// Tri le tableau par date de début d'évènement
	array_multisort(array_map(function($element) {
		return @$element['datedebut'];
	}, $array), SORT_ASC, $array);


	//highlight_string(print_r($array, true));


	// CRÉATION DE LA REQUÊTE D'AJOUT DES CONTENUS
	foreach($array as $key => $val) 
	{
		$key = $key + 1;

		//[ObjectTypeName] => Fêtes et manifestations // TAG ?

		// @todo ajouter le alt sur l'image $val['PHOTOSs'][0]['Photo']['Titre']


		// construction de l'url
		$url = encode($val['SyndicObjectName']);
		$url = mb_convert_encoding($url, 'UTF-8', 'UTF-8');// Supprime les caractères utf8 de l'url
		
		if(@$list_url[$url]) $url = $url.'-'.$key;// Si l'url existe on ajoute le numéro de la fiche en fin d'url
		$list_url[$url] = true;

		$url = str_replace('--','-', $url);// Supprime les double tiré lié à la suppression des caractères utf8


		// @todo date 
		// $val['DATESs'][0]['Datededebut'] 
		// $val['DATESs'][0]['Heuredouverture1'] $val['DATESs'][0]['Heuredefermeture1']
		// $val['DATESs'][0]['Heuredouverture2'] $val['DATESs'][0]['Heuredefermeture2']
		// $val['DATESs'][0]['Datedefin'] 

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


		// Donnée de l'évènement
		$values = array(
			//'id' => $id_start+$key,
			'id' => -$key,
			'state' => "'active'",
			'lang' => "'fr'",
			'robot' => "''",
			'type' => "'event-tourinsoft'",
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


	    $sql_content.="(".implode(",", $values)."),";

		//$GLOBALS['connect']->query(trim($sql_init_content.$sql_content,','));
		//if($GLOBALS['connect']->error) echo $sql_init_content.$sql_content.'<br><font color="red">'.$GLOBALS['connect']->error.'</font><br><br>';

	    
	    // Ajout des dates dans les métas
	    $sql_meta.="(".implode(",", array(
			//'id' => $id_start+$key,
			//'type' => "'aaaa-mm-jj'",
			//'cle' => "'".date('Y-m-d', strtotime(str_replace('-', '/', $val['DATESs'][0]['Datededebut'])))."'"
			'id' => -$key,
			'type' => "'aaaa-mm-jjTh:m:s'",
			'cle' => "'".$val['datedebut']."'"
		))."),";

	    
	     // @todo: Ajout des tag lié ?

	    if($key >= $limit) break;
	}
}

// Insertion dans la table CONTENT
$GLOBALS['connect']->query(trim($sql_init_content.$sql_content,','));
echo $GLOBALS['connect']->error;

// Insertion dans la table META
$GLOBALS['connect']->query(trim($sql_init_meta.$sql_meta,','));
echo $GLOBALS['connect']->error;

//echo '<br><br>'.str_replace('),','),<br><br>', $sql);


//file_put_contents($file, $current);

?>