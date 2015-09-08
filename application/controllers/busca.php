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
 
class busca extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('unidades_model', 'unidades');
		$this->load->model('veiculos_model', 'veiculos');
		$this->load->model('moradores_model', 'moradores');
		$this->load->model('proprietarios_model', 'proprietarios');
		$this->load->model('vagas_model', 'vagas');
	}
	
	/*
	 * Exibição das páginas
	 */
	
	public function index() {
		
		$busca =  trim($this->input->post('busca')); // recebe o termo para realizar as buscas
		
		/*
		 * Monta os filtros de morador
		 */
		$f_morador['nome'] = array("campo"=>"moradores.nome","valor"=>$busca,"operador"=>"like");
		$f_morador['rg'] = array("campo"=>"moradores.rg","valor"=>$busca,"operador"=>"or_like");
		$f_morador['cpf'] = array("campo"=>"moradores.cpf","valor"=>$busca,"operador"=>"or_like");
		$f_morador['email'] = array("campo"=>"moradores.email","valor"=>$busca,"operador"=>"or_like");
		$moradores = $this->moradores->getAll($f_morador);
		/*
		 * Monta os filtros de familiares
		 */
		$f_familiar['nome'] = array("campo"=>"moradores_familias.nome","valor"=>$busca,"operador"=>"like");
		$f_familiar['rg'] = array("campo"=>"moradores_familias.rg","valor"=>$busca,"operador"=>"or_like");
		$f_familiar['cpf'] = array("campo"=>"moradores_familias.cpf","valor"=>$busca,"operador"=>"or_like");
		$f_familiar['email'] = array("campo"=>"moradores_familias.email","valor"=>$busca,"operador"=>"or_like");
		$familiares = $this->moradores->getAllFamilia($f_familiar);
		
		/*
		 * Monta os filtros de proprietários
		 */
		$f_proprietarios['nome'] = array("campo"=>"proprietarios.nome","valor"=>$busca,"operador"=>"like");
		$f_proprietarios['rg'] = array("campo"=>"proprietarios.rg","valor"=>$busca,"operador"=>"or_like");
		$f_proprietarios['cpf'] = array("campo"=>"proprietarios.cpf","valor"=>$busca,"operador"=>"or_like");
		$f_proprietarios['email'] = array("campo"=>"proprietarios.email","valor"=>$busca,"operador"=>"or_like");
		$proprietarios = $this->proprietarios->getAll($f_proprietarios);
		if (!empty($proprietarios)){
			foreach($proprietarios as $proprietario){
				$unidades[$proprietario['id_proprietario']] = $this->proprietarios->getUnidades($proprietario['id_proprietario']);
				
			}
			$data['unidades_proprietarios'] = $unidades;
		}
		/*
		 * Monta os filtros de vagas 
		 */
		 $f_vagas['vaga'] = array("campo"=>"unidades_vagas.vaga","valor"=>$busca,"operador"=>"like");
		 $vagas = $this->vagas->getAll($f_vagas);
		 if(!empty($vagas)){
		 	foreach($vagas as $vaga){
		 		$utilizacao[$vaga['id_vaga']] = $this->vagas->getUtilizacao($vaga['id_vaga']);
		 	}
		 	$data['vaga_utilizacao'] = $utilizacao;
		 }
		 /*
		  * monta os filtros de veículos
		  */
		  $f_veiculos['placa'] = array("campo"=>"veiculos.placa","valor"=>$busca,"operador"=>"like");
		  $f_veiculos['marca'] = array("campo"=>"veiculos.marca","valor"=>$busca,"operador"=>"or_like");
		  $f_veiculos['modelo'] = array("campo"=>"veiculos.modelo","valor"=>$busca,"operador"=>"or_like");
		  $veiculos = $this->veiculos->getAll($f_veiculos);

		$data['moradores'] = $moradores;
		$data['familiares'] = $familiares;
		$data['proprietarios'] = $proprietarios;
		$data['vagas'] = $vagas;
		$data['veiculos'] = $veiculos;
		$data['resultados'] = count($moradores) + count($familiares) + count($proprietarios) + count($vagas) + count($veiculos);
		$data['busca'] = $busca;
		$data["titulo"] = "Resultados da busca";
		$this->template->sistema("busca_view", $data);
	}
}