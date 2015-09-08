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
 
class moradores extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('moradores_model', 'moradores');
		$this->load->model('veiculos_model', 'veiculos');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		
		if($this->session->userdata('tipo') != 4)
			$moradores = $this->moradores->getAll(); // traz todos os moradores

		if($this->session->userdata('tipo') == 4)
			$moradores[]= $this->moradores->GetMoradorByUser($this->session->userdata("id_usuario"));
		
		$familiares = NULL;
		if (!empty($moradores)){
			$morador_old = NULL;
			foreach ($moradores as $morador){
				if ($morador['id_unidade']!=$morador_old){
					$familiares[$morador['id_morador']] = $this->moradores->getAllFamiliaByMorador($morador['id_morador']);// traz todos os familiares dos moradores
				}
				$morador_old = $morador['id_unidade'];
			}
		}
		$data['familiares'] = $familiares; 
		$data['moradores'] = $moradores;
		$data["titulo"] = "Moradores";
		$this->template->sistema("moradores_view", $data);
	}
	
	public function registro($id_responsavel=null) {
		$data["titulo"] = "Cadastro de novo morador";

		if ($id_responsavel!=NULL){
			$morador = $this->moradores->getById($id_responsavel); // traz as informações do morador

			if($morador==NULL){
				$this->session->set_flashdata('error',"Morador não localizado !");
				redirect('moradores');
			}
			if ($morador!=NULL){
				$familiares = $this->moradores->getAllFamiliaByMorador($id_responsavel);// traz todos os familiares dos morador
				$data['familiares'] = $familiares;
				$data['morador'] = $morador;
				$data['veiculos_tipos'] = $this->veiculos->getTypes();
				$data["titulo"] = $morador['bloco']." -".$morador['unidade']." Responsável ".$morador['nome'];
			}
		}
		
		$data['tipos'] = $this->moradores->getTypes();
		$this->template->sistema("moradores_reg_view", $data);
	}
	
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 
	public function moradores_submit($id_morador=NULL)
	{
		/*
		 * operações com as informações dos moradores
		 */ 
		$dados = $this->input->post();
		
		if($dados['data_nascimento'])
			$dados['data_nascimento'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_nascimento']); // convertendo data para formato americano
		if($dados['data_moradia'])
			$dados['data_moradia'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_moradia']); // convertendo data para formato americano
			
			
		$file = $dados['foto'];
		unset($dados['foto']);

		if ($id_morador!=NULL){
			// Atualizar informações do morador
			$dados['id_morador'] = $id_morador;
			$this->moradores->update($dados);
			$this->session->set_flashdata('messege',"Morador Atualizado com sucesso !");
		}

		if($id_morador==NULL){
			// Cadastrar informações do morador
			$unidade['id_unidade'] = $dados['unidade'];
			$unidade['status'] = 1;
			$this->unidades->update($unidade);
			$id_morador = $this->moradores->insert($dados);
			$this->session->set_flashdata('messege',"Morador Cadastrado com sucesso !");
		}

		if($file){
			$dados['id_morador'] = $id_morador;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "morador_".$id_morador.".".$ext;
			if(copy($file,"uploads/moradores/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;
			$this->moradores->update($dados);
		}

		redirect('moradores/registro/'.$id_morador);
	}
	
	public function familiares_submit($id_morador, $id_familiar = NULL)
	{
		/*
		 * Operações com as informações dos familiares dos moradores
		 */ 
		 $dados = $this->input->post();
		 $dados['data_nascimento'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_nascimento']); // convertendo data para formato americano
		 $dados['id_morador'] = $id_morador;

		 $file = $dados['foto'];
		 unset($dados['foto']);
		 if ($id_familiar==NULL){
		 	/*
			 * Cadastrar informações do familiar do morador
			 */ 
		 	$id_familiar = $this->moradores->insertFamiliar($dados);
			$this->session->set_flashdata('messege',"Familiar de morador Cadastrado com sucesso !");
		 }

		 if ($id_familiar!=NULL){
		 	/*
			 * Atualizar informações do familiar do morador
			 */
		 	$dados['id_familia'] = $id_familiar;
			$this->moradores->updateFamiliar($dados);
			$this->session->set_flashdata('messege',"Familiar de morador Alterado com sucesso !");
		 }

		 if($file){
		 	
			$dados['id_familia'] = $id_familiar;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "familiar_".$id_familiar.".".$ext;
			
			if(copy($file,"uploads/familiares/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;
			
			$this->moradores->updateFamiliar($dados);
			///echo $this->db->last_query();
			
		}
		 redirect('moradores/registro/'.$id_morador);
	}
	
	public function familiares_delete($id_morador,$id_familiar)
	{
		$dados['id_familia'] = $id_familiar;
		$this->moradores->deleteFamiliar($dados);
		$this->session->set_flashdata('messege',"Familiar de morador removido com sucesso !");
		redirect('moradores/registro/'.$id_morador);		
	}
	
	/*
	 * Operações em ajax
	 */
	 
	 public function verifica_cpf()
	 {
		 $dados = $this->input->post();
		 
		 foreach($dados as $key=>$value){
		 	$filtro[] = array("campo"=>$key,"valor"=>$value);
		 }
		 
		 echo $this->moradores->getAll($filtro);		 
	 }
	 
	 public function moradorByUnidade()
	 {
	 	
	 	$unidade = $this->input->post('unidade');
		$morador = $this->moradores->getByUnidade($unidade);
		$familia = $this->moradores->getAllFamiliaByMorador($morador['id_morador']);
		if(empty($familia)) $busca = $morador;
		if(!empty($familia)) $busca = array_merge($morador,$familia);
				

		 echo json_encode($busca);
	 }
	 
	 public function busca_morador()
	 {
	 	if ($_GET["term"])
			$filtro[] = array("campo"=>'nome',"valor"=>mysql_real_escape_string($_GET["term"]),'operador'=>'like');
		$busca= $this->moradores->getAll($filtro);
		echo json_encode($busca);
	 }

}
