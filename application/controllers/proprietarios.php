<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 *  OPerações relacionadas as proprietarios
 */
 
class proprietarios extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('proprietarios_model', 'proprietarios');
		$this->load->model('unidades_model', 'unidades');
	}
	
	/*
	 * Exibição das páginas
	 */
	
	public function index() {
		$proprietarios = $this->proprietarios->getAll();
		
		$data['proprietarios'] = $proprietarios;
		if (!empty($proprietarios)){
			foreach($proprietarios as $proprietario){
				$unidades[$proprietario['id_proprietario']] = $this->proprietarios->getUnidades($proprietario['id_proprietario']);
				
			}
			$data['unidades'] = $unidades;
		}
		
		$data["titulo"] = "Proprietários";
		$this->template->sistema("proprietarios_view", $data);
	}
	
	public function registro($id_proprietario=null) {
		if ($id_proprietario!=NULL){
			$this->load->model('moradores_model', 'moradores');
			$proprietario = $this->proprietarios->getById($id_proprietario); // traz as informações do proprietário
			if ($proprietario!=NULL){
				$data['proprietario'] = $proprietario;
				$data['unidades'] = $this->proprietarios->getUnidades($id_proprietario); // traz as unidades vinculadas ao proprietário
				$data["titulo"] = "Proprietário ".$proprietario['nome'];
			}else{
				$this->session->set_flashdata('error',"Proprietário não localizado !");
				redirect('proprietarios');
			}
		}else{
			$data["titulo"] = "Cadastro de novo proprietario";
		}
		$this->template->sistema("proprietarios_reg_view", $data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 
	public function proprietario_submit($id_proprietario=NULL)
	{
		$dados = $this->input->post();
		$dados['data_nascimento'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_nascimento']); // convertendo data para formato americano
		$file = NULL;
		if(!empty($dados['foto'])){
			$file = $dados['foto'];
		
		unset($dados['foto']);	
		}
		
		if($id_proprietario==NULL){
			// Cadastrar informações do proprietario
			$id_proprietario = $this->proprietarios->insert($dados);
			$this->session->set_flashdata('messege',"Proprietário Cadastrado com sucesso !");
		
		}
		if ($id_proprietario!=NULL){
			$dados['id_proprietario'] = $id_proprietario;
			// Atualizar informações do proprietario
			$this->proprietarios->update($dados);
			$this->session->set_flashdata('messege',"Proprietário Atualizado com sucesso !");
		}
		
		if(!empty($file)){
			$dados['id_proprietario'] = $id_proprietario;
			$ext = explode(".", $file);
			$ext = end($ext);
			$foto = "proprietario_".$id_proprietario.".".$ext;
			if(copy(str_replace(base_url(),"",$file),"uploads/proprietarios/".$foto))
				unlink(str_replace(base_url(),"",$file));
			$dados['foto'] = $foto;
			
			$this->proprietarios->update($dados);
			
		}

		
		 if (!empty($_FILES) && $_FILES['foto']['name']!='') {
		 	
		 	$proprietario =$this->proprietarios->getById($id_proprietario);
			$path = 'uploads/proprietarios';
			
			// Configura o upload
			$config = array(
				'upload_path'    => $path,
				'allowed_types'  => 'gif|jpg|png',
				'file_name'      => "proprietario_".$id_proprietario,
				'maintain_ratio' => true,
				'max_size'       => 0,
				'max_filename'   => 0
			);
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload( 'foto' )) {
				$this->session->set_flashdata( "error", $this->upload->display_errors('<div class="alert alert-error">', '</div>'));   
			}

			if ($this->upload->do_upload( 'foto' )) {
				
				if (!empty($proprietario['foto']) && is_dir($path."/".$proprietario['foto']))
					unlink($path."/".$proprietario['foto']);
				// Informações do arquivo apos upload
				$infoDoc = $this->upload->data();
				// Salvando as informações da imagem
				$dadosDoc = array(
					'id_proprietario'     => $id_proprietario,
					'foto' => $infoDoc['file_name']
				);
				$this->proprietarios->update( $dadosDoc );
				
			}
		}
		redirect('proprietarios/registro/'.$id_proprietario);
	}

	public function vincular_unidade($id_proprietario)
	{
		$dados = $this->input->post();
		$dados['data_aquisicao'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_aquisicao']); // convertendo data para formato americano
		unset($dados['bloco']);
		if ($dados['morador']==1){
			$this->load->model('moradores_model','moradores');
			$morador= $this->proprietarios->getById($id_proprietario);
			$file = $morador['foto'];
			unset($morador['id_proprietario']);
			unset($morador['foto']);
			unset($morador['ativo']);
			unset($morador['endereco']);
			unset($morador['numero']);
			unset($morador['cep']);
			unset($morador['bairro']);
			unset($morador['cidade']);
			unset($morador['complemento']);
			unset($morador['estado']);
			unset($morador['telefone_recado']);
			$morador['tipo'] = 1;
			$morador['unidade'] = $dados['id_unidade'];
			$morador['data_moradia'] = $dados['data_aquisicao'];
			$id_morador = $this->moradores->insert($morador);
			if ($file){
				unset($morador);
				$morador['id_morador'] = $id_morador;
				$ext = explode(".", $file);
				$ext = end($ext);
				$foto = "morador_".$id_morador.".".$ext;
				copy("uploads/proprietarios/".$file, "uploads/moradores/".$foto);
				$morador['foto'] = $foto;
				$this->moradores->update($morador);
			}
			$unidade['id_unidade'] = $dados['id_unidade'];
			$unidade['status'] = 1;
			$this->unidades->update($unidade);
		}
		unset($dados['morador']);
		$dados["id_proprietario"] = $id_proprietario;
		if(!empty($dados['data_devolucao'])) 
			$dados['data_devolucao'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_devolucao']); // convertendo data para formato americano
		
		$this->proprietarios->insertUnidade($dados);

		$this->session->set_flashdata('messege',"Unidade Vinculada com sucesso !");
		redirect('proprietarios/registro/'.$id_proprietario);
	}
	
	public function editar_propriedade($id_unidade,$id_proprietario)
	{
		/*
		 * INSERIR DADOS DE DATA_DEVOLUÇÃO EM UNIDADES_PROPRIETARIOS, E AS OUTRAS INFOS NA TBL UNIDADES
		 */ 
		$dados = $this->input->post();
		$dados+= array('id_unidade'=>$id_unidade);
		if(!empty($dados['data_devolucao'])){
			$prop['data_devolucao'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_devolucao']); // convertendo data para formato americano
			$prop['dono'] = 0;
			$prop['id_unidade'] = $id_unidade;
			$this->proprietarios->updateUnidade($prop);
			$dados['status'] = 0;
		}
		unset($dados['data_devolucao']);
		
		$this->unidades->update($dados);
		$this->session->set_flashdata('messege',"Unidade alterada com sucesso !");
		redirect('proprietarios/registro/'.$id_proprietario);
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
		 
		 echo $this->proprietarios->getAll($filtro);
		 
		 
	 }
}
