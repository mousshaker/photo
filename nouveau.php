
<?php

$speedShotCalcul = NULL;
if(isset($_POST['speedShotFOCAL'])){
	$FocalObjectif = $_POST['speedShotFOCAL'];
	$speedShotCalcul = 1/(1.5*$_POST['speedShotFOCAL']);
	$speedShot = floor($speedShotCalcul*100000);
	$speedShot = floor(toFraction($speedShotCalcul));

}

### NOTES UTILES PHP ###
/*
floor() -> arrondi entier INFérieur
ceil() -> arrondi entier SUPérieur
number_format($value,2) -> arrondi 2 chiffres après la virgule
sqrt() -> racine carrée
abs() -> valeur absolue (valeur exacte sans tenir compte du signe + ou -)
*/

### NOTES ###
/** REGLE de luminosité
* le facteur de multiplication de l'OUVERTURE (fmo) = la racine carrée de la différence de lumière (+/- Ev).
* Donc si j'ai 2x plus de lumière (+2 Ev) : fmo = racine carrée de 2 = 1,41
* +4 Ev : fmo = racine carrée de 4 = 2
* +6 Ev : fmo = racine carrée de 6 = 2,44
**/

/** REGLE Hyperfocale
H = F2/(fxc)
H=distance hyperfocale en mm
F=longueur focale de l'objectif (28, 50mm...)
f =diaphragme choisi
c=cercle de confusion=0,033mm en 24x36, 0,05mm en 6x6.


H = (Focale * Focale)  /  (Indice d’ouverture * cercle de confusion / 1000)= X en mètres
Le cercle de confusion varie suivant les APN, soit APS-C ou full frame, de manière générale le CdC pour un APS-C est de 0.019 ou 0.020 (suivant Canon 
*/

### FIN NOTE ###
# Tableau des indices IL en fonction des condition #
# les Indices = différence entre IL de base par SOLEIL et l'IL en fonction de la condition de Lumière
# la valeur d'IL (et non pas son indice) se calcul ainsi : $IL_ISOx = $IL100 + log(ISO/100,2);
$aIL100 = array(
	0 => array('15','Beau temps'),
	2 => array('13','Nuageux / voilé'),
	3 => array('12','Très nuageux / Gris'),
	4 => array('11','Soleil couchant'),
	7 => array('8','Nuit ville éclairée'),
	10 => array('5','Intérieur'),
	18 => array('-3','Pleine lune'),
	30 => array('-15','Nuit noire / Astronomie')
	);


#IL de 100ISO correspondant à la condition sélectionné
		#-> IL qu'on applique si ISO =100 et condition sélectionnée
		$IL100_condition = $aIL100[$_POST['condition']][0];

		# IL correspondant à la condition sélectionnée, si ISO =! 100 (= "x")
		$IL_ISOx_condition = $IL100_condition + log($aISO[$_POST['iso']]/100,2);

		# Puisque tous les calculs d'Expo sont faits sur une base de ISO=100, soit indice IL = 15
		#-> il faut prendre en compte l'IL de condition ET
		$IL_to_applicate = $IL_ISOx - $aIL100[$_POST['condition']][0];

		echo 'Il de référence 0 (par beau temps) : '.floor($IL_ISOx).'<br>';
		echo 'Condition : '.$aIL100[$_POST['condition']][1].'<br>';
		echo 'iso : '.$aISO[$_POST['iso']].'<br>';
		echo 'Il de condition(IL100) : '.$IL100.'<br>';
		echo 'Log2(iso) : '.log($aISO[$_POST['iso']]/100,2).'<br>';
		echo 'Il de condition en fonction de ISO : '.number_format($IL_ISOx_condition,2).'<br>';
		echo 'IL à appliquer (soit IL entre Soleil et Condition selectionnée) : '.number_format($IL_to_applicate,1).'<br>';

##### Initialisation des variabes #####
$icon = "icon.ico";

