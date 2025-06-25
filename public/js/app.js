function StartSearching() {
    document.getElementById('form').style.display = 'none';
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderText').style.display = 'block';
}

function SwitchDarkMode() {
    isDarkMode = !isDarkMode;
    const el = document.documentElement;
    if(isDarkMode)
    {
        document.getElementById('darkmodeButton').className = 'lightmode';

        el.style.setProperty('--background-color', '#0C0805');
        el.style.setProperty('--body-color', 'black');
        el.style.setProperty('--alt-body-color', '#190F0A');
        el.style.setProperty('--button-color', '#FF8400');
        el.style.setProperty('--alt-button-color', '#D75BC3');
        el.style.setProperty('--button-hover-color', '#FFA13D');
        el.style.setProperty('--border-color', 'dimgrey');
        el.style.setProperty('--text-color', 'white');
        el.style.setProperty('--alt-text-color', 'black');

    }
    else
    {
        document.getElementById('darkmodeButton').className = 'darkmode';

        el.style.setProperty('--background-color', '#F5F8FA');
        el.style.setProperty('--body-color', 'white');
        el.style.setProperty('--alt-body-color', '#EFF4F9');
        el.style.setProperty('--button-color', 'dodgerblue');
        el.style.setProperty('--alt-button-color', '#28A745');
        el.style.setProperty('--button-hover-color', 'blue');
        el.style.setProperty('--border-color', 'lightgrey');
        el.style.setProperty('--text-color', 'black');
        el.style.setProperty('--alt-text-color', 'white');
    }
}

$(document).ready(function() {
    $.ajaxSetup({
        headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});

let isDarkMode = false;
