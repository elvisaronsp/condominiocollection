
<script>
	$(function(){
		$(".detalhes").on("click",function(){
			var vaga = $(this).attr('cod');
			window.location.href='<?php echo base_url('unidades/vaga_registro');?>/'+vaga;
		});
		$("#cadastro").on("click",function(){
			window.location.href='<?php echo base_url('unidades/vaga_registro/');?>/';
		});
	});
	
	
</script>
<p align="right"><button type='button' class='btn btn-success' id="cadastro">Nova Vaga</button></p>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Vagas Cadastradas</strong></div>
		
		<div class="panel-body">
		<?php if(!empty($vagas)) { ?>
				<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
					<thead>
						<th>Vaga</th>
						<th>Unidade</th>
						<th>&nbsp;</th>
					</thead>
<?php
		foreach ($vagas as $vaga) {
			
?>
					<tr>
						<td><?php echo $vaga['vaga'];?></td>
						<td><?php echo $vaga['bloco']!="Condomínio"?$vaga['bloco']." - ".$vaga['unidade']:"Condomínio";?></td>
						<td><button type='button' class='detalhes btn btn-warning' cod='<?php echo $vaga['id_vaga'];?>'>Detalhes</button></td>
					</tr>
<?php	
		}
?>
				</table>
<?php
	}else{
?>
		Não há vagas cadastradas !
<?php
	}
?>
        	</div>
        </div>
