
<?php

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


##### INITIALISATION des variabes #####
	$icon = "icon.ico";

	$cDc = 0.016; // pour PENTAX K5(D/1730)
	//$cDc = 0.020; // pour PENTAX K5(D/1440)

	$aFocal = array('17','20','24','28','35','50','70','80','100','135','150','200','210','300');
	$aDiaph = array('1.4','1.8','2','2.8','4','5.6','8','11','16','22','32','45');

	### Elements du MENU ####
	$aMenu = array(
		0 => array('hyperfocal','Hyperfocale'),
		1 => array('speedShot','Vitesse minimale'),
		2 => array('expo', 'Exposition'),
		3 => array('f16', 'Règle du F/16')
		);


	### tableaux des facteurs ###
	$factor['iso'] = array(	1=>'25',2=>'32',3=>'40',4=>'50',5=>'64',6=>'80',
					7=>'100',8=>'125',9=>'160',10=>'200',11=>'250',12=>'320',
					13=>'400',14=>'500',15=>'640',16=>'800',17=>'1000',18=>'1250',
					19=>'1600',20=>'2000',21=>'2500',22=>'3200',23=>'4000',24=>'5000',
					25=>'6400',26=>'8000',27=>'10000',28=>'12800',29=>'16000',30=>'20000',
					31=>'25600',32=>'32000',33=>'40000',34=>'51200',35=>'64000',36=>'80000');

	$factor['open'] = array(	1=>'0.8',2=>'0.9',3=>'1',4=>'1.1',5=>'1.2',6=>'1.4',7=>'1.6',
							8=>'1.8',9=>'2',10=>'2.2',11=>'2.5',12=>'2.8',13=>'3.2',
							14=>'3.5',15=>'4',16=>'4.5',17=>'5',18=>'5.6',19=>'6.3',
							20=>'7.1',21=>'8',22=>'9',23=>'10',24=>'11',25=>'13',
							26=>'14',27=>'16',28=>'18',29=>'20',30=>'22',31=>'25',
							32=>'29',33=>'32',34=>'36',35=>'42',36=>'45');

	// chaque clé = 1/3 d'EV. 3 clés = 1 pallier = +1 Ev.
	$factor['speed'] = array(	1=>'1/8000',2=>'1/6400',3=>'1/5000',4=>'1/4000',5=>'1/3200',6=>'1/2500',
					7=>'1/2000',8=>'1/1600',9=>'1/1250',10=>'1/1000',11=>'1/800',12=>'1/640',
					13=>'1/500',14=>'1/400',15=>'1/320',16=>'1/250',17=>'1/200',18=>'1/160',
					19=>'1/125',20=>'1/100',21=>'1/80',22=>'1/60',23=>'1/50',24=>'1/40',
					25=>'1/30',26=>'1/25',27=>'1/20',28=>'1/15',29=>'1/13',30=>'1/10',
					31=>'1/8',32=>'1/6',33=>'1/5',34=>'1/4',35=>'1/3',36=>'1/2.5',
					37=>'1/2',38=>'1/1.6',39=>'1/1.3',40=>'1"',41=>'1.3"',42=>'1.6"',
					43=>'2"',44=>'2.5"',45=>'3"',46=>'4"',47=>'5"',48=>'6"',
					49=>'8"',50=>'10"',51=>'13"',52=>'15"',53=>'20"',54=>'25"');


	### CONDITIONS de lumière ###

	# Tableau des indices IL en fonction des condition #
	# les Indices = différence entre IL de base par SOLEIL et l'IL en fonction de la condition de Lumière
	# pour ISO 100, IL beau temps = 15, IL Intérieur = 5. Indice d'IL intérieur = -10IL
	$aIL_condition = array(
		0 =>'Beau temps',
		2 => 'Nuageux / voilé',
		3 => 'Très nuageux / Gris',
		4 => 'Soleil couchant',
		7 => 'Nuit ville éclairée',
		10 => 'Intérieur',
		18 => 'Pleine lune',
		30 => 'Nuit noire / Astronomie'
		);

	$constraint_IL_condition = $_POST['condition'];

	$conditionLabel = $aIL_condition[$_POST['condition']];
	## ##


	### PRIORITE ###
	$aPriority = array('Aucune','ISO','VITESSE','OUVERTURE');
	$priority = $aPriority[$_POST['priority']];

	### FACTEURS ###
	$aFactor = array('open' => 18,'iso' => 13,'speed' => 22);

	# clé de Référence de chaque facteur #
	foreach ($aFactor as $key => $value) {
		$aRef[$key] = $value;
	}

	### FACTEURS ###
	$open = $factor['open'][$aRef['open']];
	$speed = $factor['speed'][$aRef['speed']];
	$iso = $factor['iso'][$aRef['iso']];

