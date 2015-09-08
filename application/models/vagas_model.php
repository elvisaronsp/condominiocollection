<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para vagas
 */
Class vagas_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function GetAll( $filtro = null, $limit = null)
	{
		/*
		 * Busca todas vagas de unidades, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "unidades_vagas.*,unidades.*");
		$this->db->from("unidades_vagas");
		$this->db->join("unidades","unidades.id_unidade = unidades_vagas.unidade","INNER");
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
		$this->db->order_by("unidades_vagas.vaga,unidades.bloco,unidades.unidade");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	
	public function getUtilizacao($id_vaga)
	{
		/*
		 * Busca quem é o atual utilizador da vaga a partir de sua id ou de um filtro
		 */ 
		$this->db->select( "unidades_vagas_utilizacao.*,unidades_vagas.*,unidades.*,");
		$this->db->from("unidades_vagas_utilizacao");
		$this->db->join("unidades_vagas","unidades_vagas.id_vaga = unidades_vagas_utilizacao.id_vaga");
		$this->db->join("unidades","unidades.id_unidade = unidades_vagas_utilizacao.utilizador");
		$this->db->where("unidades_vagas_utilizacao.id_vaga", $id_vaga);
		$this->db->order_by("unidades_vagas.vaga");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
			$resultado = $resultado[0];
		} else {
			$resultado = null;
		}
		return $resultado;
	}

	
	public function GetAllHistorico( $filtro = null, $limit = null)
	{
		/*
		 * Busca todas vagas de unidades, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "vagas_log.*,unidades_vagas.*,unidades.*");
		$this->db->from("vagas_log");
		$this->db->join("unidades_vagas","unidades_vagas.id_vaga = vagas_log.id_vaga","INNER");
		$this->db->join("unidades","unidades.id_unidade = unidades_vagas.unidade","INNER");
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
		$this->db->order_by("vagas_log.data","desc");
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}

	public function GetVagaByUtilizador($id_utilizador)
	{
		/*
		 * Busca todas as vagas que são utilizadas por esta unidade;
		 */ 
		$this->db->select( "unidades_vagas_utilizacao.*,unidades_vagas.*,unidades.*,");
		$this->db->from("unidades_vagas_utilizacao");
		$this->db->join("unidades_vagas","unidades_vagas.id_vaga = unidades_vagas_utilizacao.id_vaga");
		$this->db->join("unidades","unidades.id_unidade = unidades_vagas.unidade");
		$this->db->where("unidades_vagas_utilizacao.utilizador", $id_utilizador);
		$this->db->order_by("unidades_vagas.vaga");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$resultado = $query->result_array();
		} else {
			$resultado = null;
		}
		return $resultado;
	}
	
	public function getById($id_vaga)
	{
		/*
		 * Busca uma única vaga a partir de sua id
		 */
		if ($id_vaga == NULL)	return NULL;

		$filtro[] = array("campo" => "unidades_vagas.id_vaga", "valor" => $id_vaga);

		$resultado = $this->GetAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($dados)//inclui informações da vaga no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('unidades_vagas');
		 
		 return $this->db->insert_id();
	 }
	 public function insertUtilizacao($dados)//inclui informações da vaga no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('unidades_vagas_utilizacao');
		 
		 return $this->db->insert_id();
	 }
	 public function insertLog($id_vaga,$log)
	 {
		 $this->db->set("id_vaga",$id_vaga);
		 $this->db->set("log",$log);
		 $this->db->insert('vagas_log');
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações da vaga no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_vaga')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_vaga',$dados['id_vaga']);
		 $this->db->update('unidades_vagas');
	 }
	 public function updateUtilizacao($dados)//altera informações da vaga no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_utilizacao')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_utilizacao',$dados['id_utilizacao']);
		 $this->db->update('unidades_vagas_utilizacao');
	 }

}