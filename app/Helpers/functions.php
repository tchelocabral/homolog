<?php
//TODO:função para limpar campo de injection de codigo javascript
function clearFieldJsTag($value) {
        //dd($value);
        //busca com regex codigo scripts que esteja com as tags em carateres (Entity Name)  e limpa
        $tagsToStrip = array('@&lt;script[^>]*?&gt;.*?&lt;/script&gt;@si'); 
        $new_value = preg_replace($tagsToStrip, '', $value);

        
        $tagsToStrip = '&lt;script'; 
        $new_value = str_replace($tagsToStrip, '', $new_value);

        $tagsToStrip = '&lt;/script&gt;'; 
        $new_value = str_replace($tagsToStrip, '', $new_value);
        
        //busca com regex tags scripts e limpa 
        $tagsToStrip = array('@<script[^>]*?>.*?</script>@si'); 
        $new_value = preg_replace($tagsToStrip, '', $new_value);

        //dd($new_value);

        return $new_value;
}