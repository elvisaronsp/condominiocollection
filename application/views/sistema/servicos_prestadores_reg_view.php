<script>
	$(function(){
		<?php
			if (!empty($prestador) && $prestador['data_fim']!=null){
		?>
			$('form').find('input,select').attr('disabled','disabled');
			$("[type='submit']").hide();	
			$("[type='button']").removeAttr('disabled','');
		<?php
			}
		?>
		$("#bloco").on("change",function(){ // carregar unidades disponíveis
			if ($(this).val()!=""){
				$("select#unidade option:first").text('Carregando ...');
				$.ajax({
					url:'<?php echo base_url('unidades/getUnidadesByBloco');?>',
					type:'post',
					data:$.param({
						bloco:$(this).val()
					}),
					success:function(r){
						
						if (r!='null'){
							$("select option:first").text('Selecione');
							var unidades = $.parseJSON(r);
							$("#unidade").html("");
							$.each( unidades, function( key, val ) {
								$("#unidade").append("<option value='"+val.id_unidade+"'>"+val.unidade+"</option>");
							});
						}else{
							$("#unidade").html("");
							$("#unidade").append("<option value=''>Não há unidades disponíveis para esta Torre !</option>");		
						}
					}
				});
				
			}
		});
		
		$("#nome_exibe").on("change",function(){
			$( "#servico" ).val("");
		});
		
		$("#nome_exibe").autocomplete({
			source: "<?php echo base_url('servicos/busca_servicos/');?>",
			focus: function( event, ui ) {
		        $( "#nome_exibe" ).val( ui.item.servico );
		        
		        return false;
		      },
		      select: function( event, ui ) {
		        $( "#nome_exibe" ).val( ui.item.servico );
		        $( "#servico" ).val(ui.item.id_servico);
		        morador = true;
		        return false;
		      }
		    })
		    .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		      return $( "<li>" )
		        .append( "<a>" + item.servico+"</a>" )
		        .appendTo( ul );
		        
		    };
		 $("[type='submit']").on("click",function(){
		 	
			if ($("#servico").val()==""){
				$("#new_service").dialog({
			      resizable: false,
			      buttons: {
			        "Sim": function() {
			         	$("form").submit();
			        },
			        "Não": function() {
			          $( this ).dialog( "close" );
			          return false
			        }
			      }
	   			});
	   			return false;
	   		}
		 });
		 
		var i = 0;
		$("#atual_ini").on("click",function(){
			
			if (i==0){
				$("[name='data_inicio']").val('<?php echo date('d/m/Y H:i');?>');
				$("[name='data_inicio']").prop('readonly','readonly');
				i =1;
			}else{
				$("[name='data_inicio']").val('');
				$("[name='data_inicio']").removeAttr('readonly');
				i = 0;
			}
		});
		var f = 0;
		$("#atual_fim").on("click",function(){
			
			if (f==0){
				$("[name='data_fim']").val('<?php echo date('d/m/Y H:i');?>');
				$("[name='data_fim']").prop('readonly','readonly');
				f =1;
			}else{
				$("[name='data_fim']").val('');
				$("[name='data_fim']").removeAttr('readonly');
				f = 0;
			}
		});
		$("#doc_cpf").on("click",function(){
				$("[name='documento']").val("");
				$("[name='documento']").mask("111.111.111-11");
				$("[name='documento']").removeAttr('disabled');
		});		
		var r = 0;
		$("#doc_rg").on("click",function(){
				$("[name='documento']").val("");
				$("[name='documento']").mask("11.111.111-A");
				$("[name='documento']").removeAttr('disabled');
		});
	});
	function validaPrestadores() {
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
		if ($("#doc_cpf").is(':checked') && vercpf($("[name='documento']").val())==false){
			$("[name='documento']").css("border-color", "#ff0000");
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("O CPF informado não é valido !");
			return false;
		}
	}
</script>

<?php $id_prestador = !empty($prestador)?$prestador['id_prestador']:NULL; ?>
 <div class="panel panel-default">
 <form action="<?php echo base_url('servicos/prestadores_submit/'.$id_prestador);?>" method="post" class="servicos" accept-charset="utf-8" onsubmit="return validaPrestadores();" enctype="multipart/form-data">
	<div class="panel-heading"><strong>Prestador</strong></div>
		<div class="panel-body" style="padding: 10px;">

