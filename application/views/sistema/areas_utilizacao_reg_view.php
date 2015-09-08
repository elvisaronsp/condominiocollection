<?php $id_utilizacao = !empty($utilizacao)?$utilizacao['id_utilizacao']:NULL;?>
<script>
	var area = true;
	$(function(){		
		$("#areas").on("change",function(){
			
			if ($(this).val()!="" && ($(".data").val()!="" && $(".data").val().length==10)){
				$.ajax({ // verificando se há disponibilidade para liberar chaves para esta área
					url:'<?php echo base_url('areas/verifica_disponibilidade');?>',
					type:'post',
					data:$.param({
						data_reserva:$(".data").val(),
						id_area:$(this).val()
					}),
					success:function(r){
						if (r=='null'){
							$("#flash").text("");
							$(".data,#areas").css("border-color", "#3c763d");
							area = true;
						}else{
							$("#flash").text("Área já reservada para esta data!");
							$(".data,#areas").css("border-color", "#ff0000");
							area = false;
						}
					}
					
				});				
			}
		});
		
		$(".data").on("blur focus click",function(){
			if (($(this).val()!="" && $(this).val().length==10) && $("#areas").val()!=""){
				$.ajax({ // verificando se há disponibilidade para liberar chaves para esta área
					url:'<?php echo base_url('areas/verifica_disponibilidade');?>',
					type:'post',
					data:$.param({
						data_reserva:$(this).val(),
						id_area:$("#areas").val()
					}),
					success:function(r){
						if (r=='null'){
							$(".data,#areas").css("border-color", "#3c763d");
							area = true;
						}else{
							$(".modal").fadeIn('slow');
							$(".modal-title").text("Ops! Tivemos um problema ... ");
							$(".modal-body").text("Área já reservada para esta data!");
							$(".data,#areas").css("border-color", "#ff0000");
							area = false;
						}
					}
					
				});
			}
		});
		
		var r = 0;
		$("#retirada").on("click",function(){
			
			if (r==0){
				$("#data_retirada").val('<?php echo date('d/m/Y H:i');?>');
				$("#data_retirada").prop('readonly','readonly');
				r = 1;
			}else{
				$("#data_retirada").removeAttr('readonly');
				r = 0;
			}
		});
		
		
	});

	function validaUtilizacao() {
	  	  	var required =$(".area>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if (req == 1 || area==false) {
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
</script>
<div class="col-md-12">
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Solicitar Chave</strong></div>
		<div class="panel-body">

<form action="<?php echo base_url('areas/utilizacao_submit/'.$id_utilizacao);?>" method="post" class="area" accept-charset="utf-8" onsubmit='return validaUtilizacao();'>
    <div class="col-md-1">
	    	<label for="bloco">Torre</label>
	        <input type='text' class="form-control" value="<?php echo (!empty($utilizacao)?$utilizacao['bloco']:$unidade['bloco']);?>" readonly="redonly">
    </div>
	<div class="col-md-2">
			<label for="unidade">Unidade</label>
			<input type='text' class="form-control" value="<?php echo (!empty($utilizacao)?$utilizacao['unidade']:$unidade['unidade']);?>" readonly="redonly"/>
			<input type='hidden' class="form-control" value="<?php echo (!empty($utilizacao)?$utilizacao['id_unidade']:$unidade['id_unidade']);?>" name="unidade" />
  </div>
  <div class="col-md-4">
  	<label for="morador">Morador</label>
  	<select id="morador" name="morador" class="form-control" required="required">
  		<option value="">Selecione o morador</option>
  		<?php
  			foreach($moradores as $morador){
  		?>
  			<option value="<?php echo $morador['nome'];?>"><?php echo $morador['nome'];?></option>
  		<?php
			}
		?>
  	</select>
  </div>
  <div class="col-md-4">
  <label for="areas">Áreas*</label><br/>
  	<select name="area" value="" class="form-control" id="areas" required="required"/>
  		<option value=""></option>
  		<?php
  			foreach ($areas as $area){
  		?>
  			<option value="<?php echo $area['id_area'];?>"><?php echo $area['area'];?></option>
  		<?php
  			}
		?>
  	</select>
  </div>
  <div class="col-md-2">
  	<label for="data">Data Reserva*</label><br/><input type="text" name="data_reserva" value="" class="data form-control"/>
  </div>
  <div class="col-md-3">
  	<label for="data_retirada">Data e Hora da Retirada*</label><br/>
  	<input type="text" name="data_retirada" value="" class="datahora form-control" id="data_retirada"/>
  </div>
  
  <div class="col-md-2" style=" margin-top: 25px;">
      <div class="btn-group" data-toggle="buttons"  id="retirada">
      	<label class="btn btn-default" for="retirada"><input type='checkbox'>Data e Hora atual</label>
      </div>
  </div>
  <div class="col-md-12" style="margin-top: 15px;">
  <button type='submit' class='editar btn btn-success'>Cadastrar</button>
	</div>
</form>
</div></div>
</div>
