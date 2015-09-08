<script>
	$(function(){
		<?php
			if (!empty($colaborador)){
		?>
			$('form').find('input:not([name="data_demissao"],[type="submit"],[type="button"]),select ').attr('disabled','disabled').css({"border":"none","background":"transparent"});
		<?php
				if ($colaborador['data_demissao']==NULL || $colaborador['data_demissao']==""){
		?>
				$("[name='data_demissao'],[type='submit'],[type='button']").removeAttr('disabled','');
		<?php
				}else{
		?>
			$("[type='submit']").hide();
			$("[name='data_demissao']").attr('disabled','disabled').css({"border":"none","background":"transparent"});
			$("[type='button']").removeAttr('disabled','');
		<?php
				}
			}
		?>
				
		$("#nome_exibe").on("change",function(){
			$( "#empresa" ).val("");
		});
		
		$("#nome_exibe").autocomplete({
			source: "<?php echo base_url('colaboradores/busca_empresa/');?>",
			focus: function( event, ui ) {
		        $( "#nome_exibe" ).val( ui.item.razao_social );
		        
		        return false;
		      },
		      select: function( event, ui ) {
		        $( "#nome_exibe" ).val( ui.item.razao_social );
		        $( "#empresa" ).val(ui.item.id_empresa);
		        $("#tercerizado").attr('checked','checked');
		        return false;
		      }
		    })
		    .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		      return $( "<li>" )
		        .append( "<a>" + item.razao_social+"</a>" )
		        .appendTo( ul );
		        
		    };
		    
		 $("#tercerizado").on("change",function(){
		 	if ($(this).is(':checked'))    $( "#nome_exibe" ).focus();
		 });
		 $("[type='submit']").on("click",function(){
		 	
			if ($("#empresa").val()=="" && $("#nome_exibe").val()!=""){
				$("#new_company").dialog({
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
	});
	function validacolaboradores() {
	  		  	var required =$(".colaborador>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if (req == 1) {
			$("#flash").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
</script>

<?php $id_colaborador = !empty($colaborador)?$colaborador['id_colaborador']:NULL; ?>
<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Colaboradores Cadastrados</strong></div>
		<div class="panel-body">

<div id="new_company" style='display:none;'>Esta Empresa que digitou não existe, deseja cadastrá-la juntamente com o colaborador ?</div>
<form action="<?php echo base_url('colaboradores/colaboradores_submit/'.$id_colaborador);?>" method="post" class="colaborador" accept-charset="utf-8" onsubmit="return validacolaboradores();" enctype="multipart/form-data">
    
<div class="col-md-6"> 	

	<label for="nome">Nome</label><input class="form-control" type="text" name="nome" value="<?php echo !empty($colaborador)?$colaborador['nome']:"";?>" id="nome" required='required'/>
    </div>
    
    <div class="col-md-3"> 	

	<label for="rg">RG</label><input class="form-control" type="text" name="rg" value="<?php echo !empty($colaborador)?$colaborador['rg']:"";?>" id="rg" required='required'/>
    </div>
    
    <div class="col-md-3"> 	
	<label for="cpf">CPF</label><input class="form-control" type="text" name="cpf" value="<?php echo !empty($colaborador)?$colaborador['cpf']:"";?>" id="cpf" required='required'/>
    </div>

    <div class="col-md-3"> 	
    	<label for="cep">CEP</label><input type="text" name="cep" class="form-control" value="<?php echo !empty($colaborador)?$colaborador['cep']:"";?>" id="cep"  required='required'/>
	</div>
	<div class="col-md-3"> 	
    	<label for="endereco">Endereço</label><input type="text" class="form-control" name="endereco" value="<?php echo !empty($colaborador)?$colaborador['endereco']:"";?>" id="endereco"  required='required'/>
	</div>
	<div class="col-md-3"> 	
    	<label for="numero">Número</label><input type="text" name="numero" class="form-control" value="<?php echo !empty($colaborador)?$colaborador['numero']:"";?>" id="numero"  required='required' maxlength="5" />
	</div>
	<div class="col-md-3"> 	
    	<label for="complemento">Complemento</label><input type="text" class="form-control" name="complemento" value="<?php echo !empty($colaborador)?!empty($colaborador['complemento'])?$colaborador['complemento']:"":"";?>" id="complemento"/>
	</div>
	<div class="col-md-3"> 	
    	<label for="bairro">Bairro</label><input type="text" class="form-control" name="bairro" value="<?php echo !empty($colaborador)?$colaborador['bairro']:"";?>" id="bairro"  required='required'/>
	</div>
	<div class="col-md-3"> 	
    	<label for="cidade">Cidade</label><input type="text" class="form-control" name="cidade" value="<?php echo !empty($colaborador)?$colaborador['cidade']:"";?>" id="cidade"  required='required'/>
	</div>
    <div class="col-md-6">
	  	<label for="estado">Estado</label>
	  	<select class="form-control" name="estado" id="estado" required='required'>
	  		<option value="">Selecione</option>
			<option value="AC" <?php echo (!empty($colaborador)?$colaborador['estado']=='AC'?"selected='selected'":"":"");?>>AC</option>
			<option value="AL" <?php echo (!empty($colaborador)?$colaborador['estado']=='AL'?"selected='selected'":"":"");?>>AL</option>
			<option value="AM" <?php echo (!empty($colaborador)?$colaborador['estado']=='AM'?"selected='selected'":"":"");?>>AM</option>
			<option value="AP" <?php echo (!empty($colaborador)?$colaborador['estado']=='AP'?"selected='selected'":"":"");?>>AP</option>
			<option value="BA" <?php echo (!empty($colaborador)?$colaborador['estado']=='BA'?"selected='selected'":"":"");?>>BA</option>
			<option value="CE" <?php echo (!empty($colaborador)?$colaborador['estado']=='CE'?"selected='selected'":"":"");?>>CE</option>
			<option value="DF" <?php echo (!empty($colaborador)?$colaborador['estado']=='DF'?"selected='selected'":"":"");?>>DF</option>
			<option value="ES" <?php echo (!empty($colaborador)?$colaborador['estado']=='ES'?"selected='selected'":"":"");?>>ES</option>
			<option value="GO" <?php echo (!empty($colaborador)?$colaborador['estado']=='GO'?"selected='selected'":"":"");?>>GO</option>
			<option value="MA" <?php echo (!empty($colaborador)?$colaborador['estado']=='MA'?"selected='selected'":"":"");?>>MA</option>
			<option value="MG" <?php echo (!empty($colaborador)?$colaborador['estado']=='MG'?"selected='selected'":"":"");?>>MG</option>
			<option value="MS" <?php echo (!empty($colaborador)?$colaborador['estado']=='MS'?"selected='selected'":"":"");?>>MS</option>
			<option value="MT" <?php echo (!empty($colaborador)?$colaborador['estado']=='MT'?"selected='selected'":"":"");?>>MT</option>
			<option value="PA" <?php echo (!empty($colaborador)?$colaborador['estado']=='PA'?"selected='selected'":"":"");?>>PA</option>
			<option value="PB" <?php echo (!empty($colaborador)?$colaborador['estado']=='PB'?"selected='selected'":"":"");?>>PB</option>
			<option value="PE" <?php echo (!empty($colaborador)?$colaborador['estado']=='PE'?"selected='selected'":"":"");?>>PE</option>
			<option value="PI" <?php echo (!empty($colaborador)?$colaborador['estado']=='PI'?"selected='selected'":"":"");?>>PI</option>
			<option value="PR" <?php echo (!empty($colaborador)?$colaborador['estado']=='PR'?"selected='selected'":"":"");?>>PR</option>
			<option value="RJ" <?php echo (!empty($colaborador)?$colaborador['estado']=='RJ'?"selected='selected'":"":"");?>>RJ</option>
			<option value="RN" <?php echo (!empty($colaborador)?$colaborador['estado']=='RN'?"selected='selected'":"":"");?>>RN</option>
			<option value="RO" <?php echo (!empty($colaborador)?$colaborador['estado']=='RO'?"selected='selected'":"":"");?>>RO</option>
			<option value="RR" <?php echo (!empty($colaborador)?$colaborador['estado']=='RR'?"selected='selected'":"":"");?>>RR</option>
			<option value="RS" <?php echo (!empty($colaborador)?$colaborador['estado']=='RS'?"selected='selected'":"":"");?>>RS</option>
			<option value="SC" <?php echo (!empty($colaborador)?$colaborador['estado']=='SC'?"selected='selected'":"":"");?>>SC</option>
			<option value="SE" <?php echo (!empty($colaborador)?$colaborador['estado']=='SE'?"selected='selected'":"":"");?>>SE</option>
			<option value="SP" <?php echo (!empty($colaborador)?$colaborador['estado']=='SP'?"selected='selected'":"":"");?>>SP</option>
			<option value="TO" <?php echo (!empty($colaborador)?$colaborador['estado']=='TO'?"selected='selected'":"":"");?>>TO</option>
	  	</select>
    </div>
    
    <div class="col-md-3">
    	<label for="email">Email</label><input type="email" class="form-control" name="email" value="<?php echo !empty($colaborador)?$colaborador['email']:"";?>" id="email"/>
	</div>
    
    <div class="col-md-3"> 	

	<label for="telefone">Telefone</label><input class="form-control" type="text" name="telefone" value="<?php echo !empty($colaborador)?$colaborador['telefone']:"";?>" id="telefone" required='required'/>
    </div>
    
    <div class="col-md-3"> 	
		<label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo !empty($colaborador['celular'])?$colaborador['celular']:"";?>" id="celular"/>
    </div>
    
    <div class="col-md-3"> 	
    	<label for="tercerizado">Tercerizado</label><br/><input type="checkbox" name="tercerizado" value="1" id="tercerizado" <?php echo !empty($colaborador)?$colaborador['tercerizado']==1?"checked='checked'":"":"";?>/>
	</div>
    
	<div class="col-md-4"> 	

	<label for="nome_exibe">Empresa</label>
		<input class="form-control" type="text" name="nome_exibe" value="<?php echo !empty($colaborador)?$colaborador['tercerizado']==1?$colaborador['razao_social']:"":"";?>" id="nome_exibe" />
		<input class="form-control" type="hidden" name="id_empresa" value="<?php echo !empty($colaborador)?$colaborador['tercerizado']==1?$colaborador['id_empresa']:"":"";?>" id="empresa"/>
    </div>
    <div class="col-md-4"> 	

	<label for="data_inicio">Data Admissão</label><input type="text" name="data_admissao" value="<?php echo (!empty($colaborador)?date('d/m/Y',strtotime($colaborador['data_admissao'])):NULL);?>" id="data_admissao" class='data form-control' required='required'/>
    </div>
    
    <div class="col-md-4"> 	

	<label for="data_fim">Data Demissão</label><input type="text" name="data_demissao" value="<?php echo (!empty($colaborador['data_demissao'])?date('d/m/Y',strtotime($colaborador['data_demissao'])):NULL);?>" id="data_demissao" class='data form-control'/>
    </div>
    
	<div class="col-md-12"> <br>

  <p><input class="btn btn-success" type="submit" value="<?php echo !empty($colaborador)?"Alterar":"Cadastrar"; ?>"/>
  	<input class="btn btn-danger" type="button" value="Voltar" onclick='window.location.href="<?php echo base_url('colaboradores/colaboradores');?>"'/></p>
  
  </div>
  
</form>

</div>
</div>
