<script>
	$(function(){
		$(".editar").on("click",function(){
			var proprietario = $(this).attr('cod');
			window.location.href='<?php echo base_url('proprietarios/registro');?>/'+proprietario;
		});
		$("#cadastrar").on("click",function(){
			var proprietario = $(this).attr('cod');
			window.location.href='<?php echo base_url('proprietarios/registro');?>/';
		});				
	});
</script>
<p align="right"><button id='cadastrar' class="btn btn-success">Cadastrar Novo Proprietário</button></p><br />
<?php
if (!empty($proprietarios)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Proprietários Cadastrados</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th>Nome</th>
			<th>CPF</th>
			<th>E-mail</th>
			<th>Unidades</th>
			<th>&nbsp;</th>
		</thead>
	<?php
	foreach ($proprietarios as $proprietario){
	?>
	
		<tr <?php echo !empty($proprietario['foto'])?'data-image="'.base_url("uploads/proprietarios/".$proprietario['foto']).'"':"";?>>
			<td><?php echo $proprietario['nome'];?></td>
			<td><?php echo $proprietario['cpf'];?></td>
			<td><?php echo mailto($proprietario['email'],$proprietario['email']);?></td>
			<td>
				<?php 
				if (!empty($unidades) && !empty($unidades[$proprietario['id_proprietario']])){
					foreach($unidades[$proprietario['id_proprietario']] as $propriedade){
						if ($propriedade['dono']==1)
							echo $propriedade['bloco']." - ". $propriedade['unidade']."<br>";
					}
				}else{
					echo "Sem unidades vinculadas";
				}
				?>
			</td>
			<td> <button cod='<?php echo $proprietario['id_proprietario'];?>' class="btn btn-warning editar">Editar</button></td>
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
	Não há proprietarios cadastrados !
<?php
}
?>
