const form = document.getElementById('search-form');

form.addEventListener('submit', async function (event) {
    event.preventDefault();

    const make = document.getElementById('make').value;
    const modelYear = document.getElementById('model-year').value;
    const registrationNumber =
        document.getElementById('registration-number').value;

    console.log(make);
    console.log(modelYear);
    console.log(registrationNumber);
    const params = new URLSearchParams();

    if (make !== '') {
        params.append('make', make);
    }

    if (modelYear !== '') {
        params.append('model_year', modelYear);
    }

    if (registrationNumber !== '') {
        params.append('registration_number', registrationNumber);
    }

    console.log(params.toString());
    const response = await fetch(
        'search.php?' + params.toString()
    );

    if (!response.ok) {
        throw new Error('Search request failed');
    }
    
    const cars = await response.json();

    const results = document.getElementById('results');

    
    results.innerHTML = `
    <table>
        <thead>
            <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Registration</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    `;

    const tbody = results.querySelector('tbody');

    for (const car of cars) {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${car.make ?? ''}</td>
            <td>${car.model ?? ''}</td>
            <td>${car.model_year ?? ''}</td>
            <td>${car.registration_number ?? ''}</td>
        `;

        tbody.appendChild(row);
    }
});