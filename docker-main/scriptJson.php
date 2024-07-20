#!/usr/bin/php
<?php
	$lines = file("JSONtmp");
	$count=0;
	foreach ($lines as $key => $valeur) {
		$donne = explode(" ", $valeur);
		foreach ($donne as $keyExplo => $value) {
		    if ($keyExplo>10 && $value!="-") {
		        if (!isset($tablo[$count])) {
					$tablo[$count]=$value;
				}else{
					$tablo[$count]=$tablo[$count]." ".$value;
				} 
			}
		}
		$count=$count+1;
		if(isset($donne[$key])) {
			echo $donne[3].",".$donne[4].",".$donne[1]." ".$donne[2].",".$donne[5].",".$donne[6].",".$donne[7].",".$donne[8].",".$donne[9].",".$tablo[$key];
		}
	}
?>
