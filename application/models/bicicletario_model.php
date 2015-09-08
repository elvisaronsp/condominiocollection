<?php 
/**
* @author joel.medeiros
* @email contato@joelmedeiros.com.br
* @copyright 2014
*/

Class bicicletario_model extends CI_Model{

	/*
	 * Regras de bicicletario de informações no banco de dados
	 */ 
	public function getAll( $filtro = null,$limit = null){
		/*
		 * Busca todas reparos, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "bicicletas.*,unidades.*"); 
		$this->db->from("bicicletas");
		 $this->db->join("unidades","unidades.id_unidade = bicicletas.unidade");
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
		//$this->db->order_by("bicicletas.id_bicicleta");
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
		 * Busca um único bicicletario a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "bicicletas.id_bicicleta", "valor" => $id); 

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}	
	
	
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações deo bicicletario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('bicicletas');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações de bicicletario no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_bicicleta')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_bicicleta',$dados['id_bicicleta']);
		 $this->db->update('bicicletas');
	 }
}
?>