<?php
	$id_unidade = (!empty($unidade)?$unidade['id_unidade']:NULL);
		
?>
<script>
	var unidade = <?php print  (!empty($unidade)?1:0); ?>;
	var morador = false;
	$(function(){
		$("#unidade,#bloco").on("blur",function(){
			if ($("#unidade").val()!="" && $("#bloco").val()!=""){
				$.ajax({
					url:'<?php echo base_url('unidades/verifica_unidade');?>',
					type:'post',
					data:$.param({
						unidade :$("#unidade").val(),
						bloco: $("#bloco").val()
					}),
					success:function(r){
						
						if (r==true){
							
							$("#unidade,#bloco").css("border-color", "#ff0000");
							
						}else{
							
							unidade = true;
							$("#unidade,#bloco").css("border-color", "#3c763d");
							
						}
						
					}
					
				});
				
			}
		});
		$(".familiares").on("click",function(){
			var morador = $(this).attr('cod');
			$("#"+morador).toggle();
			$(this).toggleClass('btn-danger');
		});	
		$(".sem-aviso").on("click",function(){
		$(".modal").fadeIn('slow');
		$(".modal-title").text("Sem notificações :(");
		$(".modal-body").text("Não há notificações pendentes para esta unidade !");
	});
		
	});
	function validaUnidade () {
	  	var required = $("[required='required']");
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			if ($(required[i]).val() == "") {
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}
		if (req == 1 || unidade == false) {
			$("#flash").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
	
</script>
<?php
if (!empty($unidade) && $unidade['id_unidade']!=1000){
	$total_correspondencias = 0;
	if(!empty($correspondencias)){
		foreach($correspondencias as $correspondencia){
		$total_correspondencias+=$correspondencia['quantidade'];
		}
	}
	$notificacao = false;
	if($total_correspondencias>0 || !empty($visitantes) || !empty($prestadores) || !empty($ocorrencias) || !empty($reparos)) $notificacao = true;
?>

<!-- ADICIONE O class="com-aviso" QNDO TIVER AVISO -->
<div id="aviso-lateral" <?php echo ($notificacao)?"class='com-aviso'":"class='sem-aviso'";?> title="Você tem avisos pendêntes!"></div>

<div id="avisos-lateral" class="animacao-05s">
	<div class="conteudo">
		<div class="titulo">Notificações<div class="fechar cor-vermelho arredonda-15 fechar-lateral">X</div></div>				
		<div class="titulo">Correspondências </div>
		<?php
			if($total_correspondencias>0){
		?>
		<ul id="avisos-opcoes-lateral">
			<li>Você tem pendente a entrega de <strong class="arredonda-100 pointer" onclick="window.location.href='<?php echo base_url('correspondencias/'.$unidade['id_unidade']);?>'"><u><?php echo $total_correspondencias;?> correspondência(s)</u></strong>.</li>
		</ul>
		<br/>
		<?php
		}else{
		?>
		Sem corrêspondências para esta unidade. 
		<?php
		}
		?>
		<div class="titulo">Visitantes</div>
		<?php
		if(!empty($visitantes)){
		?>
		<ul id="avisos-opcoes-lateral">
			<?php
			foreach($visitantes as $visitante){
			?>
			<li>
				<strong class="arredonda-100 pointer" onclick="window.location.href='<?php echo base_url('visitantes');?>'"><?php echo $visitante['visitante']."(".$visitante['documento'].")";?></strong> entrou com mais <strong class="arredonda-100"><?php echo count($pessoas[$visitante['id_visitante']]);?> pessoa(s)</strong><br/>
				<small><?php echo date("H:i",strtotime($visitante['data']))."|".date("d/m/Y",strtotime($visitante['data']));?></small>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		Não passou ninguém por aqui hoje.
		<?php
		}
		?>
		<br/>
		<div class="titulo">Prestadores de Serviços</div>
		<?php
		if(!empty($prestadores)){
		?>
		<ul id="avisos-opcoes-lateral">
			<?php
			foreach($prestadores as $prestador){
			?>
			<li>
				<strong class="arredonda-100 pointer" onclick="window.location.href='<?php echo base_url('servicos/prestadores');?>'"><?php echo $prestador['nome']."(".$prestador['documento'].")";?></strong> da Empresa <strong><?php echo $prestador['empresa'];?></strong>está prestando um serviço de <strong class="arredonda-100"><?php echo $prestador['servico'];?></strong><br/>
				<small>desde às <?php echo date("H:i",strtotime($prestador['data_inicio']))."|".date("d/m/Y",strtotime($visitante['data']));?></small>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		Não passou ninguém por aqui hoje.
		<?php
		}
		?>
		<br/>
		<div class="titulo">Chaves</div>
		<?php
			if(!empty($chaves_reservadas) || !empty($chaves_retiradas)){
		?>
		<ul id="avisos-opcoes-lateral">
			<!-- reservas para hoje -->
			<?php
				if(!empty($chaves_reservadas)){
					foreach($chaves_reservadas as $reservada){
			?>
			<li>
				<?php echo ucfirst($reservada['morador']);?> reservou a chave da <strong class="arredonda-100 pointer" onclick="window.location.href='<?php echo base_url('areas/relatorio_utilizacao_areas');?>'"><?php echo $reservada['area'];?></strong><br/>
				<small>para <?php echo date("d/m/Y",strtotime($reservada['data_reserva']));?></small>
			</li>
			<?php
					}
				}
			?>
		<!-- retiradas hoje -->
			<?php
				if(!empty($chaves_retiradas)){
					foreach($chaves_retiradas as $retirada){
			?>
			<li>
				<?php echo ucfirst($retirada['morador']);?> retirou a chave da <strong class="arredonda-100 pointer" onclick="window.location.href='<?php echo base_url('areas/relatorio_utilizacao_areas');?>'"><?php echo $retirada['area'];?></strong><br/>
				<small><?php echo date("H:i",strtotime($retirada['data_retirada']))." | ".date("d/m/Y",strtotime($retirada['data_retirada']));?></small>
			</li>
			<?php
					}
				}
			?>
		</ul>
		<?php
			}else{
		?>
			Não há nada por aqui ...
		<?php
			}
		?>
		<br/>
		<div class="titulo">Ocorrências</div>
		<?php
		if(!empty($ocorrencias)){
		?>
		<ul id="avisos-opcoes-lateral">
			<?php
			foreach($ocorrencias as $ocorrencia){
			?>
			<li>
				<?php echo ucwords($ocorrencia['usuario']);?> solicitou com teor <strong class="arredonda-100"><?php echo $ocorrencia['nivel'];?></strong> a ocorrência <strong class="arredonda-100 pointer" onclick='window.location.href="<?php echo base_url('ocorrencias');?>"'><?php echo $ocorrencia['titulo'];?></strong><br/>
				<small><?php echo date("d/m/Y",strtotime($ocorrencia['data_ocorrencia']));?></small>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		Sem ocorrências por aqui...
		<?php
		}
		?>
		<br/>
		
		<div class="titulo">Solicitações de Reparos</div>
		<?php
		if(!empty($reparos)){
		?>
		<ul id="avisos-opcoes-lateral">
			<?php
			foreach($reparos as $reparo){
			?>
			<li>
				<?php echo ucwords($reparo['usuario']);?> solicitou com teor <strong class="arredonda-100"><?php echo $reparo['nivel'];?></strong> o reparo <strong class="arredonda-100 pointer" onclick='window.location.href="<?php echo base_url('reparos');?>"'><?php echo $reparo['titulo'];?></strong><br/>
				<small><?php echo date("d/m/Y",strtotime($reparo['data']));?></small>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		Sem solicitações de reparos por aqui...
		<?php
		}
		?>
		<br/><br/>
	</div>
</div>

<!-- INICIO DE LISTAGEM DE MORADORES QUE ESTÃO MORANDO, OU MORARAM NESTA UNIDADE -->
<div class="col-md-8">
<div class="titulo-sessao uppercase">Moradores</div>
<?php
if (!empty($moradores)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Moradores cadastrados nesta unidade</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th>Nível</th>
			<th>Morador</th>
			<th>CPF</th>
			<th>E-mail</th>
			<th>Situação</th>
			<th></th>
		</thead>
<?php
$i = 0;
	foreach ($moradores as $morador){
			
?>
		<tr <?php echo !empty($morador['foto'])? "data-image='".base_url('uploads/moradores/'.$morador['foto'])."'":"";?>>
			<td><span style="font-size: 0.8em;"><?php echo $morador['tipo'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $morador['nome'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $morador['cpf'];?></span></td>
			<td><span style="font-size: 0.7em;"><?php echo mailto($morador['email'],$morador['email']);?></span></td>
			<td><span style="font-size: 0.8em;"><?php if($i==0 && $unidade['status']==1)  echo "Morador Atual"; else echo "Morou em: ".date("d/m/Y",strtotime($morador['data_moradia'])); ?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo (!empty($familiares[$morador['id_morador']])?"<button class='familiares btn btn-warning btn-xs editar' cod='".$morador['id_morador']."'>Familiares</button>":"");?></span></td>
		</tr>	
<?php
			if (!empty($familiares[$morador['id_morador']])){
			
?>
			<tr id='<?php echo $morador['id_morador'];?>' style='display: none;' >
				<td colspan='7'>
					<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover" style="font-size: 0.9em;">
						<tr>
							<th>Parentesco</th>
							<th>Parentesco</th>
							<th>Nome</th>
							<th>CPF</th>
						</tr>
<?php
				foreach($familiares[$morador['id_morador']] as $familiar){
?>	
					<tr <?php echo !empty($familiar['foto'])? "data-image='".base_url('uploads/familiares/'.$familiar['foto'])."'":"";?>>
						<td><?php echo $familiar['parentesco'];?></td>
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
			$i++;
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
			<div class="panel-heading"><strong>Moradores cadastrados nesta unidade</strong></div>
			<div class="panel-body">
				<p>Não há moradores nesta unidade !</p>
			</div>
		</div>
	</div>
<?php
	}
?>
</div>
<!-- FIM DE LISTAGEM DE MORADORES QUE ESTÃO MORANDO, OU MORARAM NESTA UNIDADE -->
<!-- INICIO DE LISTAGEM DE VEÍCULOS . -->
<div class="col-md-4">
<div class="titulo-sessao uppercase">Veículos</div>
<?php
	if (!empty($veiculos)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Veículos cadastrados nesta unidade</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" class="table table-striped table-hover" cellpadding="5">
		<thead>
			<th style="font-size: 0.8em;">Veículo</th>
			<th style="font-size: 0.8em;">Placa</th>
			<th style="font-size: 0.8em;">Modelo</th>
			<th style="font-size: 0.8em;">Cor</th>
		</thead>
<?php

		foreach ($veiculos as $veiculo){
			
?>
		<tr class='veiculo<?php echo $veiculo['id_veiculo'];?>'>
			<td><span style="font-size: 0.8em;"><?php echo $veiculo['tipo'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo ($veiculo['placa']!=NULL?strtoupper($veiculo['placa']):"");?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo ($veiculo['modelo']!=NULL?$veiculo['modelo']:"");?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $veiculo['cor'];?></span></td>
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
			<div class="panel-heading"><strong>Veículos cadastrados nesta unidade</strong></div>
			<div class="panel-body">
				<p>Não há veículos cadastrados para esta unidade!</p>
			</div>
		</div>
	</div>
<?php
	}
?>
</div>
<!-- FIM DE LISTAGEM DE VEÍCULOS-->
<!-- INICIO DE LISTAGEM DE BICICLETAS . -->
<div class="col-md-2">
<div class="titulo-sessao uppercase">Bicicletas</div>
<?php
	if (!empty($bicicletas)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Bicicletas</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" class="table table-striped table-hover" cellpadding="5">
		<thead>
			<th style="font-size: 0.6em;">Lacre</th>
			<th style="font-size: 0.6em;">Cor</th>
		</thead>
<?php

		foreach ($bicicletas as $bicicleta){
			
?>
		<tr class='veiculo<?php echo $veiculo['id_veiculo'];?>'>
			<td><span style="font-size: 0.6em;"><?php echo $bicicleta['lacre'];?></span></td>
			<td><span style="font-size: 0.6em;"><?php echo $bicicleta['cor'];?></span></td>
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
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Bicicletas</strong></div>
			<div class="panel-body">
				<p>Não há Bicicletas cadastradas para esta unidade!</p>
			</div>
		</div>
<?php
	}
?>
</div>
<!-- FIM DE LISTAGEM DE VEÍCULOS-->

<!-- INICIO DE LISTAGEM DE VISITANTES AUTORIZADOS . -->
<div class="col-md-12">
<div class="titulo-sessao uppercase">Visitantes Autorizados</div>
<?php	
	if(!empty($visitantes_autorizados)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Moradores cadastrados nesta unidade</strong></div>
		<div class="panel-body">

		<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th>Visitante</th>
			<th>RG</th>
			<th>CPF</th>
			<th>Endereço</th>
			<th>Observações</th>
		</thead>
	<?php
		foreach ($visitantes_autorizados as $visitante) {
	?>
		<tr <?php echo !empty($visitante['foto'])? "data-image='".base_url('uploads/autorizados/'.$visitante['foto'])."'":"";?>>
			<td><span style="font-size: 0.8em;"><?php echo $visitante['nome'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $visitante['rg'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $visitante['cpf'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $visitante['endereco'].", ".$visitante['numero'].(!empty($visitante['complemento'])?"(".$visitante['complemento'].")":"")." - ".$visitante['bairro']."<br>".$visitante['cidade']." - ".$visitante['estado']."<br>".$visitante['cep'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo !empty($visitante['observacoes'])?$visitante['observacoes']:NULL;?></span></td>
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
			<div class="panel-heading"><strong>Visitantes autorizados nesta unidade</strong></div>
			<div class="panel-body">
				<p>Não há Visitantes Autorizados para esta unidade!</p>
			</div>
		</div>
	</div>
<?php
	}
?>
</div><!-- FIM DA LISTAGEM DE VISITANTES AUTORIZADOS . -->
<!-- INICIO DE LISTAGEM DE EMPREGADOS . -->
<div class="col-md-12">
<div class="titulo-sessao uppercase">Empregados</div>
<?php
	if (!empty($empregados)){
?>
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Empregados desta unidade</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" class="table table-striped table-hover" cellpadding="5">
		<thead>
			<th>Empregado</th>
			<th>CPF</th>
			<th>Contato</th>
			<th>Dias</th>
		</thead>
<?php

		foreach ($empregados as $empregado){
			
?>
		<tr  <?php echo !empty($empregado['foto'])? "data-image='".base_url('uploads/empregados/'.$empregado['foto'])."'":"";?>>
			<td><span style="font-size: 0.8em;"><?php echo $empregado['nome'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $empregado['cpf'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $empregado['telefone'].(!empty($empregado['celular'])?" || ".$empregado['celular']:"");?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $empregado['dias'];?></span></td>
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
			<div class="panel-heading"><strong>Empregados desta unidade</strong></div>
			<div class="panel-body">
				<p>Não há Empregados para esta unidade!</p>
			</div>
		</div>
	</div>
<?php
	}
?>
</div>
<!-- FIM DE LISTAGEM DE EMPREGADOS-->

<!-- INICIO DE LISTAGEM DE VAGAS DA UNIDADE . -->
<div class="col-md-8">
<div class="titulo-sessao uppercase">Vagas</div>
<?php
	if (!empty($vagas_unidade)){
?>
<div class="col-md-6"  style="padding-left: 0px;">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Vagas vinculadas a esta unidade</strong></div>
		<div class="panel-body">
			<?php
			foreach ($vagas_unidade as $vaga_unidade){			
			?>
			<div class="inline-vagas arredonda-15">
				<?php echo $vaga_unidade['vaga'];?><span class="editar" onclick="window.location.href='<?php echo base_url("unidades/vaga_registro/".$vaga_unidade['id_vaga']);?>'" title="Editar"></span>
			</div>
			<?php
			}
			?>
    </div>
  </div>
</div>
<?php
	}else{
?>
	<div class="col-md-6"  style="padding-left: 0px;">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Vagas vinculados a esta unidade</strong></div>
			<div class="panel-body">
				<p>Não há vagas vinculadas a esta unidade</p>
			</div>
		</div>
	</div>
<?php
	}

	if (!empty($vagas_utilizadas)){
?>
<div class="col-md-6" style="padding: 0px;">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Vagas utilizadas por esta unidade</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" class="table table-striped table-hover" cellpadding="5">
		<thead>
			<th>Vaga</th>
			<th>Pertence à</th>
			<th>Tipo</th>
			<th>&nbsp;</th>
		</thead>
<?php

		foreach ($vagas_utilizadas as $vaga_utilizada){
			
?>
		<tr>
			<td><span style="font-size: 0.8em;"><?php echo $vaga_utilizada['vaga'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo $vaga_utilizada['bloco']." - ".$vaga_utilizada['unidade'];?></span></td>
			<td><span style="font-size: 0.8em;"><?php echo($vaga_utilizada['tipo']=="L"?"Locação/Empréstimo":"Fixo");?></span></td>
			<td><button class="btn btn-info" onclick="window.location.href='<?php echo base_url("unidades/vaga_registro/".$vaga_utilizada['id_vaga']);?>'">+</button></td>
		</tr>
<?php
		}
?>
	</table>
    </div>
  </div>
  </div>
<?php
	}else{
?>
	<div class="col-md-6" style="padding: 0px;">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Vagas</strong></div>
			<div class="panel-body">
				<p>Não há vagas sendo utilizadas por esta unidade</p>
			</div>
		</div>
	</div>
<?php
	}
?>
</div>
<!-- FIM DE LISTAGEM DE VAGAS DA UNIDADE-->


<?php
}else if($unidade['id_unidade']==1000){// fecha verificação de unidade
?>
<div class="col-md-12">
<div class="titulo-sessao uppercase">Vagas</div>
<?php
	if (!empty($vagas_unidade)){
?>
<div class="col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>Vagas do Condominio</strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" class="table table-striped table-hover" cellpadding="5">
		<thead>
			<th>Vaga</th>
			<th>&nbsp;</th>
		</thead>
<?php
		foreach ($vagas_unidade as $vaga_unidade){
?>
		<tr>
			<td><?php echo $vaga_unidade['vaga'];?></td>
			<td><button class="btn btn-info" onclick="window.location.href='<?php echo base_url("unidades/vaga_registro/".$vaga_unidade['id_vaga']);?>'">+</button></td>
		</tr>
<?php
		}
?>
	</table>
    </div>
  </div>
  </div>
<?php
	}else{
?>
	<div class="col-md-6" >
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Vagas vinculados a esta unidade</strong></div>
			<div class="panel-body">
				<p>Não há vagas vinculadas a esta unidade</p>
			</div>
		</div>
	</div>
<?php
	}
}
?>
</div>



