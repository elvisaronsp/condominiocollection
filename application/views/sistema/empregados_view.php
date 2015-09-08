<script>
	$(function(){
		$("#cadastrar").on("click",function(){
			window.location.href='<?php echo base_url('empregados/registro');?>';
		});
		$(".detalhes").on("click",function(){
			var empregado = $(this).attr('cod');
			window.location.href='<?php echo base_url('empregados/registro');?>/'+empregado;
		});
	});
	
	
</script>


<p align="right"><button type='button' class="btn btn-success" id='cadastrar'>
	<img src="<?php echo base_url("img/icone/empregado.png"); ?>" border="0" style="margin: -3px 0px 0px -3px;" /> Novo empregado
</button></p>

<?php
	if (!empty($empregados)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos empregados Cadastrados</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
			<thead>
				<th>Nome</th>
				<th>Endereço</th>
				<th>Contato</th>
				<th>Dias</th>
				<th>&nbsp;</th>
			</thead>

<?php
		foreach ($empregados as $empregado) {
?>
			<tr>
				<td><?php echo $empregado['nome'];?></td>
				<td><?php echo $empregado['endereco'].",".$empregado['numero']." - ".$empregado['bairro']."<br>".$empregado['cidade']."-".$empregado['estado'];?></td>
				<td><?php echo $empregado['telefone']; echo !empty($empregado['celular'])?" / ".$empregado['celular']:"";?></td>
				<td><?php echo $empregado['dias'];?></td>
				<td><button type='button' class='detalhes btn btn-warning' cod='<?php echo $empregado['id_empregado'];?>'>Detalhes</button></td>
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
			<div class="panel-heading"><strong>Empregados Domésticos</strong></div>
			<div class="panel-body">
	Não trabalha ninguém por aqui...
</div>
</div>
<?php
	}
?>