##### FIN Initialisation #####


##### ACTIONS #####


### FACTEURS ###	
	## 1ere connexion ou POST vide ##
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
	## ##

	# On comptabilise le nombre de facteurs modifiés
		$count = 0;
		foreach ($aFactor as $key => $value) {
			// si POST est seté et qu'il est différent du log actuel
			if(isset($_POST[$key]) && $_POST[$key]!=wLogRead($dataPathGlobal,$key)){
				$count++;
			}
		}

	## les Clés de facteurs ##
		$Factor_key['iso'] = wLogRead($dataPathGlobal,'iso');
		$Factor_key['speed'] = wLogRead($dataPathGlobal,'speed');
		$Factor_key['open'] = wLogRead($dataPathGlobal,'open');

		#Permet de calculer la différence entre la clé de référence, et la clé sélectionnée
		if(isset($_POST['open']))
			#Diff de clé de chaque facteur = Diff entre clé de Référence et clé du POST[facteur]
			$diff_key_['open'] = $_POST['open']- $aRef['open'];

		if(isset($_POST['iso']))
			$diff_key_['iso'] = $_POST['iso'] - $aRef['iso'];

		if(isset($_POST['speed']))
			$diff_key_['speed'] = $_POST['speed']- $aRef['speed'] ;
	## ##
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

/*
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

*/



### REINITIALISATION des facteurs ###
	if(isset($_POST['initialise'])){
		$iso = $factor['iso'][$aRef['iso']];
		$speed = $factor['speed'][$aRef['speed']];
		$open = $factor['open'][$aRef['open']];
	}
### ###


### EXPOSITION ###

if(isset($_POST['calculExpo'])){
	$constraint_IL_condition_for_each_factor = number_format($constraint_IL_condition/3,1);
	$diff_EV_finale = number_format($diff_EV_['open']+$diff_EV_['speed']+$diff_EV_['iso']-$constraint_IL_condition,1);
	$newKey['iso'] = $_POST['iso'];
	$newKey['speed'] = $_POST['speed'];
	$newKey['open'] = $_POST['open'];



	## On set ISO ##
	wCountErase($dataPathGlobal,'iso');
	wCountAdd($dataPathGlobal,'iso',$newKey['iso']);
	$iso = $factor['iso'][wLogRead($dataPathGlobal,'iso')];

	## On set SPEED ##
	wCountErase($dataPathGlobal,'speed');
	wCountAdd($dataPathGlobal,'speed',$newKey['speed']);
	$speed = $factor['speed'][wLogRead($dataPathGlobal,'speed')];

	## On set OPEN ##
	wCountErase($dataPathGlobal,'open');
	wCountAdd($dataPathGlobal,'open',$newKey['open']);
	$open = $factor['open'][wLogRead($dataPathGlobal,'open')];
}
### ###



### HYPERFOCALE ###
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
	$speedShot = floor(toFraction($speedShotCalcul));

}


$isoT = 0;
$speedT = 0;
$openT = 0;
$isIsset = 0;



## Regle du F/16 ##
// si OUVERTURE selectionné

	$f16_rule_ref_key = 27;

	if(!isset($_POST['openf16'])){
		$_POST['openf16'] = $f16_rule_ref_key;
	}

	# On divise l'IL de condition par 2 pour le répartir sur les 2 facteurs à calculer (Vitesse et ISO)
	$constraint_IL_condition_for_each_factor = number_format($constraint_IL_condition/2,1);
	# Nb de clé correspondant à l'IL de condition, pour chacun des 2 facteurs
	$nb_key_by_IL_constraint = $constraint_IL_condition_for_each_factor*3;

	$diff_key = $_POST['openf16'] - 27;
	$valSpeed = $_POST['openf16'] - 7 +$nb_key_by_IL_constraint;
	$valISO = $_POST['openf16'] - 20 +$nb_key_by_IL_constraint;

	if(isset($_POST['openf16']) && $_POST['openf16']!= $f16_rule_ref_key){
		$newkey_open = $_POST['openf16'];
	}
	else{
		$newkey_open = $f16_rule_ref_key;
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



	$isoT = $factor['iso'][$keyISO];	
	$openT = $factor['open'][$newkey_open];
	$speedT = $factor['speed'][$keySpeed];
	$isIsset = 2;





