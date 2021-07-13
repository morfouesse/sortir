/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
import '../css/app.css';


if (document.getElementById('filter')) {

    // remove multiple attribute who is create in SearchForm
    document.getElementById("campuses").removeAttribute("multiple");
}

if(document.getElementById('filter2')){

    // remove multiple attribute who is create in form
    const location = document.getElementById("crud_activity_location");
    location.removeAttribute("multiple");
}


const $ = require('jquery');
global.$ = global.jQuery = $;
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input2').each(function () {
        $(this).on('blur', function () {
            if ($(this).val().trim() !== "") {
                $(this).addClass('has-val');
            } else {
                $(this).removeClass('has-val');
            }
        })
    })


    /*==================================================================
    [ Validate ]*/
    let name = $('.validate-input input[name="username"]');
    let password = $('.validate-input input[name="password"]');


    $('.validate-form').on('submit', function () {
        let check = true;

        if ($(name).val().trim() === '') {
            showValidate(name);
            check = false;
        }

        if ($(password).val().trim() === '') {
            showValidate(password);
            check = false;
        }


        return check;
    });


    $('.validate-form .input2').each(function () {
        $(this).focus(function () {
            hideValidate(this);
        });
    });

    function showValidate(input) {
        let thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        let thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }


})(jQuery);
