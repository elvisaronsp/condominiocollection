<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class login extends CI_Controller {


	public function index()
	{
		
	    $data["titulo"] = "Entrar";
		$this -> template -> sistema("login_view", $data);
	}
		
    public function entrar() {
		
		$this->load->model("login_model");
		$this->load->model("usuarios_model","usuarios");

		$usuario = $this->input->post("usuario");
		$senha   = $this->input->post("senha");
		
		if ($this->login_model->verificaLogin($usuario, $senha)) {
			$this->logs_model->insert("Início de sessão","info");

			$menus = $this->usuarios-> getPrivilegios($this->session->userdata("tipo"));
			
			$current_function = $this->uri->segment(1);
			
			$first_view = $this->usuarios-> getFirstViewPrivilegios($this->session->userdata("tipo"));
			redirect($first_view['controller']);
			
		} else {
          $this->session->set_flashdata("error", "Usuário ou senha invalidos");
		  redirect("login");
		}
	}

	public function sair() {
		
		
		$this->logs_model->insert("Finalização de sessão","info");
		//unseta as sessoes
		$this->session->unset_userdata('logado');
		$this->session->unset_userdata('id_usuario');
        $this->session->unset_userdata('usuario');
        $this->session->unset_userdata('tipo');
        $this->session->unset_userdata('logado');

		//destroi as sessoes
		$this->session->sess_destroy();
		redirect("login");
	}
}