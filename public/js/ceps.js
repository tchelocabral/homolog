/**
 * @CriaçãoCia
 * para funções com cep e preenchimento de campos
 *
 */

(function(){
    jQuery('#cep').on('blur', function (e) {
       pesquisacep(e.currentTarget.value);
    });

    /* Máscara Campo CEP */
    jQuery(".cep").mask('#####-###');
})();


function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('logradouro').value=("");
    document.getElementById('bairro').value=("");
    document.getElementById('cidade').value=("");
    document.getElementById('uf').value=("");
    // document.getElementById('ibge').value=("");
}

function cep_callback(conteudo) {
    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('logradouro').value=(conteudo.logradouro);
        document.getElementById('bairro').value=(conteudo.bairro);
        document.getElementById('cidade').value=(conteudo.localidade);
        document.getElementById('uf').value=(conteudo.uf);
        document.getElementById('numero').focus();
    }else {
        //CEP não Encontrado.
        limpa_formulário_cep();
        alert("CEP não encontrado.");
    }
}

function pesquisacep(valor) {

    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) {

            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('logradouro').value="...";
            document.getElementById('bairro').value="...";
            document.getElementById('cidade').value="...";
            document.getElementById('uf').value="...";
            // document.getElementById('ibge').value="...";

            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=cep_callback';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        }else {
            //cep é inválido.
            limpa_formulário_cep();
            $.confirm({
                icon: 'fa fa-warning',
                title: 'Whoops...',
                content: 'Formato de CEP inválido.',
                backgroundDismiss: true,
                closeIcon: true,
                type: 'blue',
                boxWidth: '30%',
                buttons: {
                    remover: {
                        text: 'Entendido!',
                        btnClass: 'btn-info',
                        action: function(){

                        }
                    }
                }
            });
            // alert("Formato de CEP inválido.");
        }
    } //end if.
    else {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
    }
};

function geo_cep(cep){

}