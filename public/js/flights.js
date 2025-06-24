function GetFlightData(flightId) {
    return response['respond']['flightsGroup']['flightGroup'][flightId];
}

function GetFlightRequest(flightId, command) {
    let flightData = GetFlightData(flightId);
    let request = {
        "token": token,
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
    $.post('/get-flight-data',
        { 'data': JSON.stringify(request) },
        function(data)
        {
            console.log(data);
        },
        'json');
}

function SendSelectRequest(flightId) {
    document.getElementById('tableDiv').style.display = 'none'

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
