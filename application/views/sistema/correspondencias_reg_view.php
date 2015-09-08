<?php $id_correspondencia = !empty($correspondencia)?$correspondencia['id_correspondencia']:NULL; ?>

<script>
	$(function(){
		<?php 
			if ($id_correspondencia!=NULL){
		?>
			$("input,select,textarea").prop("disabled","disabled").css({"border":"none","background":"transparent"});
			$("[type='submit']").hide();
		<?php
			}
		?>
		$("#bloco").on("change",function(){ // carregar unidades disponíveis
			if ($(this).val()!=""){
				$("select#unidade option:first").text('Carregando ...');
				$.ajax({
					url:'<?php echo base_url('unidades/getUnidadesByBloco');?>/',
					type:'post',
					data:$.param({
						bloco:$(this).val()
					}),
					success:function(r){
						console.log(r);
						if (r!='null'){
							var unidades = $.parseJSON(r);
							 
							$("select#unidade option:first").text('Selecione');
							var options = "";
							$.each( unidades, function( key, val ) {
								
								if (val.status == 1)
									options += "<option value='"+val.id_unidade+"'>"+val.unidade+"</option>";
								else
									options += "<option value='' >"+val.unidade+" - Unidade vazia</option>";
									
								$("#unidade").html(options);
							});
						}else{
							$("#unidade").html("");
							$("#unidade").append("<option value=''>Não há unidades disponíveis para esta Torre !</option>");
						}
					}
				});
				
			}
			$("#unidade").html("<option value=''></option>");
		});
		var i = 0;
		$("#atual").on("click",function(){
			
			if (i==0){
				$(".datahora").val('<?php echo date('d/m/Y H:i');?>');
				$(".datahora").prop('readonly','readonly');
				i =1;
			}else{
				$(".datahora").removeAttr('readonly');
				i = 0;
			}
		});

	});
	function validaCorrespondencia() // validação do formulário de informações do proprietário
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
		<div class="panel-heading"><strong>Detalhes da correspondência </strong></div>
		<div class="panel-body">

<form action="<?php echo base_url('correspondencias/correspondencias_submit/'.$id_correspondencia); ?>" method="post" accept-charset="utf-8" onsubmit='return validaCorrespondencia();'>

		<div class="col-md-1">
            <label for="bloco">Torre</label><br>
            <?php echo $correspondencia['bloco'];?>
            
        </div>
        

<div class="col-md-1">

		<label for="unidade">Unidade</label><br>
			<?php echo $correspondencia['id_unidade'];?>
            
</div>
  <div class="col-md-3">

  <label for="tipo">Tipo de Correspondência</label><br>
  <?php echo $correspondencia['tipo'];?>
    </div>
    
<div class="col-md-2">

  
  <label for="destinatario">Destinatário</label><br>
  <?php echo !empty($correspondencia)?$correspondencia['destinatario']:NULL;?>
  </div>
  
  <div class="col-md-1">
  <label for="quantidade">Quantidade</label><br><?php echo !empty($correspondencia)?$correspondencia['quantidade']:NULL;?>
  </div>
  <div class="col-md-2">
  <label for="data">Data e Hora</label><br><?php echo !empty($correspondencia)?date("d/m/Y H:i",strtotime($correspondencia['data'])):"";?>
  </div>
  <div class="col-md-12">
  <label>Observações:</label><br><?php echo !empty($correspondencia)?(!empty($correspondencia['observacoes'])?$correspondencia['observacoes']:"Sem observações"):"";?>
  </div>
  
  <div class="col-md-12"><br>
  <button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('correspondencias');?>"'>Voltar</button>
  </p></div>
  
</form>

</div>
