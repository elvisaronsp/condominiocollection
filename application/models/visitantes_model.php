<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para visitantes
 */
Class visitantes_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todos visitantes, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "visitantes.*,unidades.*");
		$this->db->from("visitantes");
		$this->db->join("unidades","unidades.id_unidade = visitantes.unidade");
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
		$this->db->order_by("visitantes.data","desc");
		$this->db->order_by("unidades.bloco,unidades.unidade");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}

	public function getAllAutorizados( $filtro = null, $limit = null){
		/*
		 * Busca todos visitantes autorizados, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "visitantes_autorizados.*,unidades.*");
		$this->db->from("visitantes_autorizados");
		$this->db->join("unidades","unidades.id_unidade = visitantes_autorizados.unidade");
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
		$this->db->order_by("unidades.bloco,unidades.unidade,visitantes_autorizados.nome");
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
		 * Busca um único visitante a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "visitantes.id_visitante", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	
	public function getPessoasByVisitante($id_visitante)
	{
		/*
		 * Busca todas as pessoas que vieram junto com o visitante a partir de sua id 
		 */ 
		$this->db->where( "id_visitante", $id_visitante);
		$query = $this->db->get('visitantes_pessoas');
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
	 public function insert($dados)//inclui informações do visitante no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('visitantes');
		 
		 return $this->db->insert_id();
	 }

	
	 public function insertPessoas($pessoas)//inclui informações das pessoas que vieram junto com o visitante no banco
	 {
		 foreach($pessoas as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('visitantes_pessoas');
		 
		 return $this->db->insert_id();
	 }
	 
	  public function insertAutorizacao($dados)//inclui informações do visitante autorizado no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('visitantes_autorizados');
		 
		 return $this->db->insert_id();
	 }
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	   public function update($dados)//altera informações do visitante no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_visitante')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_visitante',$dados['id_visitante']);
		 $this->db->update('visitantes');
	 }
	   public function updateAutorizacao($dados)//altera informações do visitante autorizado no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_autorizado')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_autorizado',$dados['id_autorizado']);
		 $this->db->update('visitantes_autorizados');
	 }
}