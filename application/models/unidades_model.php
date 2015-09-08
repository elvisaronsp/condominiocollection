<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para unidades
 */
Class unidades_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todas unidades, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "unidades.*");
		$this->db->from("unidades");
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
		 * Busca uma única unidade a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "unidades.id_unidade", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}
	public function getProprietariosByUnidade($id_unidade)
	{
		$this->db->select('unidades_proprietarios.*,proprietarios.*');
		$this->db->from('unidades_proprietarios');
		$this->db->join('unidades','unidades.id_unidade = unidades_proprietarios.id_unidade');
		$this->db->join('proprietarios','proprietarios.id_proprietario = unidades_proprietarios.id_proprietario');
		$this->db->where('unidades_proprietarios.data_devolucao is null');
		$this->db->where('unidades_proprietarios.id_unidade',$id_unidade);
		$this->db->order_by('data_aquisicao','desc');
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
	 public function insert($dados)//inclui informações da unidade no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('unidades');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	 public function update($dados)//altera informações da unidade no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_unidade')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_unidade',$dados['id_unidade']);
		 $this->db->update('unidades');
	 } 
	 
	 public function updateVagas($dados)//altera informações da vaga no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_vaga')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_vaga',$dados['id_vaga']);
		 $this->db->update('unidades_vagas');
	 }
}