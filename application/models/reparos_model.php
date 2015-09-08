<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para reparos
 */
Class reparos_model extends CI_Model{

	/*
	 * Regras de buscas de informações no banco de dados
	 */ 
	public function getAll( $filtro = null, $limit = null){
		/*
		 * Busca todas reparos, a partir ou não de um filtro ou limite. 
		 */ 
		$this->db->select( "reparos.*,unidades.*,usuarios.*,niveis_urgencia.*");
		$this->db->from("reparos");
		$this->db->join("unidades","unidades.id_unidade = reparos.unidade");
		$this->db->join("usuarios","usuarios.id_usuario = reparos.solicitante");
		$this->db->join("niveis_urgencia","niveis_urgencia.id_nivel = reparos.urgencia");
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
		$this->db->order_by("reparos.data,reparos.urgencia","desc");
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
		 * Busca um único reparo a partir de sua id
		 */
		if ($id == NULL)	return NULL;

		$filtro[] = array("campo" => "reparos.id_reparo", "valor" => $id);

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
	 public function insert($dados)//inclui informações do reparo no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($value!=NULL && !empty($value) && $value!="")
		 	$this->db->set($key,$value);
			 
		 }
		 $this->db->insert('reparos');
		 
		 return $this->db->insert_id();
	 }
	 
	 /*
	  * Regras de alterações de informações no banco de dados
	  */
	   public function update($dados)//altera informações do reparo no banco
	 {
		 foreach($dados as $key=>$value){
		 	if ($key!='id_reparo')
		 	$this->db->set($key,$value);
		 }
		 $this->db->where('id_reparo',$dados['id_reparo']);
		 $this->db->update('reparos');
	 }
}