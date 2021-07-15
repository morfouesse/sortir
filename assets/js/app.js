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

if(document.getElementById('notification')){
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;

            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });
}


if(document.getElementById('download')) {
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Display file name when select file
        let fileInputs = document.querySelectorAll('.file.has-name')
        for (let fileInput of fileInputs) {
            let input = fileInput.querySelector('.file-input')
            let name = fileInput.querySelector('.file-name')
            input.addEventListener('change', () => {
                let files = input.files
                if (files.length === 0) {
                    name.innerText = 'No file selected'
                } else {
                    name.innerText = files[0].name
                }
            })
        }

        // 2. Remove file name when form reset
        let forms = document.getElementsByTagName('form')
        for (let form of forms) {
            form.addEventListener('reset', () => {
                console.log('a')
                let names = form.querySelectorAll('.file-name')
                for (let name of names) {
                    name.innerText = 'No file selected'
                }
            })
        }
    })
}

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
    const name = $('.validate-input input[name="username"]');
    const password = $('.validate-input input[name="password"]');


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

    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;

            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });

$(document).ready(function() {

    // Check for click events on the navbar burger icon
    $(".navbar-burger").click(function() {

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");

    });
});
