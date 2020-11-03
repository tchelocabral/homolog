/**
 * @CriaçãoCia
 * para funções específicas e inicializadores js
 */

(function(){

    var forms = document.querySelectorAll("form:not(.no-block)");
    forms.forEach(function(form) {
        form.addEventListener('submit', formSubmitted);
    });

    /* Inicializa iCheck */
    iCheckStart();

    /* Máscara CPF/CNPJ no memso campo */
    jQuery(document).on('keypress', '.cpf_cnpj', function (e) {
        mascaraCPF_CNPJ(e.currentTarget);
    });

    /* Validação CPF/CNPJ no memso campo */
    jQuery(document).on('blur', '.cpf_cnpj', function (e) {
        validarCPF_CNPJ(e.currentTarget);
    });

    /* Máscara Campo TEL */
    jQuery(".tel").on('keypress', function (e) {
        mascaraTel(e.currentTarget);
    });

    /* Máscara CNPJ */
    jQuery(".cnpj").mask("00.000.000/0000-00");
    /* Validação de CNPJ on blur */
    jQuery(".cnpj").on('blur', function (e) {
        !validarCNPJ(e.currentTarget)  ? jQuery('#' + e.currentTarget.id).css("border-color", "red") : jQuery('#' + e.currentTarget.id).css("border-color", "#ccc");
    });
    /* Máscara cpf */
    jQuery(".cpf").mask("000.000.000-00");
    /* Validação de cpf on blur */
    jQuery(".cpf").on('blur', function (e) {
        !validarCPF(e.currentTarget)  ? jQuery('#' + e.currentTarget.id).css("border-color", "red") : jQuery('#' + e.currentTarget.id).css("border-color", "#ccc");
    });

    /* Inicializa Datepicker */
    // jQuery('.datepicker').datepicker({
    //     autoclose: true,
    //     todayHighlight: true
    // });

    // Abas
    $('.nav-tabs li a').click(function (e) {
        e.preventDefault();
        // $('.nav-tabs li a').tab('show');
        $(table).tab('show');
    });

    // Tolltips
    $('[data-toggle="tooltip"]').tooltip();

    // Selects via select2
    $('select').select2();

    // Carregando em chamadas Ajax
    $(document).on({
        ajaxStart: function() { $("body").addClass("loading");    },
        ajaxStop: function() { $("body").removeClass("loading"); },
        submit: function() { $("body").addClass("loading"); },
    });

    // Carregamento de gráficos de progressos
    jQuery('.progresso').each(function (index, item){
        jQuery(item).css("width", jQuery(item).attr('aria-valuenow') + '%');
        jQuery(item).text(jQuery(item).attr('aria-valuenow') + '%');
    });


    $('.card-job:not(.card-job-freela)').click(function (e) {
        var rota = jQuery(this).attr("data-rota");
        window.location = rota;
    });

 
    $('.acao-ver-job').click(function (e) {
        var rota = jQuery(this).attr("data-rota");
        window.location = rota;
    });   



    //aplicação de filtro por coluna nas  tabelas datable da home ?
    $('.com-filtro-lista thead tr').each( function (i) {
        $(this).clone().insertBefore($(this));

        $(this).find("th").each( function (i) {
            $(this).addClass("sem-padding");
            var title = $(this).text();
            if(title != 'Progresso' && title != 'Ações' && title != '100%') {
                $(this).html( '<input type="text" class="form-control-width texto-preto form-control" placeholder="'+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    var idTable = $(this).closest('.com-filtro-lista').attr('id');

                    if (table[idTable].column(i).search() !== this.value ) {

                        table[idTable]
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
                // TODO: Verificar método 
                $(".job-concluir-lista").click(function() {
                    alert("teste botao");
                });

            }
            else 
            {
                if(title == '100%')
                {
                    $(this).html( '<button id="job_concluir" class="texto-preto botao-concluir-job" style="visibility:hidden">Concluir</button>' );
                }
                else {
                    $(this).html( '' );
                }
            }
        });
//        alert($(this).html());
    });
    var table = {};
    //aplicação de filtro nas tabelas datatable     
    $('.com-filtro-lista').each( function (i) {

        var idTable = $(this).closest('.com-filtro-lista').attr('id');
        //alert(idTable + " criação da tabela dinamica");
        $.fn.dataTable.moment('DD.MM.YYYY');
//        $.fn.dataTable.moment('DD.MM.YYYY');
        table[idTable] = $("#"+idTable).DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          'sProcessing': 'Processando...',
          'orderCellsTop': true,
          'fixedHeader': true
        });
    });



    jQuery("[data-toggle=collapse]").click(function(e){
        if(jQuery(this).hasClass('open')){
            jQuery(this).find('.accordion-marc i').css({
                '-webkit-transform': 'rotate(0deg)',
                '-ms-transform':     'rotate(0deg)',
                '-o-transform':      'rotate(0deg)',
                'transform':         'rotate(0deg)'
            });
            jQuery(this).removeClass('open');
        }else{
            jQuery(this).find('.accordion-marc i').css({
                '-webkit-transform': 'rotate(90deg)',
                '-ms-transform':     'rotate(90deg)',
                '-o-transform':      'rotate(90deg)',
                'transform':         'rotate(90deg)'
            });
            jQuery(this).addClass('open');
        }
    });

    // seta tabelas search
    setSearchTable();


    // 26/10/2020 - colocado jquery da classe .com-filtro e retirado da view home e lista de usuario
    $.fn.dataTable.moment('DD.MM.YYYY');
    $.fn.dataTable.moment('DD.MM.YYYY');
    //$.fn.dataTable.moment( 'dd/mm/yyyy');
    $('.com-filtro').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        'sProcessing': 'Processando...',
        // "initComplete": function () {
        //     var api = this.api();
        //     api.$("td").click( function (e) {
        //         console.log(e);
        //         api.search( this.innerHTML ).draw();
        //     } );

        // },

    });        
    
    $(".job-concluir-lista").on('click', function () {
        alert("teste");
    })
    

    $('[type="search"]').addClass("form-control");

    $(".dataTables_length select").addClass("custom-select custom-select-sm form-control form-control-sm");


})();

