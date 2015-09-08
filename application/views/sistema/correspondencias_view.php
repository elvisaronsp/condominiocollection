<style>
	.ui-autocomplete{
		z-index:9999;
	}
	label,select option{
		font-size: 0.8em;
	}
</style>

<script>
	$(function(){
		var moradores= new Array();
		$(".detalhes").on("click",function(){
			var correspondencia = $(this).attr('cod');
			window.location.href='<?php echo base_url('correspondencias/registro');?>/'+correspondencia;
		});
		$(".entregar").on("click",function(){
			var correspondencia = $(this).attr('cod');
			$("#"+correspondencia).dialog({
				width:500,
				title:"Entregar Correspondencia"
			});
		});
		
		$( ".morador" ).autocomplete({
	      minLength: 0,
	      source: "<?php echo base_url('correspondencias/busca_moradores/');?>",
	      focus: function( event, ui ) {
	        $( ".morador" ).val( ui.item.nome );
	        return false;
	      },
	      select: function( event, ui ) {
	        $( ".morador" ).val( ui.item.nome );
	        $( ".morador" ).attr("value",ui.item.nome );
	        return false;
	      }
	    })
	    .autocomplete( "instance" )._renderItem = function( ul, item ) {
	      return $( "<li>" )
	        .append( "<a>" + item.nome + "</a>" )
	        .appendTo( ul );
	    };
	    
	    $("#unidade").on("change",function(){
	    	unidade = $(this).val();
	    	$.ajax({
	    		url:'<?php echo base_url('moradores/moradorByUnidade');?>/',
					type:'post',
					data:$.param({
						unidade:unidade
					}),
					success:function(r){
						if(r!='null'){
							
							if(moradores && moradores.length>0)
								moradores.splice(0,moradores.length)
							data = $.parseJSON(r);
							$.each(data, function (key,value)
							{
								if(key =="nome" && (typeof(key)!="number" && typeof(value) != "object")){
							  		moradores.push(value);
							  	}else if(typeof(value) == "object"){	
							  		if(value!=null){	  	
									  	$.each(value, function (chave,valor)
										{
											
											if(chave=="nome"){
												moradores.push(valor);
											}
										});
									}
							  	}							  	 
							});
						}
					}		
	    	})
	    	
		});
		
		$("#destinatario" ).autocomplete({
	      source: moradores
	    })
	    .autocomplete( "instance" )._renderItem = function( ul, item ) {
	      return $( "<li>" )
	        .append( "<a>" + item.label + "</a>" )
	        .appendTo( ul );
	        
	    };
		   
		$("#bloco").on("change",function(){ // carregar unidades disponíveis
			if ($(this).val()!=""){
				$("select#unidade option:first").text('Carregando ...');
				$.ajax({
					url:'<?php echo base_url('unidades/getUnidadesByBloco');?>/',
					type:'post',
					data:$.param({
						bloco:$(this).val()
					}),
					success:function(r){
						if (r!='null'){
							var unidades = $.parseJSON(r);
							
							$("select#unidade option:first").text('Selecione');
							var options = "<option value=''>Selecione</option>";
							$.each( unidades, function( key, val ) {
								
								if (val.status == 1)
									options += "<option value='"+val.id_unidade+"'>"+val.unidade+"</option>";
								else
									options += "<option value='' >"+val.unidade+" - Unidade vazia</option>";
								$("#unidade").html(options);
							});
						}else{
							$("#unidade").html("");
							$("#unidade").append("<option value=''>Não há unidades disponíveis para esta Torre !</option>");
						}
					}
				});
				
			}
			$("#unidade").html("<option value=''></option>");
		});
		var i = 0;
		$("#atual").on("click",function(){
			
			if (i==0){
				$(".datahora").val('<?php echo date('d/m/Y H:i');?>');
				$(".datahora").prop('readonly','readonly');
				i =1;
			}else{
				$(".datahora").removeAttr('readonly');
				i = 0;
			}
		});
		$("#cadastrar").on("click",function(){
			$(this).toggleClass("btn-danger");
			$("#nova_correspondencia").slideToggle('slow');
		});	
		
	});
	function validaCorrespondencia() // validação do formulário de informações do proprietário
	{
	  	var required = $(".correspondencias>[required='required']").parent('form');
		var req = 0;
		for ( i = 0; i < required.length; i++) {
			
			if ($(required[i]).val() == "") {
				console.log(required[i]);
				$(required[i]).css("border-color", "#ff0000");
				req = 1;
			}
		}

		if (req == 1) {
			$(".modal").fadeIn('slow');
			$(".modal-title").text("Ops! Tivemos um problema ... ");
			$(".modal-body").text("Preencha todos os campos corretamente !");
			return false;
		}
	}
