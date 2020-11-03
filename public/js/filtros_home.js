

(function() {

    jQuery('#filtro-home img').on('click', function(e){

        jQuery(this).toggleClass('selected');
        
        var input_id =  this.dataset.input;
        var type_id  =  this.dataset.tipo;
        var input    =  document.getElementById(input_id);
        
        if(input){
            input.remove();
        }else{
            jQuery('#filtro-home').append('<input type="hidden" name="tipojob_id[]" value="' + type_id + '" id="' + input_id + '">');
        }

    });

})();
