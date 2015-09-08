<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para correspondencias
 */
Class correspondencias_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todas correspondencias, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "correspondencias.*,correspondencias_tipos.*,unidades.*");
		$this->db->from("correspondencias");
		$this->db->join("correspondencias_tipos","correspondencias_tipos.id_tipo = correspondencias.tipo");
		$this->db->join("unidades","unidades.id_unidade = correspondencias.unidade");
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
		$this->db->order_by("correspondencias.data","desc");
		$this->db->order_by("unidades.bloco,unidades.unidade");
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
		 * Busca uma única correspondencia a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "correspondencias.id_correspondencia", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getTypes() 
	{
		/*
		 *  Pega todos os tipos de correspondencias
		 */ 
		$query = $this->db->get('correspondencias_tipos');
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
	 public function insert($dados)//inclui informações da correspondencia no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('correspondencias');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	   public function update($dados)//altera informações da correspondencia no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_correspondencia')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_correspondencia',$dados['id_correspondencia']);
		 $this->db->update('correspondencias');
	 }
}