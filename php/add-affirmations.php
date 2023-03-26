<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Affirmation</title>
</head>

<body>
    <h1>Add Affirmation</h1>
    <form id="add-form">
        <label for="message">Affirmation Message:</label>
        <input type="text" name="message" id="message" required>
        <br>
        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required>
        <br>
        <button type="submit">Add Affirmation</button>
    </form>
    <div id="response"></div>
    <script>
        const form = document.getElementById('add-form');
        const response = document.getElementById('response');

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const message = document.getElementById('message').value;
            const category = document.getElementById('category').value;

            fetch('http://localhost:8000/affirmations', {
                    method: 'POST',
                    body: JSON.stringify({
                        message,
                        category
                    }),
                    mode: 'cors',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    response.innerHTML = `<p>${data.message}</p>`;
                })
                .catch(error => {
                    console.error(error);
                    response.innerHTML = `<p>Something went wrong. Please try again later.</p>`;
                });
        });
    </script>

</body>

</html>