<?php
/**
 * @author joel.medeiros
 * @copyright 2014
 */

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
 *  OPerações relacionadas as unidades
 */

class unidades extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('vagas_model', 'vagas');
		$this->load->model('veiculos_model', 'veiculos');
		$this->load->model('moradores_model', 'moradores');
		$this->load->model('proprietarios_model', 'proprietarios');
		
	}

	/*
	 * Exibição das páginas
	 */

	public function index() {
		if($this->session->userdata('tipo')==4){
			$morador= $this->moradores->GetMoradorByUser($this->session->userdata("id_usuario"));
			redirect("unidades/registro/".$morador['id_unidade']);
		}
		$data["titulo"] = "Unidades";
		$this->session->unset_userdata("unidade");
		$this->template->sistema("unidades_view", $data);
	}

	public function registro($id_unidade = NULL) {
		if (!empty($_POST) || $id_unidade) {
			IF ($id_unidade != NULL) {
				$unidade[] = $this->unidades->getById($id_unidade);
				if ($this->unidades->getById($id_unidade) == NULL) {
					$this->session->set_flashdata('error', "Você deve buscar pela Torre e unidade !");
					redirect('unidades');
				}
				$filtro[] = array("campo" => "unidades.id_unidade", "valor" => $id_unidade);
				
			} else {
				$dados = $this->input->post();
				foreach ($dados as $key => $value) {
					$filtro[] = array("campo" => $key, "valor" => $value);
				}

				$unidade = $this->unidades->getAll($filtro);
			}

			$this->session->unset_userdata("unidade");
			$sess['unidade'] = $unidade[0];
			$this->session->set_userdata($sess);

			$filtro[] = array("campo" => "unidades.id_unidade", "valor" => $unidade[0]['id_unidade']);
			
			$moradores = $this->moradores->getAll($filtro);
			// traz todos os moradores vinculados a esta unidade
			if (!empty($moradores)) {
				foreach ($moradores as $morador) {
					$filtro_morador[] = array("campo" => "moradores.id_morador", "valor" => $morador['id_morador']);
					$familiares[$morador['id_morador']] = $this->moradores->getAllFamilia($filtro_morador);
					// traz todos os familiares dos moradores
				}
			} else {
				$familiares = NULL;
			}
			$this->load->model("visitantes_model", "visitantes");
			$this->load->model('correspondencias_model', 'correspondencias');
			$this->load->model('empregados_model', 'empregados');
			$this->load->model('servicos_model', 'servicos');
			$this->load->model('ocorrencias_model', 'ocorrencias');
			$this->load->model('reparos_model', 'reparos');
			$this->load->model('areas_model', 'areas');
			$this->load->model('bicicletario_model', 'bicicletario');

			$filtro_visitantes = $filtro;
			$filtro_visitantes[0] = array("campo" => "visitantes.data", "valor" => date("Y-m-d"),"operador"=>"like");
			
			$filtro_corresp  = $filtro;
			$filtro_corresp[0] =  array("campo"=>"correspondencias.status", "valor"=>0);
			
			$filtro_prestadores = $filtro;
			$filtro_prestadores[] = array("campo" => "servicos_prestadores.data_fim", "valor" => NULL);
			
			$filtro_notificacoes = $filtro;
			$filtro_notificacoes[]=array("campo"=>"lido", "valor"=>0);
			
			$filtro_chave_reserva = $filtro;
			$filtro_chave_reserva[] = array("campo" => "areas_utilizacao.data_reserva", "valor" => date('Y-m-d'),"operador"=>"like");
			$filtro_chave_reserva[] = array("campo" => "areas_utilizacao.data_entrega", "valor" =>NULL);
			
			
			$filtro_chave_retirada = $filtro;
			$filtro_chave_retirada[] = array("campo" => "areas_utilizacao.data_entrega", "valor" => NULL);
			$filtro_chave_retirada[] = array("campo" => "areas_utilizacao.data_retirada", "valor" => date('Y-m-d'),"operador"=>"like");
			$data['prestadores'] = $this->servicos->getAllprestadores($filtro_prestadores);
			$visitantes = $this->visitantes->getAll($filtro_visitantes);
			$data['visitantes'] = $visitantes;
			if(!empty($visitantes)){
				foreach($visitantes as $visitante){
					$pessoas[$visitante['id_visitante']] = $this->visitantes->getPessoasByVisitante($visitante['id_visitante']);
				}
				if (!empty($pessoas)) $data['pessoas'] = $pessoas;
			}
			$chaves_reservas = $this->areas->getAllUtilizacao($filtro_chave_reserva);
			$chaves_retiradas = $this->areas->getAllUtilizacao($filtro_chave_retirada);
			$data['bicicletas'] = $this->bicicletario->getAll($filtro);
			$data['reparos'] = $this->reparos->getAll($filtro_notificacoes);
			$data['ocorrencias'] = $this->ocorrencias->getAll($filtro_notificacoes);
			$data['empregados'] = $this->empregados->getAll($filtro);
			$data['vagas_unidade'] = $this->vagas->GetAll($filtro);
			$data['vagas_utilizadas'] = $this->vagas->GetVagaByUtilizador($unidade[0]['id_unidade']);
			$data['correspondencias'] = $this->correspondencias->getAll($filtro_corresp);
			$data['visitantes_autorizados'] = $this->visitantes->getAllAutorizados($filtro);
			$data['familiares'] = $familiares;
			$data['moradores'] = $moradores;
			$data['unidade'] = $unidade[0];
			$data['veiculos'] = $this->veiculos->getVeiculosByUnidade($unidade[0]['id_unidade']);
			$data["titulo"] = ($unidade[0]['bloco']!="Condomínio"?"Unidade " . $unidade[0]['unidade'] . " - Torre " . $unidade[0]['bloco']:"Condomínio");
			$data['chaves_reservadas'] = $chaves_reservas;
			$data['chaves_retiradas'] = $chaves_retiradas;
			$this->template->sistema("unidades_reg_view", $data);
		} else {
			$this->session->set_flashdata('error', "Você deve buscar pela Torre e unidade !");
			redirect('unidades');
		}
	}

	public function vagas() {
		$data['vagas'] = $this->vagas->GetAll();
		$data["titulo"] = "Vagas por unidade";
		$this->template->sistema("unidades_vagas_view", $data);
	}

	public function vaga_registro($id_vaga = NULL) {
		$vaga = !empty($id_vaga) ? $this->vagas->getById($id_vaga) : NULL;
		if (!empty($vaga)) {
			$utilizador = $this->vagas->getUtilizacao($id_vaga);
			$filtro[] = array("campo" => "vagas_log.id_vaga", "valor" => $id_vaga);
			$historicos = $this->vagas->GetAllHistorico($filtro);
		} else {
			$utilizador = NULL;
			$historicos = NULL;
		}

		$data['vaga'] = $vaga;
		$data['utilizador'] = $utilizador;
		$data['historicos'] = $historicos;

		$data['titulo'] = !empty($vagas) ? "Vagas da unidade " . $vaga['unidade'] . " Torre " . $vaga['bloco'] : "Nova Vaga";
		$this->template->sistema("unidades_vagas_reg_view", $data);
	}

	/*
	 * Trativa dos dados para operações Lógicas
	 */

	public function unidade_submit($id_unidade = NULL) {
		$dados = $this->input->post();
		if ($id_unidade != NULL) {
			$dados['id_unidade'] = $id_unidade;
			// Atualizar informações da unidade
			$this->unidades->update($dados);
			$this->session->set_flashdata('messege', "Unidade Atualizada com sucesso !");
		} else {
			// Cadastrar informações da unidade
			$id_unidade = $this->unidades->insert($dados);
			$this->session->set_flashdata('messege', "Unidade Cadastrada com sucesso !");

		}
		redirect('unidades/registro/' . $id_unidade);

	}

	public function vagas_submit($id_vaga = NULL) {
		$vaga = $this->input->post();
		if (!empty($id_vaga)) {
			$vaga['id_vaga'] = $id_vaga;
			$this->vagas->update($vaga);
			$this->session->set_flashdata('messege', "Vaga Alterada com sucesso !");
		} else {
			$this->session->set_flashdata('messege', "Vaga Incluída com sucesso !");
			$id_vaga = $this->vagas->insert($vaga);
		}

		redirect('unidades/vaga_registro/' . $id_vaga);
	}
	
	public function vagas_utilizacao_submit($id_vaga,$id_utilizacao=NULL)
	{
		$dados = $this->input->post();
		$dados['id_vaga'] = $id_vaga;
		$vaga = $this->vagas->getById($id_vaga);
		
		if($vaga['id_unidade']==$dados['utilizador']){
			$dados['tipo'] = "F";
		}else{
			$dados['tipo'] = "L";
		}
		$unidade = $this->unidades->getById($dados['utilizador']);
		if (!empty($id_utilizacao)) {
			$dados['id_utilizacao'] = $id_utilizacao;
			$this->vagas->updateUtilizacao($dados);
			$this->session->set_flashdata('messege', "Utilização de vaga Transferida com sucesso !");
		} else {
			$this->session->set_flashdata('messege', "Utilização de vaga Incluída com sucesso !");
			$this->vagas->insertUtilizacao($dados);
		}
		$this->vagas->insertLog($id_vaga,"Vaga ".($dados['tipo']=="F"?"Fixa":"Alocada/Emprestada")." para Unidade ".$unidade['unidade']." Torre ".$unidade['bloco']);
		redirect('unidades/vaga_registro/' . $id_vaga);
	}

	/*
	 * Operações em ajax
	 */

	public function verifica_unidade()// verifica se unidade existe
	{
		$dados = $this->input->post();

		foreach ($dados as $key => $value) {
			$filtro[] = array("campo" => $key, "valor" => $value);
		}
		echo $this->unidades->getAll($filtro);

	}

	public function getUnidadesByBloco($tipo = NULL)//pega as unidades a partir do bloco
	{
		$bloco = $this->input->post();
		foreach ($bloco as $key => $value) {
			$filtro[] = array("campo" => $key, "valor" => $value);
		}
		if ($tipo == 2)// morador
			$filtro[] = array("instrucao" => "unidades.status =0 && unidades.id_unidade not in (select unidade from moradores)", "operador" => "where");
		$unidades = $this->unidades->getAll($filtro);

		echo json_encode($unidades);
	}

}

