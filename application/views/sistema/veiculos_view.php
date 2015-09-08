<script>
	$(function(){
		$(".editar").on("click",function(){
			var veiculo = $(this).attr('cod');
			window.location.href='<?php echo base_url('veiculos/registro');?>/'+veiculo;
		});
		
		$("#cadastrar").on("click",function(){
			var morador = $(this).attr('cod');
			window.location.href='<?php echo base_url('veiculos/registro');?>/';
		});		
		
		$(".remover").on("click",function(){ // remove veículo
			var veiculo = $(this).attr('cod'); 
			$( "#veiculo"+veiculo ).dialog({
		      resizable: false,
		      buttons: {
		        "Sim": function() {
		          $.ajax({
		          	url:'<?php echo base_url('veiculos/remove_veiculo');?>/'+veiculo,
		          	success:function(r){
		          		
		          		$(".veiculo"+veiculo).remove();
		          		$("#veiculo"+veiculo).remove();
		          		$("#flash").text("Veículo Removido com sucesso !");
		          		
		          	}
		          });
		        },
		        "Não": function() {
		          $( this ).dialog( "close" );
		        }
		      }
   			});
		});
		
	});
</script>
<p align="right">
	<button type='button' class="btn btn-success" id="cadastrar">Cadastrar Veículo</button>
</p>

<?php
	if (!empty($veiculos)){
?>

 <div class="panel panel-default">
	<div class="panel-heading"><strong>Veículos</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th>Unidade</th>
			<th>Veículo</th>
			<th>Placa</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Ano</th>
			<th>Cor</th>
			<th>&nbsp;</th>
		</thead>
<?php

		foreach ($veiculos as $veiculo){
			
?>
		<tr class='veiculo<?php echo $veiculo['id_veiculo'];?>'>
			
			<td><?php echo $veiculo['bloco']." - ".$veiculo['unidade'];?></td>
			<td><?php echo $veiculo['tipo'];?></td>
			<td><?php echo ($veiculo['placa']!=NULL?$veiculo['placa']:"");?></td>
			<td><?php echo ($veiculo['marca']!=NULL?$veiculo['marca']:"");?></td>
			<td><?php echo ($veiculo['modelo']!=NULL?$veiculo['modelo']:"");?></td>
			<td><?php echo ($veiculo['ano']!=NULL?$veiculo['ano']:"");?></td>
			<td><?php echo $veiculo['cor'];?></td>
			<td>
				<button type='button' class='editar btn btn-warning' cod='<?php echo $veiculo['id_veiculo'];?>'>Editar</button>
				<button type='button' class='remover btn btn-danger' cod='<?php echo $veiculo['id_veiculo'];?>'>Remover</button>
			</td>
		</tr>
		
		<div id='veiculo<?php echo $veiculo['id_veiculo'];?>' style='display:none'>
			Tem certeza que deseja remover do <strong><?php echo $veiculo['bloco']." - ".$veiculo['unidade']; ?></strong> o(a) <strong><?php echo $veiculo['tipo']; ?></strong> de <strong><?php echo $veiculo['nome']; ?></strong> ?
		</div>
        
        
<?php
		}
?>
	</table>
        </div>
        </div>
<?php
	}else{
?>
		Não há veículos cadastrados !
<?php
	}
?>