<?php
include_once("../../../config.php");// Variables
include_once("../../../api/function.php");// Fonctions
include_once("../../../api/db.php");// Connexion à la db

$lang = get_lang();// Sélectionne  la langue
load_translation('api');// Chargement des traductions du système


//login('high', 'view-stats');// Vérifie qu'on a les droits

// Vérifi que l'on est loger
if(!token_check(@$_SESSION['token'])) die('die');

// ?period=custom&date=2021-01-01,2021-01-31
// https://plausible.io/api/v1/stats/realtime/visitors?site_id=$SITE_ID" \ -H "Authorization: Bearer ${TOKEN}"
// https://plausible.io/api/v1/stats/breakdown?site_id=&period=6mo&property=event:page&limit=5
// https://plausible.io/api/v1/stats/timeseries?site_id=
// &metrics=visitors,pageviews,bounce_rate,visit_duration
// event:page
// visit:entry_page	

// $GLOBALS['plausible_token'] => clé api plausible, dans config.php

$site = $GLOBALS['plausible'];// $GLOBALS['plausible'] => url du site donner à plausible
$period = '12mo';// 6mo 12mo

$url = 'https://plausible.io/api/v1/stats/breakdown?site_id='.$site.'&period='.$period.'&property=event:page&limit=1000';

// On récupère les données de Plausible
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Authorization: Bearer '.$GLOBALS['plausible_token']
));

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($curl);
$getinfo = curl_getinfo($curl);
curl_close($curl);

$stats = json_decode($return, true);

//highlight_string(print_r($getinfo, true));
//highlight_string(print_r($stats, true));

// Les statistiques par page
foreach($stats['results'] as $cle => $stat)
{
	$url = ltrim($stat['page'], '/');
	$page_stats[$url] = $stat['visitors'];
}

// Les contenus du site
$sel = $connect->query("SELECT title, state, url, type, tpl, date_insert FROM ".$GLOBALS['table_content']." WHERE 1");
while($res = $sel->fetch_assoc()) 
{
	$debut = new DateTime($res['date_insert']);
	$fin = new DateTime(date('Y-m-d'));

	$jours = $fin->diff($debut)->format("%a");

	$page_site[$res['url']] = '<a href="'.make_url($res['url'], array("domaine" => true)).'">'.$res['title'].'</a> - <em>'.(@$GLOBALS['tpl_name'][$res['type']]?$GLOBALS['tpl_name'][$res['type']]:$res['type']).' - '.$res['state'].' - '.$jours.' jour'.($jours>1?'s':'').'</em>';
}

// Fusionne les tableaux
foreach($page_site as $url => $label)
{
	// label = stat
	if(isset($page_stats[$url])) $final[$label] = $page_stats[$url];
}

// Tri en fonction du nombre de visite
asort($final);

// Affichage
echo'<h1>'.$site.'</h1><ul>';
foreach($final as $label => $stat)
{
	echo'<li>'.$label.' - '.$stat.' hits</li>';
}
echo'</ul>';
?>