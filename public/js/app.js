function startSearching() {
    document.getElementById('form').style.display = 'none';
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderText').style.display = 'block';
}

function switchDarkMode() {
    isDarkMode = !isDarkMode;
    if(isDarkMode)
    {
        document.getElementById('lightTheme').setAttribute('disabled', 'disabled');
        document.getElementById('darkTheme').removeAttribute('disabled');

        document.getElementById('darkmodeButton').className = 'lightmode';
    }
    else
    {
        document.getElementById('lightTheme').removeAttribute('disabled');
        document.getElementById('darkTheme').setAttribute('disabled', 'disabled');

        document.getElementById('darkmodeButton').className = 'darkmode';
    }
    setCookie('darkmode', isDarkMode, 30);
}

function getCookie(name) {
    let cookies = document.cookie.split(';');
    for(let i = 0; i < cookies.length; i++)
    {
        let cookie = cookies[i];
        if(cookie.startsWith(name))
        {
            return cookie.split('=')[1];
        }
    }
    return '';
}

function setCookie(name, value, expireTime) {
    let d = new Date();
    d.setTime(d.getTime() + (expireTime * 24 * 60 * 60 * 1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = name + "=" + value + "; " + expires + "; path=/";
}

$(document).ready(function() {
    $.ajaxSetup({
        headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});
