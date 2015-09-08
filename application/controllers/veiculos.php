<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de veiculos 
 */
 
class veiculos extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('veiculos_model', 'veiculos');
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('moradores_model', 'moradores');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todos os veículos cadastrados
		 */ 
		$data['veiculos'] = $this->veiculos->getAll();
		$data["titulo"] = "Veículos";
		$this->template->sistema("veiculos_view", $data);
	}
	
	public function registro( $id_veiculo = NULL)
	{
		/*
		 *  informações de um único veículo
		 */ 
		if ($id_veiculo!=NULL){
			$veiculo = $this->veiculos->getById($id_veiculo);
			if ($veiculo!=NULL){
				$data['veiculo'] = $veiculo;
				$data["titulo"] = $veiculo['tipo']." do ".$veiculo['bloco']."-".$veiculo['unidade'];
			}else{
				$this->session->set_flashdata("error","Veículo não localizado !");
				redirect('veiculo');
			}
		}else{
			$data['titulo'] = "Novo Veículo";
		}
		$data['veiculos_tipos'] = $this->veiculos->getTypes();
		$this->template->sistema("veiculos_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function veiculos_submit($id_veiculo = NULL)
	 {
	 	/*
		 * trata as informações do veículo a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();

		 if ($id_veiculo!=NULL){
		 	$dados['id_veiculo'] = $id_veiculo;
		 	$this->veiculos->update($dados);
			$this->session->set_flashdata('messege',"Veículo Alterado com sucesso !");
		 }else{
		 	$id_veiculo = $this->veiculos->insert($dados);
			$this->session->set_flashdata('messege',"Veículo Cadastrado com sucesso !");
		 }	
		 $url = (strpos($this->input->server('HTTP_REFERER', TRUE),"veiculos/")==TRUE?"veiculos":"moradores/registro/".$dados['morador']."#veiculos"); // verifica de onde veio o formulário e redireciona para a página.
		 redirect($url);
	 }
	 
	 	 
	 public function remove_veiculo($id_veiculo) // remove um veículo
	 {
		 $this->veiculos->delete($id_veiculo);
	 }
	/*
	 * Operações em ajax
	 */
	 
}
 