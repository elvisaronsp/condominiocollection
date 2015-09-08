<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para colaboradores
 */
Class colaboradores_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos colaboradores, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "colaboradores.*");
		$this->db->select( "colaboradores_empresas.razao_social",false);
		$this->db->from("colaboradores");
		$this->db->join("colaboradores_empresas","colaboradores_empresas.id_empresa = colaboradores.id_empresa",'LEFT');
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
		$this->db->order_by("colaboradores.data_admissao,colaboradores.data_demissao","desc");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	
	public function getAllempresas( $filtro = null, $limit = null){
		/*
		 * Busca todas empresas terceiras, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "colaboradores_empresas.*");
		$this->db->from("colaboradores_empresas");
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
		$this->db->order_by("colaboradores_empresas.razao_social");
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
		 * Busca um único colaborador a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "colaboradores.id_colaborador", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getempresaById($id) {

		/*
		 * Busca um único empresa a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "colaboradores_empresas.id_empresa", "valor" => $id);

		$resultado = $this->getAllempresas($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}

	
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações do colaborador no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('colaboradores');
		 
		 return $this->db->insert_id();
	 }
	 
	 public function insertEmpresa($dados)//inclui informações da empresa terceira no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('colaboradores_empresas');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações do colaborador no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_colaborador')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_colaborador',$dados['id_colaborador']);
		 $this->db->update('colaboradores');
	 }
	 
	 public function updateEmpresa($dados)//altera informações da empresa terceira no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_empresa')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_empresa',$dados['id_empresa']);
		 $this->db->update('colaboradores_empresas');
	 }
}