$cDc = 0.016; // pour PENTAX K5(D/1730)
//$cDc = 0.020; // pour PENTAX K5(D/1440)

$aFocal = array('17','20','24','28','35','50','70','80','100','135','150','200','210','300');
$aDiaph = array('1.4','1.8','2','2.8','4','5.6','8','11','16','22','32','45');

# Elements du MENU #
$aMenu = array(
	0 => array('hyperfocal','Hyperfocale'),
	1 => array('speedShot','Vitesse minimale'),
	2 => array('expo', 'Exposition'),
	3 => array('f16', 'Règle du F/16')
	);

$aISO = array(	1=>'25',2=>'32',3=>'40',4=>'50',5=>'64',6=>'80',
				7=>'100',8=>'125',9=>'160',10=>'200',11=>'250',12=>'320',
				13=>'400',14=>'500',15=>'640',16=>'800',17=>'1000',18=>'1250',
				19=>'1600',20=>'2000',21=>'2500',22=>'3200',23=>'4000',24=>'5000',
				25=>'6400',26=>'8000',27=>'10000',28=>'12800',29=>'16000',30=>'20000',
				31=>'25600',32=>'32000',33=>'40000',34=>'51200',35=>'64000',36=>'80000');

$aOuverture = array(	1=>'0.8',2=>'0.9',3=>'1',4=>'1.1',5=>'1.2',6=>'1.4',7=>'1.6',
						8=>'1.8',9=>'2',10=>'2.2',11=>'2.5',12=>'2.8',13=>'3.2',
						14=>'3.5',15=>'4',16=>'4.5',17=>'5',18=>'5.6',19=>'6.3',
						20=>'7.1',21=>'8',22=>'9',23=>'10',24=>'11',25=>'13',
						26=>'14',27=>'16',28=>'18',29=>'20',30=>'22',31=>'25',
						32=>'29',33=>'32',34=>'36',35=>'42',36=>'45');

// chaque clé = 1/3 d'EV. 3 clés = 1 pallier = +1 Ev.
$aVitesse = array(	1=>'1/8000',2=>'1/6400',3=>'1/5000',4=>'1/4000',5=>'1/3200',6=>'1/2500',
				7=>'1/2000',8=>'1/1600',9=>'1/1250',10=>'1/1000',11=>'1/800',12=>'1/640',
				13=>'1/500',14=>'1/400',15=>'1/320',16=>'1/250',17=>'1/200',18=>'1/160',
				19=>'1/125',20=>'1/100',21=>'1/80',22=>'1/60',23=>'1/50',24=>'1/40',
				25=>'1/30',26=>'1/25',27=>'1/20',28=>'1/15',29=>'1/13',30=>'1/10',
				31=>'1/8',32=>'1/6',33=>'1/5',34=>'1/4',35=>'1/3',36=>'1/2.5',
				37=>'1/2',38=>'1/1.6',39=>'1/1.3',40=>'1"',41=>'1.3"',42=>'1.6"',
				43=>'2"',44=>'2.5"',45=>'3"',46=>'4"',47=>'5"',48=>'6"',
				49=>'8"',50=>'10"',51=>'13"',52=>'15"',53=>'20"',54=>'25"');

# Indique le type d'alerte et le facteur concerné
// -> $aAlerte[type][facteur]
// -> type : 0 = limite maxi / 1 = limite mini
$aAlerte = array(0=>array(),1=>array());
$iTypeAlerte = "";



# Tableau de différence d'EV suivant les conditions#
// on part du postulat que l'exposition de base correspond à un temps ensoleillé
//Beau Temps devient donc la référence 0 d'EV
$aCondition = array(
	1 => array('0','Beau temps'),
	2 => array('2','Ciel voilé'),
	3 => array('4','Coucher soleil'),
	4 => array('6','Nuageux'),
	5 => array('8','Nuit (ville)'),
	6 => array('10','Intérieur')
	);
