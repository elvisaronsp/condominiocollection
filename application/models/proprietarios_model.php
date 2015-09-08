<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para proprietarios
 */
Class proprietarios_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos proprietarios, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "proprietarios.*");
		$this->db->from("proprietarios");
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
					default:
						$this->db->where( $row['campo'], $row['valor'] );
						break;
				}
			}
		}
		$this->db->order_by("proprietarios.nome");
            
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
		 * Busca um único proprietario a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "proprietarios.id_proprietario", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
		
	public function getUnidades($id_proprietario)
	{
		$this->db->select('unidades_proprietarios.*,unidades.*,proprietarios.*');
		$this->db->from('unidades_proprietarios');
		$this->db->join('unidades','unidades.id_unidade = unidades_proprietarios.id_unidade');
		$this->db->join('proprietarios','proprietarios.id_proprietario = unidades_proprietarios.id_proprietario');
		$this->db->where('unidades_proprietarios.id_proprietario',$id_proprietario);
		$query = $this->db->get();

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
	 public function insert($dados)//inclui informações da proprietario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('proprietarios');
		 
		 return $this->db->insert_id();
	 }
	 
	 public function insertUnidade($dados)
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
			$this->db->set($key,$value);
		 }
		 
		 $this->db->insert('unidades_proprietarios');
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações da proprietario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_proprietario')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_proprietario',$dados['id_proprietario']);
		 $this->db->update('proprietarios');
	 }
	 
	 public function updateUnidade($dados)
	 {
		 foreach($dados as $key=>$value){
		 	if ($key !='id_unidade')
				$this->db->set($key,$value);
		 }
		 $this->db->where('id_unidade',$dados['id_unidade']);
		 $this->db->update('unidades_proprietarios');
	 }
	 
}