<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de visitantes 
 */
 
class visitantes extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('moradores_model', 'moradores');
		$this->load->model('visitantes_model', 'visitantes');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todos os visitantes cadastradas
		 */ 
		if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$visitantes = $this->visitantes->getAll($filtro);
		$data['visitantes'] = $visitantes;
		if(!empty($visitantes)){
			foreach($visitantes as $visitante){
				$pessoas[$visitante['id_visitante']] = $this->visitantes->getPessoasByVisitante($visitante['id_visitante']);
			}
			if (!empty($pessoas)) $data['pessoas'] = $pessoas;
		}
		$data["titulo"] = "Visitantes";
		$this->template->sistema("visitantes_view", $data);
	}

	public function autorizados()
	{
		/*
		 * Lista todos os visitantes autorizados
		 */ 
		if ($this->session->userdata('unidade')){
			$unidade = $this->session->userdata('unidade');
			$filtro[] = array("campo"=>"id_unidade", "valor"=>$unidade['id_unidade']);
		}else{
			$this->session->set_flashdata('messege',"Você deve buscar pela Torre e unidade !");
		 	redirect('unidades');
		}
		$visitantes_autorizados =  $visitantes = $this->visitantes->getAllAutorizados($filtro);
		$data['autorizados'] = $visitantes_autorizados;
		$data["titulo"] = "Visitantes autorizados";
		$this->template->sistema("visitantes_autorizados_view", $data);
		
	}
	
	public function registro( $id_visitante = NULL)
	{
		/*
		 *  informações de um único visitante
		 */ 
		if ($id_visitante!=NULL){
			$visitante = $this->visitantes->getById($id_visitante);
			
			$data['visitante'] = $visitante;
			$data["titulo"] = $visitante['bloco']."-".$visitante['unidade']." Visitante: ".$visitante['visitante'];
		}else{
			$data['titulo'] = "Novo Visitante";
		}
		$this->template->sistema("visitantes_reg_view", $data);
	}
	
	public function autorizado_registro()
	{
		/*
		 *  informações de um único visitante autorizado
		 */ 
		$data['titulo'] = "Nova Autorização de Visitante";
		$this->template->sistema("visitantes_autorizados_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function visitantes_submit($id_visitante = NULL)
	 {
	 	/*
		 * trata as informações do Visitante a partir das informações do formulário
		 */ 
		 $dados = $this->input->post();
		 if (!empty($dados['nome']))
			 $pessoas = $dados['nome'];
		 unset($dados['nome']);
		$file = $dados['foto'];
		unset($dados['foto']);
		
		 
		 
		 $dados['data'] = implode('-',array_reverse(explode('/',substr($dados['data'],0,10)))).substr($dados['data'], 10);
		 unset($dados['doc']);
		 $id_visitante = $this->visitantes->insert($dados);
		 
		if($file){
			$dados['id_visitante'] = $id_visitante;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "visitante_".$id_visitante.".".$ext;
			if(copy($file,"uploads/visitantes/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;
			
			$this->visitantes->update($dados);
		}
		if (!empty($pessoas)){
			 foreach ($pessoas as $key=>$value){
			 	if ($value!=NULL && $value!="" && !empty($value)){
			 		$person['id_visitante'] = $id_visitante;
					$person['nome'] = $value;
					$this->visitantes->insertPessoas($person);
				}
			 }
		 	$this->session->set_flashdata('messege',"Visitantes Cadastrados com sucesso !");
		 }else{
		 	$this->session->set_flashdata('messege',"Visitante Cadastrado com sucesso !");
		 }
		 
		 redirect('visitantes');
	 }
	 
	 public function autorizados_submit($id_autorizacao=NULL)
	 {
		 /*
		 * trata as informações da Autorização do visitante partir das informações do formulário
		 */
		 $dados = $this->input->post();
		 $file = $dados['foto'];
		 unset($dados['foto']);
		 
		 
		 if(!empty($id_autorizacao)){
		 	$dados['id_autorizado'] = $id_autorizacao;
		 	$this->visitantes->updateAutorizacao($dados);
		 	$this->session->set_flashdata('messege',"Autorização Atualizada com sucesso !");
		 }else{
		 	$id_autorizacao = $this->visitantes->insertAutorizacao($dados);
		 	$this->session->set_flashdata('messege',"Visitante Autorizado com sucesso !");
		 }
		 
		 if($file){
			$dados['id_autorizado'] = $id_autorizacao;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "autorizado_".$id_autorizacao.".".$ext;
			if(copy($file,"uploads/autorizados/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;
			
			$this->visitantes->updateAutorizacao($dados);
		}
		 
		if ($_FILES['foto']['name']!='') {
		 	
		 	$autorizado =$this->visitantes->getById($id_autorizacao);
			$path = 'uploads/autorizados';
			
			// Configura o upload
			$config = array(
				'upload_path'    => $path,
				'allowed_types'  => 'gif|jpg|png',
				'file_name'      => "autorizados_".$id_autorizacao,
				'maintain_ratio' => true,
				'max_size'       => 0,
				'max_filename'   => 0
			);
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ($this->upload->do_upload( 'foto' )) {
				if (is_dir($path."/".$autorizado['foto']))
					unlink($path."/".$autorizado['foto']);
				// Informações do arquivo apos upload
				$infoDoc = $this->upload->data();
				// Salvando as informações da imagem
				$dadosDoc = array(
					'id_autorizado'     => $id_autorizacao,
					'foto' => $infoDoc['file_name']
				);
				$this->visitantes->updateAutorizacao( $dadosDoc );
				
			}else{
				$this->session->set_flashdata( "error", $this->upload->display_errors('<div class="alert alert-error">', '</div>'));   
			}
		}

		redirect('visitantes/autorizados');
	 }
	/*
	 * Operações em ajax
	 */
	 
}
 