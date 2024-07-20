#!/usr/bin/php
<?php
	$configLigne=file("Valeurs");
	$config=explode(",",$configLigne[0]);
	(int) $val1 = $config[0];
	$val1 = $val1-1;
	(int) $val2 = rtrim($config[1]);
	$val2 = $val2-1; // ligne permettant de passer de string a integer
	$log =file("log");
	$compteur = 1;
	foreach ($log as $nbLigne => $valeur) {
		$valeur = rtrim($valeur);
		if ($valeur!="") { // condition permettant d'enlever une ligne
			$valeur=$valeur."\n";
			$donnee=explode(",",$valeur);
			if (!isset($donnee[$val1])){
				$donnee[$val1]=null;
			}
			if (!isset($donnee[$val2])){
				$donnee[$val2]=null;
			}
			echo $compteur." ".$donnee[$val1]." ".$donnee[$val2]."\n";
			$compteur = $compteur +1 ;
		}
	} 
?>
