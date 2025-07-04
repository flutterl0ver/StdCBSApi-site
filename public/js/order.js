function cancelBooking()
{
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderText').style.display = 'block';
    document.getElementById('table_div').style.display = 'none';
    document.getElementById('info').style.display = 'none';

    $.post('/get-order-data',
        { 'token': token, 'command': 'CANCELBOOKING' },
        function(response)
        {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('loaderText').style.display = 'none';

            if(response !== null && response['respond']['token'] !== '')
            {
                document.getElementById('deletedDiv').style.display = 'block';
                return;
            }
            console.log(response);

            document.getElementById('table_div').style.display = 'block';
            document.getElementById('info').style.display = 'block';
            document.getElementById('header').style.display = 'initial';
            document.getElementById('errorText').innerText = 'Что-то пошло не так.';
        },
        'json');
}
