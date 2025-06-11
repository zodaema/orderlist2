<?php
    if(!function_exists('css_request')) {
        function css_request($css)
        {
            global $domain;
            if (isset($css)) {
                for ($i = 1; $i <= count($css); $i++) {
                    if($css[$i] != ''){
                        echo '<link rel="stylesheet" href="' . $css[$i] . '">';
                    }
                }
            }
        }
    }

    if(!function_exists('jquery_request')) {
        function jquery_request($jq)
        {
            for ($i = 1; $i <= count($jq); $i++) {
                if($jq[$i] != ''){
                    echo '<script src="' .$jq[$i] . '"></script>';
                }
            }
        }
    }

    // if(!function_exists('create_js_html')) {
    //     function create_js_html($js_name_file, $global_jquery)
    //     {
    //         echo '<script src="' . $global_jquery["url"]  . $js_name_file . '"></script>';
    //     }
    // }
?>