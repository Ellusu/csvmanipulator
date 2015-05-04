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
	$l=new CsvController('csvtest/file02.csv',';');
	
	//add in csv a string
	print_r($l->publicsize());
	
	//save a csv backup copy
	$l->savecsv('csvtest/file02_bkp.csv');
	//delete row
	$l->deleterow(1);
	
	//delete column
	$l->deletecolumnin(2);
	
	//save in original file	
	$l->savecsv();
	
?>
