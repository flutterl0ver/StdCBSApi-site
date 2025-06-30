function SwitchGender(id, gender) {
    document.getElementById(gender + id).classList.add('active');
    document.getElementById((gender === 'male' ? 'female' : 'male') + id).classList.remove('active');
    document.getElementById('gender' + id).value = gender;
}
