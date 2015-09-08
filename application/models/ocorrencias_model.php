<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para ocorrencias
 */
Class ocorrencias_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todas ocorrencias, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "ocorrencias.*,unidades.*,usuarios.*,niveis_urgencia.*");
		$this->db->from("ocorrencias");
		$this->db->join("unidades","unidades.id_unidade = ocorrencias.unidade");
		$this->db->join("usuarios","usuarios.id_usuario = ocorrencias.id_usuario");
		$this->db->join("niveis_urgencia","niveis_urgencia.id_nivel = ocorrencias.urgencia");
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
		$this->db->order_by("ocorrencias.data_ocorrencia,ocorrencias.urgencia","desc");
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
		 * Busca uma única ocorrencia a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "ocorrencias.id_ocorrencia", "valor" => $id);

		$resultado = $this->getAll($filtro);

		return ($resultado != NULL ? $resultado[0] : NULL);
	}	
	
	public function getTypes() 
	{
		/*
		 *  Pega todos os níveis de urgencia
		 */ 
		$query = $this->db->get('niveis_urgencia');
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
	 public function insert($dados)//inclui informações da ocorrencia no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('ocorrencias');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	   public function update($dados)//altera informações da ocorrencia no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_ocorrencia')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_ocorrencia',$dados['id_ocorrencia']);
		 $this->db->update('ocorrencias');
	 }
}