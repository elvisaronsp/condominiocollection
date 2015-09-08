<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de serviços 
 */
 
class servicos extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('servicos_model', 'servicos');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todos os serviços cadastrados
		 */ 
		$data['servicos'] = $this->servicos->getAll();
		$data["titulo"] = "Serviços";
		$this->template->sistema("servicos_view", $data);
	}
	
	public function reg_servico( $id_servico = NULL)
	{
		/*
		 *  informações de um único serviço
		 */ 
		
		if ($id_servico!=NULL){
			
			$servico = $this->servicos->getById($id_servico);
			if ($servico!=NULL){
				$data['servico'] = $servico;
				$data["titulo"] = $servico['servico'];
			}else{
				$this->session->set_flashdata('error','Serviço não localizado !');
				redirect('servicos');
			}
		}else{
			$data['titulo'] = "Novo Serviço";
		}
		$this->template->sistema("servicos_reg_view", $data);
	}
		
	public function prestadores() {
		/*
		 * lista todos os prestadores de serviços 
		 */ 
		 if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$data['prestadores'] = $this->servicos->getAllprestadores($filtro);
		$data["titulo"] = "Prestadores de Serviços";
		$this->template->sistema("servicos_prestadores_view", $data);
	}
	
	public function reg_prestador($id_prestador=NULL)
	{
		if ($id_prestador!=NULL){
			$prestador = $this->servicos->getPrestadorById($id_prestador);
			if ($prestador!=NULL){
				$data['prestador'] = $prestador;
				$data['titulo'] = $prestador['nome'].' - '.$prestador['empresa'];
			}else{
				$this->session->set_flashdata('error','Prestador de serviços não localizado !');
				redirect('servicos/prestadores/');
			}
		}else{
			$data['titulo'] = "Novo Prestador de Serviços";
		}
		
		$data['servicos'] = $this->servicos->getAll();
		
		$this->template->sistema("servicos_prestadores_reg_view", $data);
		
		
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function servicos_submit($id_servico = NULL)
	 {
		 $dados = $this->input->post();
		 if ($id_servico!=NULL){
		 	$dados['id_servico'] = $id_servico;
		 	$this->servicos->update($dados);
			$this->session->set_flashdata('messege',"Serviço Alterado com sucesso !");
		 }else{
		 	$this->servicos->insert($dados);
			$this->session->set_flashdata('messege',"Serviço Cadastrado com sucesso !");
		 }
		 redirect('servicos');
	 }	
	 public function prestadores_submit($id_prestador = NULL)
	 {
		 $dados = $this->input->post();
		 if ($dados['servico']==NULL){
		 	$servico['servico'] = $dados['nome_exibe'];
		 	$dados['tipo'] = $this->servicos->insert($servico);
			
		 }else{
		 	$dados['tipo'] = $dados['servico'];
		 }
		 	
		 $file = $dados['foto'];
		 unset($dados['foto']);
		 unset($dados['doc']);
		 unset($dados['servico']);
		 unset($dados['nome_exibe']);

         $dados['data_inicio'] = implode('-',array_reverse(explode('/',substr($dados['data_inicio'],0,10)))).substr($dados['data_inicio'], 10);
		 if ($dados['data_fim']!=NULL){
		 	$dados['data_fim'] = implode('-',array_reverse(explode('/',substr($dados['data_fim'],0,10)))).substr($dados['data_fim'], 10);
		 }
		 unset($dados['servico']);
		

		 if ($id_prestador!=NULL){
		 	$dados['id_prestador'] = $id_prestador;
		 	$this->servicos->updatePrestador($dados);
			$this->session->set_flashdata('messege',"Prestador de Serviços Alterado com sucesso !");
		 }else{
		 	$id_prestador = $this->servicos->insertPrestador($dados);
			$this->session->set_flashdata('messege',"Prestador de Serviços Cadastrado com sucesso !");
		 }
		 
		
		if($file){
			$dados['id_prestador'] = $id_prestador;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "prestador_".$id_prestador.".".$ext;
			if(copy($file,"uploads/prestadores/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;
			
			$this->servicos->updatePrestador($dados);
			
		}

		 redirect('servicos/prestadores');
	 }
	 
	 public function finalizar_prestador($id_prestador)
	 {
	 	 $dados['data_fim'] = date('Y-m-d H:i:s'); 
		 $dados['id_prestador'] = $id_prestador;
		 $this->servicos->updatePrestador($dados);
		 $this->session->set_flashdata('messege',"Serviço finalizado com sucesso !");
		 redirect('servicos/prestadores');
	 }
	/*
	 * Operações em ajax
	 */
	 public function busca_servicos()
	 {
	 	if ($_GET["term"])
			$filtro[] = array("campo"=>'servico',"valor"=>mysql_real_escape_string($_GET["term"]),'operador'=>'like');
		$busca= $this->servicos->getAll($filtro);
		echo json_encode($busca);
	 }
}
 