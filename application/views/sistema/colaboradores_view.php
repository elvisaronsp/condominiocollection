<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('colaboradores/registro');?>';
		});
		$(".detalhes").on("click",function(){
			var colaborador = $(this).attr('cod');
			window.location.href='<?php echo base_url('colaboradores/registro');?>/'+colaborador;
		});
	});
</script>

<p align="right"><a class="btn btn-warning" href='<?php echo base_url('colaboradores/empresas/'); ?>'>Empresas Terceiras</a> <button type='button' class="btn btn-success" id='cadastrar'>
	Adicionar Colaborador
</button></p>

<?php
	if (!empty($colaboradores)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Colaboradores Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Nome</th>
				<th>CPF</th>
				<th>Endereço</th>
				<th>Contato</th>
				<th>Data de admissão</th>
				<th>Situação</th>
				<th>Tercerizado</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($colaboradores as $colaborador) {
?>
			<tr>
				<td><?php echo $colaborador['nome'];?></td>
				<td><?php echo $colaborador['cpf'];?></td>
				<td><?php echo $colaborador['endereco'].",".$colaborador['numero']." - ".$colaborador['bairro']."<br>".$colaborador['cidade']."-".$colaborador['estado'];?></td>
				<td><?php echo $colaborador['telefone']; echo !empty($colaborador['celular'])?" / ".$colaborador['celular']:"";?></td>
				<td><?php echo date('d/m/Y',strtotime($colaborador['data_admissao']));?></td>
				<td><?php echo $colaborador['data_demissao']!=NULL?"Trabalhou até ".date('d/m/Y',strtotime($colaborador['data_demissao'])):"Colaborador";?></td>
				<td><?php echo $colaborador['tercerizado']==1?"Terceiro da Empresa ".$colaborador['razao_social']:"Não";?></td>
				<td>
					<button type='button' cod='<?php echo $colaborador['id_colaborador'];?>' class='detalhes btn btn-warning'>Detalhes</button>
				</td>
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
	Não há colaboradores cadastrados !
<?php
	}
?>
