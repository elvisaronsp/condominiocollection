<?php
/**
 * @author joel.medeiros
 * @copyright 2014
 */

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 * Todas as telas de Colaboradores
 */

class colaboradores extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('colaboradores_model', 'colaboradores');
	}

	/*
	 * Exibição das páginas
	 */
	public function index() {
		/*
		 * lista todos os Colaboradores cadastrados
		 */
		$data['colaboradores'] = $this->colaboradores->getAll();
		$data["titulo"] = "Colaboradores";
		$this->template->sistema("colaboradores_view", $data);
	}

	public function registro($id_colaborador = NULL) {
		/*
		 *  informações de um único colaborador
		 */
		if ($id_colaborador != NULL) {

			$colaborador = $this->colaboradores->getById($id_colaborador);
			$data['colaborador'] = $colaborador;
			$data["titulo"] = "Colaborador: ".$colaborador['nome'];
			$colaborador = $this->colaboradores->getById($id_colaborador);
		} else {
			$data['titulo'] = "Novo colaborador";
		}
		$this->template->sistema("colaboradores_reg_view", $data);
	}

	public function empresas() {
		/*
		 * lista todas as empresas de Colaboradores
		 */
		$data['empresas'] = $this->colaboradores->getAllempresas();
		$data["titulo"] = "Empresas Tercerizadas de Colaboradores";
		$this->template->sistema("colaboradores_empresas_view", $data);
	}

	public function empresa_registro($id_empresa = NULL) {
		if ($id_empresa != NULL) {
			$empresa = $this->colaboradores->getempresaById($id_empresa);
			if ($empresa != NULL) {
				$filtro[] = array('campo'=>'colaboradores.tercerizado',"valor"=>1);
				$filtro[] = array('campo'=>'colaboradores.id_empresa',"valor"=>(int)$empresa['id_empresa']);
				
				$colaboradores = $this->colaboradores->getAll($filtro);
				$data['empresa'] = $empresa;
				$data['colaboradores'] = $colaboradores;
				$data['titulo'] = $empresa['razao_social'];
			} else {
				$this->session->set_flashdata('error', 'Empresa Tercerizada não localizada !');
				redirect('colaboradores/empresas/');
			}
		} else {
			$data['titulo'] = "Nova Empresa Tercerizada";
		}
		$this->template->sistema("colaboradores_empresas_reg_view", $data);

	}

	/*
	 * Trativa dos dados para operações Lógicas
	 */
	public function colaboradores_submit($id_colaborador = NULL) {
		$dados = $this->input->post();
		if (!empty($dados['data_admissao']))
		$dados['data_admissao'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_admissao']);
		// convertendo data para formato americano

		if ($dados['data_demissao'] != NULL && $dados['data_demissao'] != "")
			$dados['data_demissao'] = preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $dados['data_demissao']);
		// convertendo data para formato americano
		if (!empty($dados['tercerizado']) && $dados['tercerizado'] == 1 && ($dados['id_empresa'] == NULL || $dados['id_empresa'] == "")) {// verifica se empresa terceira já está cadastrada

			$empresa['empresa'] = $dados['nome_exibe'];
			$dados['id_empresa'] = $this->colaboradores->insertEmpresa($empresa);
		}
		
		unset($dados['nome_exibe']);
		
		if ($id_colaborador != NULL) {

			$dados['id_colaborador'] = $id_colaborador;
			$this->colaboradores->update($dados);
			$this->session->set_flashdata('messege', "Colaborador Alterado com sucesso !");

		} else {

			$this->colaboradores->insert($dados);
			$this->session->set_flashdata('messege', "Colaborador Cadastrado com sucesso !");

		}
		redirect('colaboradores');
	}

	public function empresas_submit($id_empresa = NULL) {
		$dados = $this->input->post();

		if ($id_empresa != NULL) {

			$dados['id_empresa'] = $id_empresa;
			$this->colaboradores->updateEmpresa($dados);
			$this->session->set_flashdata('messege', "Empresa Tercerizada Alterada com sucesso !");

		} else {

			$id_empresa = $this->colaboradores->insertEmpresa($dados);
			$this->session->set_flashdata('messege', "Empresa Tercerizada Cadastrada com sucesso !");

		}
		redirect('colaboradores/empresas');
	}

	/*
	 * Operações em ajax
	 */
	public function busca_empresa() {
		if ($_GET["term"])
			$filtro[] = array("campo" => 'razao_social', "valor" => mysql_real_escape_string($_GET["term"]), 'operador' => 'like');
		$busca = $this->colaboradores->getAllEmpresas($filtro);
		echo json_encode($busca);
	}

}