$condition = $aCondition[$_POST['condition']][0];



### PRIORITE ###
$aPriority = array('Aucune','ISO','VITESSE','OUVERTURE');
$priority = $aPriority[$_POST['priority']];

### FACTEURS ###
$aFactor = array('open' => 18,'iso' => 13,'speed' => 22);

# clé de Référence de chaque facteur #
foreach ($aFactor as $key => $value) {
	$aRef[$key] = $value;
}

##### FIN Initialisation #####


##### ACTIONS #####
### 1ere connexion ou POST vide ###
# On set chaque facteur avec sa valeur de référence
foreach ($aFactor as $key => $value) {
	if(!isset($_POST[$key])){
	//On s'assure qu'il existe, sinon on le créé
		wLogRead($dataPathGlobal,$key);
		// On efface la valeurs actuelle
		wCountErase($dataPathGlobal,$key);
		// On ajoute la nouvelle valeur
		wCountAdd($dataPathGlobal,$key,$value);
	}
}
if(!isset($_POST['priority'])){
	$_POST['priority']==0;
}


# On comptabilise le nombre de facteurs modifiés
$count = 0;
foreach ($aFactor as $key => $value) {
	// si POST est seté et qu'il est différent du log actuel
	if(isset($_POST[$key]) && $_POST[$key]!=wLogRead($dataPathGlobal,$key)){
		$count++;
	}
}

### les Clés de facteurs ###
	$Factor_key['iso'] = wLogRead($dataPathGlobal,'iso');
	$Factor_key['speed'] = wLogRead($dataPathGlobal,'speed');
	$Factor_key['open'] = wLogRead($dataPathGlobal,'open');

	//Permet de calculer la différence entre la clé de référence, et la clé sélectionnée
	if(isset($_POST['open']))
		//Diff de clé de chaque facteur = Diff entre clé de Référence et clé du POST[facteur]
		$diff_key_['open'] = $aRef['open'] - $_POST['open'];


	if(isset($_POST['iso']))
		$diff_key_['iso'] = $_POST['iso'] - $aRef['iso'];

	if(isset($_POST['speed']))
		$diff_key_['speed'] = $_POST['speed']- $aRef['speed'] ;
### ###

### EV (pour L'AFFICHAGE) ###
	// En fonction de la différence de clé, on calcule la différence d'EV (en 1/3 d'Ev puisque 1 clé = 1/3 d'Ev)
	// diff = Nb de clés décallées x 1/3
	$tiersEV = 1/3;

	foreach ($aFactor as $key => $value) {
		// diff_EV de chaque facteur = Diff clé du facteur /3 (ou x1/3)
		$diff_EV_[$key] = number_format($diff_key_[$key]*$tiersEV,1);
	}
	// la différence totale d'EV = diff_EV de chaque facteur
	$diff_EV_TOTALE = abs($diff_EV_['open'])+abs($diff_EV_['iso'])+abs($diff_EV_['speed']);
### ###

//cf REGLES de luminosité [On arrondi la Racine carré à l'INFérieur et en valeur absolue]
$fmo = sqrt(floor(abs($diff_EV_TOTALE)));


//INFOS ERREURS
# on affiche le conteur de facteurs sétés
echo 'count : '.$count.'<br>';
# on affiche les Val setées en Data
foreach ($aFactor as $key => $value) {
	echo 'Val_Log_'.$key.' : '.$Factor_key[$key].'<br>';
}
# on AFFICHE les $_POST
foreach ($aFactor as $key => $value) {
	echo 'POST_'.$key.' : '.$_POST[$key].'<br>';
}

# on AFFICHE les différence d'EV
foreach ($aFactor as $key => $value) {
	echo 'diff_EV_'.$key.' : '.$diff_EV_[$key].'<br>';
}





