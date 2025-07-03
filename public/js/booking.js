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
