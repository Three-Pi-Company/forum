/** config */
const VERSION = 0.3;
/** end config */

function deleteCookies() { 
    var allCookies = document.cookie.split(';'); 
    for (var i = 0; i < allCookies.length; i++){
        if(!(allCookies[i].includes('lang'))){
            document.cookie = allCookies[i] + "=;expires=" + new Date(0).toUTCString();
        } 
    }
}

function setCookies(name, value) {
    document.cookie = name + '=' + value + ';path=/;SameSite=Lax';
}

function readCookies(name) {
    var nameEQ = name + '=',
    allCookies = document.cookie.split(';'),
    i,
    cookie;
    for (i = 0; i < allCookies.length; i += 1) {
      cookie = allCookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}

var cookieVersion = readCookies('version');
if( VERSION != cookieVersion ){
    if(!(typeof cookieVersion === 'undefined' || cookieVersion === null)){
        deleteCookies();
        localStorage.clear();
        console.log ('New content. Clear cookies');
    }
    setCookies('version', VERSION);
}

console.log('info for dev: ' + document.cookie.split(';'));

document.addEventListener('DOMContentLoaded', ()=>{

    // var audio = new Audio('/sound/sound.mp3');
    // audio.volume = 0.02;
    // played = 'no';
    // if(sessionStorage.getItem("played") || sessionStorage.getItem("timePlayed")){
    //     played = sessionStorage.getItem("played");
    //     tillPlayed = sessionStorage.getItem("timePlayed");
    // }
    // document.querySelector('.sound').addEventListener('click', ()=>{
    //     if(played == 'no'){
    //         audio.play();
    //         played = 'yes';
    //         sessionStorage.setItem("played", 'yes');
    //     } else{
    //         audio.pause();
    //         audio.currentTime = 0;
    //         played = 'no';
    //         sessionStorage.setItem("played", 'no');
    //         sessionStorage.setItem("timePlayed", audio.currentTime);
            
    //     }
        
    // });

    // if(played == 'yes'){
    //     if(tillPlayed){audio.currentTime = tillPlayed;}
    //     audio.play();
    // }
    // function update(){
    //     sessionStorage.setItem("timePlayed", audio.currentTime);
    // }
    // if(played == 'yes'){setInterval(update, 1);}
    
    let multilang = {
        _data: null,
        _translate_elements: null,
        _current_language: 'en',

        getLang: function(new_language) {
            return multilang._current_language
        },

        setLang: function(language) {
            for (let i = 0; i < multilang._translate_elements.length; i++) {
                const key = multilang._translate_elements[i].getAttribute('data-translatekey')
                const attribute = multilang._translate_elements[i].getAttribute('data-translateattribute')

                try {
                    var translated_text = multilang._data.translations[key][language]
                }

                catch (error) {
                    console.error('multilang: Key', "'" + key + "'", 'is not in JSON file')
                }

                if (attribute) {
                    multilang._translate_elements[i].setAttribute(attribute, translated_text)
                }

                else {
                    multilang._translate_elements[i].innerHTML = translated_text
                }
            }

            document.documentElement.lang = language
            setCookies('lang',language)
            document.querySelector('.lang').style.background = "url(/img/" + language + ".png)";
            multilang._current_language = language
        },

        loadTranslations: function(filename) {
            let request = new XMLHttpRequest()

            request.overrideMimeType('application/json')
            request.open('GET', filename, true)
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == '200') {
                    multilang._data = JSON.parse(request.responseText)
                    multilang._translate_elements = document.querySelectorAll('[data-translatekey]') // Get all elements with a translate key
                    multilang._current_language =  readCookies('lang') || multilang._data.default_language
                    multilang.setLang(multilang._current_language)
                }
            }
            
            request.send(null)
        }
    }

    multilang.loadTranslations('/translations.json');

    document.querySelector('.lang').addEventListener('click', ()=>{
        if(multilang._current_language == 'en'){
            multilang.setLang('pl');
    
        }else if(multilang._current_language == 'pl'){
            multilang.setLang('en');
        } 
    });

});