// iCheck
function iCheckStart(){
    jQuery('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });
    jQuery('input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });
    console.log('rodou icheck');
}
// fim iCheck

// Máscara CPF/CNPJ
function mascaraCPF_CNPJ(cpf_cnpj) {
    if(cpf_cnpj.value.length <= 14){
        $("#" + cpf_cnpj.id).mask("000.000.000-000");
    }else{
        $("#" + cpf_cnpj.id).mask("00.000.000/0000-00");
    }
}
//fim Máscara CPF/CNPJ

// Validar CPF/CNPJ mesmo campo
function validarCPF_CNPJ(cpf_cnpj) {
    if(cpf_cnpj.value.length <= 14) {
        !validarCPF(cpf_cnpj)  ? jQuery('#' + cpf_cnpj.id).css("border-color", "red") : jQuery('#' + cpf_cnpj.id).css("border-color", "#ccc");
    }else{
        !validarCNPJ(cpf_cnpj) ? jQuery('#' + cpf_cnpj.id).css("border-color", "red") : jQuery('#' + cpf_cnpj.id).css("border-color", "#ccc");
    }
}
// vim Validar CPF/CNPJ mesmo campo

// validar CPF
function validarCPF(elemento) {
    var cpf = elemento.value;
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf == '') {return false;}

    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11   ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999"){
        return false;
    }

    // Valida 1o digito
    var add = 0;
    for (i = 0; i < 9; i++){
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    var rev = 11 - (add % 11);
    if (rev == 10 || rev == 11){
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(9))){
        return false;
    }

    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i++){
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11){
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(10))){
        return false;
    }
    return true;
}
// fim do valida CPF

// validar CNPJ
function validarCNPJ(elemento) {

    var cnpj = elemento.value;
    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj == '') return false;

    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    var tamanho = cnpj.length - 2
    var numeros = cnpj.substring(0,tamanho);
    var digitos = cnpj.substring(tamanho);
    var soma    = 0;
    var pos     = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2){
            pos = 9;
        }
    }
    var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)){
        return false;
    }

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma    = 0;
    pos     = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2){
            pos = 9;
        }
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)){

        return false;
    }

    return true;
}
// fim do valida CNPJ

// Máscaras de Telefone com ou sem 9 dígitos
function mascaraTel(txtTel){
    if(txtTel.value.length > 14){
        $("#" + txtTel.id).mask("(00) 00000-0009");
    }else{
        $("#" + txtTel.id).mask("(00) 0000-00009");
    }
}
// Máscaras de Telefone com ou sem 9 dígitos

// ordenar reversamente array de campos personalizados pelo tipo
function ordenarCamposPersonalizadosReverse(a,b){
    if(a.tipo > b.tipo) return -1;
    if(a.tipo < b.tipo) return 1;
    return 0;
}
// Overlay no submit do form para evitar envio duplo
function formSubmitted(e) {
    var submitButtons = e.target.querySelectorAll("button[type=submit],input[type=submit]");
    submitButtons.forEach(function(submitButton) {
        if (submitButton.tagName === "INPUT") {
            submitButton.value = "Please wait...";
        } else {
            submitButton.innerHTML = '<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>';
        }
        submitButton.disabled = true;
    });

    var overlay = document.createElement("div");
    overlay.className = "submit-overlay";
    document.body.appendChild(overlay);
}

// Filtros em tabelas DataSet
function setSearchTable(){
     // DataTable
    $('.search-table').each(
        function(element, index){
            // quantas colunas tem nessa tabela ?
            // var total_cols = element.rows[0].cells.length;
            var total_cols = $(this).find("tr:first td").length;
            console.log(total_cols);
            // variável que representa última coluna
            var col_acoes = total_cols - 1;
            // array das colunas exportáveis
            var expo_cols = new Array();
            for(var i = 0; i < total_cols; i++){
                expo_cols.push(i);
                // expo_cols[] = i;
            }
            // console.log(expo_cols);
            $(this).DataTable({
                "language" : {
                    "url" : "/js/datatable-portuguese.json"
                },

                "lengthMenu":[
                    [20, 50, -1],
                    [20, 50, "Todos"]],

                "dom":  "lfBtrip",
                "buttons": [
                    {
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: expo_cols
                        }
                    },
                    {
                        extend: 'copy',
                        footer: false

                    },
                    {
                        extend: 'excel',
                        footer: false
                    }
                ],

                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": col_acoes,
                    }
                ]

                // "exportOptions": {
                //     columns: [0, 1, 2, 3, 4],
                // }


            });
        }
    );
    
}

//Deixa a primeira letra das strings em maiusculas
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1)
}
