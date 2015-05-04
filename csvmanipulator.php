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
	class CsvController {
		
		/**
		 * true = full; false = empty
		 */	
		private $status;
		
		/**
		 * Source file (path)
		 */
		private $url;
		
		/**
		 * All file
		 */		
		private $src;
		
		/**
		 * Number of row and column 
		 */	
		private $size;
		
		/**
		 * symbol used as a delimiter
		 */	
		private $delimitier;
		
		/**
		 * array of rows in the csv
		 */	
		private $row;
		
		/**
		 * csv incorrect (boolean)
		 */	
		private $error;
		
		/**
		 * csv incorrect (array list)
		 */	
		private $errorlist;
		
		/**
		 * Initialize csv
		 *
		 * @param string $url
	     * @param string $delimitier
		 */
		public function CsvController($url,$delimitier=';') {
			if(file_exists($url)){					
				$this->status=TRUE;
				$src= $this->getcsvtolink($url);
				$this->url = $url;
				$this->src = $src;
				$this->delimitier = $delimitier;
				$this->row = $this->subtrcsv($src);
				$this->size = $this->getcsvsize($src);
				//find error
				$this->errorlist = $this->findcsverror($src);
				if($this->errorlist) $this->error = TRUE;
				else $this->error = FALSE;
			}else{
				$this->CsvControllerempty($url,$delimitier=';');			
			}
		}
		
		/**
		 * Initialize csv empty
		 *
		 * @param string $url
	     * @param string $delimitier
		 */
		public function CsvControllerempty($url,$delimitier=';') {
			$this->status=false;
			$src= '';
			$this->url = $url;
			$this->src = '';
			$this->delimitier = $delimitier;
			$this->size = $this->getcsvsize($src);
			$this->row = array();
			//find error
			$this->errorlist = array();
			$this->error = FALSE;
		}
		
		/**
		 * get status of csv
		 */
		public function publicstatus(){
			return $this->status;
		}
		
		/**
		 * get size of csv
		 */
		public function publicsize(){
			return $this->size;
		}
		
		/**
		 * get contents of csv
		 */
		public function publicfile(){
			return $this->src;
		}
		
		
		/**
		 * open the file and return the contents
		 *
		 * @param string $url
		 */
		private function getcsvtolink($url){
			return file_get_contents($url);
		}
		
		/**
		 * creates an array with rows csv
		 *
		 * @param string $src
		 */
		private function subtrcsv($src){
			$righe=@explode(chr(13), $src);
			return $righe;
		}
		
		/**
		 * calculates the size of csv
		 *
		 * @param string $src
		 */
		private function getcsvsize($src){
			$righe=$this->subtrcsv($src);
			$del=$this->delimitier;
			$colonne=$this->rowtocolumn(0);
			$size[0]=count($righe);
			$size[1]=count($colonne);
			return $size;
		}
		
		/**
		 * calculates the size of csv
		 *
		 * @param string $src
		 */
		private function rowtocolumn($n){
			$del=$this->delimitier;
			$colonne=@explode($del,$this->row[$n]);
			return $colonne;
		}
		
		/**
		 * calculates the number of column of row
		 *
		 * @param integer $n
		 */
		private function rowtocolumnumber($n){
			$del=$this->delimitier;
			$colonne=@explode($del,$this->row[$n]);
			return count($colonne);
		}
		
		/**
		 * checked a correct csv
		 *
		 */
		private function findcsverror(){
			$error=array();
			$r=$this->row;
			for($i=0; $i<count($r)-1; $i++){
				if(!$this->rowcheck($i)){
					array_push($error, $i);
				}
			}
			return $error;
		}
		
		/**
		 * checked a correct row
		 *
		 * @param integer $i
		 */
		private function rowcheck($i){
			$r=$this->row;
			$number=$this->rowtocolumnumber($i);
			if($number > $this->size[1]){
				return FALSE;
			}else{
				return TRUE;
			}
			
		}
		
		/**
		 * if the state of csv
		 *
		 */
		public function checkcsverror(){
			return $this->error;
		}
		
		/**
		 * return a error list
		 *
		 */
		public function publicerrorlist(){
			return $this->errorlist;
		}
		
		/**
		 * save csv in file 
		 *
		 * @param string $name
		 */
		public function savecsv($name=false){
			if($name) $handle = fopen($name, 'w');
			else $handle = fopen($this->url, 'w');
			fwrite($handle, $this->src);
			fclose($handle);
		}	
		
		/**
		 * delete a row 
		 *
		 * @param integer $n
		 */
		public function deleterow($n){
			if($this->status){
				if($n<$this->size[0]){
					unset( $this->row[$n] );
					$this->compactcsv();
				}
			}
		}
		
		/**
		 * revise a source's variable
		 *
		 */		
		private function compactcsv(){
			$this->src=implode(chr(13), $this->row);
		} 
		
		/**
		 * add a new row in csv
		 *
		 * @param array $src
		 */
		public function addrowarray($str){
			$nn=count($str);
			if($nn<=$this->size[1] || (!$this->status)){
				$newrow=implode($this->delimitier,$str);
				if($this->status)$this->src .=chr(13).chr(10).$newrow;		
				else $this->src .=$newrow;	
				$this->row = $this->subtrcsv($this->src);
				$this->size[0]++;
				if(!$this->status){					
					$this->size = $this->getcsvsize($this->src);
					$this->status=true;
					$this->errorlist = $this->findcsverror($this->src);
					if($this->errorlist) $this->error = TRUE;
					else $this->error = FALSE;
				}
			}
		}
		
		/**
		 * add a new row in csv
		 *
		 * @param string $src
		 */
		public function addrow($str){
			$ar=@explode($this->delimitier,$str);
			$this->addrowarray($ar);			
		}
		
		/**
		 * delete column in a single row
		 *
		 * @param integer $cn
		 * @param integer $n
		 */
		private function deletecolumninrow($n,$cn){
			if($this->status){
				$a=@explode(';',$this->row[$cn]);
				unset($a[$n]);
				$b=implode(';',$a);
				$this->row[$cn]=$b;
			}
		}
	
		/**
		 * delete row in a csv
		 *
		 * @param integer $n
		 */
		public function deletecolumnin($n){
			if($this->status){
				for($i=0; $i<$this->size[0]; $i++){
					$this->deletecolumninrow($n,$i);
				}
				$this->compactcsv();
			}
		}
	} 
 
?>
