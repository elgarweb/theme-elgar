<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');

header('Content-Type: application/json; charset=utf-8');

function make_json($res)
{
	global $json;

	//strip_tags

	// Le contenu en json
	$content = json_decode($res['content'], true);

	// Type
	if($res['type'] == 'article') $type = 'actu';
	elseif($res['type'] == 'event-tourinsoft') $type = 'agenda';
	else $type = 'agenda';

	// Texte / Contenu
	$texte = (@$content['description'].@$content['texte']);

	// Transforme les lien local en url avec le domaine du site
	$texte = str_replace('href="/', 'href="'.$GLOBALS['home'], $texte);// Lien
	$texte = str_replace('src="/', 'src="'.$GLOBALS['home'], $texte);// image
	$texte = str_replace('src="media/', 'src="'.$GLOBALS['home'].'media/', $texte);// image

	// Tableau des variables
	$json[$res['id']] = array(
		"type" => $type,
		"id" => $res['id'],
		"url" => make_url($res['url'], array("domaine" => true)),
		"title" => $res['title'],
		"contenu" => $texte,
		"image" => (@$content['visuel']?$GLOBALS['home'].$content['visuel']:''),
		"date-creation" => $res['date_insert']
	);

	if(@$content['url-site-web']) $json[$res['id']]['site-web'] = $content['url-site-web'];
	if(@$content['telephone']) $json[$res['id']]['telephone'] = base64_decode($content['telephone']);
	if(@$content['mail-contact']) $json[$res['id']]['mail-contact'] = base64_decode($content['mail-contact']);
	if(@$content['adresse']) $json[$res['id']]['adresse'] = $content['adresse'];

	if(@$content['aaaa-mm-jj']) $json[$res['id']]['date-debut'] = $content['aaaa-mm-jj'];
	if(@$content['heure-ouverture']) $json[$res['id']]['heure-ouverture-debut'] = $content['heure-ouverture'];
	if(@$content['heure-fermeture']) $json[$res['id']]['heure-fermeture-debut'] = $content['heure-fermeture'];

	if(@$content['aaaa-mm-jj-fin']) $json[$res['id']]['date-fin'] = $content['aaaa-mm-jj-fin'];
	if(@$content['heure-ouverture-fin']) $json[$res['id']]['heure-ouverture-fin'] = $content['heure-ouverture-fin'];
	if(@$content['heure-fermeture-fin']) $json[$res['id']]['heure-fermeture-fin'] = $content['heure-fermeture-fin'];

	return $json;
}



$num_pp = 4;
$json = null;



// Construction de la requete pour les actu
$sql="SELECT ".$tc.".* FROM ".$tc;

$sql.=" WHERE (".$tc.".type='article' OR ".$tc.".type='article-intramuros') AND ".$tc.".lang='".$lang."' AND state='active'";

$sql.=" ORDER BY ".$tc.".date_insert DESC";

$sql.=" LIMIT 0, ".$num_pp;

//echo $sql;

$sel = $connect->query($sql);

while($res = $sel->fetch_assoc())
{
	//highlight_string(print_r($content, true));

	$json = make_json($res);
}



// Construction de la requete pour les événements
$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

// Tous les évènements
//$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

// Que les évènements a venir
$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tc.".id AND event_deb.type='aaaa-mm-jj' AND event_deb.cle >= '".date("Y-m-d")."'";

$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' AND state='active'";

// Que les évènements a venir
$sql.=" ORDER BY event_deb.cle ASC";

$sql.=" LIMIT ".$num_pp;

//echo "<b>A venir :</b> ".$sql;

$sel_event = $connect->query($sql);
$num_event = $sel_event->num_rows;


// Si peut d'évènement à venir, on prend aussi les en cours
if($num_event < $num_pp) 
{
	// Construction de la requete
	$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

	// Que les évènements en cours
	$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tc.".id AND event_deb.type='aaaa-mm-jj'";
	$sql.=" LEFT JOIN ".$tm." AS event_fin ON event_fin.id=".$tc.".id AND event_fin.type='aaaa-mm-jj-fin'";

	$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' AND state='active'";

	// Que les évènements en cours
	$sql.=" AND (event_fin.cle >= '".date("Y-m-d")."' OR event_deb.cle >= '".date("Y-m-d")."')";

	// Que les évènements en cours
	$sql.=" ORDER BY event_deb.cle ASC, event_fin.cle ASC";

	$sql.=" LIMIT ".$num_pp;

	//echo "<br><b>En cours :</b> ".$sql;

	$sel_event = $connect->query($sql);
	$num_event = $sel_event->num_rows;
}

//echo $sql;

while($res_event = $sel_event->fetch_assoc())
{
	//highlight_string(print_r($content, true));

	$json = make_json($res_event);
}


// JSON_UNESCAPED_SLASHES JSON_UNESCAPED_UNICODE
echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>