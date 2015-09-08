<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			window.location.href='<?php echo base_url('visitantes/autorizado_registro');?>';
		});
	});
	
	
</script>
<p align="right">
<button type='button' class="btn btn-success" id='cadastrar'>
	Nova autorização
</button>
</p>

<?php
	if (!empty($autorizados)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Visitantes Autorizados Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Visitante</th>
				<th>RG</th>
				<th>CPF</th>
				<th>Endereço</th>
				<th>Observações</th>
			</thead>

<?php
		foreach ($autorizados as $autorizado) {
?>
			<tr <?php echo !empty($autorizado['foto'])? "data-image='".base_url('uploads/autorizados/'.$autorizado['foto'])."'":"";?>>
				<td><?php echo $autorizado['nome'];?></td>
				<td><?php echo $autorizado['rg'];?></td>
				<td><?php echo $autorizado['cpf'];?></td>
				<td><?php echo $autorizado['endereco'].", ".$autorizado['numero'].(!empty($autorizado['complemento'])?"(".$autorizado['complemento'].")":"")." - ".$autorizado['bairro']."<br>".$autorizado['cidade']." - ".$autorizado['estado']."<br>".$autorizado['cep'];?></td>
				<td><?php echo !empty($autorizado['observacoes'])?$autorizado['observacoes']:NULL;?></td>
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
			<div class="panel-heading"><strong>Visitantes Autorizados</strong></div>
			<div class="panel-body">
	Não há ninguém por aqui...
</div>
</div>
<?php
	}
?>
