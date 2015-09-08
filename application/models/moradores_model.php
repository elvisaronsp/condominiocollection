<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para moradores
 */
 
Class moradores_model extends CI_Model{

	
	/*
	 * Regras de buscas de informações no banco de dados
	 */
	 
	
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos os moradores responsáveis, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "
			moradores.*,
			unidades.*,
			moradores_tipos.*
		");
		$this->db->from("moradores");
		$this->db->join("unidades", "unidades.id_unidade= moradores.unidade");
		$this->db->join("moradores_tipos", "moradores_tipos.id_tipo= moradores.tipo");
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
		$this->db->where("unidades.status",1);
		$this->db->order_by("unidades.bloco,unidades.unidade,moradores.nome");
		$this->db->order_by("moradores.data_moradia","desc");
		$query = $this->db->get();
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}
	
	public function getAllFamilia( $filtro = null, $limit = null)
	{
		/* 
		 * Busca todos os moradores do tipo familia, a partir ou não de um filtro ou limite. 
		 */
		$this->db->select("moradores_familias.*,moradores.nome as nome_morador");
		$this->db->from("moradores_familias");
		$this->db->join("moradores", "moradores.id_morador= moradores_familias.id_morador");
		$this->db->join("unidades", "unidades.id_unidade= moradores.unidade");
		$this->db->join("moradores_tipos", "moradores_tipos.id_tipo= moradores.tipo");
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
		$this->db->where("unidades.status",1);
		$this->db->order_by("unidades.bloco,unidades.unidade,moradores_familias.nome");
		$query = $this->db->get();
		

		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function GetMoradorByUser($id_usuario)
	{
		$this->db->where("id_usuario",$id_usuario);
		$query = $this->db->get("moradores_usuarios");
		$morador = ($query->num_rows() > 0 ? $query->result_array() : NULL);
		return (!empty($morador) ? $this->getById($morador[0]['id_morador']) : NULL);
	}
	
	public function getAllFamiliaByMorador($id_morador)
	{
		/*
		 * Busca todos os familiares a partir do id_morador
		 */ 
		 
		$this->db->where("id_morador", $id_morador);
		$query = $this->db->get('moradores_familias');
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	} 
	
	public function getById($id) {

		/*
		 * Busca um único morador a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "moradores.id_morador", "valor" => $id);

		$resultado = $this -> getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getByUnidade($unidade) {

		/*
		 * Busca um único morador a partir de sua id
		 */
		if ($unidade == NULL)	return NULL;

		$filtro[] = array("campo" => "unidades.id_unidade", "valor" => $unidade);

		$resultado = $this -> getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getTypes()
	{
		$query = $this->db->get('moradores_tipos');
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações da morador no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('moradores');
		 
		 return $this->db->insert_id();
	 }
	 
	 public function insertFamiliar($dados)//inclui informações da morador no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
		 }
		 $this->db->insert('moradores_familias');
		 
		 return $this->db->insert_id();
	 }
	 
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações da morador no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_morador')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_morador',$dados['id_morador']);
		 $this->db->update('moradores');
	 }
	 
	 public function updateFamiliar($dados)//altera informações da morador no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_familia')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_familia',$dados['id_familia']);
		 $this->db->update('moradores_familias');
	 }
	 
	 /*
	  * Regras para exclusão de informações de banco de dados
	  */
	  
	  public function deleteFamiliar($dados)
	  {
		  $this->db->where("id_familia",$dados['id_familia']);
		  $this->db->delete("moradores_familias");
	  }
}