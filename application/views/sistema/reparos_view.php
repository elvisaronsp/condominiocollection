
<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			window.location.href='<?php echo base_url('reparos/registro');?>';
		});
		$(".detalhes").on("click",function(){
			var reparo = $(this).attr('cod');
			window.location.href='<?php echo base_url('reparos/registro');?>/'+reparo;
		});
	});
	
	
</script>


<p align="right"><button type='button' class="btn btn-success" id='cadastrar'>
	<img src="<?php echo base_url("img/icone/reparo.png"); ?>" border="0" style="margin: -3px 0px 0px -3px;" /> Novo reparo
</button></p>

<?php
	if (!empty($reparos)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos reparos Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Reparo</th>
				<th>Urgência</th>
				<th>Data do reparo</th>
				<th>Solicitante</th>
				<th>&nbsp;</th>
			</thead>
<?php
		foreach ($reparos as $reparo) {
			
?>
			<tr>
				<td><?php echo $reparo['titulo'];?></td>
				<td><?php echo $reparo['nivel'];?></td>
				<td><?php echo date("d/m/Y",strtotime($reparo['data']));?></td>
				<td><?php echo ucwords($reparo['usuario']);?></td>
				<td><button type='button' class='detalhes btn btn-warning' cod='<?php echo $reparo['id_reparo'];?>'>Detalhes</button></td>
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
<div class="col-md-12" style="padding: 0px;">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Reparos</strong></div>
			<div class="panel-body">
	Não houve nenhum problema por aqui...
</div>
</div>
<?php
	}
?>
