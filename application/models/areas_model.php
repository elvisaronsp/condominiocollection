<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para areas
 */
Class areas_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todas areas, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "areas.*");
		$this->db->from("areas");
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
		$this->db->order_by("areas.area");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	
	public function getAllUtilizacao($filtro = NULL)
	{
		/*
		 * Busca todas areas utilizaveis ou não, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "areas_utilizacao.*,areas.*,unidades.*,");
		$this->db->from("areas_utilizacao");
		$this->db->join("areas","areas.id_area = areas_utilizacao.area");
		$this->db->join("unidades","unidades.id_unidade = areas_utilizacao.unidade");
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
		$this->db->order_by("areas.area");
		$this->db->order_by("areas_utilizacao.data_reserva","desc");
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
		 * Busca uma única area a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "areas.id_area", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}	
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações da area no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('areas');
		 
		 return $this->db->insert_id();
	 }
	 
	 public function insertUtilizacao($dados)//inclui informações da utilizacao da area no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('areas_utilizacao');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações da area no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_area')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_area',$dados['id_area']);
		 $this->db->update('areas');
	 }
	 
	 public function updateUtilizacao($dados)//altera informações da utilizacao da area no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_utilizacao')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_utilizacao',$dados['id_utilizacao']);
		 $this->db->update('areas_utilizacao');
	 }
}