$(document).ready(function(){
	//alert("a");
	$("#ativa-menu").click(function(){
		$("#menu-lateral").css({"left" : "0px"});
	});
	$(".fechar-lateral").click(function(){
		$("#menu-lateral").css({"left" : "-400px"});
		$("#avisos-lateral").css({"left" : "-400px"});
	});

	$("#aviso-lateral.com-aviso").click(function(){
		$("#avisos-lateral").css({"left" : "0px"});
	});	



	$("tr").hover(function(){
		var photo = $(this).attr("data-image");
		var thisX = $(this).offset().left;
		var thisY = $(this).offset().top - 20;

		if(photo != null){
			$(".imagem-usuario").show().css({"background-image" : "url("+photo+")", "top" : ""+thisY+"px", "border" : "solid 5px #FFF"});
		}
	}, function(){
		$(".imagem-usuario").css({"border" : "0px"}).hide();
	});
});