<div id="new_service" style='display:none;'>Este serviço que digitou não existe, deseja cadastrá-lo juntamente com o prestador ?</div>

	<div class="col-md-12">
	<?php 
		if(empty($prestador)){
	?>
	<div class="col-md-4" style="padding-left: 0px;text-align:center;" class="webcam"> 
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
		<div class="col-md-3">
			<div style="position: relative; width: 160px; height: 120px; ">
				<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
				<img src="<?php echo !empty($prestador)?(!empty($prestador['foto'])?base_url("uploads/prestadores/".$prestador['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
				<input type="hidden" name="foto" value="" id="foto"/>
			</div>
		</div> 
	  	<div class="col-md-2">
			<label for="bloco">Torre</label>
			<input type='text' class="form-control" value="<?php echo $unidade['bloco'];?>" readonly="redonly">      	
	    </div>
		<div class="col-md-4">
			<label for="unidade">Unidade</label>
			<input type='text' class="form-control" value="<?php echo $unidade['unidade'];?>" readonly="redonly"/>
			<input type='hidden' class="form-control" value="<?php echo $unidade['id_unidade'];?>" name="unidade" />
	  	</div>

		<div class="col-md-3"> 	
			<label for="servico">Serviço</label>
			<input class="form-control" type="text" name="nome_exibe" value="<?php echo !empty($prestador)?$prestador['servico']:"";?>" id="nome_exibe" required='required' />
			<input class="form-control" type="hidden" name="servico" value="<?php echo !empty($prestador)?$prestador['id_servico']:"";?>" id="servico"/>
	    </div>
		<div class="col-md-9"> 	
			<label for="nome">Nome</label><input class="form-control" type="text" name="nome" value="<?php echo !empty($prestador)?$prestador['nome']:"";?>" id="nome" required='required'/>
		</div>
		<div class="col-md-12">
			<div class="col-md-5">
			  	<label for="documento">Documento</label>
			  	<label for='doc_cpf'>CPF</label>
			  		<input  type='radio' name='doc' id='doc_cpf' required='required'>
			  	<label for='doc_rg'>RG</label> 
			  		<input  type='radio' name='doc' id='doc_rg' required='required'> 
			  	<input class="form-control" type="text" name="documento" value="<?php echo !empty($prestador)?$prestador['documento']:"";?> " disabled="disabled"  required='required'/>
			</div>
			<div class="col-md-7"> 	
				<label for="empresa">Empresa</label><input class="form-control" type="text" name="empresa" value="<?php echo !empty($prestador)?$prestador['empresa']:"";?>" id="empresa" required='required'/>
		    </div>
		    <div class="col-md-3"> 	
			<label for="telefone">Telefone</label><input class="form-control" type="text" name="telefone" value="<?php echo !empty($prestador)?$prestador['telefone']:"";?>" id="telefone" required='required'/>
		    </div>

		    <div class="col-md-3"> 	
		
			<label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo !empty($prestador['celular'])?$prestador['celular']:"";?>" id="celular"/>
		    </div>
		    <div class="col-md-4"> 	
			<label for="data_inicio">Data Início</label><input type="text" name="data_inicio" value="<?php echo (!empty($prestador)?date('d/m/Y',strtotime($prestador['data_inicio'])):NULL);?>" id="data_inicio" class='datahora form-control' required='required'/>
		    </div>
		    <div class="col-md-1" style="padding-left: 0px;">
		    	<br/>
				<div class="btn-group btn-sm" data-toggle="buttons" id="atual_ini">
				<label class="btn btn-default" for="atual"><input type='checkbox' > Data e Hora atual </label>
				</div>
		  	</div>
		  	<div class="col-md-5"> 	
		
			<label for="data_fim">Data Término</label><input type="text" name="data_fim" value="<?php echo (!empty($prestador['data_fim'])?date('d/m/Y',strtotime($prestador['data_fim'])):NULL);?>" id="data_fim" class='datahora form-control'/>
		    </div>
		  	<div class="col-md-1" style="padding-left: 0px;" >
		  		<br/>
			<div class="btn-group btn-sm" data-toggle="buttons" id="atual_fim">
				<label class="btn btn-default" for="atual"><input type='checkbox' > Data e Hora atual </label>
			</div>
		  </div>
		</div>
	</div>
	<p style="text-align: right;">
	<input class="btn btn-success" type="submit" value="<?php echo !empty($prestador)?"Alterar":"Cadastrar"; ?>"/>
	<input class="btn btn-danger" type="button" value="Voltar" onclick='window.location.href="<?php echo base_url('servicos/prestadores');?>"'/>
</p>
    <?php
	}else {
	?>	
	
		<div class="col-md-2">
			<div style="position: relative; width: 160px; height: 120px;">
				<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
				<img src="<?php echo !empty($prestador)?(!empty($prestador['foto'])?base_url("uploads/prestadores/".$prestador['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
				<input type="hidden" name="foto" value="" id="foto"/>
			</div>
		</div> 
	  	<div class="col-md-1">
			<label for="bloco">Torre</label>
			<input type='text' class="form-control" value="<?php echo $unidade['bloco'];?>" readonly="redonly">      	
	    </div>
		<div class="col-md-2">
			<label for="unidade">Unidade</label>
			<input type='text' class="form-control" value="<?php echo $unidade['unidade'];?>" readonly="redonly"/>
			<input type='hidden' class="form-control" value="<?php echo $unidade['id_unidade'];?>" name="unidade" />
	  	</div>

		<div class="col-md-3"> 	
			<label for="servico">Serviço</label>
			<input class="form-control" type="text" name="nome_exibe" value="<?php echo !empty($prestador)?$prestador['servico']:"";?>" id="nome_exibe" required='required' />
			<input class="form-control" type="hidden" name="servico" value="<?php echo !empty($prestador)?$prestador['id_servico']:"";?>" id="servico"/>
	    </div>
		<div class="col-md-4"> 	
			<label for="nome">Nome</label><input class="form-control" type="text" name="nome" value="<?php echo !empty($prestador)?$prestador['nome']:"";?>" id="nome" required='required'/>
		</div>
		<div class="col-md-3">
		  	<label for="documento">Documento</label>
		  	<label for='doc_cpf'>CPF</label>
		  		<input  type='radio' name='doc' id='doc_cpf' required='required'>
		  	<label for='doc_rg'>RG</label> 
		  		<input  type='radio' name='doc' id='doc_rg' required='required'> 
		  	<input class="form-control" type="text" name="documento" value="<?php echo !empty($prestador)?$prestador['documento']:"";?> " disabled="disabled"  required='required'/>
		</div>
		<div class="col-md-4"> 	
			<label for="empresa">Empresa</label><input class="form-control" type="text" name="empresa" value="<?php echo !empty($prestador)?$prestador['empresa']:"";?>" id="empresa" required='required'/>
	    </div>
	    <div class="col-md-3"> 	
		<label for="telefone">Telefone</label><input class="form-control" type="text" name="telefone" value="<?php echo !empty($prestador)?$prestador['telefone']:"";?>" id="telefone" required='required'/>
	    </div>
			
			
		    

		    <div class="col-md-3"> 	
		
			<label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo !empty($prestador['celular'])?$prestador['celular']:"";?>" id="celular"/>
		    </div>
		    <div class="col-md-3"> 	
			<label for="data_inicio">Data Início</label><input type="text" name="data_inicio" value="<?php echo (!empty($prestador)?date('d/m/Y',strtotime($prestador['data_inicio'])):NULL);?>" id="data_inicio" class='datahora form-control' required='required'/>
		    </div>
		  	<div class="col-md-3"> 	
				<label for="data_fim">Data Término</label><input type="text" name="data_fim" value="<?php echo (!empty($prestador['data_fim'])?date('d/m/Y',strtotime($prestador['data_fim'])):NULL);?>" id="data_fim" class='datahora form-control'/>
		    </div>
		  </div>
		  <p style="text-align: right;">
				<input class="btn btn-success" type="submit" value="<?php echo !empty($prestador)?"Alterar":"Cadastrar"; ?>"/>
				<input class="btn btn-danger" type="button" value="Voltar" onclick='window.location.href="<?php echo base_url('servicos/prestadores');?>"'/>
			</p>
	<?php
	}
	?>

    </div>

 </div>

</div>
</form>
</div>