<script>
	$(function(){
		$(".editar").on("click",function(){
			var usuario = $(this).attr('cod');
			window.location.href='<?php echo base_url('usuarios/registro');?>/'+usuario;
		});
		
		$("#cadastrar").on("click",function(){
			window.location.href='<?php echo base_url('usuarios/registro');?>/';
		});		
		
		$(".bootstrap-switch-container").on("click",function(){ // ativa desativa usuario
			var usuario = $(this).find('input').attr('cod');
		          $.ajax({
		          	url:'<?php echo base_url('usuarios/toggle_usuario');?>/'+usuario,
		          	success:function(r){
		          		console.log('sucesso !');
		          	}
		          });
   		});
	});
</script>
	
<button type='button' class="btn btn-warning" id="cadastrar">Cadastrar Usuário</button>

<?php
	if (!empty($usuarios)){
?>

 <div class="panel panel-default">
	<div class="panel-heading"><strong>Usuários</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th width="40%">Usuário</th>
			<th width="50%">Tipo</th>
			<th width="10%">&nbsp;</th>
		</thead>
<?php

		foreach ($usuarios as $usuario){
			
?>
		<tr>
			<td><?php echo $usuario['usuario'];?></td>
			<td><?php echo $usuario['tipo'];?></td>
			<td>
				<button type='button' class='editar btn btn-warning' cod='<?php echo $usuario['id_usuario'];?>' style="margin-bottom: 5px;">Editar</button>
				<!--input type='checkbox' > <!-- fazer interruptor on/off -->
				<input type="checkbox" name="my-checkbox" data-size="small" data-on-color="success" data-off-color="danger" data-on-text="ATIVO" data-off-text="INATIVO" class='togglechk' cod='<?php echo $usuario['id_usuario'];?>' <?php echo ($usuario['ativo']==1?"checked":"");?>>
			</td>
		</tr>
		<div id='toggle<?php echo $usuario['id_usuario'];?>' style='display:none'>
			Tem certeza que deseja <?php echo ($usuario['ativo']==1?"Desativar":"Ativar");?> o usuário <strong><?php echo $usuario['usuario'];?></strong> ?
		</div>
<?php
		}
?>
 
        </table>
        </div>
        </div>
<?php
	}else{
?>
		Não há Usuários cadastrados !
<?php
	}
?>