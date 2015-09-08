<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('colaboradores/empresa_registro');?>';
		});
		$(".detalhes").on("click",function(){
			var empresa = $(this).attr('cod');
			window.location.href='<?php echo base_url('colaboradores/empresa_registro');?>/'+empresa;
		});
	});
	
	
</script>


<p align="right"><a class="btn btn-warning" href='<?php echo base_url('colaboradores'); ?>'>Colaboradores</a> <button type='button' class="btn btn-success" id='cadastrar'>
	Adicionar Empresa Terizada
</button></p>

<?php
	if (!empty($empresas)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todas Empresas Terizadas Cadastradas</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th width="20%">Empresa</th>
				<th width="20%">E-mail</th>
				<th width="20%">CNPJ</th>
				<th width="10%">&nbsp;</th>
			</thead>

<?php
		foreach ($empresas as $empresa) {
?>
			<tr>
				<td><?php echo $empresa['razao_social'];?></td>
				<td><?php echo mailto($empresa['email'],$empresa['email']);?></td>
				<td><?php echo $empresa['cnpj'];?></td>
				<td><button type='button' cod='<?php echo $empresa['id_empresa'];?>' class='detalhes btn btn-warning'>Detalhes</button></td>
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
	Não há Empresas Tercerizadas cadastradas !
<?php
	}
?>
