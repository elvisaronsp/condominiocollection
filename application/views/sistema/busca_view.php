Foram encontrados <strong><?php echo $resultados;?></strong> resultados para "<strong><?php echo $busca;?></strong>":
<?php
if ($resultados > 0){
	if (!empty($moradores)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Resultado de moradores</strong></div>
		<div class="panel-body">

			<table border="0" cellspacing="5" cellpadding="5" class="table table-striped">
				<thead>
					<th width="30%">Morador</th>
					<th width="1%">Bloco</th>
					<th width="1%">Unidade</th>
					<th width="15%">CPF</th>
					<th width="32%">E-mail</th>
					<th width="20%">Telefone</th>
				</thead>
<?php
		foreach ($moradores as $morador){
?>
			<tr>
				<td><?php echo $morador['nome'];?></td>
				<td align="center"><?php echo $morador['bloco'];?></td>
				<td align="center"><?php echo $morador['unidade'];?></td>
				<td><?php echo $morador['cpf'];?></td>
				<td><?php echo mailto($morador['email'],$morador['email']);?></td>
				<td><?php echo $morador['telefone'];?></td>
			</tr>	
<?php
		}
?>
			</table>
	    </div>
	 </div>
<?php
	}
	if(!empty($familiares)){
		
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Resultado de Familiares</strong></div>
		<div class="panel-body">
			<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
				<tr>
					<th>Familiar</th>
					<th>Nome</th>
					<th >CPF</th>
					<th>E-mail</th>
					<th>Contato</th>
				</tr>
<?php
				foreach($familiares as $familiar){
?>	
					<tr>
						<td><?php echo $familiar['parentesco'];?> do morador <a href="<?php echo base_url('moradores/registro/'.$familiar['id_morador']);?>"> <?php echo $familiar['nome_morador'];?></a></td>
						<td><?php echo $familiar['nome'];?></td>
						<td><?php echo $familiar['cpf'];?></td>
						<td><?php echo mailto($familiar['email'],$familiar['email']);?></td>
						<td><?php echo $familiar['telefone'].(!empty($familiar['celular'])?" / ".$familiar['celular']:"");?></td>
					</tr>
<?php
			}
?>
			</table>
		</div>
	</div>
<?php
	}
?>

<?php
if (!empty($proprietarios)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Resultados de proprietários</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th>Nome</th>
			<th>CPF</th>
			<th>E-mail</th>
			<th>Unidades</th>
		</thead>
	<?php
	foreach ($proprietarios as $proprietario){
	?>
	
		<tr>
			<td><?php echo $proprietario['nome'];?></td>
			<td><?php echo $proprietario['cpf'];?></td>
			<td><?php echo mailto($proprietario['email'],$proprietario['email']);?></td>
			<td>
				<?php 
				
				if (!empty($unidades_proprietarios) && !empty($unidades_proprietarios[$proprietario['id_proprietario']])){
					foreach($unidades_proprietarios[$proprietario['id_proprietario']] as $propriedade){
						if ($propriedade['dono']==1)
							echo $propriedade['bloco']." - ". $propriedade['unidade']."<br>";
					}
				}else{
					echo "Sem unidades vinculadas";
				}
				?>
			</td>
		</tr>

<?php
	}
?>
	</table>
    
    </div>
</div>
<?php
}
	if(!empty($vagas)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Vagas localizadas</strong></div>
		
		<div class="panel-body">
			
				<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
					<thead>
						<th>Vaga</th>
						<th>Unidade</th>
						<th>Utilizador</th>
						<th>Tipo de Utilização</th>
					</thead>
<?php
		foreach ($vagas as $vaga) {
?>
					<tr>
						<td><?php echo $vaga['vaga'];?></td>
						<td><?php echo $vaga['bloco']." - ".$vaga['unidade'];?></td>
						<td><?php echo !empty($vaga_utilizacao[$vaga['id_vaga']])?$vaga_utilizacao[$vaga['id_vaga']]['bloco']." - ".$vaga_utilizacao[$vaga['id_vaga']]['unidade']:"Vaga disponível";?></td>
						<td><?php echo  !empty($vaga_utilizacao[$vaga['id_vaga']])?($vaga_utilizacao[$vaga['id_vaga']]['tipo']=="L"?"Locação":"Fixo"):"";?></td>
					</tr>
<?php	
		}
?>
				</table>
        	</div>
        </div>
<?php
	}
	
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
			</tr>
<?php
		}
?>
			</table>
		</div>
	</div>
<?php
	}
}else{
?>
	<p>Não foram localizados resultados para a sua busca !</p>
<?php
}
