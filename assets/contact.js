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

    console.log('test');

    navigator.geolocation.getCurrentPosition(position =>{
        $.get(`https://api.openweathermap.org/data/2.5/weather?lat=${position.coords.latitude}&lon=${position.coords.longitude}&appid=${config.api}&units=metric&lang=fr`).then((data)=>{
            console.log(data)
            $("#contact_ville").val(data.name)
            $("#contact_Meteo").val(data.weather[0].description)
        })
    });


});

