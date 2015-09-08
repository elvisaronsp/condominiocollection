<script>
	$(function(){		
		<?php 
			if (!empty($vaga)){
		?>
			$("#dados_Vaga").find("input,select,textarea").prop("disabled","disabled").css({"border":"none","background":"transparent"});
			$("#dados_Vaga").find("[type='submit']").hide();
		<?php
			}
		?>
		$(".bloco").on("change",function(){ // carregar unidades disponíveis
			var unidade_first  = $(this).parent().parent().find("select.unidade option:first");
			var unidade_opt = $(this).parent().parent().find("select.unidade option");
			var unidade = $(this).parent().parent().find("select.unidade");
			
			if ($(this).val()!=""){
			
				$("select#unidade option:first").text('Carregando ...');
				$.ajax({
					url:'<?php echo base_url('unidades/getUnidadesByBloco');?>',
					type:'post',
					data:$.param({
						bloco:$(this).val()
					}),
					success:function(r){
						if (r!='null'){
							
							$("select option:first").text('Selecione');
							var unidades = $.parseJSON(r);
							
							unidade.html("<option value=''>Selecione</option>");
							$.each( unidades, function( key, val ) {
								unidade.append("<option value='"+val.id_unidade+"'>"+val.unidade+"</option>");
							});
							
						}else{

							if (unidade_opt.length>1)
								unidade_first.remove();
								
							unidade_opt.text('Não há vagas disponíveis para esta Torre!');		
							
						}
					}
				});
				
			}else{
				unidade.html("<option value=''></option>");
			}
		});
		
		$("#utilizar").on("click",function(){
			$("#utilizador").toggle();
			$(this).toggleClass("btn-danger");
		});
		
	});
	function validaVagas() // validação do formulário de informações do Visitante
	{
	  		  	var required =$(".vagas>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}

		if (req == 1) {
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
</script>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Vaga da Unidade <strong><?php echo $vaga['unidade']; ?></strong> da Torre <strong><?php echo $vaga['bloco'];?></strong></div>
			<div class="panel-body">
				
				<form action="<?php echo base_url('unidades/vagas_submit/'. (!empty($vaga['id_vaga'])?$vaga['id_vaga']:NULL));?>" method="post" id="dados_Vaga" accept-charset="utf-8" class="vagas" onsubmit='return validaVagas();'>
					<div class="col-md-4">
						<label for="vaga">Vaga</label>
						<input type='text' class="form-control" value="<?php echo $vaga['vaga'];?>" name="vaga" maxlength="4" />
					</div>
					<div class="col-md-4">
			            <label for="bloco">Torre</label>
			            <select class="form-control bloco" required='required'>
			            	<option value="">Selecione</option>
			            	<option value="A" <?php echo (!empty($vaga)?$vaga['bloco']=='A'?"selected='selected'":"":"");?>>A</option>
			            	<option value="B" <?php echo (!empty($vaga)?$vaga['bloco']=='B'?"selected='selected'":"":"");?>>B</option>
			            	<option value="C" <?php echo (!empty($vaga)?$vaga['bloco']=='C'?"selected='selected'":"":"");?>>C</option>
			            	<option value="Condomínio" <?php echo (!empty($vaga)?$vaga['bloco']=='Condomínio'?"selected='selected'":"":"");?>>Condomínio</option>
			            </select>
			        </div>
			        
					
					<div class="col-md-4">
					
							<label for="unidade">Unidade</label>
								<select name="unidade" class="form-control unidade" required='required'/>
									<option value=""></option>
									<?php
										if (!empty($vaga)){
									?>
										<option value="<?php echo $vaga['id_unidade'];?>" selected='selected'><?php echo $vaga['unidade'];?></option>
									<?php
										}
									?>
								</select>
					            
					</div>
					<div class="col-md-12">
						<br>
						<button type='submit' class='detalhes btn btn-success'><?php echo !empty($vaga)?"Alterar":"Cadastrar";?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php if (!empty($vaga)){
	?>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Atual Utilizador da Vaga <strong><?php echo $vaga['vaga']; ?></strong></div>
			<div class="panel-body">
				<?php 
				 if(!empty($utilizador)){ 
					echo "Unidade <strong>".$utilizador['unidade']."</strong> Bloco <strong>".$utilizador['bloco']."</strong> (<strong>".($utilizador['tipo']=="L"?"Locação/Empréstimo":"Fixo")."</strong>)";
				?>
				<p><button type="button" class="btn btn-success" id="utilizar"> Transferir Vaga</button></p>
				<?php	 
				 }else{ 
					echo "Ninguém utilizando esta vaga atualmente.";
				?>
				<p><button type="button" class="btn btn-success" id="utilizar">Novo Utilizador</button></p>
				<?php
				 } 
				?>
				
				<form action="<?php echo base_url('unidades/vagas_utilizacao_submit/'.$vaga['id_vaga']."/".(!empty($utilizador)?$utilizador['id_utilizacao']:NULL));?>" id="utilizador" style="display:none;" method="post">
					<br>
					<div class="col-md-5">
			            <label for="bloco">Bloco</label>
			            <select class="form-control bloco" required='required'>
			            	<option value="">Selecione</option>
			            	<option value="A">A</option>
			            	<option value="B">B</option>
			            	<option value="C">C</option>
			            </select>
		        	</div>
		        
		
					<div class="col-md-7">
						<label for="unidade">Unidade</label>
						<select name="utilizador" class="form-control unidade" required='required'/>
							<option value=""></option>
						</select>
					</div>
					<div class="col-md-12">
						<br>
						<button type="submit" class="btn btn-success">Concluído</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">Histórico da Vaga <strong><?php echo $vaga['vaga']; ?></strong></div>
			<div class="panel-body">
				<?php 
				 if(!empty($historicos)){
				 	foreach($historicos as $historico){
				 ?> 
				 <div class="row">
				 	<div class="col-md-12">
						<?php echo date("d/m/Y",strtotime($historico['data']))." - ".$historico['log']; ?>
					</div>
				</div>
				<?php
					}
				 }else{ 
					echo "Sem histórico a exibir da vaga";
				 } 
				?>
			</div>
		</div>
	</div>
	<?php 
	}
	?>
	<div class="col-md-12" align="right">
		<button type='button' class='detalhes btn btn-danger' onclick="window.location.href='<?php echo base_url('unidades/vagas');?>'">Voltar</button>
	</div>
</div>