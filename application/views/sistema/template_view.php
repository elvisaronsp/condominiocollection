<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="<?php echo base_url('js/jquery.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/jquery-ui.js');?>"></script><!-- ui js -->
		<script type="text/javascript" src="<?php echo base_url('js/jquery.mask.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/bootstrap.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/efeito.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/webcam.js');?>"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css"> <!-- ui css -->		
		<link rel="stylesheet" href="<?php echo base_url('css/layout.css') ?>">
		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css') ?>">
		<link href="<?php echo base_url('css/bootstrap-switch.min.css');?>" rel="stylesheet">
		<script src="<?php echo base_url('js/bootstrap-switch.min.js');?>"></script>

		<title><?php echo $titulo;?></title>
		<script>
			jQuery(document).ready(function(){
			  $('#data').mask('11/11/1111');
			  $('#ano').mask('1111');
			  $('.data').mask('11/11/1111');
			  $('.hora').mask('11:11');
			  $('.datahora').mask('11/11/1111 11:11');
			  $(".data").datepicker({ showButtonPanel: true}); // box com calendario
			  $(".data_afterwards").datepicker({ showButtonPanel: true, minDate: new Date()}); // box com calendario
			  $('#cep').mask('00000-000');
			  $('#placa').mask('AAA-0000').css({"text-transform":"uppercase"});
			  $('#telefone').mask('(00) 0000-0000');
			  $('.telefone').mask('(00) 0000-0000');
			  $('#celular').mask('(00) 0000-00000');
			  $('.celular').mask('(00) 0000-00000');
			  $('#cpf').mask('000.000.000-00', {reverse: true});
			  $('.cpf').mask('000.000.000-00', {reverse: true});
			  $('#rg').mask('00.000.000-A', {reverse: true});
			  $('.rg').mask('00.000.000-A', {reverse: true});
			  $('#valor').mask('0000,00', {reverse: true});
			  $("#cnpj").mask("99.999.999/9999-99");
			  
			  $("#close_modal").on("click",function(){
			  	$(".modal").fadeOut();
			  });

			  $("[name='my-checkbox']").bootstrapSwitch();

			  $("#tel_condo").on("focus",function(){
			 	 $(this).attr('style', 'border: solid 1px #ffffff !important');
			  		//.css({"": " !important;"});
			  });
			  $("#tel_condo").on("blur",function(){
			  	 $(this).attr('style', 'border: none !important; background-color: #f5f5f5 !important;');
			  });
			  <?php
			  	if ($this->template->verifica_acesso($menus,"editar")==FALSE){
			  ?>
			  $("#cadastrar,.entregar,[type='submit']").hide();
			  $(".editar").text("Detalhes");
			  $("textarea,select,input,checkbox,radio").prop("disabled","disabled");
			  <?php
			  	}
			  ?>
			});
			function vercpf(cpf) 
			{
			 cpf = cpf.replace(/[^\d]+/g,'');
			 console.log(cpf);
				if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
					return false;
				add = 0;
				for (i=0; i < 9; i ++)
					add += parseInt(cpf.charAt(i)) * (10 - i);
					rev = 11 - (add % 11);
				if (rev == 10 || rev == 11)
					rev = 0;
				if (rev != parseInt(cpf.charAt(9)))
					return false;
				add = 0;
				for (i = 0; i < 10; i ++)
					add += parseInt(cpf.charAt(i)) * (11 - i);
					rev = 11 - (add % 11);
				if (rev == 10 || rev == 11)
					rev = 0;
				if (rev != parseInt(cpf.charAt(10)))
					return false;
				
				return true;
			}
			
			function validaBusca(form) {
				var required = $("#form"+form+">[required='required']");
				var req = 0;
				for ( i = 0; i < required.length; i++) {
					if ($(required[i]).val() == "") {
						$(required[i]).css("border-color", "#ff0000");
						req = 1;
					}
				}

				if (req == 1 ) {
					$(".modal").fadeIn('slow');
					$(".modal-title").text("Ops! Tivemos um problema ... ");
					$(".modal-body").text("Você deve preencher o campo para fazer uma busca !");
					return false;
				}
			}
			
			webcam.set_api_url("<?php echo base_url('camera/tirar_foto');?>");
			webcam.set_quality( 100 ); // JPEG quality (1 - 100)
			webcam.set_shutter_sound( false ); // play shutter click sound
	
		</script> 
	</head>
	<body <?php echo ($this->session->userdata("tipo") == 4 ? "onload=\"$('#aviso-lateral').remove();\"": "");?>>
	<!-- MINIATURA IMAGEM USUARIO -->
	<div class='imagem-usuario arredonda-100'></div>


	<div id="topo"><a href='<?php echo base_url('unidades');?>' style="text-decoration: none;"><div id="logotipo"></div></a></div>
	<?php
		if ($this->session->userdata('logado')){
	?>
	<a href='<?php echo base_url('unidades');?>' style="text-decoration: none;"><div id="home-lateral" title="Voltar ao Início"></div></a>
	<div id="ativa-menu" class="animacao-05s" title="Configurações"></div>
	<div id="menu-lateral" class="animacao-05s">
		<div class="conteudo">
			
			<div class="titulo">Buscar <div class="fechar cor-vermelho arredonda-15 fechar-lateral">X</div></div>
			Digite no campo abaixo o que você deseja encontrar:
			<div>
				<form action="<?php echo base_url('busca');?>" method="post" accept-charset="utf-8" id="form1"  class="form-inline" role="form" onsubmit="return validaBusca(1);">
					<button type="submit" class="button-modelo-1 arredonda-100 button-sucess" style="position: absolute; right: 4px; margin-top: 12px; padding: 5px 10px 5px 10px; z-index: 2;"><img src="<?php echo base_url('img/icone/lupa-branca.png');?>" alt="" border="0" /></button>
					<input type="text" name="busca" value=""  required='required' class="input-modelo-1 arredonda-100 uppercase aparence-none busca" style="margin-top: 10px;" placeholder="Quero encontrar..." />
				</form>
			</div>
			<br/><br/>
			<div class="titulo">Configurações</div>
			<ul id="opcoes-lateral">
				<?php
					foreach ($menus as $menu){
						if($menu['visualizar']==1){
				?>
							<a href='<?php echo base_url($menu['controller']);?>' style="text-decoration: none;"><li><?php echo $menu['pagina'];?></li></a>
				<?php
						}
					}
				?>
				<a href='<?php echo base_url('login/sair');?>' style="text-decoration: none;"><li>Sair</li></a>
			</ul>
		</div>
	</div>
	<?php 
		}
	?>

	<div class="modal fade bs-example-modal-sm in" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="false" id="flash" <?php if (!empty($error) || !empty($messege)) echo 'style="display: block;"';?>>
		  <div class="modal-dialog modal-sm">
		    <div class="modal-content">
				<div class="modal-header">
					
					<h4 class="modal-title">
						<?php
							echo (!empty($error)?"Ops! Tivemos um problema ... ":NULL); // EXIBIR MENSAGEM DE ERRO 
				
							echo (!empty($messege)!=NULL?"Sucesso !":NULL); // EXIBIR MENSAGEM DE SUCESSO	
						?>
					</h4>
				</div>
				<div class="modal-body">
					<p>
						<?php
							echo (!empty($error)?$error:NULL); // EXIBIR MENSAGEM DE ERRO 
				
							echo (!empty($messege)!=NULL?$messege:NULL); // EXIBIR MENSAGEM DE SUCESSO	
						?>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal" id='close_modal'>Fechar</button>
				</div>
		    </div>
		</div>
	</div>
	<!-- CONTEÚDO DO SITE -->
	<div id="conteudo" class="col-md-12">
		<div class="conteudo-dentro">
		<?php
		$telas = array ('servicos/prestadores','visitantes/','visitantes/autorizados','empregados/','ocorrencias/','reparos/','areas/relatorio_utilizacao_areas','unidades/registro');
		if ($this->session->userdata('unidade') && in_array( $this->uri->segment(1)."/".$this->uri->segment(2), $telas)){
		?>
			
			
				<div class="col-md-6">
					<div class="uppercase" style="text-align: left;">
						<form action="<?php echo base_url('unidades/unidade_submit/'.$unidade['id_unidade']);?>" method="post" class="form-group" enctype="multipart/form-data">
							<input type="text" name="telefone" class="form-control telefone" id="tel_condo" value="<?php echo $unidade['telefone'];?>" style="border:none !important;background-color: #f5f5f5 !important;" placeholder="Digite o número de telefone da unidade e pressione enter !" ></input>
						</form>
					</div>
				</div>
				<div class="col-md-6">
					<div class="uppercase" style="text-align: right;">
					Eu estou em: <strong><?php echo ($unidade['bloco']!="Condomínio"?"Unidade ".$unidade['unidade']."</strong> da <strong>Torre ".$unidade['bloco']:"Condomínio");?></strong><br/>
					<a href="<?php echo base_url('unidades/registro/'.$unidade['id_unidade']);?>" class="link-painel arredonda-100">Voltar para o Painel da Unidade</a>
					</div>
					
				</div>
			<div class="col-md-12">
				<div class="divisor-horizontal" style="margin: 20px 0px 20px 0px;"></div>
			</div>
			
		<?php
			
		}
			$this->load->view($view);//exibe a tela
		?>
		</div>
	</div>

	<?php
	if ($this->session->userdata('unidade') && in_array( $this->uri->segment(1)."/".$this->uri->segment(2), $telas)){ 
	?>
	<div id="rodape" class="col-md-12">
		<div class="conteudo-dentro" style="width: 1100px; margin: 0 auto;">
			<div class="col-md-7">
				<h3>Ferramentas <strong>Rápidas</strong></h3>
				<ul id="opcoes-rodape">
					<a href="<?php echo base_url('visitantes/registro');?>"><li>Chegou novo visitante!</li></a>
					<a href='<?php echo base_url('servicos/prestadores');?>'><li>Prestadores de Serviços</li></a>
					<a href='<?php echo base_url('empregados');?>'><li>Empregados Domésticos</li></a>
					<a href='<?php echo base_url('ocorrencias');?>'><li>Livro de Ocorrência</li></a>
					<a href='<?php echo base_url('reparos');?>'><li>Solicitação de Reparos</li></a>
					<a href='<?php echo base_url('areas/relatorio_utilizacao_areas');?>'><li>Solicitação de Chaves</li></a>
					
				</ul>
			</div>
			<div class="col-md-5">
				<h3>Quero <strong>Encontrar</strong></h3>
				<div>
				<form action="<?php echo base_url('busca');?>" method="post" accept-charset="utf-8"  class="form-inline" id="form2" role="form" onsubmit="return validaBusca(2);">
					<button type="submit" class="button-modelo-1 arredonda-100 button-sucess" style="position: absolute; right: 24px; margin-top: 18px; padding: 5px 10px 5px 10px; z-index: 2;"><img src="<?php echo base_url('img/icone/lupa-branca.png');?>" alt="" border="0" /></button>
					<input type="text" name="busca" value="" required='required' class="input-modelo-1 arredonda-100 uppercase aparence-none busca" style="margin-top: 10px;" placeholder="Quero encontrar..." />
				</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	?>

	</body>
</html>

