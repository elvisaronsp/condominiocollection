<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para servicos
 */
Class servicos_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos servicos, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "servicos.*");
		$this->db->from("servicos");
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
		$this->db->order_by("servicos.servico");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	
	public function getAllprestadores( $filtro = null, $limit = null){
		/*
		 * Busca todos prestadores servicos, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "servicos_prestadores.*,unidades.*,servicos.*");
		$this->db->from("servicos_prestadores");
		$this->db->join("servicos","servicos.id_servico = servicos_prestadores.tipo");
		$this->db->join("unidades","unidades.id_unidade= servicos_prestadores.unidade");
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
		$this->db->order_by("unidades.bloco,unidades.unidade,servicos_prestadores.data_inicio");
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
		 * Busca um único servico a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "servicos.id_servico", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getPrestadorById($id) {

		/*
		 * Busca um único prestador a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "servicos_prestadores.id_prestador", "valor" => $id);

		$resultado = $this->getAllprestadores($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}

	
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações do servico no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('servicos');
		 
		 return $this->db->insert_id();
	 }
	 
	 public function insertPrestador($dados)//inclui informações do prestador dde servico no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('servicos_prestadores');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações do servico no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_servico')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_servico',$dados['id_servico']);
		 $this->db->update('servicos');
	 }
	 
	 public function updatePrestador($dados)//altera informações do prestador de servico no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_prestador')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_prestador',$dados['id_prestador']);
		 $this->db->update('servicos_prestadores');
	 }
}