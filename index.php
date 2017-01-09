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
<!-- style pour cacher/afficher les div -->
<style type="text/css">
	<?php 
	foreach ($aMenu as $key => $value) {
		echo '#'.$aMenu[$key][0].'{
	    	display:none;
		}';
		echo'
		#'.$aMenu[$key][0].':target {
	    display:block;
		}';
	}
	?>
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

		<nav id="primary_nav" class="menuHead">
			<ul>
				<?php
				# Génération du Menu
				foreach ($aMenu as $key => $value) {
					echo ' <li><a href="#'.$aMenu[$key][0].'" class="menuPadding"> '.$aMenu[$key][1].'</a></li>';
				}
				#Lien info vers autres page
				?>
				<li><a href="infophoto.php" class="menuPadding"> Infos utiles</a></li>
			</ul>
		</nav>

		<!-- HyperFocale -->
		<div id="<?php echo $aMenu[0][0]; ?>" class="center panelDark">
			<h2><?php echo $aMenu[0][1]; ?></h2>
			
			<form action="#<?php echo $aMenu[0][0]; ?>" method="post">
				<label>Focale objectif</label>
				<select name="<?php echo $aMenu[0][0]; ?>FOCAL">
				<?php
				foreach ($aFocal as $key => $value) {		
					if ($value==$FocalObjectif){
						echo '<option value="'.$value.'" selected>'.$value.' mm</option>';
					}
					else{
						echo '<option value="'.$value.'">'.$value.' mm</option>';
					}
				}
				?>
					
				</select>

				<label>Ouverture</label>
				<select name="<?php echo $aMenu[0][0]; ?>DIAPH">
					<?php
				foreach ($aDiaph as $key => $value) {		
					if ($value==$diaph){
						echo '<option value="'.$value.'" selected>F '.$value.'</option>';
					}
					else{
						echo '<option value="'.$value.'">F '.$value.'</option>';
					}
				}
				?>
				</select>
				<input type="submit" value="OK">
			</form>
			<div class="resultFocus">
				<?php
				if($hyperFocale != NULL){
					echo number_format($hyperFocale,2).' m';
				}
				?>
			</div>
			<div class="ligneSeparator">
			</div>	
			<div class="panelText">
				<p>L'hyperfocale est définie comme la distance la plus courte à laquelle un sujet sera net lorsque la mise au point est réalisée sur l'infini.<br>
				La M.A.P sera donc nette à partir du point hyperfocal jusqu'à l'infini.</p>
				<p>Ce module est réglé pour un cercle de confusion (CdC) stricte à D/1730* sur un PENTAX K5 (soit 0.016mm).<br>
				Cela correspond à la norme globale des capteurs APS-C (format 15.7 x 23.6 et environs)</p>
			</div>
			<div>
				<img src="hypefocal.jpg" class="illu">
			</div>
			<div class="infoSmall center">
				*  D/1730 est la valeur la + proche de l'accuité humaine, contrairement à la valeur D/1140, moins exigeante mais + couramment utilisée.
			</div>
		</div>

		<!-- Vitesse minimale -->
		<div id="<?php echo $aMenu[1][0]; ?>" class="center panelDark">
			<h2><?php echo $aMenu[1][1]; ?></h2>
			<p>La vitesse minimale (dite "de sûreté") est celle à laquelle  le flou produit par le photographe en tenant l'appareil photo peut être considéré comme inexistant.<br>
			Ici, on peut la calculer en fonction de la focale utilisée</p>
			<form action="#<?php echo $aMenu[1][0]; ?>" method="post">
				<label>Focale objectif</label>
				<select name="<?php echo $aMenu[1][0]; ?>FOCAL">
				<?php
				foreach ($aFocal as $key => $value) {		
					if ($value==$FocalObjectif){
						echo '<option value="'.$value.'" selected>'.$value.' mm</option>';
					}
					else{
						echo '<option value="'.$value.'">'.$value.' mm</option>';
					}
				}
				?>				
				</select>
				<input type="submit" value="Calculer">
			</form>

			<div class="resultFocus">
				<?php
				if($speedShot!= NULL){
					echo '1/'.$speedShot.' sec.';
				}
				?>
			</div>		
		</div>

		<!-- Expo -->
		<div id="<?php echo $aMenu[2][0]; ?>" class="center panelDark">
			<h2><?php echo $aMenu[2][1]; ?></h2>
			
			<div name="form">
				<form action="#<?php echo $aMenu[2][0]; ?>" method="post">
					<label>ISO</label>
					<select name="iso">
					<option></option>
						<?php
						foreach ($factor['iso'] as $key => $value) {
							if(isset($_POST['iso'])){
								if ($key==wLogRead($dataPathGlobal,'iso')){
									echo '<option value="'.$key.'" selected>'.$value.'</option>';
								}
								else{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
							else{
								if ($key==13){
									echo '<option value="'.$key.'" selected>'.$value.'</option>';
								}
								else{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
						}
						?>				
					</select>
					<label>Vitesse</label>
					<select name="speed">
					<option></option>
						<?php
						foreach ($factor['speed'] as $key => $value) {	
							if(isset($_POST['speed'])){
								if ($key==wLogRead($dataPathGlobal,'speed')){
									echo '<option value="'.$key.'" selected>'.$value.'</option>';
								}
								else{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
							else{
								if ($key==22){
								echo '<option value="'.$key.'" selected>'.$value.'</option>';
								}
								else{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
							
						}
						?>				
					</select>

					<label>Ouverture</label>
					<select name="open">
					<option></option>
						<?php
						foreach ($factor['open'] as $key => $value) {
							if(isset($_POST['open'])){
								if ($key==wLogRead($dataPathGlobal,'open')){
									echo '<option value="'.$key.'" selected>'.$value.'</option>';
								}
								else{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
							else{
								if ($key==18){
									echo '<option value="'.$key.'" selected>'.$value.'</option>';
								}
								else{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
						}
						?>				
					</select>


					<!-- CONDITIONS -->
					<label>Conditions</label>
					<select name="condition">
						<?php
						foreach ($aIL_condition as $key => $value) {
							if ($key == $_POST['condition']){
								echo '<option value="'.$key.'" selected>'.$value.'</option>';
							}
							else{
								echo '<option value="'.$key.'">'.$value.'</option>';
							}
						}
						?>				
					</select>
					<input type="submit" value="Calculer" name="calculExpo">
				</form>
			</div>
			<form action="#<?php echo $aMenu[2][0]; ?>" method="post">
					<input type="submit" name="initialise" value="Réinitialiser" class="lanceur darkButton"  onclick="if(!confirm('Les valeurs reviendront à ISO : 400 - VITESSE : 1/60 - OUVERTURE : 5.6')) return false;">
				</form>


			<div class="resultFocus">
				<?php
				//if($count != 0){
					echo 'ISO : '.$iso.
						' - Vitesse : '.$speed.
						' - Ouverture : f/'.$open;
				//}
				?>		
			</div>	
			<div class="childResult">
				<div class="alerte">
					<?php
					if($iTypeAlerte != ""){
						if($iTypeAlerte == 1){
							echo '<font class="yellowResult">ATTENTION ! Vous dépassez la limite maximum de la plage de réglages possibles. Nous avons affiché la + grande valeur possible dans la plage</font>';
							echo '<br>Facteur(s) concerné(s) : ';
							foreach ($aAlerte[1] as $key => $value) {
								echo $value.' (+'.$diffOut[1][$key].' EV)';
							}
						}
						
						if($iTypeAlerte == 2){
							echo '<font class="redResult">ATTENTION ! Vous dépassez la limite minimum de la plage de réglages possibles. Nous avons affiché la + petite valeur possible dans la plage.</font>';
							echo '<br>Facteur(s) concerné(s) : ';
							foreach ($aAlerte[2] as $key => $value) {
								echo $value.' (+'.$diffOut[2][$key].' EV)';
							}
						}
						
					}
					?>

				</div>
				<?php
				if($_POST['condition']>1){
					echo 'Contrainte EV en condition '.$aIL_condition[$_POST['condition']].' : -'.$constraint_IL_condition.' EV<br>';
				}						

				echo 'Diff ISO : ';
				if($diff_EV_['iso'] > 1){echo'<font class="yellowResult">+'.$diff_EV_['iso'].'</font>';}
				if($diff_EV_['iso'] < -1){echo'<font class="redResult">'.$diff_EV_['iso'].'</font>';}
				if($diff_EV_['iso']>= -1 && $diff_EV_['iso']<= 1){echo '<font class="greenResult">'.$diff_EV_['iso'].'</font>';}
				echo' EV (-'.$constraint_IL_condition_for_each_factor.') |';

				echo 'Diff Vitesse : ';
				if($diff_EV_['speed'] > 1){echo'<font class="yellowResult">+'.$diff_EV_['speed'].'</font>';}
				if($diff_EV_['speed'] < -1){echo'<font class="redResult">'.$diff_EV_['speed'].'</font>';}
				if($diff_EV_['speed'] >= -1 && $diff_EV_['speed']<= 1){echo '<font class="greenResult">'.$diff_EV_['speed'].'</font>';}
				echo' EV (-'.$constraint_IL_condition_for_each_factor.') |';

				echo 'Diff ouverture : ';
				if($diff_EV_['open'] > 1){echo'<font class="yellowResult">+'.$diff_EV_['open'].'</font>';}
				if($diff_EV_['open'] < -1){echo'<font class="redResult">'.$diff_EV_['open'].'</font>';}
				if($diff_EV_['open'] >= -1 && $diff_EV_['open']<= 1){echo '<font class="greenResult">'.$diff_EV_['open'].'</font>';}
				echo' EV (-'.$constraint_IL_condition_for_each_factor.')';

				echo'<br>';
				
				echo 'Indice de lumination final : ';
				if($diff_EV_finale > 1){echo'<font class="yellowResult">+'.number_format($diff_EV_finale,1).'</font>';}
				if($diff_EV_finale < -1){echo'<font class="redResult">'.number_format($diff_EV_finale,1).'</font>';}
				if($diff_EV_finale >= -1 && $diff_EV_finale <=1){echo '<font class="greenResult">'.number_format($diff_EV_finale,1).'</font>';}
				echo' EV ';

				echo'<br><br>';
					echo '<font class="redResult">Sous-expo.</font> / <font class="greenResult">Expo. correcte </font>/ <font class="yellowResult">Sur-expo.</font>';
				?>
			</div>	
			<div class="ligneSeparator">
			</div>	
			<div class="panelText">
				<p>Le principe de base de la photographie permettant d'obtenir une exposition correcte repose sur "le triangle d'exposition" qui agit sur les 3 facteurs indispensables: <ul>
								<li>La sensibilité du capteur (ISO)</li>
								<li>Le temps d’obturation (vitesse)</li>
								<li>L’ouverture du diaphragme de l’objectif</li>					
							</ul>
				</p>
				<p>
					Par convention, l'exposition correcte par beau temps est estimée à 400 ISO pour une ouverture f/5.6 et une vitesse de 1/60s. Cela correspond à l'ISO, la VITESSE et l'OUVERTURE moyenne des plages utilisées pour chaque facteur.<br>
					Partant de cette base, ce module vous donne l'indice d'EV ("Exposure Value") de chaque facteur et l'indice EV final en fonction des paramétrage que vous lui indiquez.
					Vous pourrez ainsi modifier vos réglages en vous appuyant sur les tableaux de facteurs ci-dessous afin de respecter la règle du Triangle et de garder une exposition idéale (soit 0 EV. On admettra toutefois une exposition correcte entre -/+1 EV)
				</p>
	
			</div>
			<div style="overflow-x:auto;" name="demoTable">
				<h3> Palliers d'EV </h3>
				<table>
					<tr>
						<th colspan="3">-1 pallier</th>
						<th colspan="3">+1 pallier</th>
					</tr>
					<tr>
						<td colspan="3">-1 EV</td>
						<td colspan="3">+1 EV</td>
					</tr>
					<tr>
						<td>-3/3 EV</td>
						<td>-2/3 EV</td>
						<td>-1/3 EV</td>
						<td>+1/3 EV</td>
						<td>+2/3 EV</td>
						<td>+3/3</td>
					</tr>
					<tr>
						<td>-1 EV</td>
						<td>-0.7 EV</td>
						<td>-0.3 EV</td>
						<td>0.3 EV</td>
						<td>0.7 EV</td>
						<td>1 EV</td>
					</tr>
				</table>
				<div class="panelText">
					<p> 1 EV correspond à 1 pallier de réglage. Chaque pallier est divisé en 3 tiers d'EV (correspondant aux 3 sous-palliers de réglages qu'on retrouve sur les APN. Lorsque vous modifiez un paramètre sur votre APN, chaque tour de molette = 1/3 de pallier = 1/3 d'EV</p>
				</div>
			</div>

			<div style="overflow-x:auto;" name="openTable">
			<h3> Table des palliers d'OUVERTURES </h3>
				<table>
					<tr>
						<?php
						for($i=1;$i<=3;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=1;$i<=9;$i++) {
							if($i==$aRef['open']){echo '<td class="tdFocus">f/'.$factor['open'][$i].'</td>';}
							elseif($factor['open'][$i]==$open){echo '<td class="tdSelected">f/'.$factor['open'][$i].'</td>';}
							else{echo '<td>f/'.$factor['open'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=4;$i<=6;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=10;$i<=18;$i++) {
							if($factor['open'][$i]==$open){echo '<td class="tdSelected">f/'.$factor['open'][$i].'</td>';}
							else{echo '<td>f/'.$factor['open'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=7;$i<=9;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=19;$i<=27;$i++) {
							if($factor['open'][$i]==$open){echo '<td class="tdSelected">f/'.$factor['open'][$i].'</td>';}
							else{echo '<td>f/'.$factor['open'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=10;$i<=12;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=28;$i<=36;$i++) {
							if($factor['open'][$i]==$open){echo '<td class="tdSelected">f/'.$factor['open'][$i].'</td>';}
							else{echo '<td>f/'.$factor['open'][$i].'</td>';}
						}
						?>
					</tr>
				</table>
			</div>

			<div style="overflow-x:auto;" name="isoTable">
			<h3> Table des palliers des ISO </h3>
				<table>
					<tr>
						<?php
						for($i=1;$i<=3;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=1;$i<=9;$i++) {
							if($i==$aRef['iso']){echo '<td class="tdFocus">'.$factor['iso'][$i].'</td>';}
							elseif($factor['iso'][$i]==$iso){echo '<td class="tdSelected">'.$factor['iso'][$i].'</td>';}
							else{echo '<td>'.$factor['iso'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=4;$i<=6;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=10;$i<=18;$i++) {
							if($factor['iso'][$i]==$iso){echo '<td class="tdSelected">'.$factor['iso'][$i].'</td>';}
							else{echo '<td>'.$factor['iso'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=7;$i<=9;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=19;$i<=27;$i++) {
							if($factor['iso'][$i]==$iso){echo '<td class="tdSelected">'.$factor['iso'][$i].'</td>';}
							else{echo '<td>'.$factor['iso'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=10;$i<=12;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=28;$i<=36;$i++) {
							if($factor['iso'][$i]==$iso){echo '<td class="tdSelected">'.$factor['iso'][$i].'</td>';}
							else{echo '<td>'.$factor['iso'][$i].'</td>';}
						}
						?>
					</tr>
				</table>
			</div>

			<div style="overflow-x:auto;" name="speedTable">
			<h3> Table des palliers des VITESSES </h3>
				<table>
					<tr>
						<?php
						for($i=1;$i<=3;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=1;$i<=9;$i++) {
							if($factor['speed'][$i]==$speed){echo '<td class="tdSelected">'.$factor['speed'][$i].'</td>';}
							else{echo '<td>'.$factor['speed'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=4;$i<=6;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=10;$i<=18;$i++) {
							if($i==$aRef['speed']){echo '<td class="tdFocus">'.$factor['speed'][$i].'</td>';}
							elseif($factor['speed'][$i]==$speed){echo '<td class="tdSelected">'.$factor['speed'][$i].'</td>';}
							else{echo '<td>'.$factor['speed'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=7;$i<=9;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=19;$i<=27;$i++) {
							if($factor['speed'][$i]==$speed){echo '<td class="tdSelected">'.$factor['speed'][$i].'</td>';}
							else{echo '<td>'.$factor['speed'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=10;$i<=12;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=28;$i<=36;$i++) {
							if($factor['speed'][$i]==$speed){echo '<td class="tdSelected">'.$factor['speed'][$i].'</td>';}
							else{echo '<td>'.$factor['speed'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=13;$i<=15;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=37;$i<=45;$i++) {
							if($factor['speed'][$i]==$speed){echo '<td class="tdSelected">'.$factor['speed'][$i].'</td>';}
							else{echo '<td>'.$factor['speed'][$i].'</td>';}
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=16;$i<=18;$i++) {
							echo '<th colspan="3">Pallier '.$i.'</th>';
						}
						?>
					</tr>
					<tr>
						<?php
						for($i=46;$i<=54;$i++) {
							if($factor['speed'][$i]==$speed){echo '<td class="tdSelected">'.$factor['speed'][$i].'</td>';}
							else{echo '<td>'.$factor['speed'][$i].'</td>';}
						}
						?>
					</tr>
				</table>
			</div>


		</div>

		<!-- Règle du F/16 -->
		<div id="<?php echo $aMenu[3][0]; ?>" class="center panelDark">
			<h2><?php echo $aMenu[3][1]; ?></h2>

			<form action="#<?php echo $aMenu[3][0]; ?>" method="post">
				

				<label>Ouverture</label>
				<select name="openf16">
					<?php
					foreach ($factor['open'] as $key => $value) {
						if ($key==$_POST['openf16']){
							echo '<option value="'.$key.'" selected>'.$value.'</option>';
						}
						else{
							echo '<option value="'.$key.'">'.$value.'</option>';
						}
					}
					?>				
				</select>

				<!-- CONDITIONS -->
				<label>Conditions</label>
				<select name="condition">
					<?php
					foreach ($aIL_condition as $key => $value) {
						if ($key == $_POST['condition']){
							echo '<option value="'.$key.'" selected>'.$value.'</option>';
						}
						else{
							echo '<option value="'.$key.'">'.$value.'</option>';
						}
					}
					?>				
				</select>
				<input type="submit" value="Calculer">
			</form>

			<div class="resultFocus" id="ul_responsive">
				<ul>
					<li>Ouverture : <font color=#fff>f/<?php echo $openT ?></font></li>
					<li>ISO : <font color=#fff><?php echo $isoT ?></font></li>
					<li>VITESSE : <font color=#fff><?php echo $speedT ?></font></li>
				</ul>
			</div>	
			<div class="ligneSeparator">
			</div>	
			<div class="panelText">
				<p>Selon cette règle, une scène ensoleillée doit être exposée à une vitesse équivalente à la sensibilité ISO de la surface sensible pour une ouverture de f/16. Soit, pour une sensibilité de 100 ISO et une ouverture de f/16, une vitesse de 1/100 seconde (1/125 sur votre APN). Bien entendu, il est possible de conserver cette norme d'exposition en modifiant l'ouverture ou en fonction des conditions de lumière</p>
				<p>Sélectionnez l'OUVERTURE et/ou les CONDITIONS de lumière souhaitée(s) pour obtenir les réglages de VITESSE et d'ISO à effectuer pour conserver cette norme.</p>
			</div>
			
		</div>


		<!-- Conseils photos -->
		<div id="<?php echo $aMenu[4][0]; ?>" class="panelDark">
			<h2><?php echo $aMenu[4][1]; ?></h2>
			
			<div>
				<h5>A quoi penser avant une photo ?</h5>
				<ul>
					<li>Le mode de prise de vue (Av, P, Manuel,…)</li>
					<li>Le mode d’exposition (MULTIZONE, PONDEREE, SPOT)</li>
					<li>L’exposition (On peut la faire varier de 1 ou 2 IL sans avoir besoin de modifier les réglages, grâce au bouton d’expo - à coté de ISO sur K5)</li>
					<li>La profondeur de champs (f/2.8 bcp de flou, petite profondeur / f22 beaucoup de netteté et grande profondeur de champs)</li>
					<li>La MAP (auto ou manuelle)</li>
					<li>Balance des blancs</li>
					<li>Format RAW - JPEG ?</li>
				</ul>
			</div>

			<div>
				<h5>Modes de prise de vue</h5>
				<p>
					<p1 class="whiteTitle">Tav</p1> [réglage OUV & VIT - auto ISO]</br>
					Permet de régler l’ouverture et la vitesse de façon indépendante.<br>
					En fonction des réglages, les ISO s’ajustent automatiquement.
				</p>

				<p>
					<p1 class="whiteTitle">Av</p1> [réglages OUV - ISO]</br>
					Permet de régler l’ouverture et les ISO.<br>
					En fonction, la vitesse sera calculée par l'APN.
				</p>

				<p>
					<p1 class="whiteTitle">P</p1> [réglage OUV, VIT, ISO]</br>
					Permet de choisir les ISO et de régler l’OUV et la VIT de façon dépendante (lorsque vous réglez l'OUVerture, la VITesse s'adapte, et vis et versa).<br>
					On fonction de l’ISO choisi, l’APN adapte le 3ème facteur en fonction du 2nd.<br>
					A noter : si en sélectionnant 1 facteur on atteint la plage maximum du 2nd facteur, alors l’APN empêche d’aller + loin dans la plage du facteur 1.<br>
					<ul>
					<li>Molette avant : VITesse -> l’ouverture s’ajustera en fonction.</li>
					<li>Molette arrière : OUVerture -> la vitesse s’ajustera en fonction.</li>
					<li>Molette arrière + btn ISO -> réglage des ISO</li>
					</ul>
				</p>
			</div>

			<div>
				<h5>Types de photos</h5>

				<p1 class="whiteTitle">Paysage lumineux, éclairé</p1>
				<ul>
					<li>Objectif grand angle</li>
					<li>MAP sur l'Infini</li>
					<li>Mode TaV ou P</li>
					<li>Ouverture : F8 en moyenne</li>
					<li>Mode expo : Multizones</li>
				</ul>

				<p1 class="whiteTitle">Coucher de soleil</p1>
				<ul>
					<li>Objectif grand angle</li>
					<li>MAP sur l'Infini</li>
					<li>Mode Av ou P</li>
					<li>Ouverture : F11 - F16 en moyenne</li>
					<li>ISO 100</li>
					<li>Vitesse : 1/200 (privilégier l'OUVerture et faire confiance au calcul de l’appareil pour la VITesse)</li>
					<li>Mode expo : Multizones</li>
				</ul>

				<p1 class="whiteTitle">Nuit / astro</p1>
				<ul>
					<li>Objectif grande ouverture (f/2.8 - f/1.4)</li>
					<li>MAP sur l'Infini</li>
					<li>Mode Manuel</li>
					<li>Ouverture : F2.8</li>
					<li>ISO : faible (100)</li>
					<li>Vitesse : pause longue </li>
					<li>Mode expo : Multizones</li>
					<li>A noter : « heure bleue »  15min après coucher de soleil</li>
				</ul>

				<p1 class="whiteTitle">Nuit ville</p1>
				<ul>
					<li>Objectif angle</li>
					<li>MAP auto</li>
					<li>Mode Av (ou Manuel pour effet filet lumineux)</li>
					<li>Ouverture : f/8 - f/11</li>
					<li>ISO : 100 - 200</li>
					<li>Vitesse : laisser l’APN choisir OU pause longue pour effet « filer de lumière)</li>
					<li>Mode expo : Multizones</li>
				</ul>


				<p1 class="whiteTitle">Voie lactée</p1>
				<ul>
					<li>Objectif grande ouverture (f/2.8 - f/1.4)</li>
					<li>MAP sur l'Infini</li>
					<li>Mode Manuel</li>
					<li>Ouverture : F2.8</li>
					<li>ISO :  3200</li>
					<li>Vitesse : 30"</li>
					<li>Mode expo : Pondérée</li>
					<li>A noter : Juin - septembre, bonnes parties lumineuses</li>
				</ul>

				<p1 class="whiteTitle">Lune</p1>
				<ul>
					<li>Téléobjectif</li>
					<li>MAP sur l'Infini</li>
					<li>Mode Manuel</li>
					<li>Ouverture : f/8 au moins voir f/10</li>
					<li>Vitesse : 20"</li>
					<li>ISO : 800 - 1600</li>
					<li>Mode expo : SPOT</li>
				</ul>

				

			</div>

		</div>

		<!-- Panel logo -->
		<div class="panelDark childResult">
			Les indications techniques des différentes rubriques concernant l'APN sont basées sur un PENTAX k-5.<br>
			Elles correspondront donc avec tout appareil reflexe numérique de gamme similaire (expert - semi pro).<br>
			Le cas échéant, reportez vous au manuel de votre APN pour adapter les résultats.
		</div>
		<div name="logo-illu" class="center panelLight">
			<a href="index.php"> 
				<img src="logo.png" class="logo">
			</a>
			<br>
			<font class="blueDefault">YOA</font><font class="greyDark">PHOTO</font>
		</div>
		

		<div class="footer">			
			@Mousshk - YoaPli tous droits réservés - <a href="https://github.com/mousshaker/photo" target="_blank">Télécharger l'application</a>
		</div>

	</div>
</body>