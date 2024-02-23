<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');

header('Content-Type: application/json; charset=utf-8');

$num_pp = 10;

// Construction de la requete
$sql="SELECT ".$tc.".* FROM ".$tc;

$sql.=" WHERE (".$tc.".type='article' OR ".$tc.".type='event-tourinsoft' OR ".$tc.".type='event') AND ".$tc.".lang='".$lang."' AND state='active'";

$sql.=" ORDER BY ".$tc.".date_insert DESC";

$sql.=" LIMIT 0, ".$num_pp;

//echo $sql;

$sel = $connect->query($sql);

while($res = $sel->fetch_assoc())
{
	$content = json_decode($res['content'], true);

	// @$content_fiche['visuel-1'], $res_fiche['url'], $res_fiche['title'], @$content_fiche['aaaa-mm-jj']

	//strip_tags

	$json[$res['id']] = array(
		"type" => $res['type'],
		"title" => $res['title'],
		"image" => (@$content['visuel']?$GLOBALS['home'].$content['visuel']:''),
		"contenu" => (@$content['description'].@$content['texte']),
		"permalien" => make_url($res['url'], array("domaine" => true)),
		"date-insert" => $res['date_insert']
	);

	//highlight_string(print_r($content, true));
}

// JSON_UNESCAPED_SLASHES JSON_UNESCAPED_UNICODE
echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>