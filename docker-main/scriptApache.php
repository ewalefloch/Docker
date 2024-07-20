#!/usr/bin/php
<?php
    $ligneFic = file("Apachetmp");
    $count=0;
    foreach ($ligneFic as $nbLigne => $ValLig) {
        rtrim($ValLig);
        $sep1 = explode(" ", "$ValLig");
	foreach ($sep1 as $keyExplo => $value) {
            if ($keyExplo>9 && $value!="-") {
                if (!isset($tablo[$count])) {
			$tablo[$count]=$value;
		}else{
			$tablo[$count]=$tablo[$count]." ".$value;
		}
            }else {
                $save=$value;
            }
        }
        $count=$count+1;
        if ($sep1[2]=="-"){
        	$sep1[2]="null";
        }
	echo $sep1[0].",".$sep1[2].",".$sep1[3]." ".$sep1[4].",".$sep1[5].",".$sep1[6].",".$sep1[7].",".$sep1[8].",".$sep1[9].",".$tablo[$nbLigne];
    }
    echo "\n";
?>
