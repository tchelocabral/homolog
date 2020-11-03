
/**
 * @CriaçãoCia
 * para funções específicas e inicializadores js
 */

(function() {

    // Perfil
    // Clear event
    jQuery('.image-preview-clear').click(function(){
        jQuery('.image-preview').attr("data-content","").popover('hide');
        jQuery('.image-preview-filename').val("");
        jQuery('.image-preview-clear').hide();
        jQuery('.image-preview-input input:file').val("");
        jQuery(".bkg-img-avatar").css('background', 'url(/storage/images/user/avatar-default.png)');
        jQuery(".image-preview-input-title").text("Procurar");
    });
    // Create the preview image
    jQuery(".image-preview-input input:file").change(function (){
        var file = this.files[0];
        var reader = new FileReader();
        var input = this;
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            jQuery(".image-preview-input-title").text("Alterar");
            jQuery(".image-preview-clear").show();
            jQuery(".image-preview-filename").val(file.name);
            jQuery(".bkg-img-avatar").css('background', 'url(' + e.target.result + ')' );
        };
        reader.readAsDataURL(file);
    });
    /* File Input View */

    // if(jQuery("#"))

})();