<?php 
/**
* @author joel.medeiros
* @email contato@joelmedeiros.com.br
* @copyright 2014
*/
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de reparos 
 */
 
class cadastros extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('veiculos_model', 'veiculos');
		$this->load->model('moradores_model', 'moradores');
		$this->load->model('proprietarios_model', 'proprietarios');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		
		$data["titulo"] = "Painel de Cadastros";
		$this->template->sistema("cadastros_view", $data);
		
	}
	

	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	public function logica($value='')
	{
		
	}
	 
	/*
	 * Operações em ajax
	 */
	 
	 public function _ajax()
	 {
		 
	 } 
}