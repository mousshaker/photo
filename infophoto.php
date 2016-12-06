<?php
// code by mousshk@gmail.com
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Paris");
#inclusion des fonctions
include('fonctions.php');
#inclusion des calculs sources
include('source.php');

?>
<head>
<style>
    <?php include('style.css'); ?>
</style>


<!-- cette balise permet de s'adapter au format mobile et d'avoir le bon zoom
à l'ouverture de la page -->
<meta name="viewport" content="width=device-width, maximum-scale=1"/>
<!-- Inclusion de l'icone d'onglet -->
<link rel="shortcut icon" type="image/x-icon" href="icon.ico" />

<title>PhotoHelp - YoaPli</title>
</head>
<body>
<div class="wrapper">
	<header>
	    <a class="to_nav" href="#primary_nav">Menu</a>
	</header>
	<a href="index.php">
	<h1>PhotoHelp</h1>
	</a>

	<nav class="menuHead">
		<ul>			
			<li><a href="index.php" class="menuPadding"> Retour</a></li>
		</ul>
	</nav>

	<div id="condition" class="panelDark">
	<table>
	<tr><th>Condition</th><th>Diff. EV</th></tr>
		<?php
			foreach ($aIL_condition as $key => $value) {
				echo '<tr><td>'.$value.'</td><td> -'.$key.' EV</td></tr>';
			}
		?>	
	</table>		
	</div>
	<div class="panelText">
		<p>Ci-dessous, vous trouverez différents liens renvoyant vers des pages d'informations diverses afin de vous aider à appréhender certaines régles et bases de la photographie</p>
		<p>Ces liens n'ont aucun but commercial, ni même aucun partenariat.</p>
	</div>

	<nav id="secondary_nav" class="menuHead">
		<ul>
			<li><a href="http://www.1point2vue.com/principe-exposition/" class="menuPadding" target="_blank">Principe Exposition</a></li>
			<li><a href="https://fr.wikipedia.org/wiki/Indice_de_lumination" class="menuPadding" target="_blank">Indice lumination</a></li>
			<li><a href="http://www.posepartage.fr/forum/vos-trucs-et-astuces/comment-calculer-la-distance-hyperfocale,fil-21289.html" class="menuPadding" target="_blank">Tableau HyperFocal</a></li>
			<li><a href="http://www.100iso.fr/cours/hyperfocale.htm#Calcul%20th%C3%A9orique%20de%20l%27hyperfocale:"class="menuPadding" target="_blank">Info Hyperfocale</a></li>
			<li><a href="http://www.la-photo-en-faits.com/2012/11/format-capteurs-photo-numerique.html"class="menuPadding" target="_blank">Infos Capteurs</a></li>
			<li><a href="http://www.focus-numerique.com/test-3240/prise-de-vue-triangle-exposition-1.html"class="menuPadding" target="_blank">Triangle exposition</a></li>
			<li><a href="http://www.la-photo-en-faits.com/2013/01/triangle-exposition-ouverture-vitesse-sensibilite-ISO.html"class="menuPadding" target="_blank">Ouverture et EV</a></li>
			
		</ul>
	</nav>
	<div name="logo-illu" class="center panelLight">
		<img src="logo.png" class="logo">
		<br>
		<font class="blueDefault">YOA</font><font class="greyDark">PHOTO</font>
	</div>
	

	<div class="footer">			
		@Mousshk - YoaPli (tous droits réservés)
	</div>
</div>