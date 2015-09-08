<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para empregados
 */
Class empregados_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos empregados, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "empregados.*,unidades.*");
		$this->db->from("empregados");
		$this->db->join("unidades","unidades.id_unidade = empregados.unidade");
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
		 * Busca um único empregado a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "empregados.id_empregado", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}	
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações do empregado no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('empregados');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	   public function update($dados)//altera informações do empregado no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_empregado')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_empregado',$dados['id_empregado']);
		 $this->db->update('empregados');
	 }
}