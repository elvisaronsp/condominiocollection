<?php $id_morador = !empty($morador)?$morador['id_morador']:NULL; ?>

<script>
	var morador = true;
	$(function(){
		$("#cpf").on("blur",function(){
			if ($("#cpf").val()!=""){// validando cpf
				$.ajax({
					url:'<?php echo base_url('moradores/verifica_cpf');?>',
					type:'post',
					data:$.param({
						cpf :$(this).val(),
					}),
					success:function(r){
						if (r){
							
							$("#cpf").css("border-color", "#ff0000");
							morador = false;
						}else{
							
							$("#cpf").css("border-color", "#3c763d");
						}
					}
				});
			}
		});
		
		
		$("#bloco").on("change",function(){ // carregar unidades disponíveis
			if ($(this).val()!=""){
				$("select#unidade option:first").text('Carregando ...');
				$.ajax({
					url:'<?php echo base_url('unidades/getUnidadesByBloco');?>/2',
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
		$("#familiar").on("click",function(){ // cadastrar familiar
			$("#cad_familiar").slideToggle();
			$(".edita_familia").slideUp();
			$(this).toggleClass('btn-danger');
		});
		
		$(".edita_familiar").on("click",function(){ // cadastrar familiar
			$("#familiar,.edita_familiar").removeClass('btn-danger');
			$(this).toggleClass('btn-danger');
			
			$(".edita_familia").slideUp();
			var id = $(this).attr('cod');
			$("#familia"+id).slideToggle();
			$("#cad_familiar").slideUp();
		});
		$(".remover").on("click",function(){ // remove veículo
			var id_familiar = $(this).attr('cod');
			
			window.location.href="<?php echo base_url('moradores/familiares_delete/'.$id_morador);?>/"+id_familiar;
			
		});
	});
	function validaMorador (classe) {
	 	var required =$("."+classe+">[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		
		if (req == 1 || morador == false) {
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("Preencha todos os campos corretamente !");
			return false;
		}
		<?php
			if(empty($morador)){
		?>
		if (vercpf($(".cpf").val())==false){
			$(".cpf").css("border-color", "#ff0000");
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("O CPF informado não é valido !");
			return false;
		}
		<?php
			}
		?>
	}
</script>
<!-- INICIO DADOS DO MORADOR -->
<form action="<?php echo base_url('moradores/moradores_submit/'.$id_morador);?>" method="post" accept-charset="utf-8" class="morador" onsubmit='return validaMorador("morador");'>
<div class="panel panel-default">
	<div class="panel-heading"><strong>Foto do Morador</strong></div>
	<div class="panel-body" style="padding: 10px;">
		<!-- Code to handle the server response (see test.php) -->
		<div class="col-md-12" style="padding: 0px;" >
			<?php if(empty($morador)){ ?>  <!-- SE FOR CADASTRO EXIBE A BICAM CASO SEJA ALTERAÇÕES DE DADOS NÃO EXIBE A BICAM-->
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
				<!-- colocar foto default grande -->
				<div class="col-md-2">
					<div style="position: relative; width: 160px; height: 120px; left: -50px;">
						<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
						<img src="<?php echo !empty($morador)?(!empty($morador['foto'])?base_url("uploads/moradores/".$morador['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
						<input type="hidden" name="foto" value="" id="foto"/>
					</div>
				</div>
				<div class="col-md-10">
					
					<!--div class="col-md-9">
						<label for="arq">Via upload</label><input type="file" name="foto" class="form-control" value="" id="arq"/>
						
					</div-->
					<div class="col-md-12" style="padding: 0px;"> 	
						<label for="nome">Nome*</label><input class="form-control" type="text" name="nome" value="<?php echo !empty($morador)?$morador['nome']:""; ?>" id="nome" required='required' />
					</div>

					<div class="col-md-4" style="padding-left: 0px;">     
						<label for="rg">RG*</label><input type="text" name="rg" value="<?php echo !empty($morador)?$morador['rg']:""; ?>" class="rg form-control" id="rg" required='required' />
					</div>

					<div class="col-md-4" style="padding-left: 0px;">     
						<label for="cpf">CPF*</label><input  type="text" name="cpf" value="<?php echo !empty($morador)?$morador['cpf']:""; ?>" required='required' class="form-control<?php echo (!empty($morador['cpf'])?"":" cpf");?>" <?php echo (!empty($morador['cpf'])?"readonly='readonly'":"");?>/>
					</div>

					<div class="col-md-4" style="padding: 0px;"> 
						<label for="data_nascimento">Data de Nascimento*</label><input type="text" name="data_nascimento" value="<?php echo !empty($morador)?date('d/m/Y',strtotime($morador['data_nascimento'])):""; ?>" id="data_nascimento" class='data form-control' required='required' />
					</div>
				</div>
			</div>

			<div class="col-md-3"> 
    
				<label for="sexo">Sexo*</label><br/>
					<label style="margin-right: 10px;">Masculino&nbsp;<input type="radio" name="sexo" value="M" id="sexo" required='required' <?php echo !empty($morador)?($morador['sexo']=='M'?"checked='checked'":""):""; ?>/></label>
					<label>Feminino&nbsp;<input type="radio" name="sexo" value="F" id="sexo" required='required' <?php echo !empty($morador)?($morador['sexo']=='F'?"checked='checked'":""):""; ?>/></label> 
			</div>

			<div class="col-md-3">
				<label for="tipo">Nível de Responsabilidade:</label>
					<select class="form-control" name="tipo" id="tipo" required="required"> 
						<option value="">Selecione</option>
					<?php
						foreach($tipos as $tipo){
							if ($tipo['id_tipo']!=1){
					?>
								<option value="<?php echo $tipo['id_tipo'];?>" selected='selected'><?php echo $tipo['tipo'];?></option>
					<?php
							}
						}
					?>
					</select>
			</div>
			<div class="col-md-2">
				<label for="telefone">Telefone*</label><input class="form-control" type="text" name="telefone" value="<?php echo !empty($morador)?$morador['telefone']:""; ?>" id="telefone" required='required' />
			</div>

			<div class="col-md-2">
				<label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo !empty($morador)?$morador['celular']:""; ?>" id="celular"/>
			</div>

			<div class="col-md-4">
				<label for="email">E-mail*</label><input class="form-control" type="email" name="email" value="<?php echo !empty($morador)?$morador['email']:""; ?>" id="email" required='required' />
			</div>

			<div class="col-md-1">
	            <label for="bloco">Torre</label>
	            <select id="bloco" class="form-control" required='required' style="width:65px;">
	            	<option value="">Selecione</option>
	            	<option value="A" <?php echo (!empty($morador)?$morador['bloco']=='A'?"selected='selected'":"":"");?>>A</option>
	            	<option value="B" <?php echo (!empty($morador)?$morador['bloco']=='B'?"selected='selected'":"":"");?>>B</option>
	            	<option value="C" <?php echo (!empty($morador)?$morador['bloco']=='C'?"selected='selected'":"":"");?>>C</option>
	            </select>
	        </div>
			        

			<div class="col-md-1">

					<label for="unidade">Unidade</label>
					<select name="unidade" class="form-control"  id="unidade" required='required' style="width:70px;"/>
						<option value=""></option>
						<?php
							if (!empty($morador)){
						?>
							<option value="<?php echo $morador['id_unidade'];?>" selected='selected'><?php echo $morador['unidade'];?></option>
						<?php
							}
						?>
					</select>
			            
			</div>
			<div class="col-md-1">
				<label for="animais">Animais</label><input type="text" name="animais" class="form-control" value="<?php echo !empty($morador)?$morador['animais']:""; ?>" id="animais" maxlength="2"/>
				
			</div>
			<div class="col-md-2">
				<label for="data_moradia">Morador desde:</label><input class="form-control data" type="text" name="data_moradia" value="<?php echo !empty($morador)?date('d/m/Y',strtotime($morador['data_moradia'])):""; ?>" id="data_moradia" />
			</div>
			<div class="col-md-12 text-right">
				<br/>
				<button class="btn btn-success" type='submit'><?php echo !empty($morador)?"Alterar":"Cadastrar";?></button> 
			</div>
			<?php }else { ?>
			
			
				<!-- colocar foto default grande -->
				<div class="col-md-2">
					<div style="position: relative; width: 160px; height: 120px; left: 0px;">
						<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
						<img src="<?php echo !empty($morador)?(!empty($morador['foto'])?base_url("uploads/moradores/".$morador['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_exibir">
						<input type="hidden" name="foto" value="" id="foto"/>
					</div>
				</div>
				<div class="col-md-10">
					
					<!--div class="col-md-9">
						<label for="arq">Via upload</label><input type="file" name="foto" class="form-control" value="" id="arq"/>
						
					</div-->
					<div class="col-md-4" style="padding-left: 0px;"> 	
						<label for="nome">Nome*</label><input class="form-control" type="text" name="nome" value="<?php echo !empty($morador)?$morador['nome']:""; ?>" id="nome" required='required' />
					</div>

					<div class="col-md-3" style="padding-left: 0px;">     
						<label for="rg">RG*</label><input type="text" name="rg" value="<?php echo !empty($morador)?$morador['rg']:""; ?>" class="rg form-control" id="rg" required='required' />
					</div>

					<div class="col-md-3" style="padding-left: 0px;">     
						<label for="cpf">CPF*</label><input  type="text" name="cpf" value="<?php echo !empty($morador)?$morador['cpf']:""; ?>" required='required' class="form-control<?php echo (!empty($morador['cpf'])?"":" cpf");?>" <?php echo (!empty($morador['cpf'])?"readonly='readonly'":"");?>/>
					</div>

					<div class="col-md-2" style="padding: 0px;"> 
						<label for="data_nascimento">Data de Nascimento*</label><input type="text" name="data_nascimento" value="<?php echo !empty($morador)?date('d/m/Y',strtotime($morador['data_nascimento'])):""; ?>" id="data_nascimento" class='data form-control' required='required' />
					</div>
				</div>
			

			<div class="col-md-3"> 
    
				<label for="sexo">Sexo*</label><br/>
					<label style="margin-right: 10px;">Masculino&nbsp;<input type="radio" name="sexo" value="M" id="sexo" required='required' <?php echo !empty($morador)?($morador['sexo']=='M'?"checked='checked'":""):""; ?>/></label>
					<label>Feminino&nbsp;<input type="radio" name="sexo" value="F" id="sexo" required='required' <?php echo !empty($morador)?($morador['sexo']=='F'?"checked='checked'":""):""; ?>/></label> 
			</div>

			<div class="col-md-3">
				<label for="tipo">Nível de Responsabilidade:</label>
					<select class="form-control" name="tipo" id="tipo">
						<option value="">Selecione</option>
					<?php
						foreach($tipos as $tipo){
							if ($tipo['id_tipo']!=1){
					?>
								<option value="<?php echo $tipo['id_tipo'];?>" selected='selected'><?php echo $tipo['tipo'];?></option>
					<?php
							}
						}
					?>
					</select>
			</div>
			<div class="col-md-2">
				<label for="telefone">Telefone*</label><input class="form-control" type="text" name="telefone" value="<?php echo !empty($morador)?$morador['telefone']:""; ?>" id="telefone" required='required' />
			</div>

			<div class="col-md-2">
				<label for="celular">Celular</label><input class="form-control" type="text" name="celular" value="<?php echo !empty($morador)?$morador['celular']:""; ?>" id="celular"/>
			</div>

			<div class="col-md-4">
				<label for="email">E-mail*</label><input class="form-control" type="email" name="email" value="<?php echo !empty($morador)?$morador['email']:""; ?>" id="email" required='required' />
			</div>

			<div class="col-md-1">
	            <label for="bloco">Torre</label>
	            <select id="bloco" class="form-control" required='required' style="width:65px;">
	            	<option value="">Selecione</option>
	            	<option value="A" <?php echo (!empty($morador)?$morador['bloco']=='A'?"selected='selected'":"":"");?>>A</option>
	            	<option value="B" <?php echo (!empty($morador)?$morador['bloco']=='B'?"selected='selected'":"":"");?>>B</option>
	            	<option value="C" <?php echo (!empty($morador)?$morador['bloco']=='C'?"selected='selected'":"":"");?>>C</option>
	            </select>
	        </div>
			        

			<div class="col-md-1">

					<label for="unidade">Unidade</label>
					<select name="unidade" class="form-control"  id="unidade" required='required' style="width:70px;"/>
						<option value=""></option>
						<?php
							if (!empty($morador)){
						?>
							<option value="<?php echo $morador['id_unidade'];?>" selected='selected'><?php echo $morador['unidade'];?></option>
						<?php
							}
						?>
					</select>
			            
			</div>
			<div class="col-md-1">
				<label for="animais">Animais</label><input type="text" name="animais" class="form-control" value="<?php echo !empty($morador)?$morador['animais']:""; ?>" id="animais" maxlength="2"/>
				
			</div>
			<div class="col-md-2">
				<label for="data_moradia">Morador desde:</label><input class="form-control data" type="text" name="data_moradia" value="<?php echo !empty($morador)?date('d/m/Y',strtotime($morador['data_moradia'])):""; ?>" id="data_moradia" />
			</div>
			<div class="col-md-12 text-right">
				<br/>
				<button class="btn btn-success" type='submit'><?php echo !empty($morador)?"Alterar":"Cadastrar";?></button> 
			</div>
			<?php } ?>
		</div>	
	</div>
</div>
</form>
<!-- FIM DADOS DO MORADOR -->

<?php
	if (!empty($morador)){
?>
<p align="right"><button type='button' class="btn btn-success" id="familiar">Novo membro na familia!</button></p>
<div id="cad_familiar" style='display:none;'>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Cadastro de Familiares</strong></div>
		<div class="panel-body" style="padding: 10px;">
			
			<!-- INICIO DE DADOS DE FAMILIARES -->
			<form action="<?php echo base_url('moradores/familiares_submit/'.$id_morador);?>" method="post" accept-charset="utf-8" class="familiar" onsubmit='return validaMorador("familiar");' >
				
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
								$("#foto_f").val(image_url);
								$("#foto_f_exibir").attr("src",image_url);
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
							<img src="<?php echo base_url('img/layout/foto-default.png');?>" id="foto_f_exibir">
							<input type="hidden" name="foto" value="" id="foto_f"/>
						</div>
					</div>	
				
				<div class="col-md-6">
				  <label for="nome">Nome*</label><input type="text" name="nome" class="form-control" value="" id="nome" required/>
				</div>
				
				<div class="col-md-4">
				  <label for="parentesco">Parentesco*</label>
				  <select class="form-control" name="parentesco" id="parentesco" required/>
				  	<option value=""></option>
				  	<option value="Filho">Filho</option>
				  	<option value="Pai">Pai</option>
				  	<option value="Mãe">Mãe</option>
				  	<option value="Irmã(o)">Irmã(o)</option>
				  	<option value="Cônjuge">Cônjuge</option> 
				  	<option value="Amigo">Amigo</option>
				  	<option value="Outro">Outro</option>
				  </select>
				</div>
				<div class="col-md-3">
				  <label for="rg">CPF</label><input type="text" name="cpf" value="" class="cpf form-control"/>
				</div>
				<div class="col-md-3">
				  <label for="rg">RG</label><input type="text" name="rg" value="" class="rg form-control"/>
				</div>
				<div class="col-md-4">
				  <label for="data_nasc">Data de Nascimento*</label><input type="text" name="data_nascimento" value="" class='form-control data' id="data_nasc" required/>
				</div>
			</div>
			<div class="col-md-8" style="padding-left: 0px;">
				<div class="col-md-4">
				  <label for="email">E-mail</label><input type="email" name="email" value="" class='form-control' id="email" />
				</div>
				<div class="col-md-4">
				  <label for="telefone">Telefone</label><input type="text" name="telefone" value="" class="form-control telefone" />
				</div>
				<div class="col-md-4">
				  <label for="celular">Celular</label><input type="text" name="celular" value="" class="form-control celular" />
				</div>
				<div class="col-md-6">
				  <label for="sexo">Sexo*</label><br>
					<label>Masculino&nbsp;<input type="radio" name="sexo" value="M" id="sexo" required/></label>
					<label>Feminino&nbsp;<input type="radio" name="sexo" value="F" id="sexo" required/></label>
				</div>
				<div class="col-md-6" style="text-align: right;">
					<br/>
				  <input class="btn btn-success" type="submit" value="Cadastrar Familiar"/>
				</div>
				</div>
			</div>
				
			</form>
		</div>
	</div>
</div>

<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Familiares</strong></div>
		<div class="panel-body" style="padding: 10px;">
		<div class="edita_familia"></div>
	<!-- INICIO DE EXIBIÇÃO DE TODOS OS FAMILIARES -->
	<?php
		if (!empty($familiares)){
	?>
		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Parentesco</th>
				<th>Nome</th>
				<th>CPF</th>
				<th>RG</th>
				<th>Data de Nascimento</th>
				<th>Sexo</th>
				<th>&nbsp;</th>
			</thead>

	<?php
			foreach ($familiares as $familiar){
	?>
			<tr <?php echo !empty($familiar['foto'])? "data-image='".base_url('uploads/familiares/'.$familiar['foto'])."'":"";?>>
				<td><?php echo $familiar['parentesco'];?></td>
				<td><?php echo $familiar['nome'];?></td>
				<td><?php echo $familiar['cpf'];?></td>
				<td><?php echo $familiar['rg'];?></td>
				<td><?php echo date('d/m/Y',strtotime($familiar['data_nascimento']));?></td>
				<td><?php echo ($familiar['sexo']=='M'?'Masculino':"Feminino");?></td>
				<td>
					<button type='button' class="edita_familiar btn btn-warning" cod='<?php echo $familiar['id_familia'];?>'>Editar</button>
					<button type='button' class='remover btn btn-danger' cod='<?php echo $familiar['id_familia'];?>'>Remover</button>
				</td>
			</tr>
			
			<div id="familia<?php echo $familiar['id_familia'];?>" class="edita_familia" style='display:none;' >
				<div class="panel panel-default">
					<div class="panel-heading">Editar <?php echo $familiar['parentesco']."<strong> ".$familiar['nome'];?></strong></div>
					<div class="panel-body" style="padding: 10px;">
			
						<!-- INICIO DE DADOS DE FAMILIARES -->
						<form action="<?php echo base_url('moradores/familiares_submit/'.$id_morador."/".$familiar['id_familia']);?>" method="post" accept-charset="utf-8"  onsubmit='return validaMorador("familiar");'  >
								<div class="col-md-2">
									<div style="position: relative; width: 160px; height: 120px; left: -50px;">
										<div style="position: absolute; width: 160px; height: 120px; background: url(<?php echo base_url("img/layout/desfarca-borda.png");?>) no-repeat center center; z-index: 9;"></div>
										<img src="<?php echo !empty($familiar)?(!empty($familiar['foto'])?base_url("uploads/familiares/".$familiar['foto']):base_url('img/layout/foto-default.png')):base_url('img/layout/foto-default.png');?>" id="foto_f_exibir<?php echo $familiar['id_familia'];?>">
										<input type="hidden" name="foto" value="<?php echo $familiar['foto'];?>" />
									</div>
								</div>	
							<div class="col-md-2">
							  <label for="parentesco">Parentesco*</label>
							  <select class="form-control" name="parentesco" id="parentesco" required/>
							  	<option value=""></option>
							  	<option value="Filho" <?php echo $familiar['parentesco']=='Filho'?"selected='selected'":"";?>>Filho</option>
							  	<option value="Pai" <?php echo $familiar['parentesco']=='Pai'?"selected='selected'":"";?>>Pai</option>
							  	<option value="Mãe" <?php echo $familiar['parentesco']=='Mãe'?"selected='selected'":"";?>>Mãe</option>
							  	<option value="Irmã(o)"  <?php echo $familiar['parentesco']=='Irmã(o)'?"selected='selected'":"";?>>Irmã(o)</option>
							  	<option value="Cônjuge" <?php echo $familiar['parentesco']=='Cônjuge'?"selected='selected'":"";?>>Cônjuge</option>
							  	<option value="Amigo"<?php echo $familiar['parentesco']=='Amigo'?"selected='selected'":"";?>>Amigo</option>
							  	<option value="Outro" <?php echo $familiar['parentesco']=='Outro'?"selected='selected'":"";?>>Outro</option>
							  </select>
							</div>	
							<div class="col-md-6">
							  <label for="nome">Nome*</label><input type="text" name="nome" class="form-control" value="<?php echo $familiar['nome'];?>" id="nome" required/>
							</div>
							<div class="col-md-2">
							  <label for="rg">CPF*</label><input type="text" name="cpf" value="<?php echo $familiar['cpf'];?>" class="cpf form-control" required/>
							</div>
							<div class="col-md-2">
							  <label for="rg">RG*</label><input type="text" name="rg" value="<?php echo $familiar['rg'];?>" class="rg form-control" required/>
							</div>
							<div class="col-md-2">
							  <label for="data_nascimento<?php echo $familiar['id_familia'];?>">Data de Nascimento*</label><input type="text" name="data_nascimento" value="<?php echo date('d/m/Y',strtotime($familiar['data_nascimento']));?>" class='form-control data' id="data_nascimento<?php echo $familiar['id_familia'];?>" required/>
							</div>
							<div class="col-md-4">
							  <label for="email">E-mail*</label><input type="email" name="email" value="<?php echo $familiar['email'];?>" class='form-control' id="email" required/>
							</div>
							<div class="col-md-2">
							  <label for="telefone">Telefone*</label><input type="text" name="telefone" value="<?php echo $familiar['telefone'];?>" class="form-control telefone" required/>
							</div>
							<div class="col-md-2">
							  <label for="celular">Celular*</label><input type="text" name="celular" value="<?php echo $familiar['celular'];?>" class="form-control celular" />
							</div>
							<div class="col-md-3">
							  <label for="sexo">Sexo*</label><br>
								<label>Masculino&nbsp;<input type="radio" name="sexo" value="M" id="sexo" required <?php echo $familiar['sexo']=='M'?"checked='checked'":"";?>/></label>
								<label>Feminino&nbsp;<input type="radio" name="sexo" value="F" id="sexo" required <?php echo $familiar['sexo']=='F'?"checked='checked'":"";?>/></label>
							</div>
							<div class="col-md-12">
								<br/>
							  <input class="btn btn-success" type="submit" value="Alterar Familiar"/>
							</div>
						</form>
					</div>
				</div>
			</div>
			
	<?php
			}
	?>
		</table>
	<?php
		}else{
	?>
			Sem familiares cadastrados !
	<?php
		}
	?>
	<!-- FIM DE EXIBIÇÃO DE TODOS OS FAMILIARES -->
	</div></div>
<!-- FIM DE CADASTRO DE FAMILIARES -->
<?php
}
?>	
<p align="right"><button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('moradores');?>"'>Voltar</button></p>