</script>
<p align="right"><button id='cadastrar' class="btn btn-success"><img src="<?php echo base_url("img/icone/email.png"); ?>" border="0" style="margin: -3px 0px 0px -3px;" /> Chegou nova correspondência!</button></p><br />
	<div class="panel panel-default" id="nova_correspondencia" style="display:none">
		<div class="panel-heading"><strong>Chegou nova Correspondência!</strong></div>
		<div class="panel-body">

			<form action="<?php echo base_url('correspondencias/correspondencias_submit/'); ?>" method="post" accept-charset="utf-8" class="correspondencias" onsubmit='return validaCorrespondencia();'>
			
					<div class="col-md-1">
			            <label for="bloco">Torre</label>
			            <select id="bloco" class="form-control input-sm" required='required' style="width:65px;">
			            	<option value="">Selecione</option>
			            	<option value="A">A</option>
			            	<option value="B">B</option>
			            	<option value="C">C</option>
			            </select>
			        </div>
			        
			
				<div class="col-md-1" style="width:10%;important!">
				
						<label for="unidade">Unidade</label>
							<select name="unidade" class="form-control input-sm" id="unidade" required='required'/>
								<option value=""></option>
							</select>
				</div>
			  <div class="col-md-2">
			
				  <label for="tipo">Tipo de Correspondência</label>
				  	<select class="form-control input-sm" name="tipo" required='required'>
				  		<option value=''>Selecione</option>
				  		<?php 
				  			foreach($tipos as $tipo){
				  		?>
				  			<option value='<?php echo $tipo['id_tipo'];?>'><?php echo $tipo['tipo'];?></option>
				  		<?php
				  			}
						?>
				  	</select>
			    </div>
			    
				<div class="col-md-3">
					<label for="destinatario">Destinatário</label><input type="text" class="form-control input-sm" name="destinatario" value="" id="destinatario" required='required'/>
				</div>
			  
			  <div class="col-md-1">
			 	 <label for="quantidade">Quantidade</label><input type="text" class="form-control input-sm" name="quantidade" value="" id="quantidade" maxlength="3" required='required' pattern="([0-99]|[0-9]|[0-9])" />
			  </div>
			  
			  <div class="col-md-2">
			 	 <label for="data">Data e Hora</label><input type="text" class="form-control input-sm datahora" name="data" value="" required='required'/>
			  </div>
			  <div class="col-md-1" style="margin-top:-31px;">
			  	<br>&nbsp;
				<div class="btn-group btn-sm" data-toggle="buttons" id="atual">
					<label class="btn btn-default" for="atual"><input type='checkbox' > Data e Hora atual </label>
				</div><br>&nbsp;
			  </div>
			  <div class="col-md-12">
			  	<label>Observações:</label><textarea name="observacoes" class="form-control " rows="2" cols="40" required='required'> </textarea>
			  </div>
			  <div class="col-md-12"><br>
				  <p>
				  	<button class="btn btn-success" type="submit" />Cadastrar</button>
				  </p>
			  </div>
			  
			</form>
		</div>
	</div>
<?php
if (!empty($correspondencias)){
?>

	<div class="panel panel-default">
		<div class="panel-heading"><strong>Correspondências a serem entregues<!--Todas Correspondências Cadastradas para hoje (<?php echo date("d/m/Y");?>)--></strong></div>
		<div class="panel-body">

	<table border="0" cellspacing="5" cellpadding="5" class="table table-striped table-hover">
		<thead>
			<th>Destinatário</th>
			<th>Tipo</th>
			<th>Quantidade</th>
			<th>Data</th>
			<th>&nbsp;</th>
		</thead>
<?php
	foreach ($correspondencias as $correspondencia){
?>
	
		<tr>
			<td><?php echo $correspondencia['destinatario'];?></td>
			<td><?php echo $correspondencia['tipo'];?></td>
			<td><?php echo $correspondencia['quantidade'];?></td>
			<td><?php echo date('d/m/Y H:i',strtotime($correspondencia['data']));?></td>
			<td>
				<button cod='<?php echo $correspondencia['id_correspondencia'];?>' class="btn btn-warning detalhes">Detalhes</button>
				<button cod='<?php echo $correspondencia['id_correspondencia'];?>' class="btn btn-success entregar">Entregue</button>
			</td>
		</tr>	
		<div id="<?php echo $correspondencia['id_correspondencia'];?>" style="display:none">
			<form action="<?php echo base_url('correspondencias/entregar/'.$correspondencia['id_correspondencia']);?>" method="post" accept-charset="utf-8">
			  
				<label for="morador">Entregue a </label>&nbsp;<input type="text" name="morador" value="" id="morador" class="morador"/>
				<button type='submit' class="btn btn-success">Finalizar</button>
			</form>
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
<div class="panel panel-default">
		<div class="panel-heading"><strong>Correspondências</strong></div>
		<div class="panel-body">
	Não chegou nada por aqui...
</div>
</div>
<?php
}
?>
