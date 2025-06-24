function GetFlightData(flightId) {
    return response['respond']['flightsGroup']['flightGroup'][flightId];
}

function GetFlightRequest(flightId, command) {
    let flightData = GetFlightData(flightId);
    let request = {
        "token": searchToken,
        "flightsGroup": {
            "flightGroup": [
                {
                    "token": flightData['token'],
                    "itineraries": {
                        "itinerary": []
                    }
                }
            ]
        },
        "command": command
    };

    for(let i = 0; i < flightData['itineraries']['itinerary'].length; i++)
    {
        let itinerary = flightData['itineraries']['itinerary'][i];
        let requestItinerary = {
            "token": itinerary['token'],
            "flights": {
                "flight": []
            }
        };
        for(let j = 0; j < itinerary['flights']['flight'].length; j++)
        {
            let flight = itinerary['flights']['flight'][j];
            requestItinerary['flights']['flight'].push({ "token": flight['token'] });
        }
        request['flightsGroup']['flightGroup'][0]["itineraries"]['itinerary'].push(requestItinerary);
    }

    return request;
}

function OpenFlightRequest(flightId, command) {
    const request = GetFlightRequest(flightId, command);
    $.post('/request/flight',
        { 'data': JSON.stringify(request) },
        function(data)
        {
            OpenJson(data);
        },
        'json')

    return 'Информация загружается...';
}

function GetSearchRequest(response) {
    let token = response['respond']['token'];

    $.post('/request/searchresult',
        { 'data': JSON.stringify({
                'token': token })
        },
        function(data) {
            OpenJson({ 'request': data, 'response': response });
        },
        'json');
    return 'Информация загружается...';
}

function SwitchMoreInfo(flightId) {
    let cell = document.getElementById('moreinfo' + flightId);
    if(cell.style.display === 'none')
    {
        cell.style.display = 'table-cell';
    }
    else
    {
        cell.style.display = 'none';
    }
}

function SendFlightRequest(flightId, command) {
    const request = GetFlightRequest(flightId, command);
    $.post('/request/flight',
        { 'data': JSON.stringify(request) },
        function(data)
        {
            OpenJson({ 'request':  data, 'response': '!#Информация загружается...'});
            $.post('/get-flight-data',
                { 'data': JSON.stringify(request) },
                function(response)
                {
                    OpenJson({ 'request': data, 'response': response})

                },
                'json');
        },
        'json')

    return 'Информация загружается...';
}

function SendSelectRequest(flightId) {
    document.getElementById('tableDiv').style.display = 'none';
    const jsonField = document.getElementById('selectResponse');
    jsonField.style.display = 'block';
    jsonField.innerHTML = '<t class="jsonMsg">Информация загружается...</t>';

    const request = GetFlightRequest(flightId, 'SELECTFLIGHT');
    $.post('/request/flight',
        { 'data': JSON.stringify(request) },
        function(data)
        {
            $.post('/get-flight-data',
                { 'data': JSON.stringify(request) },
                function(response)
                {
                    jsonField.innerHTML = '<button class="getreq" onclick="CloseSelectRequest()">Выбрать другой перелёт</button>';
                    jsonField.appendChild(renderjson({ 'request': data, 'response': response }));
                },
                'json');
        },
        'json')
}

function CloseSelectRequest() {
    document.getElementById('tableDiv').style.display = 'block';
    document.getElementById('selectResponse').style.display = 'none';
}
