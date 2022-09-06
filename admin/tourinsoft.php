<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");// Les variables
include_once($_SERVER['DOCUMENT_ROOT']."/api/db.php");// Connexion à la db
include_once($_SERVER['DOCUMENT_ROOT']."/api/function.php");// Fonction

// @todo voir erreur mysql
// https://mairie-ciboure.fr/fetes-et-manifestations/marche-de-produits-regionaux-8/

//echo login('medium', 'add-event', 'true');
//echo php_sapi_name().'<br>';
//echo $_SESSION['token'].'<br>';
//echo token_check($_SESSION['token']).'<br>';

//print_r($_REQUEST);

// Si execution par cron ou admin avec droit création d'évènement
//if(php_sapi_name() !== 'cli')
if(@$_SERVER['PHP_AUTH_PW'] != $GLOBALS['tourinsoft_login'])// PHP_AUTH_USER
if(!token_check(@$_SESSION['token'])) 
	die('die');

// supprime les caractères utf8 invalide des urls
ini_set('mbstring.substitute_character', 'none');

$verbose = (@$_SERVER['PHP_AUTH_PW']?false:true);// $verbose = false;// Affiche les infos de rapatriement
$verbose_source = (@$_SERVER['PHP_AUTH_PW']?false:($dev?true:false));// $verbose_source = false;// Affiche le tableau du json

$img = true;// get img
$keep_img = true;// garde les images originales sur le serveur
//$_REQUEST['clean'] = true;// Force la vidange des dossiers images

$sql_content = $sql_meta = $sql_tag = $visuel_dest = null;
$id_start = -1000000000;// Plus utiliser car on a des id négatifs
$limit = 50;// 50 10 // Nombre d'évènement rapatrié

$chemin_visuel = $GLOBALS['media_dir'].'/tourinsoft';
$racine = '../../../';
$new_width = 320;


// Construction des requetes mysql
$sql_init_content = "REPLACE LOW_PRIORITY INTO `".$tc."` (`id`, `state`, `lang`, `robots`, `type`, `tpl`, `url`, `title`, `description`, `content`, `user_update`, `date_update`, `user_insert`, `date_insert`) VALUES ";
$sql_init_meta = "REPLACE LOW_PRIORITY INTO `".$tm."` (`id`, `type`, `cle`) VALUES ";
$sql_init_tag = "REPLACE LOW_PRIORITY INTO `".$tt."` (`id`, `zone`, `lang`, `encode`, `name`, `ordre`) VALUES ";


// Récupère le fichier json distant
$json = file_get_contents($GLOBALS['tourinsoft_flux']);// curl()
$array = json_decode($json, true)['value'];



