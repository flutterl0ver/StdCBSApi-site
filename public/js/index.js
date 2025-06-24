function RecalculateRadioButtons() {
    const maxAdults = MAX_PEOPLE - children - infants;
    const maxChildren = MAX_PEOPLE - adults - infants;
    const maxInfants = MAX_PEOPLE - adults - children;

    for(let i = maxAdults + 1; i <= MAX_PEOPLE; i++)
    {
        document.getElementById('adults' + i).style.display = 'none';
    }
    for(let i = 1; i <= maxAdults; i++)
    {
        document.getElementById('adults' + i).style.display = 'inline-block';
    }

    for(let i = maxChildren + 1; i < MAX_PEOPLE; i++)
    {

        document.getElementById('children' + i).style.display = 'none';
    }
    for(let i = 0; i <= maxChildren; i++)
    {
        document.getElementById('children' + i).style.display = 'inline-block';
    }


    for(let i = maxInfants + 1; i < MAX_PEOPLE; i++)
    {
        document.getElementById('infants' + i).style.display = 'none';
    }
    for(let i = 0; i <= maxInfants; i++)
    {
        document.getElementById('infants' + i).style.display = 'inline-block';
    }
}

function SwapPlaces() {
    const inputTo = document.getElementById('from');
    const inputFrom = document.getElementById('to');

    const toValue = inputTo.value;
    inputTo.value = inputFrom.value;
    inputFrom.value = toValue;
}

function SwitchDateFrom() {
    let div = document.getElementById('date_from_div');
    let button = document.getElementById('date_from_button');

    if(div.style.display !== 'none')
    {
        div.style.display = 'none';
        button.style.display = 'inline-block';
        document.getElementById('hasDateFrom').value = 'false';
    }
    else
    {
        div.style.display = 'block';
        button.style.display = 'none';
        document.getElementById('hasDateFrom').value = 'true';
    }
}

function ChangeAdults(newValue) {
    document.getElementById('adults' + adults).classList.remove('active');
    document.getElementById('adults' + newValue).classList.add('active');

    adults = newValue;
    document.getElementById('adults').value = adults;

    RecalculateRadioButtons();
}

function ChangeChildren(newValue) {
    document.getElementById('children' + children).classList.remove('active');
    document.getElementById('children' + newValue).classList.add('active');

    children = newValue;
    document.getElementById('children').value = children;

    RecalculateRadioButtons();
}

function ChangeInfants(newValue) {
    document.getElementById('infants' + infants).classList.remove('active');
    document.getElementById('infants' + newValue).classList.add('active');

    infants = newValue;
    document.getElementById('infants').value = infants;

    RecalculateRadioButtons();
}

function SwitchAirports(id, display) {
    return;
    let airports = document.getElementById('airports_' + id);

    if(display !== 'block' || airports.childElementCount > 0)
        airports.style.display = display;
}

function SearchAirports(id) {
    let search = document.getElementById(id).value;
    $.ajax({
        url: '/search-airports',
        method: 'get',
        data: { term: search },
        success: function(data) {
            console.log(data);
        }
    });
}

let adults = 1;
let children = 0;
let infants = 0;
renderjson.set_show_to_level(5);