/********
echo 'Diff EV avec Settings : '.floor($diff_EV_TOTALE).' Ev / Diff exacte : ('.$diff_EV_TOTALE.')<br>';
echo number_format($fmo,2).' _> Diff exacte Facteur x Ouverture : ('.$fmo.')<br>';
echo 'Diff OUV : '.$diff_EV_['open'].' / Diff ISO : '.$diff_EV_['iso'].' / Diff Vitesse : '.$diff_EV_['speed'].'<br>';
*******/

$open = "";
$speed = "";
$iso = "";
$expo_alerte ="";

if(isset($_POST['initialise'])){
	$iso = $aISO[$aRef['iso']];
	$speed = $aVitesse[$aRef['speed']];
	$open = $aOuverture[$aRef['open']];
}



// Si 1 ou AUCUN Facteur n'est modifié
if($count==0){
	if($_POST['priority']==0){
		
		# $factor = $aFactor[logFactor] + condition_EV_by_factor
		$newKey['iso'] = $Factor_key['iso'];
		$newKey['speed'] = $Factor_key['speed'];
		$newKey['open'] = $Factor_key['open'];

		$diff_EV_finale = $diff_EV_['iso']+$diff_EV_['speed']+$diff_EV_['open'];;
		
		# On set ISO #
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		# on set la VITESSE #
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];

		# on set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];


	}
	else{
		// sinon ca revient à 1 facteur sélectionné (via la priorité)
		$count==1;
				echo '$diff_EV_finale '.$diff_EV_finale;
	}
}




