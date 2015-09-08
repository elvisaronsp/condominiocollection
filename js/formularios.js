function part_I() {
	var emailfilter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	var required = $("[required='required']");
	var req = 0;
	for ( i = 0; i < required.length; i++) {
		if ($(required[i]).val() == "") {
			$(required[i]).css("border-color", "#ff0000");
			req = 1;
		}
	}

	var email = $("[type='email']");
	for ( i = 0; i < email.length; i++) {
		if (!emailfilter.test($(email[i]).val())) {
			$(email[i]).css("border-color", "#ff0000");
			req = 1;
		}
	}

	arquivo = $("[type='file']").val();

	if (arquivo != "") {
		tipo = arquivo.substr(arquivo.length - 4, arquivo.length);
		tipo = tipo.toLowerCase();
		console.log(tipo);
		if ((tipo != "jpeg") && (tipo != ".jpg") && (tipo != ".gif") && (tipo != ".bmp") && (tipo != ".png")) {
			$("#logo").css({
				"border-color" : "red"
			});

			$("#error").html("<br><small style='color:red;'>O arquivo deve ter um dos seguintes formatos: .JPG,.GIF,.PNG ou .BMP</small>");
			return false;
		}
	}
	if (req == 1) {
		alert('Preencha os campos obrigatórios demarcados pelo asterisco (*)');
		return false;
	}
}