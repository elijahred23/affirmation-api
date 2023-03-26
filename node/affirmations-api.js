/*
This is a Node.js server that implements an API for handling affirmations.

First, it requires and initializes the necessary dependencies - Express, Body Parser for parsing incoming JSON requests and the File System module for reading and writing to a JSON file.

Then, it creates an instance of the Express application using express().

It adds a middleware to parse incoming request bodies as JSON using app.use(bodyParser.json()).

It defines two routes for handling GET and POST requests for affirmations.

For GET requests to '/api/affirmations', it reads the affirmations from the affirmations.json file using the fs module, and checks for the existence of the query parameters affirmations or categories. If affirmations is present, it returns all affirmations from all categories. If categories is present, it returns all categories. If neither is present, it returns a JSON response with an error message.

For POST requests to '/api/affirmations', it extracts the affirmation message and category from the request body, and checks if both are present. If not, it returns a JSON response with an error message. If both are present, it loads the existing affirmations from the affirmations.json file using the fs module, creates a new category if necessary, adds the affirmation to the appropriate category, and writes the updated affirmations to the affirmations.json file. It then returns a JSON response with a success message.

Finally, it starts the server listening on port 8000 using app.listen(8000, () => {...}).

Overall, this code defines a simple API for handling affirmations and persisting them to a JSON file.
*/
const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');

// create the affirmations.json file if it doesn't exist
if (!fs.existsSync('affirmations.json')) {
  fs.writeFileSync('affirmations.json', JSON.stringify({}));
}

// add dummy affirmation data to the JSON file
let affirmations = JSON.parse(fs.readFileSync('affirmations.json', 'utf8'));
affirmations['health'] = ['I am healthy', 'My body is strong'];
affirmations['success'] = ['I am successful', 'I achieve my goals'];
fs.writeFileSync('affirmations.json', JSON.stringify(affirmations));


const app = express();

// parse incoming request body as JSON
app.use(bodyParser.json());

// handle GET requests for all affirmations or all categories
app.get('/api/affirmations', (req, res) => {
    const affirmations = JSON.parse(fs.readFileSync('affirmations.json', 'utf8'));

    if (req.query.affirmations) {
        const allAffirmations = Object.values(affirmations).flat();
        res.json(allAffirmations);
    } else if (req.query.categories) {
        const categories = Object.keys(affirmations);
        res.json(categories);
    } else {
        res.status(400).json({ error: 'Query parameter required.' });
    }
});

// handle POST requests to add a new affirmation
app.post('/api/affirmations', (req, res) => {
    const affirmation = req.body.affirmation;
    const category = req.body.category;

    // make sure the required fields are present
    if (!affirmation || !category) {
        res.status(400).json({ error: 'Affirmation and category are required fields.' });
        return;
    }

    // load the existing affirmations
    let affirmations = {};
    try {
        affirmations = JSON.parse(fs.readFileSync('affirmations.json', 'utf8'));
    } catch (err) {
        // affirmations file doesn't exist yet, create an empty object
    }

    // add the affirmation to the appropriate category
    if (!affirmations[category]) {
        affirmations[category] = [];
    }
    affirmations[category].push(affirmation);

    // save the updated affirmations to the file
    fs.writeFileSync('affirmations.json', JSON.stringify(affirmations));

    res.json({ message: 'Affirmation added successfully.' });
});

// start the server
app.listen(8000, () => {
    console.log('Server listening on port 8000');
});
