<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para os veiculos dos moradores
 */
Class veiculos_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos veiculos, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "veiculos.*,unidades.*,veiculos_tipos.*");
		$this->db->from("veiculos");
		$this->db->join("veiculos_tipos","veiculos_tipos.id_tipo = veiculos.tipo");
		$this->db->join("unidades","unidades.id_unidade = veiculos.unidade");
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
		$this->db->order_by("unidades.bloco,unidades.unidade,veiculos.tipo");
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
		 * Busca um único veiculo a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "veiculos.id_veiculo", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getVeiculosByUnidade($id_unidade)
	{
		
		/*
		 * Busca veiculos a partir da id do morador
		 */
		if ($id_unidade == NULL)	return NULL;

		$filtro[] = array("campo" => "veiculos.unidade", "valor" => $id_unidade);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado : NULL);
	}
	
	public function getTypes() 
	{
		/*
		 *  Pega todos os tipos de veículos
		 */ 
		$query = $this->db->get('veiculos_tipos');
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
	 public function insert($dados)//inclui informações do veiculo no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('veiculos');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações do veiculo no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_veiculo')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_veiculo',$dados['id_veiculo']);
		 $this->db->update('veiculos');
	 }
	 
	 /*
	  * Regras de remoção de informações
	  */
	  
	  public function delete($id_veiculo)
	  {
	  	  $this->db->where('id_veiculo',$id_veiculo);
		  $this->db->delete('veiculos');
		  
	  }
}