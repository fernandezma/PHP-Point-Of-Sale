<?php session_start(); ob_start();
#### Roshan's very simple code to export data to excel   
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you find any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://php-ajax-guru.blogspot.com

class ExportExcel
{
	//variable of the class
	var $titles=array();
	var $all_values=array();
	var $filename;

	//functions of the class
	function ExportExcel($f_name) //constructor
	{
		$this->filename=$f_name;
	}
	function setHeadersAndValues($hdrs,$all_vals) //set headers and query
	{
		$this->titles=$hdrs;
		$this->all_values=$all_vals;
	}
	function GenerateExcelFile() //function to generate excel file
	{

		foreach ($this->titles as $title_val) 
 		{ 
 			$header .= $title_val."\t"; 
 		} 
 		for($i=0;$i<sizeof($this->all_values);$i++) 
 		{ 
 			$line = ''; 
 			foreach($this->all_values[$i] as $value) 
			{ 
 				if ((!isset($value)) OR ($value == "")) 
				{ 
 					$value = "\t"; 
 				} //end of if
				else 
				{ 
 					$value = str_replace('"', '""', $value); 
 					$value = '"' . $value . '"' . "\t"; 
 				} //end of else
 				$line .= $value; 
 			} //end of foreach
 			$data .= trim($line)."\n"; 
 		}//end of the while 
 		$data = str_replace("\r", "", $data); 
		if ($data == "") 
 		{ 
 			$data = "\n(0) Records Found!\n"; 
 		} 
		//echo $data;
		header("Content-type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=$this->filename"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		print "$header\n$data";  
	}
}
#### Roshan's very simple code to export data to excel   
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://php-ajax-guru.blogspot.com
	//code to download the data of report in the excel format
	$fn=$_GET['fn'].".xls";
	include_once("class.export_excel.php");
	//create the instance of the exportexcel format
	$excel_obj=new ExportExcel("$fn");
	//setting the values of the headers and data of the excel file 
	//and these values comes from the other file which file shows the data
	$excel_obj->setHeadersAndValues($_SESSION['report_header'],$_SESSION['report_values']); 
	//now generate the excel file with the data and headers set
	$excel_obj->GenerateExcelFile();
	//print_r($_SESSION['report_values']);

?>
