<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			
			window.location.href='<?php echo base_url('bicicletario/registro');?>';
		});
		$(".editar").on("click",function(){
			var id_bicicleta = $(this).attr('cod');
			window.location.href='<?php echo base_url('bicicletario/registro');?>/'+id_bicicleta;
		});
	});
	
	
</script>

<p align="right">
	<button type='button' class="btn btn-success" id='cadastrar'>
		Adicionar bicicleta
	</button>
</p>

<div class="panel panel-default">
	<div class="panel-heading"><strong>Bicicletário</strong></div>
		<div class="panel-body">

			<?php if (empty($bicicletas)){ ?>
				Não há bicicletas cadastradas !
			<?php }?>
			<?php if (!empty($bicicletas)){ ?>
				<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
					<thead >
						<th>Unidade</th>
						<th>Lacre</th>
						<th>Cor</th>
						<th>&nbsp;</th>
					</thead>
					<tbody>
						<?php foreach ($bicicletas as $bicicleta) { ?>
						<tr>
							<td><?php echo $bicicleta['bloco']." - ".$bicicleta['unidade'];?></td>
							<td><?php echo $bicicleta['lacre'];?></td>
							<td><?php echo $bicicleta['cor'];?></td>
							<td>
								<button type='button' class="btn btn-success editar" cod="<?php echo $bicicleta['id_bicicleta'];?>" >
									Editar
								</button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>	
		</div>
	</div>
</div>
