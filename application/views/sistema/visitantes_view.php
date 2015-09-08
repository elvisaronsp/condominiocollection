<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			window.location.href='<?php echo base_url('visitantes/registro');?>';
		});
		$("#autorizado").on("click",function(){
			window.location.href='<?php echo base_url('visitantes/autorizados');?>';
		});
		$(".editar").on("click",function(){
			var visitante = $(this).attr('cod');
			window.location.href='<?php echo base_url('visitantes/registro');?>/'+visitante;
		});
		$(".pessoas").on("click",function(){
			var visitante = $(this).attr('cod');
			console.log(visitante);
			$(this).toggleClass("btn-danger");
			$("#"+visitante).toggle();
		});
	});
	
	
</script>


<p align="right"><button type='button' class="btn btn-success" id='cadastrar'>
	<img src="<?php echo base_url("img/icone/usuario.png"); ?>" border="0" style="margin: -3px 0px 0px -3px;" /> Chegou novo visitante!
</button>
<button type='button' class="btn btn-success" id='autorizado'>
	Visitantes Autorizados
</button>
</p>

<?php
	if (!empty($visitantes)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Visitantes Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Visitante</th>
				<th>Documento</th>
				<th>Data da Visita</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($visitantes as $visitante) {
?>
			
			<tr <?php echo !empty($visitante['foto'])? "data-image='".base_url('uploads/visitantes/'.$visitante['foto'])."'":"";?>>
				<td><?php echo $visitante['visitante'];?></td>
				<td><?php echo $visitante['documento'];?></td>
				<td><?php echo date("d/m/Y H:i",strtotime($visitante['data']));?></td>
				<td><?php if (!empty($pessoas) && !empty($pessoas[$visitante['id_visitante']])){ ?><button type='button' class="btn btn-success pessoas" cod="<?php echo $visitante['id_visitante'];?>">+<?php echo count($pessoas[$visitante['id_visitante']]);?> Pessoas</button> <?php } ?></td>
			</tr>
			<?php if (!empty($pessoas) && !empty($pessoas[$visitante['id_visitante']])){
			?>
			<tr id="<?php echo $visitante['id_visitante'];?>" style="display:none;">
				<td colspan="4">
					<table  class="table">
						<tr>
							<td colspan="4"> <strong>Entrou com mais <?php echo count($pessoas[$visitante['id_visitante']]);?> Pessoas:</strong></td>
						</tr>
						<?php
							foreach($pessoas[$visitante['id_visitante']] as $pessoa){
						?>
						<tr>
							<td colspan='4'><?php echo $pessoa['nome'];?></td>
						</tr>
				
			<?php
							}
			?>
					</table>
				</td>
			</tr>
			<?php
			}
			?>
			
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
			<div class="panel-heading"><strong>Visitantes</strong></div>
			<div class="panel-body">
	Não passou ninguém por aqui...
</div>
</div>
<?php
	}
?>
