<script>
	function validaUtilizacao() {
	  	  	var required =$(".area>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if (req == 1) {
			$("#flash").text("Preencha a área corretamente !");
			return false;
	}
</script>
<?php $id_area = !empty($area)?$area['id_area']:NULL;?>
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Solicitar Chave</strong></div>
		<div class="panel-body">
			<form action="<?php echo base_url('areas/areas_submit/'.$id_area);?>" method="post" class="area" accept-charset="utf-8" onsubmit='return validaArea();'>
			  <div class="col-md-9">
			  	<label for="area">Área</label><br/>
			  	<input class="form-control" type="text" name="area" value="<?php echo !empty($area['area'])?$area['area']:"";?>" id="area" required='required'/>
			  </div>
			  <div class="col-md-3">
			  	<br/>
				  <input class="btn btn-success" type="submit" value="<?php echo !empty($area)?"Alterar":"Cadastrar";?>"/>
				  <button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('areas');?>"'>Voltar</button>
			</form>
		</div>
</div>