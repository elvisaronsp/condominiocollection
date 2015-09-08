<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as operações de camera
 */
 
class camera extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
	}
	
	public function tirar_foto()
	{
		$filename = date('YmdHis') . '.jpg'; // nome do arquivo temporario
		$result = file_put_contents("uploads/temp/".$filename, file_get_contents('php://input') );
		if (!$result) {
			echo "ERROR: Failed to write data to $filename, check permissions\n";
			exit();
		}
		
		$url = base_url("uploads/temp/".$filename);
		echo "$url\n";
	}
}