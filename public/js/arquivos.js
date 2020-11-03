
/**
 * @CriaçãoCia
 * para funções específicas e inicializadores js
 * que usem arquivos e façam upload de arquivos
 */

function transformaFilePreview(){
    'use strict';
    // Clear event
    jQuery('.image-preview-clear').click(function () {
        // console.log(jQuery(this));
        var img_preview = jQuery(this).parents('.image-preview')[0];
        var preview_title = jQuery(img_preview).find(".image-preview-input-title")[0];
        var preview_clear = jQuery(img_preview).find('.image-preview-clear')[0];
        var preview_filename = jQuery(img_preview).find('.image-preview-filename')[0];
        var preview_input = jQuery(img_preview).find('.image-preview-input input:file')[0];


        //excluir imagem
        //jQuery(".bkg-img-avatar").css('background', 'url(/storage/images/user/avatar-default.png)');


       // var preview_submit = jQuery(img_preview).find('.image-preview-submit')[0];

        jQuery(img_preview).attr("data-content", "").popover('hide');
        jQuery(preview_filename).val("");
        jQuery(preview_clear).hide();
        jQuery(preview_input).val("");
        jQuery(preview_title).text("Search");

        console.log(this);
        var current_file = jQuery(this).attr("id");

        if(current_file=="imagem-avatar-limpar")
        {
            jQuery(".bkg-img-avatar").css('background', 'url(/storage/images/user/avatar-default.png)');
        }

       


    });
    // Create the preview image
    jQuery(".image-preview-input input:file").change(function () {
        // console.log(jQuery(this));
        // var img_input        = jQuery(this);
        var img_preview = jQuery(this).parents('.image-preview')[0];
        var preview_title = jQuery(this).siblings(".image-preview-input-title")[0];
        var preview_clear = jQuery(this).parents('.image-preview').find('.image-preview-clear')[0];
        var preview_filename = jQuery(this).parents('.image-preview').find('.image-preview-filename')[0];

        //colocar imagem fundo
        //jQuery(".bkg-img-avatar").css('background', 'url(' + e.target.result + ')' );
        
        var file = this.files[0];
        var reader = new FileReader();

        var current_file = jQuery(this).attr("id");

        // Set preview image into the popover data-content
        reader.onload = function (e) {
            //console.log(jQuery(this));
            jQuery(preview_title).text("Change"); // Vai pegar todos irmãos com as classes correspondente.
            jQuery(preview_clear).show();
            //jQuery(preview_submit).show();
            if(current_file=="image-avatar"){
                jQuery(".bkg-img-avatar").css('background', 'url(' + e.target.result + ')' );
            }

            jQuery(preview_filename).val(file.name);
        };
        reader.readAsDataURL(file);
    });
}

(function() {

    transformaFilePreview();

})();