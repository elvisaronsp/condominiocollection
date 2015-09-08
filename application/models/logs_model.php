<?php
/**
* @author joel.medeiros
* @copyright 2014
*/


/*
 * Regras de negócio para os logs do sistema
 */
Class logs_model extends CI_Model{

	/*
	 * Regras de insersão no banco de dados
	 */
	 public function insert($acao,$tipo)//inclui informações do log no banco
	 {
		 $this->db->set('acao',$acao);
		 $this->db->set('tipo',$tipo);
		 $this->db->set('id_usuario',$this->session->userdata('id_usuario'));
		 $this->db->insert('logs');
		 
		 return $this->db->insert_id();
	 }
}