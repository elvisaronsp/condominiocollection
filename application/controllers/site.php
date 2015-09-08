<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas do site 
 */
 
class site extends CI_Controller {

	public function index() {

		$data["titulo"] = "Condominio collection";
		$this->template->site("index_view", $data);
	}
}
