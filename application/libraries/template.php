<?php
/**
* @author joel.medeiros
* @copyright 2014
*/

class template
{
	var $CI;

	function __construct() {

		$this->CI = & get_instance();
		
	}

	public function verifica_acesso($menus,$campo_menu)
	{
		foreach($menus as $menu){
			if($menu['controller']==$this->CI->uri->segment(1) && $menu[$campo_menu]==TRUE){
				return true;
			}
		}
		return false;
	}
	/*
	 * Redireciona para as páginas do site .
	 */
	public function site( $view, $data ) {
		if ($this ->CI-> session -> flashdata("error")) {
			$data['error'] = $this ->CI-> session -> flashdata("error");
		}
		
		if ($this ->CI-> session -> flashdata("messege")) {
			$data['messege'] = $this ->CI-> session -> flashdata("messege");
		}

		$data['view'] = $view;
		$this->CI->load->view("template_view", $data);
	}
	
	/*
	 * Redireciona para as páginas internas do sistema
	 */
	public function sistema( $view, $data ) {
		
		$this->CI->load->model('unidades_model','unidades'); 
		$this->CI->load->model('usuarios_model','usuarios'); 
		if( $this->CI->session->userdata("logado")){
			$menus = $this->CI->usuarios-> getPrivilegios($this->CI->session->userdata("tipo"));
			
			$current_function = $this->CI->uri->segment(1);
			

			if($this->verifica_acesso($menus,"visualizar")===FALSE){
				$this ->CI-> session -> set_flashdata("error","Você não tem acesso a esta página !");
				$first_view = $this->CI->usuarios-> getFirstViewPrivilegios($this->CI->session->userdata("tipo"));
				redirect($first_view['controller']);
			}
			$data['menus'] = $menus;
		}

		if ($this ->CI-> session -> flashdata("error")) {
			$data['error'] = $this ->CI-> session -> flashdata("error");
		}
		
		if ($this ->CI-> session -> flashdata("messege")) {
			$data['messege'] = $this ->CI-> session -> flashdata("messege");
		}
		
		if ($this->CI->session->userdata('unidade')){

			$unidade = $this->CI->session->userdata('unidade');
			$this->CI->load->model('unidades_model','unidades');
			$data['unidade'] = $this-> CI -> unidades-> getById($unidade['id_unidade']);			
		}
		$data['view'] = "sistema/".$view;
		$this->CI->load->view("sistema/template_view", $data); echo $view;
	}
}
?>