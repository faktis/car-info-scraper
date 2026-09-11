<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Search</title>
</head>
<body>

<form id="search-form">
    <div>
        <label for="make">Make</label>
        <input type="text" id="make" name="make">
    </div>

    <div>
        <label for="model-year">Model year</label>
        <input type="number" id="model-year" name="model_year">
    </div>

    <div>
        <label for="registration-number">Registration number</label>
        <input
            type="text"
            id="registration-number"
            name="registration_number"
        >
    </div>

    <button type="submit">Search</button>
</form>

<div id="results"></div>

<script src="js/app.js"></script>

</body>
</html>