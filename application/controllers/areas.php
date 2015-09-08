<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de areas 
 */
 
class areas extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('areas_model', 'areas');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todas as areas cadastradas
		 */ 
		$data['areas'] = $this->areas->getAll();
		$data["titulo"] = "Áreas";
		$this->template->sistema("areas_view", $data);
	}
	
	public function registro( $id_area = NULL)
	{
		/*
		 *  informações de uma única area
		 */ 
		if ($id_area!=NULL){
			$area = $this->areas->getById($id_area);
			$data['area'] = $area;
			$data["titulo"] = ucwords($area['area']);
		}else{
			$data['titulo'] = "Nova Área";
		}
		$this->template->sistema("areas_reg_view", $data);
	}
	
	public function utilizacao()
	{
		/*
		 * Cadastrar Utilização de chave 
		 */ 
		$filtro[] = array("campo"=>"visibilidade","valor"=>0);
		$data['areas'] = $this->areas->getAll($filtro);
		$this->load->model("moradores_model","moradores");
		$unidade = $this->session->userdata('unidade');
		$morador[] = $this->moradores->getByUnidade($unidade['id_unidade']);
		$familia = $this->moradores->getAllFamiliaByMorador($morador[0]['id_morador']);
		 
			if(!empty($familia)){
				
			 	$moradores = array_merge($morador,$familia);
			}else{
				$moradores = $morador;
			}
		$data['moradores'] = $moradores;
		$data["titulo"] = "Solicitar Chave";
		$this->template->sistema("areas_utilizacao_reg_view", $data);
	}
	
	public function relatorio_utilizacao_areas()
	{
		/*
		 * Cadastrar Utilização de chave 
		 */ 
		if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$data['utilizacoes'] = $this->areas->getAllUtilizacao($filtro);
		$data["titulo"] = "Relatório de utilização das Áreas";
		$this->template->sistema("areas_utilizacao_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function areas_submit($id_area = NULL)
	 {
	 	/*
		 * trata as informações da area a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();
		 if ($id_area!=NULL){
		 	
		 	$dados['id_area'] = $id_area;
		 	$this->areas->update($dados);
			$this->session->set_flashdata('messege',"Área Alterada com sucesso !");
		 }else{
		 	$id_area = $this->areas->insert($dados);
		 	$this->session->set_flashdata('messege',"Área Cadastrada com sucesso !");
		 }
		 redirect('areas');
	 }
	 
	 public function utilizacao_submit($id_utilizacao=NULL)
	 {
		 /*
		 * trata as informações de utilização da area a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();
		 if(!empty($dados['data_retirada']))
		 	$dados['data_retirada'] = implode('-',array_reverse(explode('/',substr($dados['data_retirada'],0,10)))).substr($dados['data_retirada'], 10);
		 if(!empty($dados['data_reserva']))
			$dados['data_reserva'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1',$dados['data_reserva']);
		 
		 if ($id_utilizacao!=NULL){
		 	$dados['id_utilizacao'] = $id_utilizacao;
		 	$this->areas->updateUtilizacao($dados);
			$this->session->set_flashdata('messege',"Utilização de Área Alterada com sucesso !");
		 }else{
		 	$id_area = $this->areas->insertUtilizacao($dados);
		 	$this->session->set_flashdata('messege',"Utilização de Área Cadastrada com sucesso !");
		 }
		 redirect('areas/relatorio_utilizacao_areas');
	 }
	 
	 public function utilizacao_finalizar($id_utilizacao)
	 {
	 	/*
		 * finaliza a operação de utilização .
		 */ 
	 	$dados = $this->input->post();
		$this->load->helper('date');
		$now = time($dados['data_entrega']);
		$dados['data_entrega'] = unix_to_human($now,false,'br'); // formatando para formato mysql
		$dados['id_utilizacao'] = $id_utilizacao;
		$this->areas->updateUtilizacao($dados);
		$this->session->set_flashdata('messege',"Utilização de Área Alterada com sucesso !");
		redirect('areas/relatorio_utilizacao_areas');
	 }
	/*
	 * Operações em ajax
	 */
	 
	 
	 public function verifica_disponibilidade()
	 {
		 $dados = $this->input->post();
		 foreach($dados as $key=>$value){
		 	if ($key=='data_reserva') $value = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1',$value); // convertendo data para formato americano
		 	$filtro[] = array("campo"=>$key,"valor"=>$value);
		 }
		 
		$utilizacao = $this->areas->getAllUtilizacao($filtro);
		//echo $this->db->last_query();
		echo json_encode($utilizacao);
	 }
	 
}
 