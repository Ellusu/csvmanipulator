<?php

/**
 * Csv Controller
 * 
 * @author Matteo Enna, Cagliari, Italy, info@matteoenna.it
 * http://www.matteoenna.it
 * @copyright LGPL
 * @version 0.9.6
 *
 */
	include('csvmanipulator.php');
	
	//settings
	$l=new CsvController('csvtest/file01.csv',';');
	
	//add in csv a string
	$l->addrow('a;b;c;d');
	
	for($i=0; $i<5; $i++){
		
		//add in csv ad array
		$array=array($i+1,$i+2,$i+3,$i+4);
		$l->addrowarray($array);
	}
	
	//view file
	echo $l->publicfile();
	
	//save the new csv
	$l->savecsv();
?>
