function SwitchContextData() {
    let contextData = document.getElementById('contextdata');

    if(contextOpened)
    {
        contextData.style.height = '0';
        contextData.style.padding = '0';
        contextData.style.borderWidth = '0';
    }
    else
    {
        contextData.style.height = 'calc-size(max-content, size)';
        contextData.style.padding = '20px';
        contextData.style.borderWidth = '1px';
    }

    contextOpened = !contextOpened;
}

function OpenJson(data) {
    const bg = document.getElementById('jsonPopupBackground');
    const json = document.getElementById('jsonText');

    bg.style.display = 'block';

    json.innerHTML = '';
    if(typeof data === 'string') json.innerHTML = '<t class="jsonMsg">' + data + '</t>';
    else json.appendChild(renderjson(data));

    bg.style.opacity = 1;
}

function CloseJson() {
    let bg = document.getElementById('jsonPopupBackground');

    bg.style.opacity = 0;

    setTimeout(() => {
        bg.style.display = 'none';
    }, 800);
}

function JsonClick(e) {
    if(e === null || e === undefined) return;
    if(!document.getElementById('jsonPopup').contains(e.target))
    {
        CloseJson();
    }
}

function ApplyJson(request) {
    let jsonRequest = document.getElementById('jsonRequest');
    jsonRequest.value = JSON.stringify(request);
}

$(document).ready(function() {
    $.ajaxSetup({
        headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});

let contextOpened = false;
renderjson.set_icons('> ', 'v ');
renderjson.set_show_to_level(4);
renderjson.set_collapse_msg((len) => {
    return '...';
});
