<?php
	$id_proprietario = (!empty($proprietario)?$proprietario['id_proprietario']:NULL);
?>
<script>
	var proprietario = <?php print (!empty($proprietario)?1:0);?>;
	$(function(){
			
		$("#cpf").on("blur",function(){ // validando cpf
			if ($("#cpf").val()!=""){
				$.ajax({
					url:'<?php echo base_url('proprietarios/verifica_cpf');?>',
					type:'post',
					data:$.param({
						cpf :$(this).val(),
					}),
					success:function(r){
						if (r){
							proprietario = false;
							$("#cpf").css("border-color", "#ff0000");
							
						}else{
							
							proprietario = true;
							$("#cpf").css("border-color", "#3c763d");
						}
					}
				});
			}
		});
		
		$("#dono").on("click",function(){
			 if ($("#data_devolucao").attr('disabled')) $("#data_devolucao").removeAttr('disabled');
            else $("#data_devolucao").attr('disabled', 'disabled');
		});
		
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
							$("select#unidade option:first").text('Selecione');
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
			$("#unidade").html("<option value=''></option>");
		});
		
		$(".editar").on("click",function(){
			var tr = $(this).attr('cod');
			$("#"+tr).show();
			$(this).parent().parent().hide();
		});
		
		$(".cancelar").on("click",function(){
			var eq = ($(this).attr('cod')==0?1:parseInt($(this).attr('cod'))+2);
			$("#"+$(this).attr('cod')).hide();
			$('tr:eq('+(eq)+')').show();
		});
		$("[type='file']").on("change",function(){
			if($(this).val()!=null && $(this).val()!=""){
				$("#bicam").hide();
			}
		})
	}); // final function js
	
	function validaProprietario(classe) // validação do formulário de informações do proprietário
	{
	  		  	var required =$("."+classe+">[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
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
		if (req == 1 || proprietario == false) {
			
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			if(proprietario == false)
				$(".modal-body").text("CPF de proprietário já cadastrado !");
			else
				$(".modal-body").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
	
</script>
<!-- INICIO DE DADOS CADASTRAIS DO PROPRIETÁRIO ! -->

 <div class="panel panel-default">
	<div class="panel-heading"><strong>Dados do Proprietário</strong></div>
		<form action="<?php echo base_url('proprietarios/proprietario_submit/'.$id_proprietario);?>" method="post" class="proprietarios" accept-charset="utf-8" onsubmit="return validaProprietario('proprietarios');" enctype="multipart/form-data">
		<div class="panel-body" style="padding: 10px;">
	
	<div class="col-md-12" style="padding: 0px;" > 
		<div class="col-md-4" style="padding-left: 0px;text-align:center;" class="webcam" id="bicam"> 
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
					<img src="<?php echo !empty($proprietario)?(!empty($proprietario['foto'])?base_url("uploads/proprietarios/".$proprietario['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir" style="width">
					<input type="hidden" name="foto" value="" id="foto"/>
				</div>
			</div> 
		<div class="col-md-10" id="arq">
			<label for="arquivo">Via upload</label><input  class="form-control" type="file" name="foto" value="" id="arquivo"/>
			
		</div>
			<div class="col-md-6" >  

  <label for="nome">Nome*</label><input type="text" name="nome" class="form-control" value="<?php echo (!empty($proprietario)?$proprietario['nome']:NULL);?>" id="nome" required='required' />
</div>  

<div class="col-md-4" >  
  <label for="rg">RG*</label><input class="form-control" type="text" name="rg" value="<?php echo (!empty($proprietario)?$proprietario['rg']:NULL);?>" id="rg" required='required' />
</div> 
        		  
<div class="col-md-4" >  
  <label for="cpf">CPF*</label><input class="form-control" type="text" name="cpf" value="<?php echo (!empty($proprietario)?$proprietario['cpf']:NULL);?>" required='required' <?php echo (!empty($proprietario['cpf'])?"readonly='readonly'":"id='cpf'");?>/>
</div>

      	 <div class="col-md-4" >
		  	<label for="endereco">Endereco</label><input type="text" class="form-control" name="endereco" value="<?php echo (!empty($proprietario)?$proprietario['endereco']:NULL);?>" id="endereco"  required="required"/>
		  </div>
		  
		  <div class="col-md-2" >
		  	<label for="numero">Número</label><input type="text" class="form-control" name="numero" value="<?php echo (!empty($proprietario)?$proprietario['numero']:NULL);?>" id="numero" required="required"/>
		  </div>
	</div>
	<div class="col-md-8" style="padding-left: 0px;">
		  <div class="col-md-3" >
		  	<label for="complemento">Complemento</label><input type="text" class="form-control" name="complemento" value="<?php echo (!empty($proprietario)?$proprietario['complemento']:NULL);?>" id="complemento"/>
		  </div>
		  
		  <div class="col-md-3" >
		  	<label for="cep">CEP</label><input type="text" class="form-control" name="cep" value="<?php echo (!empty($proprietario)?$proprietario['cep']:NULL);?>" id="cep"  required="required"/>
		  </div>
		  
		  <div class="col-md-6" >
		  	<label for="bairro">Bairro</label><input type="text" class="form-control" name="bairro" value="<?php echo (!empty($proprietario)?$proprietario['bairro']:NULL);?>" id="bairro"  required="required"/>
		  </div>
		  <div class="col-md-3" >
		  	<label for="cidade">Cidade</label><input type="text" class="form-control" name="cidade" value="<?php echo (!empty($proprietario)?$proprietario['cidade']:NULL);?>" id="cidade"  required="required"/>
		  </div>
		  <div class="col-md-3" >
		  	<label for="estado">Estado</label>
		  	<select class="form-control" name="estado" id="estado" required='required'>
			  		<option value="">Selecione</option>
					<option value="AC" <?php echo (!empty($proprietario)?$proprietario['estado']=='AC'?"selected='selected'":"":"");?>>AC</option>
					<option value="AL" <?php echo (!empty($proprietario)?$proprietario['estado']=='AL'?"selected='selected'":"":"");?>>AL</option>
					<option value="AM" <?php echo (!empty($proprietario)?$proprietario['estado']=='AM'?"selected='selected'":"":"");?>>AM</option>
					<option value="AP" <?php echo (!empty($proprietario)?$proprietario['estado']=='AP'?"selected='selected'":"":"");?>>AP</option>
					<option value="BA" <?php echo (!empty($proprietario)?$proprietario['estado']=='BA'?"selected='selected'":"":"");?>>BA</option>
					<option value="CE" <?php echo (!empty($proprietario)?$proprietario['estado']=='CE'?"selected='selected'":"":"");?>>CE</option>
					<option value="DF" <?php echo (!empty($proprietario)?$proprietario['estado']=='DF'?"selected='selected'":"":"");?>>DF</option>
					<option value="ES" <?php echo (!empty($proprietario)?$proprietario['estado']=='ES'?"selected='selected'":"":"");?>>ES</option>
					<option value="GO" <?php echo (!empty($proprietario)?$proprietario['estado']=='GO'?"selected='selected'":"":"");?>>GO</option>
					<option value="MA" <?php echo (!empty($proprietario)?$proprietario['estado']=='MA'?"selected='selected'":"":"");?>>MA</option>
					<option value="MG" <?php echo (!empty($proprietario)?$proprietario['estado']=='MG'?"selected='selected'":"":"");?>>MG</option>
					<option value="MS" <?php echo (!empty($proprietario)?$proprietario['estado']=='MS'?"selected='selected'":"":"");?>>MS</option>
					<option value="MT" <?php echo (!empty($proprietario)?$proprietario['estado']=='MT'?"selected='selected'":"":"");?>>MT</option>
					<option value="PA" <?php echo (!empty($proprietario)?$proprietario['estado']=='PA'?"selected='selected'":"":"");?>>PA</option>
					<option value="PB" <?php echo (!empty($proprietario)?$proprietario['estado']=='PB'?"selected='selected'":"":"");?>>PB</option>
					<option value="PE" <?php echo (!empty($proprietario)?$proprietario['estado']=='PE'?"selected='selected'":"":"");?>>PE</option>
					<option value="PI" <?php echo (!empty($proprietario)?$proprietario['estado']=='PI'?"selected='selected'":"":"");?>>PI</option>
					<option value="PR" <?php echo (!empty($proprietario)?$proprietario['estado']=='PR'?"selected='selected'":"":"");?>>PR</option>
					<option value="RJ" <?php echo (!empty($proprietario)?$proprietario['estado']=='RJ'?"selected='selected'":"":"");?>>RJ</option>
					<option value="RN" <?php echo (!empty($proprietario)?$proprietario['estado']=='RN'?"selected='selected'":"":"");?>>RN</option>
					<option value="RO" <?php echo (!empty($proprietario)?$proprietario['estado']=='RO'?"selected='selected'":"":"");?>>RO</option>
					<option value="RR" <?php echo (!empty($proprietario)?$proprietario['estado']=='RR'?"selected='selected'":"":"");?>>RR</option>
					<option value="RS" <?php echo (!empty($proprietario)?$proprietario['estado']=='RS'?"selected='selected'":"":"");?>>RS</option>
					<option value="SC" <?php echo (!empty($proprietario)?$proprietario['estado']=='SC'?"selected='selected'":"":"");?>>SC</option>
					<option value="SE" <?php echo (!empty($proprietario)?$proprietario['estado']=='SE'?"selected='selected'":"":"");?>>SE</option>
					<option value="SP" <?php echo (!empty($proprietario)?$proprietario['estado']=='SP'?"selected='selected'":"":"");?>>SP</option>
					<option value="TO" <?php echo (!empty($proprietario)?$proprietario['estado']=='TO'?"selected='selected'":"":"");?>>TO</option>
			  	</select>
		  </div>
		  <div class="col-md-3" >  
			  <label for="telefone">Telefone*</label><input class="form-control" type="text" name="telefone" value="<?php echo (!empty($proprietario)?$proprietario['telefone']:NULL);?>" id="telefone" required='required' />
			</div>  
			<div class="col-md-3" >  
  <label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo (!empty($proprietario['celular'])?$proprietario['celular']:NULL);?>" id="celular" />
</div>

<div class="col-md-3" >
  <label for="telefone_recado">Telefone de recado</label><input class="form-control telefone" type="text" name="telefone_recado" value="<?php echo (!empty($proprietario)?$proprietario['telefone_recado']:NULL);?>" id="telefone_recado" />
</div>  
<div class="col-md-4" >  
  <label for="email">Email*</label><input class="form-control" type="email" name="email" value="<?php echo (!empty($proprietario)?$proprietario['email']:NULL);?>" id="email" required='required' />
</div>

<div class="col-md-5" >  
  <label for="data">Data de Nascimento*</label><input class="form-control data" type="text" name="data_nascimento" value="<?php echo (!empty($proprietario)?date('d/m/Y',strtotime($proprietario['data_nascimento'])):NULL);?>" required='required' />
</div>

<div class="col-md-12" >  
  <label for="sexo">Sexo*</label><br/>
  	<label style="margin-right: 10px;"><input type="radio" name="sexo" value="M" id="sexo" required='required' <?php echo (!empty($proprietario)?($proprietario['sexo']=='M'?"checked='checked'":""):NULL);?>/>&nbsp;&nbsp;Masculino</label>
  	<label><input type="radio" name="sexo" value="F" id="sexo" required='required' <?php echo (!empty($proprietario)?($proprietario['sexo']=='F'?"checked='checked'":""):NULL);?> />&nbsp;&nbsp;Feminino</label>
</div>
		</div>
	</div>

</div>
</div>
<p align="right">
	<input class="btn btn-success" type="submit" value="<?php echo (!empty($proprietario)?"Alterar":"Cadastrar");?>" />
</p>
</form>

<!-- FIM DE DADOS CADASTRAIS DO PROPRIETÁRIO ! -->
</br>
<!-- INICIO DE VINCULO DE UNIDADES AO PROPRIETÁRIO ! --> 
<?php
	if (!empty($proprietario)){
?>
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Vincular <?php echo (!empty($unidades)?"Nova":"");?> Unidade:</strong></div>
		<div class="panel-body" style="padding: 10px;">


    
	<form action="<?php echo base_url('proprietarios/vincular_unidade/'.$id_proprietario);?>" method="post" accept-charset="utf-8" class="unidade" onsubmit="return validaProprietario('unidade');">
    	<div class="col-md-3" >
            <label for="bloco">Torre</label>
            <select id="bloco" class="form-control" required='required'>
            	<option value="">Selecione</option>
            	<option value="A">A</option>
            	<option value="B">B</option>
            	<option value="C">C</option>
            </select>
        </div>
        
        <div class="col-md-3" >
            <label for="unidade">Unidade</label>
			<select name="id_unidade" id="unidade" class="form-control" required='required'/>
				<option value="" selected></option>
			</select>
            
        </div>

        <div class="col-md-3">
            <label for="data_aquisicao">Data de Aquisição</label><input type="text" name="data_aquisicao" class='data form-control' value="" id="data_aquisicao" required='required'/> <!-- class data apenas para a formatação do INPUT favor não remover -->
        </div>
        <!--div class="col-md-12"><br>&nbsp;
            <label class="checkbox-inline" for="dono">Proprietário Atual &nbsp;&nbsp;</label><input type="hidden" name="dono" value="1" id="dono"/><br>&nbsp;
		</div-->
        <input type="hidden" name="dono" value="1" id="dono"/>
        <!--div class="col-md-4">
            <label for="data_devolucao">Data de devolução</label><input type="text" name="data_devolucao" class='data form-control' value="" id="data_devolucao"/> &nbsp;
        </div-->
		<div class="col-md-3" >
			<label class="" for="morador">Mora nesta unidade?</label><br/>
	            <label>Sim <input type="radio" name="morador" value="1" id="morador"/></label>
	            <label>Não <input type="radio" name="morador" value="0" id="morador"/></label>
			</div>
        <div class="col-md-12" >
            <br/><button class="btn btn-success" type='submit'>Vincular Unidade a Proprietário</button>
        </div>
	 </form>
     </div>
     </div>
<?php
	}
?>
<!-- FIM DE VINCULO DE UNIDADES AO PROPRIETÁRIO ! -->
</br>
<!-- INICIO DE UNIDADES DO PROPRIETÁRIO ! -->
<?php
if (!empty($unidades)){
?>

<div class="panel panel-default">
		<div class="panel-heading"><strong>Unidades:</strong></div>
		<div class="panel-body">
	<table border="0" cellspacing="5" cellpadding="5" id='propriedades' class="table table-striped table-hover">
		<thead>
			<th>Bloco</th>
			<th>Unidade</th>
			<th>Ocupado</th>
			<th>Porprietário</th>
			<th>Proprietário desde</th>
			<th>Status</th>
			<th>&nbsp;</th>
		</thead>
		
	<?php
	$i=0;
	foreach ($unidades as $unidade){
	?>
	<form action="<?php echo base_url('proprietarios/editar_propriedade/'.$unidade['id_unidade']."/".$id_proprietario);?>" method="post" accept-charset="utf-8" id='editar_prioridade'>
		<tr>
			<td><?php echo $unidade['bloco'];?></td>
			<td><?php echo $unidade['unidade'];?></td>
			<td> 
				<?php echo ($unidade['status']==1?"Ocupado":"Disponível");?>
			</td>
			<td><?php echo $unidade['nome'];?></td>
			<td><?php echo date('d/m/Y',strtotime($unidade['data_aquisicao']));?></td>
			<td>
				<?php echo ($unidade['dono']==1?"Dono atual":"Devolvido em ".date('d/m/Y',strtotime($unidade['data_devolucao'])));?>
			</td>
			<td>
				<?php 
					if ($unidade['dono']==1){
				?>
						<button class="btn btn-success editar" type="button" cod='<?php echo $i;?>'>Editar</button>
				<?php
					}
				?>
			</td>
		</tr>
		<tr id='<?=$i;?>' style='display:none;'>
			<td><?php echo $unidade['bloco'];?></td>
			<td><?php echo $unidade['unidade'];?></td>
			<td> 
				<select name="status" class="form-control">
					<option value="0" <?php echo ($unidade['status']==0?"selected='selected'":"");?>>Disponível</option>
					<option value="1" <?php echo ($unidade['status']==1?"selected='selected'":"");?>>Ocupado</option>
				</select>
			</td>
			<td><?php echo $unidade['nome'];?></td>
			<td><?php echo date('d/m/Y',strtotime($unidade['data_aquisicao']));?></td>
			<td>
				Entregar unidade: <br><input type="text" name="data_devolucao" class='form-control data_afterwards' maxlength="10">
			</td>
			<td>
				<input type="submit"  class='btn btn-success' value="Concluir" id="concluir">
				<button type="button" class='cancelar btn btn-warning' cod='<?=$i;?>'>Cancelar</button>
			</td>
		</tr>
	</form>
<?php
	$i++;
	}
?>
		
	</table>
    
    </div>
</div>
	
<?php
}else{
	if (!empty($proprietario)){
?>
	Não há unidades Vinculadas a este proprietário!
<?php
	}
}
?>
<!-- FIM DE UNIDADES DO PROPRIETÁRIO ! -->

