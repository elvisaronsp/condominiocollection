<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('areas/registro');?>';
		});
		$(".editar").on("click",function(){
			var area = $(this).attr('cod');
			window.location.href='<?php echo base_url('areas/registro');?>/'+area;
		});
	});
	
	
</script>


<p align="right">
	<button type='button' class="btn btn-success" id='cadastrar'>
	Adicionar área
</button></p>

<?php
	if (!empty($areas)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos áreas Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Área</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($areas as $area) {
?>
			<tr>
				<td width="90%"><?php echo $area['area'];?></td>
				<td width="10%"><button type='button' cod='<?php echo $area['id_area'];?>' class='editar btn btn-warning'>Editar</button></td>
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
	Não há áreas cadastrados !
<?php
	}
?>