if($count==1) { # 1 facteur sléectionné = 2 facteurs impactés

	# Si ISO sélectionné -> modification OPEN & SPEED
	if($_POST['priority']==1 XOR $_POST['iso']!=wLogRead($dataPathGlobal,'iso')){

		$diff_EV_TO_APPLICATE = -$diff_EV_['iso']/2;
		# Différence de clé à appliquer pour chaque facteur impacté = différence d'EV x3 (puisque 1EV =  3 clés)
		$diff_key_TO_APPLICATE = floor($diff_EV_TO_APPLICATE*3);

		$diff_EV_['speed'] = floor($diff_EV_TO_APPLICATE);
		$diff_EV_['open'] = floor($diff_EV_TO_APPLICATE);

		$diff_EV_finale = $diff_EV_['iso']+$diff_EV_['speed']+$diff_EV_['open'];

		# Nouvelle clé = ancienne clé + différence de clé à appliquer
		$newKey['speed'] = $aRef['speed']+$diff_key_TO_APPLICATE;
		$newKey['open'] = $aRef['open']-$diff_key_TO_APPLICATE;
		#Nouvelle clé du facteur selectionnée = POST du facteur
		$newKey['iso'] = $_POST['iso'];

		
		# on set l'Ouverture #
		if($newKey['open']<1){
			// On efface la valeurs actuelle
			wCountErase($dataPathGlobal,'open');
			// On ajoute la nouvelle valeur
			wCountAdd($dataPathGlobal,'open',1);

			# limite maxi ouverture
			$iTypeAlerte = 0;
			$aAlerte[0][0] = 'OUVERTURE';
		}
		elseif($newKey['open']>36){
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',36);
			#limite mini ouverture
			$iTypeAlerte = 1;
			$aAlerte[1][0] = 'OUVERTURE';
		}
		else{
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',$newKey['open']);									
		}
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];
		
		# On set la vitesse #
		if($newKey['speed']<1){
			//si <1, alors on prend 1 en valeur mini
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',1);
			//... et on ajoute une alerte
			# limite maxi vitesse
			$iTypeAlerte = 0;
			$aAlerte[0][1] = 'VITESSE';
		}
		elseif($newKey['speed']>54){
			//si > 54 (fin de tableau), on prend la dernière valeur en valeur maxi
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',54);
			//... et on ajoute une alerte
			# limite mini vitesse
			$iTypeAlerte = 1;
			$aAlerte[1][1] = 'VITESSE';
		}
		else{
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',$newKey['speed']);			
		}
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];	

		# On set ISO #
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		foreach ($aFactor as $key => $value) {
			echo 'val_Log_'.$key.' : '.wLogRead($dataPathGlobal,$key).'<br>';
		}
	}
	//si priorité VITESSe
	elseif($_POST['priority']==2 XOR $_POST['speed']!=wLogRead($dataPathGlobal,'speed')){
		$diff_EV_TO_APPLICATE = -$diff_EV_['speed']/2;
		# Différence de clé à appliquer pour chaque facteur impacté = différence d'EV x3 (puisque 1EV =  3 clés)
		$diff_key_TO_APPLICATE = floor($diff_EV_TO_APPLICATE*3);

		$diff_EV_['iso'] = floor($diff_EV_TO_APPLICATE);
		$diff_EV_['open'] = floor($diff_EV_TO_APPLICATE);

		$diff_EV_finale = $diff_EV_['speed']+$diff_EV_['iso']+$diff_EV_['open'];
		# Nouvelle clé = ancienne clé + différence de clé à appliquer
		$newKey['iso'] = $aRef['iso']+$diff_key_TO_APPLICATE;
		$newKey['open'] = $aRef['open']-$diff_key_TO_APPLICATE;
		#Nouvelle clé du facteur selectionnée = POST du facteur
		$newKey['speed'] = $_POST['speed'];


		# on set l'ISO #
		if($newKey['iso']<1){
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',1);
			$iTypeAlerte = 0;
			$aAlerte[0][0] = 'ISO';
		}
		elseif($newKey['iso']>36){
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',36);
			$iTypeAlerte = 1;
			$aAlerte[1][0] = 'ISO';
		}
		else{
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',$newKey['iso']);			
		}
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		# on set l'Ouverture #
		if($newKey['open']<1){
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',1);
			$iTypeAlerte = 0;
			$aAlerte[0][1] = 'OUVERTURE';
		}
		elseif($newKey['open']>36){
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',36);
			$iTypeAlerte = 1;
			$aAlerte[1][1] = 'OUVERTURE';
		}
		else{
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',$newKey['open']);									
		}
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];

		# on set la VITESSE #
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];
	}
	//si priorité OUVERTURE
	elseif($_POST['priority']==3 XOR $_POST['open']!=wLogRead($dataPathGlobal,'open')){
		$diff_EV_TO_APPLICATE = -$diff_EV_['open']/2;
		# Différence de clé à appliquer pour chaque facteur impacté = différence d'EV x3 (puisque 1EV =  3 clés)
		$diff_key_TO_APPLICATE = floor($diff_EV_TO_APPLICATE*3);

		$diff_EV_['speed'] = floor($diff_EV_TO_APPLICATE);
		$diff_EV_['iso'] = floor($diff_EV_TO_APPLICATE);

		$diff_EV_finale = $diff_EV_['open']+$diff_EV_['speed']+$diff_EV_['iso'];
		# Nouvelle clé = ancienne clé + différence de clé à appliquer
		$newKey['speed'] = $aRef['speed']+$diff_key_TO_APPLICATE;
		$newKey['iso'] = $aRef['iso']+$diff_key_TO_APPLICATE;
		#Nouvelle clé du facteur selectionnée = POST du facteur
		$newKey['open'] = $_POST['open'];


		# On set la vitesse #
		if($newKey['speed']<1){
			//si <1, alors on prend 1 en valeur mini
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',1);
			//... et on ajoute une alerte
			$iTypeAlerte = 0;
			$aAlerte[0][0] = 'VITESSE';
		}
		elseif($newKey['speed']>54){
			//si > 54 (fin de tableau), on prend la dernière valeur en valeur maxi
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',54);
			//... et on ajoute une alerte
			$iTypeAlerte = 1;
			$aAlerte[1][0] = 'VITESSE';
		}
		else{
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',$newKey['speed']);			
		}
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];	

		# on set l'ISO #
		if($newKey['iso']<1){
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',1);
			$iTypeAlerte = 0;
			$aAlerte[0][1] = 'ISO';
		}
		elseif($newKey['iso']>36){
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',36);
			$iTypeAlerte = 1;
			$aAlerte[1][1] = 'ISO';
		}
		else{
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',$newKey['iso']);			
		}
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		# on set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];
	}
	// si aucune PRIORITE sélectionnée
	else{
		$newKey['iso'] = $_POST['iso'];
		$newKey['speed'] = $_POST['speed'];
		$newKey['open'] = $_POST['open'];

		$diff_EV_finale = $diff_EV_TOTALE;
		
		# On set ISO #
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		# on set la VITESSE #
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];

		# on set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];
	}

}

