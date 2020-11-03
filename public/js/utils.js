/**
 * Funções úteis
 * Criado em: 18/06/20
 * Colocado no fullfreela 07/08/2020
 * Última atualização: 07/08/2020
 */

// Disponibiliza o foreach mais legítvel
var forEach = Array.prototype.forEach;


function getFormattedDate(data = null, padrao = "br") {
    var date = null;

    if(data) 
    {
        date = new Date(data);
    }
    else{

        date = new Date();
    }
    
    if(padrao = "br") {
        
        var day = date.getDate()+1;
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        var formatterDay;	
        if (day < 10) {
            formatterDay = '0'+ day;
        } else {
            formatterDay = day;
        }
            
        var formatterMonth;	
        if (month < 10) {
            formatterMonth = '0'+ month;
        } else {
            formatterMonth = month;
        }

        return formatterDay +'.'+ formatterMonth +'.'+ year;
    }
}

console.log(getFormattedDate());
