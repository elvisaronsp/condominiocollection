<script>
	$(function(){
		<?php
			if(!empty($empresa)){
		?>
			$("input:not([type='button']),select,textarea").prop("disabled","disabled").css({"border":"none","background":"transparent"});
			$("[type='submit']").hide();
			
		<?php
			}
		?>			
	
		$(".detalhes").on("click",function(){
			var colaborador = $(this).attr('cod');
			window.location.href='<?php echo base_url('colaboradores/registro');?>/'+colaborador;
		});
	});
	function validaEmpresa() {
	  	  	var required =$(".empresa>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if (req == 1) {
			$("#flash").text("Preencha a Empresa corretamente !");
			return false;
		}
	}
</script>
<?php $id_empresa = !empty($empresa)?$empresa['id_empresa']:NULL;?>

<div class="panel panel-default">
		<div class="panel-heading"><strong>Empresa</strong></div>
		<div class="panel-body">

<form action="<?php echo base_url('colaboradores/empresas_submit/'.$id_empresa);?>" method="post" class="empresa" accept-charset="utf-8" onsubmit='return validaEmpresa();'>
	<label for="razao_social">Razão Social</label>
  <input class="form-control" type="text" name="razao_social" value="<?php echo !empty($empresa['razao_social'])?$empresa['razao_social']:"";?>" id="razao_social" required="required"/>
  <label for="nome_fantasia">Nome Fantasia</label>
  <input class="form-control" type="text" name="nome_fantasia" value="<?php echo !empty($empresa['nome_fantasia'])?$empresa['nome_fantasia']:"";?>" id="nome_fantasia" required="required"/>
  <label for="cnpj">CNPJ</label>
  <input class="form-control" type="text" name="cnpj" value="<?php echo !empty($empresa['cnpj'])?$empresa['cnpj']:"";?>" id="cnpj" required="required"/>
  <label for="email">E-mail</label>
  <input class="form-control" type="email" name="email" value="<?php echo !empty($empresa['email'])?$empresa['email']:"";?>" id="cnpj" required="required"/>
  <label for="endereco">Endereço</label>
  <input class="form-control" type="text" name="endereco" value="<?php echo !empty($empresa['endereco'])?$empresa['endereco']:"";?>" id="endereco" required="required"/>
  <label for="numero">Número</label>
  <input class="form-control" type="text" name="numero" value="<?php echo !empty($empresa['numero'])?$empresa['numero']:"";?>" id="numero" required="required"/> 
  <label for="complemento">Complemento</label>
  <input class="form-control" type="text" name="complemento" value="<?php echo !empty($empresa['complemento'])?$empresa['complemento']:"";?>" id="complemento"/>
  <label for="bairro">Bairro</label>
  <input class="form-control" type="text" name="bairro" value="<?php echo !empty($empresa['bairro'])?$empresa['bairro']:"";?>" id="bairro" required="required"/>
  <label for="cep">CEP</label>
  <input class="form-control" type="text" name="cep" value="<?php echo !empty($empresa['cep'])?$empresa['cep']:"";?>" id="cep"required="required"/>
  <label for="complemento">Complemento</label>
  <input class="form-control" type="text" name="complemento" value="<?php echo !empty($empresa['complemento'])?$empresa['complemento']:"";?>" id="complemento"/>
  <label for="telefone">Telefone</label>
  <input class="form-control" type="text" name="telefone" value="<?php echo !empty($empresa['telefone'])?$empresa['telefone']:"";?>" id="telefone"/>
  <label for="site">Site</label>
  <input class="form-control" type="text" name="site" value="<?php echo !empty($empresa['site'])?$empresa['site']:"";?>" id="site"/>  
  <label for="cidade">Cidade</label>
  <input class="form-control" type="text" name="cidade" value="<?php echo !empty($empresa['cidade'])?$empresa['cidade']:"";?>" id="cidade" required="required"/>
  <label for="estado">Estado</label>
	  	<select class="form-control" name="estado" id="estado" required='required'>
	  		<option value="">Selecione</option>
			<option value="AC" <?php echo (!empty($empresa)?$empresa['estado']=='AC'?"selected='selected'":"":"");?>>AC</option>
			<option value="AL" <?php echo (!empty($empresa)?$empresa['estado']=='AL'?"selected='selected'":"":"");?>>AL</option>
			<option value="AM" <?php echo (!empty($empresa)?$empresa['estado']=='AM'?"selected='selected'":"":"");?>>AM</option>
			<option value="AP" <?php echo (!empty($empresa)?$empresa['estado']=='AP'?"selected='selected'":"":"");?>>AP</option>
			<option value="BA" <?php echo (!empty($empresa)?$empresa['estado']=='BA'?"selected='selected'":"":"");?>>BA</option>
			<option value="CE" <?php echo (!empty($empresa)?$empresa['estado']=='CE'?"selected='selected'":"":"");?>>CE</option>
			<option value="DF" <?php echo (!empty($empresa)?$empresa['estado']=='DF'?"selected='selected'":"":"");?>>DF</option>
			<option value="ES" <?php echo (!empty($empresa)?$empresa['estado']=='ES'?"selected='selected'":"":"");?>>ES</option>
			<option value="GO" <?php echo (!empty($empresa)?$empresa['estado']=='GO'?"selected='selected'":"":"");?>>GO</option>
			<option value="MA" <?php echo (!empty($empresa)?$empresa['estado']=='MA'?"selected='selected'":"":"");?>>MA</option>
			<option value="MG" <?php echo (!empty($empresa)?$empresa['estado']=='MG'?"selected='selected'":"":"");?>>MG</option>
			<option value="MS" <?php echo (!empty($empresa)?$empresa['estado']=='MS'?"selected='selected'":"":"");?>>MS</option>
			<option value="MT" <?php echo (!empty($empresa)?$empresa['estado']=='MT'?"selected='selected'":"":"");?>>MT</option>
			<option value="PA" <?php echo (!empty($empresa)?$empresa['estado']=='PA'?"selected='selected'":"":"");?>>PA</option>
			<option value="PB" <?php echo (!empty($empresa)?$empresa['estado']=='PB'?"selected='selected'":"":"");?>>PB</option>
			<option value="PE" <?php echo (!empty($empresa)?$empresa['estado']=='PE'?"selected='selected'":"":"");?>>PE</option>
			<option value="PI" <?php echo (!empty($empresa)?$empresa['estado']=='PI'?"selected='selected'":"":"");?>>PI</option>
			<option value="PR" <?php echo (!empty($empresa)?$empresa['estado']=='PR'?"selected='selected'":"":"");?>>PR</option>
			<option value="RJ" <?php echo (!empty($empresa)?$empresa['estado']=='RJ'?"selected='selected'":"":"");?>>RJ</option>
			<option value="RN" <?php echo (!empty($empresa)?$empresa['estado']=='RN'?"selected='selected'":"":"");?>>RN</option>
			<option value="RO" <?php echo (!empty($empresa)?$empresa['estado']=='RO'?"selected='selected'":"":"");?>>RO</option>
			<option value="RR" <?php echo (!empty($empresa)?$empresa['estado']=='RR'?"selected='selected'":"":"");?>>RR</option>
			<option value="RS" <?php echo (!empty($empresa)?$empresa['estado']=='RS'?"selected='selected'":"":"");?>>RS</option>
			<option value="SC" <?php echo (!empty($empresa)?$empresa['estado']=='SC'?"selected='selected'":"":"");?>>SC</option>
			<option value="SE" <?php echo (!empty($empresa)?$empresa['estado']=='SE'?"selected='selected'":"":"");?>>SE</option>
			<option value="SP" <?php echo (!empty($empresa)?$empresa['estado']=='SP'?"selected='selected'":"":"");?>>SP</option>
			<option value="TO" <?php echo (!empty($empresa)?$empresa['estado']=='TO'?"selected='selected'":"":"");?>>TO</option>
	  	</select>
  
  <input class="btn btn-success" type="submit" value="<?php echo !empty($empresa)?"Alterar":"Cadastrar";?>"/>
