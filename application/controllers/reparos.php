<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de reparos 
 */
 
class reparos extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('reparos_model', 'reparos');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todas as reparos cadastradas
		 */ 
		if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$data['reparos'] = $this->reparos->getAll($filtro);
		$data["titulo"] = "Reparos";
		$this->template->sistema("reparos_view", $data);
	}
	
	public function registro( $id_reparo = NULL)
	{
		/*
		 *  informações de uma única reparo
		 */ 
		if ($id_reparo!=NULL){
			$reparo = $this->reparos->getById($id_reparo);
			
			$data['reparo'] = $reparo;
			$data["titulo"] = $reparo['bloco']."-".$reparo['unidade']." Reparo: ".date("d/m/Y",strtotime($reparo['data']));
		}else{
			$data['titulo'] = "Nova Reparo";
		}
		$data['tipos'] = $this->reparos->getTypes();
		$this->template->sistema("reparos_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function reparos_submit($id_reparo = NULL)
	 {
	 	/*
		 * trata as informações da reparo a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();
		 $dados['data'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data']); // convertendo data para formato americano
		 if ($id_reparo!=NULL){
		 	
		 	$dados['id_reparo'] = $id_reparo;
		 	$this->reparos->update($dados);
			$this->session->set_flashdata('messege',"Reparo Alterada com sucesso !");
		 }else{
		 	$dados['solicitante'] = $this->session->userdata('id_usuario');
		 	$id_reparo = $this->reparos->insert($dados);
		 	$this->session->set_flashdata('messege',"Reparo Cadastrada com sucesso !");
		 }
		 redirect('reparos');
	 }
	/*
	 * Operações em ajax
	 */
	 
}
 