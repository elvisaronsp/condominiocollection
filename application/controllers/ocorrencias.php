<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de ocorrencias 
 */
 
class ocorrencias extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('ocorrencias_model', 'ocorrencias');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todas as ocorrencias cadastradas
		 */ 
		if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$data['ocorrencias'] = $this->ocorrencias->getAll($filtro);
		$data["titulo"] = "Ocorrências";
		$this->template->sistema("ocorrencias_view", $data);
	}
	
	public function registro( $id_ocorrencia = NULL)
	{
		/*
		 *  informações de uma única ocorrencia
		 */ 
		if ($id_ocorrencia!=NULL){
			$ocorrencia = $this->ocorrencias->getById($id_ocorrencia);
			
			$data['ocorrencia'] = $ocorrencia;
			$data["titulo"] = $ocorrencia['bloco']."-".$ocorrencia['unidade']." ocorrência: ".date("d/m/Y",strtotime($ocorrencia['data_ocorrencia']));
		}else{
			$data['titulo'] = "Nova Ocorrência";
		}
		$data['tipos'] = $this->ocorrencias->getTypes();
		$this->template->sistema("ocorrencias_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function ocorrencias_submit($id_ocorrencia = NULL)
	 {
	 	/*
		 * trata as informações da ocorrencia a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();
		 $dados['data_ocorrencia'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_ocorrencia']); // convertendo data para formato americano
		 if ($id_ocorrencia!=NULL){
		 	
		 	$dados['id_ocorrencia'] = $id_ocorrencia;
		 	$this->ocorrencias->update($dados);
			$this->session->set_flashdata('messege',"Ocorrência Alterada com sucesso !");
		 }else{
		 	$dados['id_usuario'] = $this->session->userdata('id_usuario');
		 	$id_ocorrencia = $this->ocorrencias->insert($dados);
		 	$this->session->set_flashdata('messege',"Ocorrência Cadastrada com sucesso !");
		 }
		 redirect('ocorrencias');
	 }
	/*
	 * Operações em ajax
	 */
	 
}
 