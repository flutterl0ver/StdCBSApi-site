function StartSearching() {
    document.getElementById('form').style.display = 'none';
    document.getElementById('loader').style.display = 'block';
    document.getElementById('loaderText').style.display = 'block';
}

$(document).ready(function() {
    $.ajaxSetup({
        headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});
