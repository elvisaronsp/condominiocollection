<script>
	function validaServico() {
	  	var required =$(".servico>[required='required']").parent('form');
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
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Serviço</strong></div>
		<div class="panel-body" style="padding: 10px;">
<?php $id_servico = !empty($servico)?$servico['id_servico']:NULL;?>
<form action="<?php echo base_url('servicos/servicos_submit/'.$id_servico);?>" method="post" class='servico' accept-charset="utf-8" onsubmit='return validaServico()'>
  <div class="col-md-10"><label for="servico">Serviço</label><input class="form-control" type="text" name="servico" value="<?php echo !empty($servico['servico'])?$servico['servico']:"";?>" id="servico" required="required"/><br/></div>
  <div class="col-md-2"><br/><input class="btn btn-success" type="submit" value="<?php echo !empty($servico)?"Alterar":"Cadastrar";?>"/></div>
</form>
</div></div>