elseif($count==2) {

	$diff_EV_TO_APPLICATE = -$diff_EV_TOTALE/1;
	// on *3 pour avoir le bon nombre de 1/3 d'EV (et donc de clés)
	$diff_key_TO_APPLICATE = floor($diff_EV_TO_APPLICATE*3);

	//Si ISO identique à log -> OUVERTURE & VITESSE selectionnés
	if($_POST['iso']==wLogRead($dataPathGlobal,'iso')){
		//Affichera la différence d'EV en ISO pour compenser
		$diff_EV_['iso'] = floor($diff_EV_TO_APPLICATE);
		$diff_EV_finale = $diff_EV_TOTALE + $diff_EV_['iso'];
		
		$newKey['iso'] = $_POST['iso'] + $diff_key_TO_APPLICATE;
		$newKey['speed'] = $_POST['speed'];
		$newKey['open'] = $_POST['open'];
		
		$open = $aOuverture[$_POST['open']];
		$speed = $aVitesse[$_POST['speed']];

		# on set l'ISO #
		if($newKey['iso']<1){
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',1);
			$iTypeAlerte = 0;
			$aAlerte[0][0] = 'ISO';
		}
		elseif($newKey['iso']>36){
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',36);
			$iTypeAlerte = 1;
			$aAlerte[1][0] = 'ISO';
		}
		else{
			wCountErase($dataPathGlobal,'iso');
			wCountAdd($dataPathGlobal,'iso',$newKey['iso']);			
		}
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];


		// On set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];

		// On set SPEED
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];
	}
	//si VITESSE identique à log -> ISO & OUVERTURE sélectionnés
	elseif($_POST['speed']==wLogRead($dataPathGlobal,'speed')){
		$diff_EV_['speed'] = floor($diff_EV_TO_APPLICATE);
		$diff_EV_finale = $diff_EV_TOTALE + $diff_EV_['speed'];
		
		$newKey['speed'] = $_POST['speed'] + $diff_key_TO_APPLICATE;
		$newKey['iso'] = $_POST['iso'];
		$newKey['open'] = $_POST['open'];			

		# On set la vitesse #
		if($newKey['speed']<1){
			//si <1, alors on prend 1 en valeur mini
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',1);
			//... et on ajoute une alerte
			$iTypeAlerte = 0;
			$aAlerte[0][0] = 'VITESSE';
		}
		elseif($newKey['speed']>54){
			//si > 54 (fin de tableau), on prend la dernière valeur en valeur maxi
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',54);
			//... et on ajoute une alerte
			$iTypeAlerte = 1;
			$aAlerte[1][0] = 'VITESSE';
		}
		else{
			wCountErase($dataPathGlobal,'speed');
			wCountAdd($dataPathGlobal,'speed',$newKey['speed']);			
		}

		// on set SPEED
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];

		// On set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];

		// On set ISO
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];
	}
	//si OUVERTURE identique à log -> ISO & VITESSE sélectionné
	elseif($_POST['open']==wLogRead($dataPathGlobal,'open')){
		$diff_EV_['open'] = floor($diff_EV_TO_APPLICATE);
		$diff_EV_finale = $diff_EV_TOTALE + $diff_EV_['open'];
		
		$newKey['open'] = $_POST['open'] - $diff_key_TO_APPLICATE;
		$newKey['iso'] = $_POST['iso'];
		$newKey['speed'] = $_POST['speed'];
				
		
		$speed = $aVitesse[$_POST['speed']];
		$iso = $aISO[$_POST['iso']];

		# on set l'Ouverture #
		if($newKey['open']<1){
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',1);
			$iTypeAlerte = 0;
			$aAlerte[0][0] = 'OUVERTURE';
		}
		elseif($newKey['open']>36){
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',36);
			$iTypeAlerte = 1;
			$aAlerte[1][0] = 'OUVERTURE';
		}
		else{
			wCountErase($dataPathGlobal,'open');
			wCountAdd($dataPathGlobal,'open',$newKey['open']);									
		}
		# on set l'ouverture


		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];
		// On set ISO
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		// On set SPEED
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];
	}
	// si aucune PRIORITE sélectionnée
	else{
		$newKey['iso'] = $_POST['iso']+$diff_key_TO_APPLICATE;
		$newKey['speed'] = $_POST['speed']+$diff_key_TO_APPLICATE;
		$newKey['open'] = $_POST['open']-$diff_key_TO_APPLICATE;

		$diff_EV_finale = $diff_EV_TOTALE;
		
		// On set ISO
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		// On set SPEED
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];

		// On set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];
	}
}
elseif($count==3){
	$message = 'Euh... vous avez modifé les 3 paramètres vous-même';

	$diff_EV_TO_APPLICATE = -$diff_EV_TOTALE/3;
	// on *3 pour avoir le bon nombre de 1/3 d'EV (et donc de clés)
	$diff_key_TO_APPLICATE = $diff_EV_TO_APPLICATE*3;


	if($_POST['priority']==1){
		
	}
	//si priorité VITESSe
	elseif($_POST['priority']==2){
		
	}
	//si priorité OUVERTURE
	elseif($_POST['priority']==3){
		
	}
	// si aucune PRIORITE sélectionnée
	else{

		$diff_EV_finale = number_format($diff_EV_['open']+$diff_EV_['speed']+$diff_EV_['iso'],1);
		$newKey['iso'] = $_POST['iso'];
		$newKey['speed'] = $_POST['speed'];
		$newKey['open'] = $_POST['open'];


		
		// On set ISO
		wCountErase($dataPathGlobal,'iso');
		wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
		$iso = $aISO[wLogRead($dataPathGlobal,'iso')];

		// On set SPEED
		wCountErase($dataPathGlobal,'speed');
		wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
		$speed = $aVitesse[wLogRead($dataPathGlobal,'speed')];

		// On set OPEN
		wCountErase($dataPathGlobal,'open');
		wCountAdd($dataPathGlobal,'open',$newKey['open']);
		$open = $aOuverture[wLogRead($dataPathGlobal,'open')];
	}
}




