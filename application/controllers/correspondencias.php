<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de correspondencias 
 */
 
class correspondencias extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('moradores_model', 'moradores');
		$this->load->model('correspondencias_model', 'correspondencias');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index($unidade=NULL) {
		/*
		 * lista todos as correspondencias cadastradas
		 */
		$filtro[] =  array("campo"=>"correspondencias.status", "valor"=>0);
		if(!empty($unidade))
			$filtro[] =  array("campo"=>"correspondencias.unidade", "valor"=>$unidade);
		$data['tipos'] = $this->correspondencias->getTypes();
		$data['correspondencias'] = $this->correspondencias->getAll($filtro);
		$data["titulo"] = "Correspondências";
		$this->template->sistema("correspondencias_view", $data);
	}
	
	public function registro( $id_correspondencia = NULL)
	{
		/*
		 *  informações de uma única correspondencia
		 */ 
		if ($id_correspondencia!=NULL){
			$correspondencia = $this->correspondencias->getById($id_correspondencia);
			
			$data['correspondencia'] = $correspondencia;
			$data["titulo"] = $correspondencia['bloco']."-".$correspondencia['unidade']." Correspondência: ".date("d/m/Y H:i",strtotime($correspondencia['data']));
		}else{
			$data['titulo'] = "Nova Correspondência";
		}
	
		$this->template->sistema("correspondencias_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function correspondencias_submit($id_correspondencia = NULL)
	 {
	 	/*
		 * trata as informações da Correspondência a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();
		
		 $dados['data'] = implode('-',array_reverse(explode('/',substr($dados['data'],0,10)))).substr($dados['data'], 10);
		 if ($id_correspondencia!=NULL){
		 	
		 	$dados['id_correspondencia'] = $id_correspondencia;
		 	$this->correspondencias->update($dados);
			$this->session->set_flashdata('messege',"Correspondência Alterada com sucesso !");
		 }else{
		 	$id_correspondencia = $this->correspondencias->insert($dados);
			/*
			 *  inicio de operações para enviar e-mail
			 */ 
				$this->load->model('email_model');
				$this->load->library('email');
				
				$morador = $this->moradores->getByUnidade($dados['unidade']); // informações do morador responsável da unidade
				$correspondencia = $this->correspondencias->getById($id_correspondencia); // informações da correspondencia
				if ($correspondencia['quantidade']>1) $s = "s"; else $s = ""; // verificando a quantidade
				
				/*
				 *  iniciando a mensagem
				 */ 
				$mensagem = nl2br("Você recebeu ".$correspondencia['quantidade']." nova$s correspondência$s em sua caixa:
				Destinatário: <strong>".$correspondencia['destinatario']."</strong>
				Tipo da correspondência: <strong>". $correspondencia['tipo']."</strong>
				Data: ".date('d/m/Y H:i',strtotime($correspondencia['data'])). 
				(!empty($correspondencia['obeservacoes'])?"Observações: ".$correspondencia['observacoes']:""));
				
				
				$this->email_model->EnviarEmail("noreply@condominiocollection.com.br", "Informe Condominio Collection", $morador['email'], "Nova correspondência em sua caixa !", $mensagem);
			/*
			 * fim de operações para enviar o email
			 */ 
		 	//$this->session->set_flashdata('messege',"Correspondência Cadastrada com sucesso !");
		 }	 
		// $url = (strpos($this->input->server('HTTP_REFERER', TRUE),"correspondencias/")==TRUE?"correspondencias":"moradores/registro/".$dados['morador']."#correspondencias"); // verifica de onde veio o formulário e redireciona para a página.
		 redirect('correspondencias');
	 }

	public function entregar($id_correspondencia=NULL)
	{
		$dados['morador'] = $this->input->post('morador');
		$dados['status'] = 1;
		$dados['id_correspondencia'] = $id_correspondencia;
		$this->correspondencias->update($dados);
		$this->session->set_flashdata('messege',"Correspondência entregue com sucesso !");
		redirect('correspondencias');
	}
	/*
	 * Operações em ajax
	 */
	 
	 public function busca_moradores()
	 {
		 if ($_GET["term"])
			$filtro[] = array("campo" => 'nome', "valor" => mysql_real_escape_string($_GET["term"]), 'operador' => 'like');
		$morador = $this->moradores->getAll($filtro);
		$filtro[0]['campo'] = 'moradores_familias.nome';
		$familia = $this->moradores->getAllFamilia($filtro);
		
		if (!empty($morador) && !empty($familia))
			$busca = array_merge($morador,$familia);
		else if (!empty($morador) && empty($familia))
			$busca = $morador;
		else if (empty($morador) && !empty($familia))
			$busca = $familia;
		else
			$busca = NULL;
		echo json_encode($busca);
	 }
	 
}
 