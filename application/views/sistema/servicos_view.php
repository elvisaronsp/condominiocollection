<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('servicos/reg_servico');?>';
		});
		$(".editar").on("click",function(){
			var servico = $(this).attr('cod');
			window.location.href='<?php echo base_url('servicos/reg_servico');?>/'+servico;
		});
	});
	
	
</script>


<p align="right"><a class="btn btn-primary" href='<?php echo base_url('servicos/prestadores'); ?>'>Prestadores de Serviços</a> <button type='button' class="btn btn-success" id='cadastrar'>
	Adicionar Serviços
</button></p>

<?php
	if (!empty($servicos)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Serviços Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Serviço</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($servicos as $servico) {
?>
			<tr>
				<td width="90%"><?php echo $servico['servico'];?></td>
				<td width="10%"><button type='button' cod='<?php echo $servico['id_servico'];?>' class='editar btn btn-warning'>Editar</button></td>
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
	Não há serviços cadastrados !
<?php
	}
?>
