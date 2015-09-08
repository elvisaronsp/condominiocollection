<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			window.location.href='<?php echo base_url('ocorrencias/registro');?>';
		});
		$(".detalhes").on("click",function(){
			var ocorrencia = $(this).attr('cod');
			window.location.href='<?php echo base_url('ocorrencias/registro');?>/'+ocorrencia;
		});
	});
	
	
</script>


<p align="right"><button type='button' class="btn btn-success" id='cadastrar'>
	<img src="<?php echo base_url("img/icone/ocorrencia.png"); ?>" border="0" style="margin: -3px 0px 0px -3px;" /> Nova Ocorrência
</button></p>

<?php
	if (!empty($ocorrencias)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todas ocorrências Cadastradas</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Ocorrência</th>
				<th>Urgência</th>
				<th>Data da Ocorrência</th>
				<th>Solicitante</th>
				<th>&nbsp;</th>
			</thead>
<?php
		foreach ($ocorrencias as $ocorrencia) {
			
?>
			<tr>
				<td><?php echo $ocorrencia['titulo'];?></td>
				<td><?php echo $ocorrencia['nivel'];?></td>
				<td><?php echo date("d/m/Y",strtotime($ocorrencia['data_ocorrencia']));?></td>
				<td><?php echo ucwords($ocorrencia['usuario']);?></td>
				<td><button type='button' class='detalhes btn btn-warning' cod='<?php echo $ocorrencia['id_ocorrencia'];?>'>Detalhes</button></td>
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
			<div class="panel-heading"><strong>Ocorrências</strong></div>
			<div class="panel-body">
	Não houveram problemas por aqui...
</div></div>
<?php
	}
?>
