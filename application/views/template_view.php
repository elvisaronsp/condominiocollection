<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="<?php echo base_url('js/jquery.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/jquery.mask.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/formularios.js');?>"></script>
		<title><?php echo $titulo;?></title>
		<script>
			jQuery(document).ready(function(){
			  $('#data').mask('11/11/1111');
			  $('#cep').mask('00000-000');
			  $('#telefone').mask('0000-0000');
			  $('#telefone').mask('(00) 0000-00000');
			  $('#cpf').mask('000.000.000-00', {reverse: true});
			  $('#valor').mask('0000,00', {reverse: true});
			  $("#cnpj").mask("99.999.999/9999-99");  
			});
		</script>
	</head>
	
	<body >
		<?php
		
			echo (!empty($error)?$error:NULL); // EXIBIR MENSAGEM DE ERRO 
			
			echo (!empty($messege)!=NULL?$messege:NULL); // EXIBIR MENSAGEM DE SUCESSO
			
			$this->load->view($view);//exibe a tela
		?>
	</body>
</html>

