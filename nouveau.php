<body>
    


<div class="panelDark">
<div class="wrapper">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius tempore facere harum illo quaerat eos laboriosam accusantium quidem sint assumenda fugiat culpa, quisquam enim atque laborum, laudantium dignissimos reprehenderit nobis.
</div>

<ul>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>
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





?>
</div>


</body>
