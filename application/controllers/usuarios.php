<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de usuarios 
 */
 
class usuarios extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this -> load -> model('usuarios_model', 'usuarios');
	}
	
	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todos os usuários cadastrados
		 */ 
		$data['usuarios'] = $this->usuarios->getAll();
		$data["titulo"] = "Usuários";
		$this -> template -> sistema("usuarios_view", $data);
	}
	
	public function registro( $id_usuario = NULL)
	{
		/*
		 *  informações de um único usuário
		 */ 
		if ($id_usuario!=NULL){
			$usuario = $this->usuarios->getById($id_usuario);
			if ($usuario!=NULL){
				$data['usuario'] = $usuario;
				$data["titulo"] = $usuario['tipo'].":".$usuario['usuario'];
			}else{
				$this->session->set_flashdata("error","usuário não localizado !");
				redirect('usuários');
			}
		}else{
			$data['titulo'] = "Novo usuário";
		}
		$data['usuarios_tipos'] = $this->usuarios->getTypes();
		$this -> template -> sistema("usuarios_reg_view", $data);
	}

	public function tipos()
	{
		$data['titulo'] = "Perfis de Acesso";
		$data['tipos'] = $this -> usuarios -> getTypes();
		$this -> template -> sistema("usuarios_perfil_view", $data);	
	}
	public function tipo_registro($id_tipo = NULL)
	{
		$data['titulo'] = "Cadastro de Novo Perfil";
		if(!empty($id_tipo)){
			$tipo = $this -> usuarios -> getTypes($id_tipo);
			$data['titulo'] = "Editando Informações de ". $tipo['tipo'];
			$data['tipo'] = $tipo;
			//$data['acessos'] = $this -> usuarios -> getAllAcessos();
			$privilegios = $this -> usuarios -> getPrivilegios($id_tipo);	
			if($privilegios==NULL)
				$privilegios = $this->usuarios->getAllAcessos();
			$data['privilegios'] = $privilegios;
		}

		$this -> template -> sistema("usuarios_perfil_registro_view",$data);
	}
	/*
	 * Trativa dos dados para operações Lógicas
	 */ 
	 public function usuarios_submit($id_usuario = NULL)
	 {
	 	/*
		 * trata as informações do usuário a partir das informações do formulário
		 */ 
		$dados = $this->input->post();
		
		if ($id_usuario!=NULL){
		 	$dados['id_usuario'] = $id_usuario;
		 	if ($dados['senha']==NULL && $dados['senha']=="") unset($dados['senha']); else $dados['senha'] = md5($dados['senha']);
			$this->usuarios->update($dados);
			$this->session->set_flashdata('messege',"Usuário Alterado com sucesso !");
		 }else{
		 	$dados['senha'] = md5($dados['senha']);
		 	$id_usuario = $this->usuarios->insert($dados);
			$this->session->set_flashdata('messege',"Usuário Cadastrado com sucesso !");
		 }		 
		 redirect("usuarios");
	 }

	 public function tipos_submit($id_tipo = NULL)
	 {
	 	/*
		 * trata as informações do usuário a partir das informações do formulário
		 */ 
		$dados = $this->input->post();
		
		if($id_tipo == NULL){
		 	$id_tipo = $this->usuarios->insertTipo($dados);
		 	for ($i=1;$i<=12;$i++){
				$this->db ->query("INSERT INTO acessos_privilegios (`id_tipo`,`id_acesso`) VALUES ($id_tipo,$i)");
			}
			$this->session->set_flashdata('messege',"Privilégio Cadastrado com sucesso !");
		}

		if ($id_tipo!=NULL){
		 	$dados['id_tipo'] = $id_tipo;
			$this->usuarios->updateTipo($dados);
			$this->session->set_flashdata('messege',"Privilégio Alterado com sucesso !");
		}

		 redirect("usuarios/tipo_registro/".$id_tipo);
	 }

	 public function privilegios_submit($id_tipo)
	 {

	 	$limpa['visualizar'] = 0;
	 	$limpa['editar'] = 0;
	 	$limpa['id_tipo'] = $id_tipo;
	 	$limpa['where'] = "id_tipo";
	 	$this->usuarios->updatePrivilegios($limpa);
	 	$dados = $this->input->post();
	 	if(!empty($dados['visualizar'])){
	 		$visualizar = $dados['visualizar'];
		 	foreach ($visualizar as $id_privilegio=>$value){

		 		$p_visualizar['id_privilegio'] = $id_privilegio;	
		 		$p_visualizar['visualizar'] = 1;
		 		$p_visualizar['where'] = "id_privilegio";
		 		$this->usuarios->updatePrivilegios($p_visualizar);
		 	
		 	}
		}

	 	if(!empty($dados['editar'])){
	 		$editar = $dados['editar'];
		 	foreach ($editar as $id_privilegio=>$value){

		 		$p_editar['id_privilegio'] = $id_privilegio;	
		 		$p_editar['editar'] = 1;
		 		$p_editar['where'] = "id_privilegio";
		 		$this->usuarios->updatePrivilegios($p_editar);
		 	}
		}
		$this->session->set_flashdata('messege',"Privilégios de acesso Alterados com sucesso !");
	 	redirect("usuarios/tipo_registro/".$id_tipo);

	 }
	 
	 public function verifica_usuario() // verifica se nome de usuário existe
	 {
	 	$filtro[] = array("campo"=>'usuario',"valor"=>$this->input->post('usuario'));
		$usuario = $this->usuarios->getAll($filtro);
		echo $usuario;
	 }
	 	 
	 public function toggle_usuario($id_usuario) // alterar status usuário
	 {
	 	$usuario = $this->usuarios->getById($id_usuario);
		$dados['ativo'] = $usuario['ativo']==1?0:1;
		$dados['id_usuario'] = $id_usuario;
		$this->usuarios->update($dados);
		echo $this->db->last_query();
	 }
	/*
	 * Operações em ajax
	 */
	 
}
 