<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('usuarios/tipo_registro');?>';
		});
		$(".editar").on("click",function(){
			var tipo = $(this).attr('cod');
			window.location.href='<?php echo base_url('usuarios/tipo_registro');?>/'+tipo;
		});
	});
	
	
</script>


<p align="right">
	<button type='button' class="btn btn-success" id='cadastrar'>Novo Perfil
</button></p>

<?php
	if (!empty($tipos)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Perfis Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Perfil</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($tipos as $tipo) {
?>
			<tr>
				<td width="90%"><?php echo $tipo['tipo'];?></td>
				<td width="10%"><button type='button' cod='<?php echo $tipo['id_tipo'];?>' class='editar btn btn-warning'>Editar</button></td>
			</tr>
<?php	
		}
?>
		</table>
        
        </div>
   </div>
<?php
	}
?>
