/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';


$(document).ready(()=>{

    const config ={
        api:"f75178d4bdd3b197eb5df3c95d7d02ed",
        units:"metric",
        lang:'fr'
    }

    const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://google-news.p.rapidapi.com/v1/top_headlines?lang=fr",
        "method": "GET",
        "headers": {
            "x-rapidapi-key": "7f3796d181msh460c9d9b6c4f345p18a850jsneb544ee86af7",
            "x-rapidapi-host": "google-news.p.rapidapi.com"
        }
    };
    
    $.ajax(settings).done(function (response) {
        
        let articles=response.articles;
        let titles = new Array()

        articles.forEach(article => {
            titles.push(article.title)
        });

        titles.forEach(title=>{

            $('#contact_News').append(`<option class="w-1/2" value="${title}">${title}</option>`)
        })
    });

    navigator.geolocation.getCurrentPosition(position =>{
        $.get(`https://api.openweathermap.org/data/2.5/weather?lat=${position.coords.latitude}&lon=${position.coords.longitude}&appid=${config.api}&units=metric&lang=fr`).then((data)=>{
            
            $("#contact_ville").val(data.name)
            $("#contact_Meteo").val(data.weather[0].description)
        })
    });

    $('#localisation').click(()=>{
        navigator.geolocation.getCurrentPosition(position =>{
            $('#localisationError').empty()
            $.get(`https://api.openweathermap.org/data/2.5/weather?lat=${position.coords.latitude}&lon=${position.coords.longitude}&appid=${config.api}&units=metric&lang=fr`).then((data)=>{
                
                $("#contact_ville").val(data.name)
                $("#contact_Meteo").val(data.weather[0].description)
            })
           
        }),
            $('#localisationError').empty()
            $('#localisationError').append( 'veillez autoriser la geolocation sur votre navigateur');
    })

});

