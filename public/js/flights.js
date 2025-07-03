function SendSelectRequest(flightId) {
    document.getElementById('table_div').style.display = 'none';
    document.getElementById('selectLoader').style.display = 'block';
    document.getElementById('selectLoaderText').style.display = 'block';
    document.getElementById('header').style.display = 'none';

    const request = GetFlightRequest(flightId, 'SELECTFLIGHT');
    console.log(request);
    $.post('/select',
        { 'data': JSON.stringify(request) },
        function(response)
        {
            if(response !== null && response['respond']['token'] !== '')
            {
                window.location.replace('/booking?token=' + response['respond']['token']);
                return;
            }
            console.log(response);

            document.getElementById('selectLoader').style.display = 'none';
            document.getElementById('selectLoaderText').style.display = 'none';
            document.getElementById('table_div').style.display = 'block';
            document.getElementById('header').style.display = 'initial';
        },
        'json');
}
