<style>
	label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
</style>
<script>
	$(function(){

		$(".entregar").on("click",function(){
			var cod = $(this).attr('cod');
			$( ".form-entrega"+cod ).dialog( "open" );
		});
		$( ".dialog" ).dialog({
			autoOpen: false,
			height: 240,
			width: 350,
			buttons: {
				"Entregue": function() {
					$(this).find('form').submit();
					//console.log();
				},
				"Cancelar": function() {
					$( this ).dialog( "close" );
				}
			}
		});
		$(".entrega").on("click",function(){
			$("#data_entrega"+$(this).attr('cod')).val('<?php echo date('d/m/Y H:i');?>');
		});
		
	});	
</script>

<p align="right"><a class="btn btn-success" href='<?php echo base_url('areas/utilizacao'); ?>'><img src="<?php echo base_url("img/icone/chave.png"); ?>" border="0" style="margin: -3px 0px 0px -3px;" /> Solicitar Chave</a></p>

 
<?php
	if (!empty($utilizacoes)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todas as Solicitações de Chaves</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Área</th>
				<th>Solicitante</th>
				<th>Data da Reserva</th>
				<th>Retirada das Chaves</th>
				<th>Entrega das Chaves</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($utilizacoes as $utilizacao) {
			
?>
			<div title="Entrega de Chaves" class="form-entrega<?php echo $utilizacao['id_utilizacao'];?> dialog">
			  <form action='<?php echo base_url('areas/utilizacao_finalizar/'.$utilizacao['id_utilizacao']);?>' method="post">
			  <fieldset>
			    <label for="data_entrega">Hora da Entrega</label>
			    <input type="text" name="data_entrega" id="data_entrega<?php echo $utilizacao['id_utilizacao'];?>" class="datahora">
			    <div class="btn-group entrega" data-toggle="buttons" cod='<?php echo $utilizacao['id_utilizacao'];?>'>
		      	 <label class="btn btn-default" for="entrega"><input type='checkbox'>Data e Hora atual</label>
		        </div>
			  </fieldset>
			  </form>
			</div>
			<tr>
				<td><?php echo $utilizacao['area'];?></td>
				<td><?php echo ucfirst($utilizacao['morador']);?></td>
				<td><?php echo date('d/m/Y',strtotime($utilizacao['data_reserva']));?></td>
				<td><?php echo date('d/m/Y H:s',strtotime($utilizacao['data_retirada']));?></td>
				<td><?php echo !empty($utilizacao['data_entrega'])?date('d/m/Y H:i',strtotime($utilizacao['data_entrega'])):"Ainda não entregue";?></td>
				<td><?php echo !empty($utilizacao['data_entrega'])?"":"<button type='button' class='btn btn-success entregar' cod='".$utilizacao['id_utilizacao']."'>Entrege !</a>";?></td
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
			<div class="panel-heading"><strong>Chaves</strong></div>
			<div class="panel-body">
	Não há Solicitações de chaves para esta unidade!
</div>
</div>
<?php
	}
?>
