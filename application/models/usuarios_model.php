<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para os usuarios dos moradores
 */
Class usuarios_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos usuarios, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "usuarios.*,usuarios_tipos.*");
		$this->db->from("usuarios");
		$this->db->join('usuarios_tipos','usuarios_tipos.id_tipo = usuarios.tipo');
		if ($filtro != null) {
			foreach ($filtro as $row) {
				$row['operador'] = (isset($row['operador'])?$row['operador']:null);
				switch ( $row['operador'] ) {
					case 'like':
						$this->db->like($row['campo'], $row['valor']);
						break;
					case 'or_like':
						$this->db->or_like($row['campo'], $row['valor']);
						break;
					case 'or':
						$this->db->or_where($row['campo'], $row['valor']);
						break;
					case 'where':
						$this->db->where($row['instrucao']);
						break;
					default:
						$this->db->where( $row['campo'], $row['valor'] );
						break;
				}
			}
		}
		$this->db->order_by("usuarios.usuario");
		$this->db->order_by("usuarios.data_cadastro","desc");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}

	public function getById($id) {

		/*
		 * Busca um único usuario a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "usuarios.id_usuario", "valor" => $id);

		$resultado = $this -> getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getusuariosByMorador($id_morador)
	{
		
		/*
		 * Busca usuarios a partir da id do morador
		 */
		if ($id_morador == NULL)	return NULL;

		$filtro[] = array("campo" => "usuarios.morador", "valor" => $id_morador);

		$resultado = $this -> getAll($filtro);

		return ($resultado != NULL ? $resultado : NULL);
	}
	
	public function getTypes($id_tipo = NULL ) 
	{

		if($id_tipo != NULL ) $this -> db -> where("id_tipo",$id_tipo);
		$query = $this->db->get('usuarios_tipos');
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
			if($id_tipo != NULL ) $resultado = $resultado[0];
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	public function getPrivilegios($id_tipo)
	{
		
		$this->db ->select("acessos.*");
		$this->db ->select("acessos_privilegios.*");
		$this->db ->select("usuarios_tipos.*");
		$this->db ->from("acessos");
		$this->db ->join("acessos_privilegios","acessos_privilegios.id_acesso = acessos.id_acesso","LEFT");
		$this->db ->join("usuarios_tipos","usuarios_tipos.id_tipo = acessos_privilegios.id_tipo","LEFT");
		$this->db ->where("usuarios_tipos.id_tipo = $id_tipo");
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	public function getFirstViewPrivilegios($id_tipo)
	{
		
		$this->db ->select("acessos.*");
		$this->db ->select("acessos_privilegios.*");
		$this->db ->select("usuarios_tipos.*");
		$this->db ->from("acessos");
		$this->db ->join("acessos_privilegios","acessos_privilegios.id_acesso = acessos.id_acesso","LEFT");
		$this->db ->join("usuarios_tipos","usuarios_tipos.id_tipo = acessos_privilegios.id_tipo","LEFT");
		$this->db ->where("usuarios_tipos.id_tipo = $id_tipo");
		$this->db ->where("acessos_privilegios.visualizar = 1"); 
		$this->db->order_by("id_privilegio","asc");
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado[0];
	}



	public function getAllAcessos() 
	{

		$query = $this->db->get('acessos');
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	
	
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações do usuario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('usuarios');
		 
		 return $this->db->insert_id();
	 }
	 
	 public function insertTipo($dados)//inclui informações do usuario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('usuarios_tipos');
		 
		 return $this->db->insert_id();
	 }
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações do usuario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_usuario')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_usuario',$dados['id_usuario']);
		 $this->db->update('usuarios');
	 }
	 public function updateTipo($dados)//altera informações do usuario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_tipo')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_tipo',$dados['id_tipo']);
		 $this->db->update('usuarios_tipos');
	 }

	 public function updatePrivilegios($dados)
	 {

	 	foreach($dados as $key=>$value){
		 	if ($key!=$dados['where'] && $key!="where")
		 	$this->db->set($key,$value);
		 }
		 $this->db->where($dados['where'],$dados[$dados['where']]);
		 $this->db->update('acessos_privilegios');
	 }
	 
	 /*
	  * Regras de remoção de informações
	  */
	  
	  public function delete($id_usuario)
	  {
	  	  $this->db->where('id_usuario',$id_usuario);
		  $this->db->delete('usuarios');
		  
	  }
}