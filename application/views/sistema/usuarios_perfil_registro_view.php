<?php $id_tipo = !empty($tipo) ? $tipo['id_tipo'] : NULL;?>
<div class="panel panel-default">
	<div class="panel-heading"><strong><?php echo !empty($tipo) ? "Editando os dados do Perfil ".$tipo['tipo'] : "Cadastro de Novo Perfil";?></strong></div>
		<div class="panel-body">
			<form action="<?php echo base_url('usuarios/tipos_submit/'.$id_tipo);?>" method="post" class="" enctype="multipart/form-data">
					<label for="tipo">Perfil</label><input type="text" name="tipo" id="tipo" class="form-control" value="<?php echo !empty($tipo) ? $tipo['tipo'] : NULL;?> ">
					<button type="submit" class="btn btn-success"><?php echo !empty($tipo) ? "Alterar" : "Cadastrar";?></button>
				</form>

<?php if(!empty($tipo)){
?>

<div class="panel-heading"><strong>Painel de privilégios de acesso</strong></div>
	<div class="panel-body">
		<form action="<?php echo base_url('usuarios/privilegios_submit/'.$id_tipo);?>" method="post" class="" enctype="multipart/form-data">
			<table  border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
				<thead>
					<th>Páginas</th>
					<th>Visualizar</th>
					<th width="100%">Editar</th>
				</thead>
				<tbody>
					<?php 
					foreach($privilegios as $privilegio){
					?>
					<tr>
						<td><?php echo $privilegio['pagina'];?></td>
						<td width="1%">
							<div class="checkbox">
								<input type="checkbox" name="visualizar[<?php echo $privilegio['id_privilegio'];?>]" id=""  value="" <?php echo ($privilegio['visualizar']==1?"checked='checked'":"");?>>
							</div>
						</td>
						<td width="1%">
							<div class="checkbox">
								<input type="checkbox" name="editar[<?php echo $privilegio['id_privilegio'];?>]" id=""  value="" <?php echo ($privilegio['editar']==1?"checked='checked'":"");?>>
							</div>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			<button type="submit" class="btn btn-success">Finalizar</button>
		</form>
	</div>
<?php }// painel de privilégios de acesso 
?>


		<button class="btn btn-danger" onclick="window.location.href='<?php echo base_url('perfil');?>'">Voltar</button>
	</div>
</div>
