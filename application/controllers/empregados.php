<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de Empregado 
 */
 
class empregados extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('empregados_model', 'empregados');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todos as Empregado cadastradas
		 */ 
		if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$data['empregados'] = $this->empregados->getAll($filtro);
		$data["titulo"] = "Empregados domésticos";
		$this->template->sistema("empregados_view", $data);
	}
	
	public function registro( $id_empregado = NULL)
	{
		/*
		 *  informações de um único Empregado
		 */ 
		if ($id_empregado!=NULL){
			$empregado = $this->empregados->getById($id_empregado);
			
			$data['empregado'] = $empregado;
			$data["titulo"] = $empregado['bloco']."-".$empregado['unidade']." Doméstico: ".$empregado['nome'];
		}else{
			$data['titulo'] = "Novo Empregado doméstico";
		}
		$this->template->sistema("empregados_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function empregados_submit($id_empregado = NULL)
	 {
	 	/*
		 * trata as informações do Empregado a partir das informações do formulário
		 */ 
		 
		 $dados = $this->input->post();
		 
		 $file = $dados['foto'];
		 unset($dados['foto']);
		 
		 $dados['dias'] = implode(",", $dados['dias']);
		 if ($id_empregado!=NULL){
		 	$dados['id_empregado'] = $id_empregado;
		 	$this->empregados->update($dados);
			$this->session->set_flashdata('messege',"Empregado Alterado com sucesso !");
		 }else{
		 	$id_empregado = $this->empregados->insert($dados);
		 	$this->session->set_flashdata('messege',"Empregado Cadastrado com sucesso !");
		 }
		 
		 if($file){
		 	echo $file;
			$dados['id_empregado'] = $id_empregado;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "empregado_".$id_empregado.".".$ext;
			if(copy(str_replace(base_url(),"",$file),"uploads/empregados/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;

			$this->empregados->update($dados);
									
		}
		 
		 redirect('empregados');
	 }
	/*
	 * Operações em ajax
	 */
	 
}
 