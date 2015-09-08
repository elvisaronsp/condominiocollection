<script>
	$(function(){
		$(".bloco").on("click",function(){ // carregar unidades disponíveis
			$(".grupo-botao button").addClass("hover").css({"background" : "#b7b7b7", "box-shadow" : "0px 1px 0px #9d9d9d"});
			$(this).removeClass("hover").css({"background" : "#f0d200", "box-shadow" : "0px 1px 0px #ecc200"});
			
			if ($(this).val()!=""){
				$("input").attr("readonly","readonly").removeAttr('required').val("");
				$("#unidade").removeAttr("disabled");
				$("#unidade option:first").text('Carregando ...')
					$.ajax({
						url:'<?php echo base_url('unidades/getUnidadesByBloco');?>/',
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
								$("#unidade").append("<option value=''>Não há unidades cadastradas para esta Torre !</option>");	
							}
						}
					});
			}else{
				$("#unidade").html("<option value=''></option>");	
			}
			
		});	
		$("#placa").on("click focus",function(){
			$("button").removeClass("active");
			$(this).removeAttr("readonly").attr("required","required");
			$("input:not(#placa)").attr("readonly","readonly").removeAttr('required').val("");
			$("#unidade").attr("disabled","disabled").val("");
		});
		$("#morador").on("click focus",function(){
			$("button").removeClass("active");
			$(this).removeAttr("readonly").attr("required","required");
			$("input:not(#morador)").attr("readonly","readonly").removeAttr('required').val("");
			$("#unidade").attr("disabled","disabled").val("");
		});
		$("#proprietario").on("click focus",function(){
			$("button").removeClass("active");
			$(this).removeAttr("readonly").attr("required","required");
			$("input:not(#proprietario)").attr("readonly","readonly").removeAttr('required').val("");
			$("#unidade").attr("disabled","disabled").val("");
		});	
	});
</script>


<form action="<?php echo base_url("unidades/registro");?>" method="post" accept-charset="utf-8" style="position: relative; width: 500px; margin: 0 auto;">

	<div class="col-md-12">
		<div class="grupo-botao">
			<button type="button" class="bloco button-modelo-1 arredonda-left-100 uppercase hover button-default width-33" value="A">Torre A</button>
			<button type="button" class="bloco button-modelo-1 uppercase hover button-default width-33" value="B">Torre B</button>
			<button type="button" class="bloco button-modelo-1 arredonda-right-100 uppercase hover button-default width-33" value="C">Torre C</button>
		</div>
	</div>
	<div class="col-md-12" style="margin-top: 10px;">
		<img src="img/icone/setas-input-login.jpg" alt="" border="0" style="position: absolute; right: 40px; top: 24px; z-index: 1;" />
		<select name="id_unidade" id="unidade" class="input-modelo-1 arredonda-100 uppercase aparence-none" style="height: 80px; font-size: 1.5em;" required='required'/>
			<option value="" selected>Unidades Encontradas</option>
		</select>
	</div>
	<div class="col-md-12">
		<div class="divisor-horizontal"></div>
	</div>
	<div class="col-md-12">
		<button type="submit" class="button-modelo-1 arredonda-100 uppercase button-sucess width-100">Buscar</button>
	</div>
	<div class="col-md-12">
		<br/>
		<a href="<?php echo base_url('unidades/registro/1000');?>" style="text-decoration: none;"><div class="button-modelo-1 arredonda-100 uppercase button-blue width-100" style="text-align: center; text-decoration: none;">Condomínio</div></a>
	</div>
</form>