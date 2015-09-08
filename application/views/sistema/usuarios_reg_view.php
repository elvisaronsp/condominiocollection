<?php $id_usuario = !empty($usuario)?$usuario['id_usuario']:NULL; ?>
<script>
	$(function(){
		var usuario = 0;
		$("#usuario").on("blur",function(){
			if ($(this).val()!=""){
				
				$.ajax({
					url:'<?php echo base_url('usuarios/verifica_usuario');?>',
					type:'post',
					data:$.param({
						usuario:$(this).val()
					}),
					success:function(r){
						
						if (r){
							$("#usuario").css("border-color", "#ff0000");
							usuario = 1;
							
						}else{
							$("#usuario").css("border-color", "#3c763d");
							usuario = 0;
						}
					}
				});
				
			}
			
		});
	});
	function validausuario() {
	  		  	var required =$(".usuario>[required='required']").parent('form');
		var req = 0;
		var regex = '[^a-zA-Z0-9]+[^ \]+';
		for ( i = 0; i < required.length; i++) {
			
			if ($(required[i]).val() == "" || $(required[i]).val()==" ") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if($('#usuario').val().match(regex)){
			req = 1;
			$('#error_usu').text("Proíbida a entrada de caracteres especiais !");
			$('#usuario').css("border-color", "#ff0000");
		}

		if (req == 1 || usuario == 1) {
			$("#flash").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
</script>
<!-- FORM DE INFORMAÇÕES Do Usuário -->
<div id="cad_usuario">
	
 <div class="panel panel-default">
	<div class="panel-heading"><strong>Cadastro de Usuário:</strong></div>
		<div class="panel-body">
			<form action="<?php echo base_url('usuarios/usuarios_submit/'.$id_usuario);?>" method="post" accept-charset="utf-8" class="usuario" onsubmit='return validausuario();' >
				<div id='error_usu'></div>
				<div class="col-md-3"> 	
					<label for="usuario">Usuário</label>
					<input type="text" name="usuario" class="form-control" value="<?php echo !empty($usuario)?$usuario['usuario']:"";?>" id="usuario"  <?php echo !empty($usuario)?"readonly='readonly'":"required='required'";?> />
				</div>
				<div class="col-md-3"> 	
					<label for="senha">Senha</label>
					<input type="password" name="senha" class="form-control" id="senha" <?php echo empty($usuario)?"required='required'":"";?>/> <?php echo !empty($usuario)?"<small>Preencha para alterar</small>":"";?>
				</div>
				<div class="col-md-3">
					<label for="tipo">Privilégio</label>
					<select name='tipo' class="form-control" id='tipo_usuario' required='required'>
					<option value=""></option>
				<?php
				
					foreach ($usuarios_tipos as $tipo){
				?>
					<option value="<?php echo $tipo['id_tipo'];?>" <?php echo !empty($usuario)?($usuario['id_tipo']==$tipo['id_tipo']?"selected='selected'":""):"";?> ><?php echo $tipo['tipo'];?></option>	
				<?php
					}
				?>
					</select>
				</div>
				
		  		<div class="col-md-3">
		  			<br/>
		  			<input class="btn btn-success" type="submit" value="<?php echo !empty($usuario)?"Alterar":"Cadastrar";?> Usuário"/>
		  			<button type='button' class="btn btn-danger" onclick='window.location.href="<?php echo base_url('usuarios');?>"'>Voltar</button>
		  		</div>
			</form>
		</div>
        </div>
        </div>
</div>

<br/>&nbsp;