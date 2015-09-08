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
							$("select#unidade option:first").text('Selecione');
							var unidades = $.parseJSON(r);
							$("#unidade").html("");
							$.each( unidades, function( key, val ) {
								$("#unidade").append("<option value='"+val.id_unidade+"'>"+val.unidade+"</option>");
							});
						}else{
							$("#unidade").html("");
							$("#unidade").append("<option value=''>Não há unidades disponíveis para este bloco !</option>");	
						}
					}
				});
				
			}
			$("#unidade").html("<option value=''></option>");
		});
	});
	function validabicicleta() {
	  	  	var required =$(".bicicleta>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if (req == 1) {
			$("#flash").text("Preencha todos os campos corretamente corretamente !");
			return false;
		}
	}
</script>
<?php $id_bicicleta = !empty($bicicleta)?$bicicleta['id_bicicleta']:NULL;?>
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Adicionar nova bicicleta</strong></div>
		<div class="panel-body">

	<form action="<?php echo base_url('bicicletario/bicicletas_submit/'.$id_bicicleta);?>" method="post" class="bicicleta" accept-charset="utf-8" onsubmit='return validabicicleta();'>
	 <div class="col-md-1">
	            <label for="bloco">Torre</label>
	            <select id="bloco" class="form-control" required='required' style="width:65px;">
	            	<option value="">Selecione</option>
	            	<option value="A" <?php echo (!empty($bicicleta)?$bicicleta['bloco']=='A'?"selected='selected'":"":"");?>>A</option>
	            	<option value="B" <?php echo (!empty($bicicleta)?$bicicleta['bloco']=='B'?"selected='selected'":"":"");?>>B</option>
	            	<option value="C" <?php echo (!empty($bicicleta)?$bicicleta['bloco']=='C'?"selected='selected'":"":"");?>>C</option>
	            </select>
	        </div>
			<div class="col-md-1">

					<label for="unidade">Unidade</label>
					<select name="unidade" class="form-control"  id="unidade" required='required' style="width:70px;"/>
						<option value=""></option>
						<?php
							if (!empty($bicicleta)){
						?>
							<option value="<?php echo $bicicleta['id_unidade'];?>" selected='selected'><?php echo $bicicleta['unidade'];?></option>
						<?php
							}
						?>
					</select>
			</div>
			<div class="col-md-3">
				<label for="lacre">Lacre</label><input type="text" name="lacre" id="lacre" class="form-control" value="<?php echo (!empty($bicicleta)?$bicicleta['lacre']:NULL); ?>" required="required" maxlength="15">
			</div>
					<div class="col-md-3" id="">
						<label for="cor">Cor</label><input type="text" name="cor" id="cor" class="form-control" required="" value="<?php echo (!empty($bicicleta)?$bicicleta['cor']:NULL); ?>">
							
					</div>
	  <div class="col-md-3">
	  	<br/>
		  <input class="btn btn-success" type="submit" value="<?php echo !empty($bicicleta)?"Alterar":"Cadastrar";?>"/>
		  <button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('bicicletario');?>"'>Voltar</button>
	  </div>
	</form>

</div>
</div>