//echo '<br>Ouverture : f/'.$open.' Vitesse :'.$speed.' ISO :'.$iso;




$hyperFocale = NULL;
if(isset($_POST['hyperfocalFOCAL'])|| isset($_POST['hyperfocalDIAPH'])){
	$FocalObjectif = $_POST['hyperfocalFOCAL'];
	$diaph = $_POST['hyperfocalDIAPH'];
	$hyperFocale = ($FocalObjectif*$FocalObjectif) / ($_POST['hyperfocalDIAPH']*$cDc)/1000;
}

$speedShotCalcul = NULL;
if(isset($_POST['speedShotFOCAL'])){
	$FocalObjectif = $_POST['speedShotFOCAL'];
	$speedShotCalcul = 1/(1.5*$_POST['speedShotFOCAL']);
	$speedShot = floor($speedShotCalcul*100000);
}

$isoT = 0;
$speedT = 0;
$openT = 0;
$isIsset = 0;

$conditionLabel = $aCondition[$_POST['condition']][1];


## Regle du F16 ##
// si OUVERTURE selectionné
if($_POST['open']!= "..."){
	$diff_key_['open'] = 27 - $_POST['open'];
	$valSpeed = $_POST['open'] - (7+($diff_key_['open']/3));
	$valISO = $_POST['open'] - (20-$diff_key_['open']);

	
	# Limite des résultats #
	$keySpeed = "";	
	// si résultat < au tableau
	if($valSpeed+$condition<1){
		//... on prend la + 1ere valeur
		$keySpeed = 1;
	}
	//sinon si resultat > à tableau
	elseif($valSpeed+$condition>54){
		//... on prend la + dernière valeur
		$keySpeed = 54;
	}
	else{
		$keySpeed = $valSpeed+$condition;
	}

	$keyISO = "";
	// si résultat < au tableau
	if($valISO+$condition<1){
		//... on prend la + 1ere valeur
		$keyISO = 1;
	}
	//sinon si resultat > à tableau
	elseif($valISO+$condition>36){
		//... on prend la + dernière valeur
		$keyISO = 36;
	}
	else{
		$keyISO = $valISO+$condition;
	}

	$isoT = $aISO[$keyISO];	
	$openT = $aOuverture[$_POST['open']];
	$speedT = $aVitesse[$keySpeed];
	$isIsset = 2;


}




