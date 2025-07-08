function cancelBooking()
{
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderTextCancel').style.display = 'block';
    document.getElementById('table_div').style.display = 'none';
    document.getElementById('info').style.display = 'none';

    $.post('/get-order-data',
        { 'token': token, 'command': 'CANCELBOOKING' },
        function(response)
        {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('loaderTextCancel').style.display = 'none';
            let status = document.getElementById('status');
            document.getElementById('cancelBtn').remove();
            document.getElementById('payBtn').remove();

            if(response !== null && response['respond']['token'] !== '')
            {
                document.getElementById('deletedDiv').style.display = 'block';
                status.innerText = '(отменён)';
                status.classList.add('red');
                return;
            }
            console.log(response);

            document.getElementById('table_div').style.display = 'block';
            document.getElementById('info').style.display = 'block';
            document.getElementById('errorText').innerText = 'Что-то пошло не так.';
            status.innerText = '(ошибка)';
            status.classList.add('red');
        },
        'json');
}

function payDeposit()
{
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderTextPay').style.display = 'block';
    document.getElementById('table_div').style.display = 'none';
    document.getElementById('info').style.display = 'none';

    $.post('/get-order-data',
        { 'token': token, 'command': 'PAYDEPOSIT' },
        function(response)
        {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('loaderTextPay').style.display = 'none';
            document.getElementById('table_div').style.display = 'block';
            document.getElementById('info').style.display = 'block';
            let status = document.getElementById('status');
            document.getElementById('payBtn').remove();

            if(response !== null && response['respond']['messages']['message'].length === 0)
            {
                status.innerText = '(оплачен)';
                status.classList.add('green');
                return;
            }
            console.log(response);

            document.getElementById('cancelBtn').remove();
            document.getElementById('errorText').innerText = 'Что-то пошло не так.';
            status.innerText = '(ошибка)';
            status.classList.add('red');
        },
        'json');
}
