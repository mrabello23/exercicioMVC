$(function(){
	var base_url = $("#base_url").val();

	$("[data-toggle=\"tooltip\"]").tooltip();
	$('.cpf').mask('000.000.000-00');

	$('.datas, .datas2').datepicker({
		format: "dd/mm/yyyy",
		todayBtn: "linked",
		language: "pt-BR",
		daysOfWeekDisabled: "0,6",
		autoclose: true,
		todayHighlight: true
	});

	$('.selectpicker').selectpicker({
		actionsBox: true,
		deselectAllText: "Desmarcar Todos",
		selectAllText: "Marcar Todos",
		noneSelectedText: "Nada Selecionado",
		dropdownAlignRight: "auto"
	});

	$('.cpfCnpjDocumento').keydown(function(event) {
		var elemento = $(this);
		var dado = elemento.val().replace(/[^0-9]/gi, "");

		if (dado.length < 11) {
			elemento.mask('000.000.000-00');
		} else {
			elemento.mask('00.000.000/0000-00');
		}
	});

	// VALIDAÇÃO DADOS FORM
	$("form").submit(function(e){
		var valida = 0;

		$(".obrigatorio").each(function(i, el){
			if ( $(this).val() == "" ) {
				valida++;
			}
		});

		$("input[type='text']").each(function(i, el) {
			if ($(this).hasClass(".cpf")) {
				if ($(this).length < 14) {
					valida++;
				}
			}
		});

		if( valida > 0 ){
			$(".alert-form").fadeIn("slow").removeClass("hide");
			return false;
		}

		return true;
	});

	// PREENCHE O INPUT ESCONDIDO NO MODAL E MOSTRA MODAL DE CONFIRMAÇÃO DE EXCLUSÃO
	$(".excluir").click(function(e){
		e.preventDefault();

		var id = $(this).prop("rel");

		$("#codigo").val(id);
		$('#modal_confirmacao').modal();
	});

	// BOTAO DASHBOARD
	$(".btn-dashboard").click(function(e) {
		e.preventDefault();
		$("#status").val(this.rel);

		$("#formDashboard").submit();
	});
});

function log(message) {
    if (window.console) {
        window.console.log(message);
    }
}

var blockUI = new function() {
    var start = function (msg) {
        if (msg) {
            message(msg);
        } else {
            message('Carregando...');
        }

        $.blockUI({ message: $('#blockPanel') });
    };

    var message = function (msg) {
        $('#blockPanel').html(msg);
    };

    var stop = function() {
        $.unblockUI();
    };

    this.start = start;
    this.message = message;
    this.stop = stop;
};