## Regle du F/16 ##
// si OUVERTURE selectionné

	$f16_rule_ref_key = 27;

	if(!isset($_POST['openf16'])){
		$_POST['openf16'] = $f16_rule_ref_key;
	}
	echo 'POST OPENF16 : '.$_POST['openf16'].'<br>';
	echo 'newKey open : '.$newkey_open.'<br>';

	# On divise l'IL de condition par 2 pour le répartir sur les 2 facteurs à calculer (Vitesse et ISO)
	$constraint_IL_condition_for_each_factor = number_format($constraint_IL_condition/2,1);
	# Nb de clé correspondant à l'IL de condition, pour chacun des 2 facteurs
	$nb_key_by_IL_constraint = $constraint_IL_condition_for_each_factor*3;
	$diff_key_['openf16'] = 27 - $_POST['openf16'];
	$valSpeed = $_POST['openf16'] - (7+(($diff_key_['openf16'])/3));
	$valISO = $_POST['openf16'] - (20-($diff_key_['openf16']);

	if(isset($_POST['openf16']) && $_POST['openf16']!= $f16_rule_ref_key){
		$newkey_open = $_POST['openf16'];
		echo 'je passe ici';
	}
	else{
		$newkey_open = $f16_rule_ref_key;
		echo 'oh je pas par la';
	}

	
	# Limite des résultats #
	$keySpeed = "";	
	// si résultat < au tableau
	if($valSpeed+$condition<1){
		//... on prend la + 1ere valeur
		$keySpeed = 1;
	}
	//sinon si resultat > à tableau
	elseif($valSpeed+$condition>54){
		//... on prend la + dernière valeur
		$keySpeed = 54;
	}
	else{
		$keySpeed = $valSpeed+$condition;
	}

	$keyISO = "";
	// si résultat < au tableau
	if($valISO+$condition<1){
		//... on prend la + 1ere valeur
		$keyISO = 1;
	}
	//sinon si resultat > à tableau
	elseif($valISO+$condition>36){
		//... on prend la + dernière valeur
		$keyISO = 36;
	}
	else{
		$keyISO = $valISO+$condition;
	}
	echo 'newKey open 2 : '.$newkey_open.'<br>';


	$isoT = $factor['iso'][$keyISO];	
	$openT = $factor['open'][$newkey_open];
	$speedT = $factor['speed'][$keySpeed];
	$isIsset = 2;

	echo 'OPEN T : '.$factor['open'][$newkey_open];






