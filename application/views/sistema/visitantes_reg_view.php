<script>
	$(function(){		
		var a = 0;
		$("#atual").on("click",function(){
			
			if (a==0){
				
				$(".datahora").val('<?php echo date('d/m/Y H:i');?>');
				$(".datahora").prop('readonly','readonly');
				a = 1;
			}else{
				$(".datahora").removeAttr('readonly');
				a = 0;
			}
		});
		
		var c = 0;
		$("#doc_cpf").on("click",function(){
			if (c==0){
				$("[name='documento']").mask("111.111.111-11");
				$("[name='documento']").removeAttr('disabled');
				$("[name='documento']").val("");
				c=1;
			}
		});
		
		var r = 0;
		$("#doc_rg").on("click",function(){
			if (r==0){
				$("[name='documento']").mask("11.111.111-A");
				$("[name='documento']").removeAttr('disabled');
				$("[name='documento']").val("");
				r=1;
			}
		});
		
		$("#pessoas").on("click",function(){
			$("#entraram").toggle();
		});
		
		$("#entrou").on("click",function(){
			
			if ($(this).parent().parent().find("#nome").val()!=""){
				console.log($(this).parent());
				
				$(this).parent().parent().find("#nome").css("border-color", "#ccc");
				$(this).parent().parent().find('#visitantes .col-md-4').last().clone().appendTo("#visitantes");
				$(this).parent().parent().find("#visitantes").find("input").prop('readonly','readonly');
				$("#visitantes").find("input").last().val("").removeAttr('readonly');
			}else{
				$(this).parent().parent().find("#nome").css("border-color", "#ff0000");
				$(".modal").fadeIn('slow');
				$(".modal-title").text("Ops! Tivemos um problema ... ");
				$(".modal-body").text("Preencha todos os campos corretamente !");
				return false;
			}
		});
	});


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
		
		if ($("#doc_cpf").is(':checked') && vercpf($("[name='documento']").val())==false){
			$("[name='documento']").css("border-color", "#ff0000");
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("O CPF informado não é valido !");
			return false;
		}
	}
</script>

<div class="col-md-12">
<form action="<?php echo base_url('visitantes/visitantes_submit');?>" method="post" class="visitante" accept-charset="utf-8" onsubmit="return validaVisitante();">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Visitantes</strong></div>
		<div class="panel-body">


	
	<div class="col-md-12" style="padding: 0px;" > 
		
		<div class="col-md-4" style="padding-left: 0px;text-align:center" class="webcam"> 
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
			<div class="col-md-12" style="padding: 0px; margin: 0px;">
				<div class="col-md-2">
					<div style="position: relative; width: 160px; height: 120px; left: -50px;">
						<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
						<img src="<?php echo !empty($visitante)?(!empty($visitante['foto'])?base_url("uploads/visitantes/".$visitante['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
						<input type="hidden" name="foto" value="" id="foto"/>
					</div>
				</div>
			  	<div class="col-md-3">
			    	<label for="bloco">Torre</label>
			        <input type='text' class="form-control" value="<?php echo $unidade['bloco'];?>" readonly="redonly">
			    </div>
				<div class="col-md-3">
					<label for="unidade">Unidade</label>
				   <input type='text' class="form-control" value="<?php echo $unidade['unidade'];?>" readonly="redonly"/>
				   <input type='hidden' class="form-control" value="<?php echo $unidade['id_unidade'];?>" name="unidade" />
			  	</div>
			  	<div class="col-md-4">
					<label for="visitante">Visitante</label><input type="text" class="form-control" name="visitante" value="" id="visitante"  required="required"/>
			  	</div>

			  	<div class="col-md-4">
			  		<label for="documento">Documento</label>
					<label for='doc_cpf'>CPF</label>
					<input  type='radio' name='doc' id='doc_cpf' required='required'>
					<label for='doc_rg'>RG</label> 
					<input  type='radio' name='doc' id='doc_rg' required='required'> 
					<input class="form-control" type="text" name="documento" value="" disabled="disabled"  required='required'/>
			  	</div>
			  	<div class="col-md-3">
					<label for="data">Data e Hora</label><input class="form-control datahora" type="text" name="data" value="" required='required'/>
				</div>
				<div class="col-md-3">
					<br/>
					<div class="btn-group" data-toggle="buttons"  id="atual">
						<label class="btn btn-default" for="atual"><input type='checkbox'>Data e Hora atual</label>
					</div>
				</div>
		  	</div>

		  	<div class="col-md-12" style="padding: 0px; margin-top: 25px;">
				Entrou com mais pessoas? 
				<label for='pessoas'>Sim</label>
				<input type='checkbox' id='pessoas'>
			</div>

			<div class="col-md-12" id="entraram"  style='display:none; padding: 0px;'>
				<div id="visitantes">
					<div class="col-md-4" >
						<label for="nome">Nome</label><input type="text" class="form-control" name="nome[]" value="" id="nome"/>
					</div>
				</div>

				<div class="col-md-4">
					<br>
					<button class="btn btn-success" type="button" id="entrou"/>Entrou</button>
				</div>
			</div>
	  	</div>
	</div>

</div>
</div>

<p style="text-align: right;">
	<input class="btn btn-success" type="submit" value="Cadastrar"/>
	<button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('visitantes');?>"'>Voltar</button>
</p>
</form>
</div>