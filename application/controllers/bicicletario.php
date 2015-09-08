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
 
class bicicletario extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('bicicletario_model', 'bicicletario');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		$data['bicicletas'] = $this->bicicletario->getAll();
		$data["titulo"] = "Bicicletas";
		$this->template->sistema("bicicletas_view", $data);
		
	}

	public function registro($id_bicicleta=NULL)
	{
		if(empty($id_bicicleta)){
			$data['titulo'] ="Cadastro de Bicicleta";
		}
		if(!empty($id_bicicleta)){
			$data['titulo'] ="Alteração de bicicleta";
			$data['bicicleta'] = $this->bicicletario->getById($id_bicicleta);
		}
		
		$this->template->sistema("bicicletas_reg_view", $data);
	}
	

	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	public function bicicletas_submit($id_bicicleta=NULL)
	{
		$dados = $this->input->post();
		if(!empty($id_bicicleta)){
			$dados['id_bicicleta'] = $id_bicicleta;
			$this->bicicletario->update($dados);
			$this->session->set_flashdata("messege","Bicicleta alterada com sucesso !");
		}else{
			$id_bicicleta = $this->bicicletario->insert($dados);
			$this->session->set_flashdata("messege","Bicicleta cadastrada com sucesso !");
		}

		redirect("bicicletario");
	}
	 
	/*
	 * Operações em ajax
	 */
	 
	 public function _ajax()
	 {
		 
	 } 
}