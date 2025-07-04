function SwitchGender(id, gender) {
    document.getElementById(gender + id).classList.add('active');
    document.getElementById((gender === 'male' ? 'female' : 'male') + id).classList.remove('active');
    document.getElementById('gender' + id).value = gender.toUpperCase();
}

function DisableEmail(id) {
    let input = document.getElementById('passenger_email' + id);
    input.value = '';
    input.classList.add('disabled');
}

function EnableEmail(id) {
    document.getElementById('passenger_email' + id).classList.remove('disabled');
    document.getElementById('email_refused' + id).checked = false;
    document.getElementById('email_absent' + id).checked = false;
}

function startBooking()
{
    document.getElementById('bigform').style.display = 'none';
    document.getElementById('table_div').style.display = 'none';
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderText').style.display = 'block';
    document.getElementById('header').style.display = 'none';
}
