<script>
	$(function(){
		$(".editar").on("click",function(){
			var morador = $(this).attr('cod');
			window.location.href='<?php echo base_url('moradores/registro');?>/'+morador;
		});
		
		$("#cadastrar").on("click",function(){
			var morador = $(this).attr('cod');
			window.location.href='<?php echo base_url('moradores/registro');?>/';
		});	
		$(".familiares").on("click",function(){
			var morador = $(this).attr('cod');
			$("#"+morador).toggle();
			$(this).toggleClass('btn-danger');
		});		
	});
</script>
<?php echo ($this->session->userdata('tipo') != 4 ? '<p align="right"><button id="cadastrar" class="btn btn-success">Cadastrar Novo Responsável</button></p><br />' : NULL); ?>
<?php
if (!empty($moradores)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Todos Moradores Cadastrados</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" cellpadding="5" class="table table-striped">
		<thead>
			<th width="1%">Torre</th>
			<th width="1%">Unidade</th>
			<th width="5%">Nível</th>
			<th width="20%">Morador</th>
			<th width="10%">CPF</th>
			<th width="10%">E-mail</th>
			<th width="28%">Telefone</th>
			<th width="25%">&nbsp;</th>
		</thead>
<?php
$morador_old = NULL;
	foreach ($moradores as $morador){
		if ($morador['id_unidade']!=$morador_old){
?>
	
		<tr <?php echo !empty($morador['foto'])? "data-image='".base_url('uploads/moradores/'.$morador['foto'])."'":"";?>>
			<td><?php echo $morador['bloco'];?></td>
			<td><?php echo $morador['unidade'];?></td>
			<td><?php echo $morador['tipo'];?></td>
			<td><?php echo $morador['nome'];?></td>
			<td><?php echo $morador['cpf'];?></td>
			<td><?php echo mailto($morador['email'],$morador['email']);?></td>
			<td><?php echo $morador['telefone'];?></td>
			<td align="right">
				<button cod='<?php echo $morador['id_morador'];?>' class="btn btn-warning editar">Editar</button>
				<?php echo (!empty($familiares[$morador['id_morador']])?"<button class='familiares btn btn-warning' style='margin-top: 5px;' cod='".$morador['id_morador']."'>Familiares</button>":"");?>
			</td>
		</tr>	
<?php
			if (!empty($familiares[$morador['id_morador']])){
			
?>
			<tr id='<?php echo $morador['id_morador'];?>' style='display: none;'>
				<td colspan='9'>
					<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
						<tr>
							<th>Parentesco</th>
							<th>Nome</th>
							<th>CPF</th>
						</tr>
<?php
				foreach($familiares[$morador['id_morador']] as $familiar){
?>	
					<tr <?php echo !empty($familiar['foto'])?"data-image='".base_url('uploads/familiares/'.$familiar['foto'])."'":"";?>>
						<td><?php echo $familiar['parentesco'];?></td>
						<td><?php echo $familiar['nome'];?></td>
						<td><?php echo $familiar['cpf'];?></td>
					</tr>
<?php
			}
?>
					</table>
				</td>	
			</tr>	
<?php	
			}
		}

		$morador_old = $morador['id_unidade'];
	}
?>
	</table>
    
    </div>
 </div>
<?php
}else{
?>
	Não há moradores cadastrados !
<?php
}
?>