</form>

</div>
</div>

<?php
	if (!empty($empresa)){
		if (!empty($colaboradores)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Colaboradores Cadastrados a esta empresa</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Nome</th>
				<th>CPF</th>
				<th>Endereço</th>
				<th>Contato</th>
				<th>Data de admissão</th>
				<th>Situação</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($colaboradores as $colaborador) {
?>
			<tr>
				<td><?php echo $colaborador['nome'];?></td>
				<td><?php echo $colaborador['cpf'];?></td>
				<td><?php echo $colaborador['endereco'].",".$colaborador['numero']." - ".$colaborador['bairro']."<br>".$colaborador['cidade']."-".$colaborador['estado'];?></td>
				<td><?php echo $colaborador['telefone']; echo !empty($colaborador['celular'])?" / ".$colaborador['celular']:"";?></td>
				<td><?php echo date('d/m/Y',strtotime($colaborador['data_admissao']));?></td>
				<td><?php echo $colaborador['data_demissao']!=NULL?"Trabalhou até ".date('d/m/Y',strtotime($colaborador['data_demissao'])):"Colaborador";?></td>
				<td>
					<button type='button' cod='<?php echo $colaborador['id_colaborador'];?>' class='detalhes btn btn-success'>Detalhes</button>
				</td>
			</tr>
<?php	
		}
?>
		</table>
        
        </div>
   </div>
<?php
		}else{
?>
		Não há colaboradores que trabalham para esta empresa !
<?php
		}
	}
?>
<input class="btn btn-danger" type="button" value="Voltar" onclick='window.location.href="<?php echo base_url('colaboradores/empresas');?>"'/></p>
