<script>
	$(function(){
		<?php
			if(!empty($empregado)){
		?>
			$("input:not([type='button']),select").prop('disabled','disabled').css({"border":"none","background":"transparent"});
			$("[type='submit'],[type='checkbox']").hide();
			$(".btn-group").removeAttr('data-toggle');
		<?php
			}else{
		?>
			$(".btn-default").on("click",function(){
				$(this).removeClass('btn-danger');
				$(this).toggleClass("btn-success");
			});
		<?php
			}
		?>
	});
	
	function validaEmpregado() // validação do formulário de informações do empregado
	{
	  	  	var required =$(".empregado>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}	
		if(!$("[type='checkbox']:checked").length){
			$("#dias").find('.btn-group label').addClass("btn-danger");
			req = 1;
		}else{
			$("#dias").find('.btn-group label').removeClass("btn-danger");
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
<form action="<?php echo base_url('empregados/empregados_submit'); ?>" method="post" accept-charset="utf-8" class="empregado" onsubmit='return validaEmpregado();'>
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Empregados</strong></div>
		<div class="panel-body">

	<div class="col-md-4" style="padding-left: 0px;text-align:center" id="webcam"> 
		<script language="JavaScript">
			document.write( webcam.get_html(320, 240, 160, 120));
		</script>
		<br><br>
		<input type="button" value="Configurar WebCam" onClick="webcam.configure()" class="btn btn-primary">
		<input type="button" value="Tirar Foto" onClick="take_snapshot()" class="btn btn-success">
		<script language="JavaScript">
			webcam.set_hook( 'onComplete', 'my_completion_handler' );
			function take_snapshot() {
				webcam.snap();
			}
			function my_completion_handler(msg) {
				// extract URL out of PHP output
				if (msg.match(/(http\:\/\/\S+)/)) {
					var image_url = RegExp.$1;
					// show JPEG image in page
					$("#foto").val(image_url);
					$("#foto_exibir").attr("src",image_url);
					webcam.reset();
				}
					else console.log("PHP Error: " + msg);
			}
		</script>
	</div>
	<div class="col-md-8">
		<div class="col-md-2">
			<div style="position: relative; width: 160px; height: 120px; left: -50px;">
				<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
				<img src="<?php echo !empty($empregado)?(!empty($empregado['foto'])?base_url("uploads/empregados/".$empregado['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
				<input type="hidden" name="foto" value="" id="foto"/>
			</div>
		</div>
		<div class="col-md-2">
		    	<label for="bloco">Torre</label>
		        <input type='text' class="form-control" value="<?php echo (!empty($empregado)?$empregado['bloco']:$unidade['bloco']);?>" readonly="redonly">
	    </div>
		<div class="col-md-8">
				<label for="unidade">Unidade</label>
				<input type='text' class="form-control" value="<?php echo (!empty($empregado)?$empregado['unidade']:$unidade['unidade']);?>" readonly="redonly"/>
				<input type='hidden' class="form-control" value="<?php echo (!empty($empregado)?$empregado['id_unidade']:$unidade['id_unidade']);?>" name="unidade" />
	  </div>
	    <div class="col-md-10">
		  <label for="nome">Nome</label><input class="form-control" type="text" name="nome" value="<?php echo !empty($empregado) ? $empregado['nome'] : "";?>" id="nome" maxlength="100" required='required'/>
		</div>
	</div>
	

  <div class="col-md-3">
  <label for="rg">RG</label><input class="form-control" type="text" name="rg" value="<?php echo !empty($empregado) ? $empregado['rg'] : "";?>" id="rg" required='required'/>
</div>
  <div class="col-md-3">
  <label for="cpf">CPF</label><input class="form-control" type="text" name="cpf" value="<?php echo !empty($empregado) ? $empregado['cpf'] : "";?>" id="cpf" required='required'/>
  </div>
  <div class="col-md-6">
  <label for="endereco">Endereço</label><input class="form-control" type="text" name="endereco" value="<?php echo !empty($empregado) ? $empregado['endereco'] : "";?>" id="endereco" maxlength="50" required='required'/>
 </div>
  <div class="col-md-2">
  <label for="numero">Número</label><input class="form-control" type="text" name="numero" value="<?php echo !empty($empregado) ? $empregado['numero'] : "";?>" id="numero" maxlength="5" required='required'/>
  </div>
  <div class="col-md-4">
  <label for="complemento">Complemento</label><input class="form-control" type="text" name="complemento" value="<?php echo !empty($empregado) ? !empty($empregado['complemento'])?$empregado['complemento'] : "" : ""; ?>" id="complemento" maxlength="50"/>
</div>
  <div class="col-md-4">
  <label for="bairro">Bairro</label><input class="form-control" type="text" name="bairro" value="<?php echo !empty($empregado) ? $empregado['bairro'] : "";?>" id="bairro" maxlength="50" required='required'/>
  </div>
  <div class="col-md-4">
  <label for="cidade">Cidade</label><input class="form-control" type="text" name="cidade" value="<?php echo !empty($empregado) ? $empregado['cidade'] : "";?>" id="cidade" maxlength="50" required='required'/>
  </div>
  <div class="col-md-4">
  <label for="estado">Estado</label>
  	<select class="form-control" name="estado" id="estado" required='required'>
  		<option value="">Selecione</option>
		<option value="AC" <?php echo (!empty($empregado)?$empregado['estado']=='AC'?"selected='selected'":"":"");?>>AC</option>
		<option value="AL" <?php echo (!empty($empregado)?$empregado['estado']=='AL'?"selected='selected'":"":"");?>>AL</option>
		<option value="AM" <?php echo (!empty($empregado)?$empregado['estado']=='AM'?"selected='selected'":"":"");?>>AM</option>
		<option value="AP" <?php echo (!empty($empregado)?$empregado['estado']=='AP'?"selected='selected'":"":"");?>>AP</option>
		<option value="BA" <?php echo (!empty($empregado)?$empregado['estado']=='BA'?"selected='selected'":"":"");?>>BA</option>
		<option value="CE" <?php echo (!empty($empregado)?$empregado['estado']=='CE'?"selected='selected'":"":"");?>>CE</option>
		<option value="DF" <?php echo (!empty($empregado)?$empregado['estado']=='DF'?"selected='selected'":"":"");?>>DF</option>
		<option value="ES" <?php echo (!empty($empregado)?$empregado['estado']=='ES'?"selected='selected'":"":"");?>>ES</option>
		<option value="GO" <?php echo (!empty($empregado)?$empregado['estado']=='GO'?"selected='selected'":"":"");?>>GO</option>
		<option value="MA" <?php echo (!empty($empregado)?$empregado['estado']=='MA'?"selected='selected'":"":"");?>>MA</option>
		<option value="MG" <?php echo (!empty($empregado)?$empregado['estado']=='MG'?"selected='selected'":"":"");?>>MG</option>
		<option value="MS" <?php echo (!empty($empregado)?$empregado['estado']=='MS'?"selected='selected'":"":"");?>>MS</option>
		<option value="MT" <?php echo (!empty($empregado)?$empregado['estado']=='MT'?"selected='selected'":"":"");?>>MT</option>
		<option value="PA" <?php echo (!empty($empregado)?$empregado['estado']=='PA'?"selected='selected'":"":"");?>>PA</option>
		<option value="PB" <?php echo (!empty($empregado)?$empregado['estado']=='PB'?"selected='selected'":"":"");?>>PB</option>
		<option value="PE" <?php echo (!empty($empregado)?$empregado['estado']=='PE'?"selected='selected'":"":"");?>>PE</option>
		<option value="PI" <?php echo (!empty($empregado)?$empregado['estado']=='PI'?"selected='selected'":"":"");?>>PI</option>
		<option value="PR" <?php echo (!empty($empregado)?$empregado['estado']=='PR'?"selected='selected'":"":"");?>>PR</option>
		<option value="RJ" <?php echo (!empty($empregado)?$empregado['estado']=='RJ'?"selected='selected'":"":"");?>>RJ</option>
		<option value="RN" <?php echo (!empty($empregado)?$empregado['estado']=='RN'?"selected='selected'":"":"");?>>RN</option>
		<option value="RO" <?php echo (!empty($empregado)?$empregado['estado']=='RO'?"selected='selected'":"":"");?>>RO</option>
		<option value="RR" <?php echo (!empty($empregado)?$empregado['estado']=='RR'?"selected='selected'":"":"");?>>RR</option>
		<option value="RS" <?php echo (!empty($empregado)?$empregado['estado']=='RS'?"selected='selected'":"":"");?>>RS</option>
		<option value="SC" <?php echo (!empty($empregado)?$empregado['estado']=='SC'?"selected='selected'":"":"");?>>SC</option>
		<option value="SE" <?php echo (!empty($empregado)?$empregado['estado']=='SE'?"selected='selected'":"":"");?>>SE</option>
		<option value="SP" <?php echo (!empty($empregado)?$empregado['estado']=='SP'?"selected='selected'":"":"");?>>SP</option>
		<option value="TO" <?php echo (!empty($empregado)?$empregado['estado']=='TO'?"selected='selected'":"":"");?>>TO</option>
  	</select>
    </div>
    <div class="col-md-4">
  <label for="cep">CEP</label><input class="form-control" type="text" name="cep" value="<?php echo !empty($empregado) ? $empregado['cep'] : "";?>" id="cep" maxlength="9" required='required'/>
  </div>
  <div class="col-md-4">
  <label for="funcao">Função</label><input class="form-control" type="text" name="funcao" value="<?php echo !empty($empregado) ? $empregado['funcao'] : "";?>" id="funcao" maxlength="50" required='required'/>
  </div>
  <div class="col-md-4">
  <label for="telefone">Telefone</label><input class="form-control" type="text" name="telefone" value="<?php echo !empty($empregado) ? $empregado['telefone'] : "";?>" id="telefone" required='required'/>
  </div>
  <div class="col-md-4">
  <label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo !empty($empregado) ? !empty($empregado['celular'])?$empregado['celular'] : "" : ""; ?>" id="celular"/>
  </div>
  <div class="col-md-12" style="padding-top:10px;"  id='dias'>  
  <label for='dias'>Dias</label>
  <div class="btn-group" data-toggle="buttons">
  	<?php
  		if (!empty($empregado)){
  			$dias = explode(",", $empregado['dias']);
  		}
	?>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Segunda-feira", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Segunda-feira" <?php echo !empty($empregado)?in_array("Segunda-feira", $dias)?"checked='checked'":"":"";?> >Segunda-feira</label>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Terça-Feira", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Terça-Feira" <?php echo !empty($empregado)?in_array("Terça-Feira", $dias)?"checked='checked'":"":"";?>>Terça-Feira</label>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Quarta-Feira", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Quarta-Feira" <?php echo !empty($empregado)?in_array("Quarta-Feira", $dias)?"checked='checked'":"":"";?>>Quarta-Feira</label>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Quinta-Feira", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Quinta-Feira" <?php echo !empty($empregado)?in_array("Quinta-Feira", $dias)?"checked='checked'":"":"";?>>Quinta-Feira</label>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Sexta-Feira", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Sexta-Feira" <?php echo !empty($empregado)?in_array("Sexta-Feira", $dias)?"checked='checked'":"":"";?>>Sexta-Feira</label>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Sábado", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Sábado" <?php echo !empty($empregado)?in_array("Sábado", $dias)?"checked='checked'":"":"";?>>Sábado</label>
      <label class="btn btn-default <?php echo !empty($empregado)?in_array("Domingo", $dias)?"btn-success active":"":"";?>"><input type='checkbox' name='dias[]' value="Domingo" <?php echo !empty($empregado)?in_array("Domingo", $dias)?"checked='checked'":"":"";?>>Domingo</label>
  </div>
    </div>

</div>
</div>
<p align="right"><input class="btn btn-success" type="submit" value="<?php echo !empty($empregado)?"Alterar":"Cadastrar"; ?>"/>&nbsp;<input class="btn btn-danger" type="button" value="Voltar" onclick="window.location.href='<?php echo base_url('empregados');?>'"/></p>
</form>
</div>
