<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_model extends CI_Model
{
	public function verificaLogin($usuario, $senha) {

		$this->db->where("usuario", $usuario);
		$this->db->where("senha", md5 ($senha));
		$this->db->where("ativo", true);
		$this->db->from("usuarios");

		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {

			$usuario = $query->result_array();
						
			$session = array(
				"logado" => true,
				"id_usuario" => $usuario[0]["id_usuario"],
				"usuario" => $usuario[0]["usuario"],
				"tipo" => $usuario[0]["tipo"]
			);

			$this->session->set_userdata($session);
			$resultado = true;
		} else {
			$resultado = false;
		}
		
		return $resultado;
	}
	//gera senhas aleatórias
	private function randGenerator( $tamanho ) {
		
		if( $tamanho > 0 ){
			
			$CaracteresAceitos = 'abcdxywzABCDZYWZ0123456789';
			$max = strlen($CaracteresAceitos)-1;
			$password = null;
			
			for($i=0; $i < $tamanho; $i++) {
				$password .= $CaracteresAceitos{mt_rand(0, $max)};
			}

			return $password;
		}
		else {
			return '';
		}
	}
}	