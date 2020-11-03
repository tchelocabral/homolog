
/**
 * @CriaçãoCia
 * para funções específicas e inicializadores js
 * que usem imagens e uploads de imagens
 */

function transformaImageFileInput(){
    // Imagens
    $(document).on('click', '#close-preview', function () {
        console.log($(this));
        var popover = $(this).parents('.popover')[0];
        var image_preview = $(popover).siblings('.image-preview')[0];
        $(image_preview).popover('hide');
        // Hover befor close the preview
        $(image_preview).hover(
            function () {
                $(image_preview).popover('show');
            },
            function () {
                $(image_preview).popover('hide');
            }
        );
    });
    // Create the close button
    var closebtn = $('<button/>', {
        type: "button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class", "close pull-right");
    // Set the popover default content
    $('.image-preview').popover({
        trigger: 'manual',
        html: true,
        title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
        content: "Nenhuma imagem selecionada.",
        placement: 'bottom'
    });
    // Clear event
    $('.image-preview-clear').click(function () {
        console.log($(this));
        var img_preview = $(this).parents('.image-preview')[0];
        var preview_title = $(img_preview).find(".image-preview-input-title")[0];
        var preview_clear = $(img_preview).find('.image-preview-clear')[0];
        var preview_filename = $(img_preview).find('.image-preview-filename')[0];
        var preview_input = $(img_preview).find('.image-preview-input input:file')[0];

        $(img_preview).attr("data-content", "").popover('hide');
        $(preview_filename).val("");
        $(preview_clear).hide();
        $(preview_input).val("");
        $(preview_title).text("Procurar");
    });
    // Create the preview image
    $(".image-preview-input input:file").change(function () {
        // console.log($(this));
        // var img_input        = $(this);
        var img_preview = $(this).parents('.image-preview')[0];
        var preview_title = $(this).siblings(".image-preview-input-title")[0];
        var preview_clear = $(this).parents('.image-preview').find('.image-preview-clear')[0];
        var preview_filename = $(this).parents('.image-preview').find('.image-preview-filename')[0];
        var img = $('<img/>', {
            id: 'dynamic',
            width: 250,
            height: 200
        });
        var file = this.files[0];
        var url = file.name;
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            // console.log($(this));
            $(preview_title).text("Alterar"); // Vai pegar todos irmãos com as classes correspondente.
            $(preview_clear).show();
            // $(".image-preview-clear").show();
            $(preview_filename).val(file.name);
            // $(".image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            if (file && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
            {
                $(img_preview).attr("data-content", $(img)[0].outerHTML).popover("show");
            }
        }
        reader.readAsDataURL(file);
    });
}

(function() {

    transformaImageFileInput();

})();