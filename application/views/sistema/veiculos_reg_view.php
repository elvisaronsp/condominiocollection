<?php $id_veiculo = !empty($veiculo)?$veiculo['id_veiculo']:NULL; ?>
<script>
	$(function(){
		$("#bloco").on("change",function(){ // carregar unidades disponíveis
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
							
							$("#unidade").html("<option value=''>Selecione</option>");
							$.each( unidades, function( key, val ) {
								$("#unidade").append("<option value='"+val.id_unidade+"'>"+val.unidade+"</option>");
							});
							
						}else{

							if ($("select#unidade option").length>1)
								$("select#unidade option:first").remove();
								
							$("select#unidade option").text('Não há vagas disponíveis para esta Torre !');		
							
						}
					}
				});
				
			}else{
				$("#unidade").html("<option value=''></option>");
			}
		});		
	});		
	function validaVeiculo() {
	  	var required = $(".veiculos>[required='required']").parent('form');
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
<!-- FORM DE INFORMAÇÕES Do VEÍCULO -->
<div id="cad_veiculo">
	
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Cadastro de Veículo:</strong></div>
		<div class="panel-body">

	<form action="<?php echo base_url('veiculos/veiculos_submit/'.$id_veiculo);?>" method="post" accept-charset="utf-8" class="veiculos" onsubmit='return validaVeiculo();' enctype="multipart/form-data">        
        <div class="col-md-6">
		  <label for="tipo">Veículo*</label>
			<select name='tipo' class="form-control" id='tipo_veiculo' required='required'>
				<option value=""></option>
				<?php
				
					foreach ($veiculos_tipos as $tipo){
				?>
					<option value="<?php echo $tipo['id_tipo'];?>" <?php echo !empty($veiculo)?($veiculo['id_tipo']==$tipo['id_tipo']?"selected='selected'":""):"";?> ><?php echo $tipo['tipo'];?></option>	
				<?php
					}
				?>
			</select>
            
           </div>
			<div class="col-md-2" >
	            <label for="bloco">Torre</label>
	            <select id="bloco" class="form-control" required='required' <?php echo (!empty($veiculo)?"disabled='disabled'":"");?> >
	            	<option value="">Selecione</option>
	            	<option value="A" <?php echo (!empty($veiculo)?$veiculo['bloco']=='A'?"selected='selected'":"":"");?>>A</option>
	            	<option value="B" <?php echo (!empty($veiculo)?$veiculo['bloco']=='B'?"selected='selected'":"":"");?>>B</option>
	            	<option value="C" <?php echo (!empty($veiculo)?$veiculo['bloco']=='C'?"selected='selected'":"":"");?>>C</option>
	            </select>
	        </div>
	        
	
			<div class="col-md-2">
	
				<label for="unidade">Unidade</label>
				<select name="unidade" class="form-control" id="unidade" name="unidade" required='required' <?php echo (!empty($veiculo)?"disabled='disabled'":"");?> />
					<option value=""></option>
					<?php
						if (!empty($veiculo)){
					?>
						<option value="<?php echo $veiculo['id_unidade'];?>" selected='selected'><?php echo $veiculo['unidade'];?></option>
					<?php
						}
					?>
				</select>
				            
			</div>
          	
       		<div class="col-md-6">
		  
		  	<label for="placa">Placa</label><input class="form-control" type="text" name="placa" value="<?php echo !empty($veiculo)?$veiculo['placa']!=NULL?$veiculo['placa']:"":"";?>" id="placa" <?php echo (!empty($veiculo)?($veiculo['id_tipo']==1?"disabled='disabled'":""):"");?>/>
            </div>
            <div class="col-md-6">
			<label for="marca">Marca</label><input class="form-control" type="text" name="marca" value="<?php echo !empty($veiculo)?$veiculo['marca']!=NULL?$veiculo['marca']:"":"";?>" id="marca" <?php echo (!empty($veiculo)?($veiculo['id_tipo']==1?"disabled='disabled'":""):"");?>/>
            </div>
            <div class="col-md-6">
			<label for="modelo">Modelo</label><input class="form-control" type="text" name="modelo" value="<?php echo !empty($veiculo)?$veiculo['modelo']!=NULL?$veiculo['modelo']:"":"";?>" id="modelo" <?php echo (!empty($veiculo)?($veiculo['id_tipo']==1?"disabled='disabled'":""):"");?>/>
            </div>
            <div class="col-md-6">
			<label for="ano">Ano</label><input class="form-control" type="text" name="ano" value="<?php echo !empty($veiculo)?$veiculo['ano']!=NULL?$veiculo['ano']:"":"";?>" id="ano" maxlength="4" <?php echo (!empty($veiculo)?($veiculo['id_tipo']==1?"disabled='disabled'":""):"");?>/>
            </div>
            
            <div class="col-md-6">
			<label for="cor">Cor*</label><input class="form-control" type="text" name="cor" value="<?php echo !empty($veiculo)?$veiculo['cor']!=NULL?$veiculo['cor']:NULL:NULL;?>" id="cor" required='required'/>
            </div>
            <div class="col-md-12">
            <br>
		  	<input class="btn btn-success" type="submit" value="<?php echo !empty($veiculo)?"Alterar":"Cadastrar";?> Veículo"/>
		  	<button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('veiculos');?>"'>Voltar</button>
            </div>
		</form>
        </div>
        </div>
</div>

<br/>&nbsp;