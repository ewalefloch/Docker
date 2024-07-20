#!/usr/bin/php
<?php
    $lines =file("CVStmp");
    foreach ($lines as $nu => $val) {
        $donne = explode(";" , $val);
        $max=count($donne);
        $mo="";
        for ($num =10; $num<$max; $num++){
        	$mo=($mo . $donne[$num]);
        }
        if ($donne[1]==null){
        	$donne[1]="null";
        }
        echo($donne[0].",".$donne[1].",".$donne[2].",".$donne[3].",".$donne[4].",".$donne[5].",".$donne[6].",".$donne[7].",".$donne[8].",".$donne[9].",".$mo);
        
    }
?>