if(is_array($array))
{
	// Création du dossier pour les images
	@mkdir(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/'.$chemin_visuel, 0755, true);//.$GLOBALS['path'] /resize
	

	// Nettoie les images dans le dossier
	if(@$_REQUEST['clean'])
	{
		array_map('unlink', array_filter((array) glob(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/'.$chemin_visuel.'/*')));
		array_map('unlink', array_filter((array) glob(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/'.$GLOBALS['media_dir'].'/resize/tourinsoft/*')));

		//exit('clean');
	}
	

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


	if($verbose_source) highlight_string(print_r($array, true));


	// CRÉATION DE LA REQUÊTE D'AJOUT DES CONTENUS
	foreach($array as $key => $val) 
	{
		$key = $key + 1;
		$copy = false;

		if($verbose) echo '<hr><h2>'.$key.'. '.$val['SyndicObjectName'].'</h2>';

		// construction de l'url
		$url = encode($val['SyndicObjectName']);
		$url = mb_convert_encoding($url, 'UTF-8', 'UTF-8');// Supprime les caractères utf8 de l'url
		
		if(@$list_url[$url]) $url = $url.'-'.$key;// Si l'url existe on ajoute le numéro de la fiche en fin d'url
		$list_url[$url] = true;

		$url = str_replace('--','-', $url);// Supprime les double tiré lié à la suppression des caractères utf8


		// Retravaille des images
		if($img and @$val['PHOTOSs'][0]['Photo']['Url']) 
		{
			$visuel_source = $val['PHOTOSs'][0]['Photo']['Url'];

			$visuel_dest = $chemin_visuel.'/'.strtolower(basename($visuel_source));
			

			// Fichier déjà rapatrié ?
			if($keep_img and file_exists($racine.$visuel_dest))
			{
				$copy = true;

				$source_size = round(filesize($racine.$visuel_dest)/1024);

				if($verbose) echo '⚠️ fichier '.$visuel_source.' déjà sur le serveur<br>';				
			}
			// Copie de l'image distante
			elseif(copy($visuel_source, $racine.$visuel_dest))
			{
				$copy = true;

				$source_size = round(filesize($racine.$visuel_dest)/1024);

				if($verbose) echo '✅ copy '.$visuel_source.' <b>'.$source_size.'ko</b><br>';
				
			}
			elseif($verbose) echo '❌ copy error '.$visuel_source.'<br>';


			// Besoin de resize ?
			if($copy)
			{
				// Taille de l'image source rapatrier
				list($source_width, $source_height, $type, $attr) = getimagesize($racine.$visuel_dest);
				
				// Si l'image est plus grande que la taille limtie on la resize
				if($source_width > $new_width) 
				{
					$visuel_source = $racine.$visuel_dest;

					// Resize
					$visuel_dest = resize($racine.$visuel_dest, $new_width, null, 'tourinsoft');	

					// Supp l'image source si resize
					if(!$keep_img) unlink($visuel_source);

					$new_size = round(filesize($racine.strtok($visuel_dest, '?'))/1024);
					$p100 = 100-round($new_size*100/$source_size);

					if($verbose) echo 'ℹ️ resize '.$new_size.'ko ('.$p100.'%)<br>';
				} 
			}

		}


		// $val['DATESs'][0]['Datededebut'] 
		// $val['DATESs'][0]['Heuredouverture1'] $val['DATESs'][0]['Heuredefermeture1']
		// $val['DATESs'][0]['Heuredouverture2'] $val['DATESs'][0]['Heuredefermeture2']
		// $val['DATESs'][0]['Datedefin'] 
		$date = explode('T', $val['datedebut'])[0];

		// Si date de fin et != de la date de début
		if(@$val['DATESs'][0]['Datedefin'] and @$val['DATESs'][0]['Datededebut'] != @$val['DATESs'][0]['Datedefin'])
			$date_fin = explode('T', @$val['DATESs'][0]['Datedefin'])[0];
		else $date_fin = null;

		// On regarde si la description est courtes ou longue
		if(strlen($val['DESCRIPTIFSs'][0]['Descriptioncommerciale'])<500) {
			$description = $val['DESCRIPTIFSs'][0]['Descriptioncommerciale'];
			$texte = '';
		}
		else {
			$description = '';
			$texte = $val['DESCRIPTIFSs'][0]['Descriptioncommerciale'];
		}


		// Moyen de communication
		// C1 = Téléphone filaire | C4 = Mél | C5 = Site web (URL) | C6 = Téléphone cellulaire
		unset($com);
		for($i=0; $i<3; $i++) 
		{ 
			if(isset($val['MOYENSCOMs'][$i]['TypedaccesTelecom']['ThesCode']))
				$com[$val['MOYENSCOMs'][$i]['TypedaccesTelecom']['ThesCode']] = @$val['MOYENSCOMs'][$i]['CoordonneesTelecom'];
		}


		// Contenu de l'article
		$content = array (
			'title' => $val['SyndicObjectName'],
			'visuel' => $visuel_dest,
			'visuel-alt' => '',//@$val['PHOTOSs'][0]['Photo']['Titre'].' - '.@$val['PHOTOSs'][0]['Photo']['Credit']
			'aaaa-mm-jj' => $date,
			'heure-ouverture' => $val['DATESs'][0]['Heuredouverture1'],
			'heure-fermeture' => $val['DATESs'][0]['Heuredefermeture1'],
			'aaaa-mm-jj-fin' => @$date_fin,
			'heure-ouverture-fin' => @$val['DATESs'][0]['Heuredouverture2'],
			'heure-fermeture-fin' => @$val['DATESs'][0]['Heuredefermeture2'],
			'description' => (@$description?'<p>'.$description.'</p>':''),
			'texte' => (@$texte?'<p>'.$texte.'</p>':''),
			'url-site-web' => @$com['C5'],
			'telephone' => (@$com['C1']?base64_encode(@$com['C1']) : (@$com['C6']?base64_encode(@$com['C6']):'')),
			'mail-contact' => (@$com['C4']?base64_encode(@$com['C4']):''),
			'adresse' => (@$val['ADRESSEs'][0]['CodePostal']?'<p>'.$val['ADRESSEs'][0]['Adresse2'].'<br>'.$val['ADRESSEs'][0]['CodePostal'].' '.$val['ADRESSEs'][0]['Commune'].'</p>':''),
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
			'type' => "'aaaa-mm-jj'",
			'cle' => "'".$date."'"
		))."),";

	    
	     // Ajout des tag lié
		//[ObjectTypeName] => Fêtes et manifestations
	    foreach($val['TYPEs'][0]['Typedemanifestation'] as $key_tag => $val_tag) 
	    {
	    	$sql_tag.="(".implode(",", array(
				'id' => -$key,
				'zone' => "'".encode(__('Agenda'))."'",
				'lang' => "'fr'",
				'encode' => "'".encode($val_tag['ThesLibelle'])."'",
				'name' => "'".$GLOBALS['connect']->real_escape_string($val_tag['ThesLibelle'])."'",
				'ordre' => "'".($key_tag+1)."'"
			))."),";
	    }
		

	    if($key >= $limit) break;
	}
}



// Suppression des tag lié au évènement tourinsoft
$GLOBALS['connect']->query("DELETE FROM ".$tt." WHERE zone='agenda' AND id<=0");
if($GLOBALS['connect']->error) die($GLOBALS['connect']->error);

// Suppression des dates dans les méta avant ajout ?
$GLOBALS['connect']->query("DELETE FROM ".$tm." WHERE type='aaaa-mm-jj' AND id<=0");
if($GLOBALS['connect']->error) die($GLOBALS['connect']->error);

// Suppression des contenus
$GLOBALS['connect']->query("DELETE FROM ".$tc." WHERE type='event-tourinsoft'");// AND id<=0
if($GLOBALS['connect']->error) die($GLOBALS['connect']->error);



// Insertion dans la table CONTENT
$GLOBALS['connect']->query(trim($sql_init_content.$sql_content,','));
if($GLOBALS['connect']->error) die($GLOBALS['connect']->error);

// Insertion dans la table META
$GLOBALS['connect']->query(trim($sql_init_meta.$sql_meta,','));
if($GLOBALS['connect']->error) die($GLOBALS['connect']->error);

// Insertion dans la table TAG
$GLOBALS['connect']->query(trim($sql_init_tag.$sql_tag,','));
if($GLOBALS['connect']->error) die($GLOBALS['connect']->error);



//echo '<br><br>'.str_replace('),','),<br><br>', $sql);

echo '<br>time : '.benchmark().'s';
?>