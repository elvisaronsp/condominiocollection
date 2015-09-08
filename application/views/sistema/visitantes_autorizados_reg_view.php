<script>
	$(function(){
		$("[type='file']").on("change",function(){
			if($(this).val()!=null && $(this).val()!=""){
				$("#bicam").hide();
				console.log("exonte");
			}
		});
	})
</script>
<script>
	function validaVisitante() // validação do formulário de informações do Visitante
	{
	  		  	var required =$(".visitante>[required='required']").parent('form');
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
		arquivo = $("[type='file']").val();
			
			if (arquivo!=""){
				tipo = arquivo.substr(arquivo.length-4,arquivo.length);
				tipo = tipo.toLowerCase();
				
				if ((tipo != "jpeg") && (tipo != ".jpg") && (tipo != ".gif") && (tipo != ".bmp") && (tipo != ".png")) {
					$("#arquivo").css({
						"border-color":"red"
					});
					
					 $("[type='file']").parent().append("<br><small style='color:red;'>O arquivo deve ter um dos seguintes formatos: .JPG,.GIF,.PNG ou .BMP</small>");
					return false;
				}
			}
		if (vercpf($("#cpf").val())==false){
			$("#cpf").css("border-color", "#ff0000");
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("O CPF informado não é valido !");
			return false;
		}
	}
</script>

<div class="col-md-12">
<form action="<?php echo base_url('visitantes/autorizados_submit');?>" method="post" accept-charset="utf-8" class="visitante" onsubmit="return validaVisitante();" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Visitantes Autorizados</strong></div>
		<div class="panel-body">

		<div class="col-md-12" style="padding: 0px;" >
			<div class="col-md-4" style="padding-left: 0px;text-align:center" class="webcam" id="bicam"> 
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
							$("#arq").hide().find('input').attr('name','');
							webcam.reset();
						}
							else console.log("PHP Error: " + msg);
					}
				</script>
			</div>
			<div class="col-md-8">
				<div class="col-md-2">
					<label>Foto</label>
					<div style="position: relative; width: 160px; height: 120px; left: -50px;">
						<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
						<img src="<?php echo !empty($autorizado)?(!empty($autorizado['foto'])?base_url("uploads/autorizados/".$autorizado['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
						<input type="hidden" name="foto" value="" id="foto"/>
					</div>
				</div>
				 
		<div class="col-md-10" id="arq">
			<label for="arquivo">Via upload</label><input  class="form-control" type="file" name="foto" value="" id="arquivo"/>
			
		</div>
			  	<div class="col-md-2">
			    	<label for="bloco">Torre</label>
			            <input type='text' class="form-control" value="<?php echo $unidade['bloco'];?>" readonly="redonly">
			            	
			    </div>
				<div class="col-md-3">
					<label for="unidade">Unidade</label>
				   <input type='text' class="form-control" value="<?php echo $unidade['unidade'];?>" readonly="redonly"/>
				   <input type='hidden' class="form-control" value="<?php echo $unidade['id_unidade'];?>" name="unidade" />
			  	</div>
			  	<div class="col-md-5">
					<label for="visitante">Visitante</label><input type="text" class="form-control" name="nome" value="" id="visitante"  required="required"/>
			  	</div>

			  	<div class="col-md-3">
			  		<label for="cpf">CPF</label>
  					<input class="form-control" type="text" name="cpf" value="" id="cpf" required='required'/>
			  	</div>
			  	<div class="col-md-3">
			  		<label for='rg'>RG</label> 
  					<input  class="form-control" type="text" name="rg" value="" id="rg" required='required'/>
			  	</div>
			  	<div class="col-md-4">
			  		<label for="endereco">Endereco</label><input type="text" class="form-control" name="endereco" value="" id="endereco"  required="required"/>
			  	</div>
			</div>

			<div class="col-md-8" style="margin-top: 20px;">
				<div class="col-md-2">
			  	<label for="numero">Número</label><input type="text" class="form-control" name="numero" value="" id="numero" required="required"/>
			  </div>
			  
			  <div class="col-md-3">
			  	<label for="complemento">Complemento</label><input type="text" class="form-control" name="complemento" value="" id="complemento"/>
			  </div>
			  
			  <div class="col-md-3">
			  	<label for="cep">CEP</label><input type="text" class="form-control" name="cep" value="" id="cep"  required="required"/>
			  </div>
			  
			  <div class="col-md-4">
			  	<label for="bairro">Bairro</label><input type="text" class="form-control" name="bairro" value="" id="bairro"  required="required"/>
			  </div>
			  <div class="col-md-4">
			  	<label for="cidade">Cidade</label><input type="text" class="form-control" name="cidade" value="" id="cidade"  required="required"/>
			  </div>
			  <div class="col-md-5">
			  	<label for="estado">Estado</label>
			  	<select class="form-control" name="estado" id="estado" required='required'>
				  		<option value="">Selecione</option>
						<option value="AC" >AC</option>
						<option value="AL" >AL</option>
						<option value="AM" >AM</option>
						<option value="AP" >AP</option>
						<option value="BA" >BA</option>
						<option value="CE" >CE</option>
						<option value="DF" >DF</option>
						<option value="ES" >ES</option>
						<option value="GO" >GO</option>
						<option value="MA" >MA</option>
						<option value="MG" >MG</option>
						<option value="MS" >MS</option>
						<option value="MT" >MT</option>
						<option value="PA" >PA</option>
						<option value="PB" >PB</option>
						<option value="PE" >PE</option>
						<option value="PI" >PI</option>
						<option value="PR" >PR</option>
						<option value="RJ" >RJ</option>
						<option value="RN" >RN</option>
						<option value="RO" >RO</option>
						<option value="RR" >RR</option>
						<option value="RS" >RS</option>
						<option value="SC" >SC</option>
						<option value="SE" >SE</option>
						<option value="SP" >SP</option>
						<option value="TO" >TO</option>
				  	</select>
			  </div>
			</div>
		</div>


  
  
<div class="col-md-12">
	<label for="observacoes">Observações</label>
	<textarea name="observacoes" rows="8" cols="40" id="observacoes" class="form-control"></textarea>
</div>
  

</div>
</div>
<p style="text-align: right;">
	<input class="btn btn-success" type="submit" value="Cadastrar"/>
	<button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('visitantes/autorizados');?>"'>Voltar</button>
</p>
</form>
</div>