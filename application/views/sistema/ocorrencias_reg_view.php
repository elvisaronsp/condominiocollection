<?php $id_ocorrencia = (!empty($ocorrencia)?$ocorrencia['id_ocorrencia']:NULL);?>
<script>
	$(function(){		
		<?php 
			if (!empty($ocorrencia)){
		?>
			$("input,select,textarea").prop("disabled","disabled").css({"border":"none","background":"transparent"});
			$("[type='submit']").hide();
		<?php
			}	
		?>
	});
	function validaocorrencias() // validação do formulário de informações da ocorrencia
	{
	  		  	var required =$(".ocorrencias>[required='required']").parent('form');
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
	<div class="panel-heading"><strong>Ocorrencias</strong></div>
		<div class="panel-body">

<form action="<?php echo base_url('ocorrencias/ocorrencias_submit/'.$id_ocorrencia);?>" method="post" accept-charset="utf-8" class="concorrencias" onsubmit='return validaocorrencias();'>
  <div class="col-md-1">
	    	<label for="bloco">Torre</label>
	        <input type='text' class="form-control" value="<?php echo (!empty($ocorrencia)?$ocorrencia['bloco']:$unidade['bloco']);?>" readonly="redonly">
    </div>
	<div class="col-md-4">
			<label for="unidade">Unidade</label>
			<input type='text' class="form-control" value="<?php echo (!empty($ocorrencia)?$ocorrencia['unidade']:$unidade['unidade']);?>" readonly="redonly"/>
			<input type='hidden' class="form-control" value="<?php echo (!empty($ocorrencia)?$ocorrencia['id_unidade']:$unidade['id_unidade']);?>" name="unidade" />
  </div>
  <div class="col-md-2">
  <label for="tipo">Nível de Urgência:</label>
  <select class="form-control" id="tipo" name='urgencia'  required='required'>
  	<option value=""></option>
  	<?php
  		foreach($tipos as $tipo){
  	?>
  	<option value="<?php echo $tipo['id_nivel'];?>" <?php echo (!empty($ocorrencia)?($ocorrencia['urgencia']==$tipo['id_nivel']?"selected='selected'":""):"");?>><?php echo $tipo['nivel'];?></option>
  	<?php
  		}
  	?>
  </select>
  </div>
  <div class="col-md-5">
  	<label for="titulo">Título</label><input type="text"  class="form-control" name="titulo" value="<?php echo (!empty($ocorrencia)?$ocorrencia['titulo']:NULL);?>" id="titulo"/>
  </div>
  <div class="col-md-12">
  
  <label for="ocorrencia">Ocorrência</label><textarea class="form-control" name="ocorrencia" rows="8" cols="40" id="ocorrencia"  required="required"><?php echo !empty($ocorrencia)?$ocorrencia['ocorrencia']:"";?></textarea>
  </div>
  <div class="col-md-2">
  <label for="data">Data da ocorrência</label><input class="form-control data" type="text" name="data_ocorrencia" value="<?php echo !empty($ocorrencia)?date('d/m/Y',strtotime($ocorrencia['data_ocorrencia'])):"";?>" required='required'/>
  </div>
  <div class="col-md-12"><br>

  <p>
  	<input class="btn btn-success" type="submit" value="<?php echo !empty($ocorrencia)?"Alterar":"Cadastrar";?>"/>
    
  	<button type='button' class="btn btn-warning" onclick='window.location.href="<?php echo base_url('ocorrencias');?>"'>Voltar</button>
    </div>
  </p>
</form>

</div>
</div>
</div>
