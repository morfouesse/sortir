/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';


    // remove multiple attribute who is create in SearchForm
    document.getElementById("campuses").removeAttribute("multiple");
    // change default value of form(text) to get a date
    document.getElementById("startDate").setAttribute("type","date");
    document.getElementById("lastDate").setAttribute("type","date");



