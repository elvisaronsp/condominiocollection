<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('servicos/reg_prestador');?>';
		});
		$(".editar").on("click",function(){
			var prestador = $(this).attr('cod');
			window.location.href='<?php echo base_url('servicos/reg_prestador');?>/'+prestador;
		});
		$(".finalizar").on("click",function(){
			var prestador = $(this).attr('cod');
			window.location.href='<?php echo base_url('servicos/finalizar_prestador');?>/'+prestador;
		});
	});
</script>

<p align="right"><button type='button' class="btn btn-success" id='cadastrar'>
	Adicionar Prestador
</button></p>

<?php
	if (!empty($prestadores)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Prestadores de Serviços Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Local</th>
				<th>Serviço</th>
				<th>Nome</th>
				<th>Empresa</th>
				<th>Data de início</th>
				<th>Data de conclusão</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($prestadores as $prestador) {
?>
			<tr <?php echo !empty($prestador['foto'])? "data-image='".base_url('uploads/prestadores/'.$prestador['foto'])."'":"";?>>
				<td><?php echo $prestador['bloco']." - ". $prestador['unidade'];?></td>
				<td><?php echo $prestador['servico'];?></td>
				<td><?php echo $prestador['nome'];?></td>
				<td><?php echo $prestador['empresa'];?></td>
				<td><?php echo date('d/m/Y',strtotime($prestador['data_inicio']));?></td>
				<td><?php echo $prestador['data_fim']!=NULL?date('d/m/Y',strtotime($prestador['data_fim'])):"Trabalhando";?></td>
				<td>
					<?php
						if ($prestador['data_fim']==NULL){
					?>
							<button type='button' cod='<?php echo $prestador['id_prestador'];?>' class='finalizar btn btn-success'>Finalizar Trabalho</button>
					<?php
						}
					?>
					<button type='button' cod='<?php echo $prestador['id_prestador'];?>' class='editar btn btn-warning'>Detalhes</button>
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
	Não há prestadores serviços cadastrados !
<?php
	}
?>
