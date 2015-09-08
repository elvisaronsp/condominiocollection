<?php $id_reparo = (!empty($reparo)?$reparo['id_reparo']:NULL);?>
<script>
	$(function(){		
		<?php 
			if (!empty($reparo)){
		?>
			$("input,select,textarea").prop("disabled","disabled").css({"border":"none","background":"transparent"});
			$("[type='submit']").hide();
		<?php
			}	
		?>
	});
	function validaReparos() // validação do formulário de informações do Visitante
	{
	  	var required = $("[required='required']").parent('form');
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
<div class="col-md-12">
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Reparos</strong></div>
		<div class="panel-body">

<form action="<?php echo base_url('reparos/reparos_submit/'.$id_reparo);?>" method="post" accept-charset="utf-8" onsubmit='return validaReparos();'>
  <div class="col-md-1">
	    	<label for="bloco">Torre</label>
	        <input type='text' class="form-control" value="<?php echo (!empty($reparo)?$reparo['bloco']:$unidade['bloco']);?>" readonly="redonly">
    </div>
	<div class="col-md-4">
			<label for="unidade">Unidade</label>
			<input type='text' class="form-control" value="<?php echo (!empty($reparo)?$reparo['unidade']:$unidade['unidade']);?>" readonly="redonly"/>
			<input type='hidden' class="form-control" value="<?php echo (!empty($reparo)?$reparo['id_unidade']:$unidade['id_unidade']);?>" name="unidade" />
  </div>
  <div class="col-md-2">
  <label for="tipo">Nível de Urgência:</label>
  <select class="form-control" id="tipo" name='urgencia' required="required">
  	<option value=""></option>
  	<?php
  		foreach($tipos as $tipo){
  	?>
  	<option value="<?php echo $tipo['id_nivel'];?>" <?php echo (!empty($reparo)?($reparo['urgencia']==$tipo['id_nivel']?"selected='selected'":""):"");?>><?php echo $tipo['nivel'];?></option>
  	<?php
  		}
  	?>
  </select>
  </div>
  
  <div class="col-md-5">
  	<label for="titulo">Título</label><input type="text"  class="form-control" name="titulo" value="<?php echo !empty($reparo['titulo'])?$reparo['titulo']:NULL?>" id="titulo"/>
  </div>
    <div class="col-md-12">
  
  <label for="reparo">Reparo</label><textarea class="form-control" name="descricao" rows="8" cols="40" id="reparo"  required="required"><?php echo !empty($reparo)?$reparo['descricao']:"";?></textarea>
  </div>
    <div class="col-md-2">
  <label for="data">Data da solicitação</label><input class="form-control data" type="text" name="data" value="<?php echo !empty($reparo)?date('d/m/Y',strtotime($reparo['data'])):"";?>" required='required'/>
</div>
  <div class="col-md-12"><br>
  <p>
  	<input class="btn btn-success" type="submit" value="<?php echo !empty($reparo)?"Alterar":"Cadastrar";?>"/>
  	<button type='button' class="btn btn-warning" onclick='window.location.href="<?php echo base_url('reparos');?>"'>Voltar</button>
  </p>
  
  </div>
</form>
</div>
</div>
</div>
