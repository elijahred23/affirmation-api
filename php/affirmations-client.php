<?php
/*
The code is an example of a command-line PHP application that interacts with a remote API. The code implements a simple command-line interface that allows the user to interact with an affirmations API.

The application runs in a continuous loop that prompts the user to enter a command. The available commands are "affirmations", "categories", "add", and "exit". Depending on the user's input, the application makes requests to the affirmations API using the curl library.

If the user enters "affirmations", the application makes a GET request to the API to retrieve all affirmations, and then prints them to the console.

If the user enters "categories", the application makes a GET request to the API to retrieve all categories, and then prints them to the console.

If the user enters "add", the application prompts the user to enter an affirmation message and category, and then makes a POST request to the API to add the affirmation. The API response is then printed to the console.

If the user enters "exit", the loop is exited and the application terminates.

If the user enters an invalid command, an error message is printed to the console.

Overall, this code is a basic example of how to build a simple command-line PHP application that interacts with a remote API using the curl library.
*/
while (true) {
  // Print the prompt and wait for user input
  echo "Enter a command (affirmations, categories, add, exit): ";
  $input = trim(fgets(STDIN));

  if ($input === 'affirmations') {
    // Make a GET request for all affirmations
    $ch = curl_init('http://localhost:8000/api/affirmations?affirmations');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Print the response
    $affirmations = json_decode($response, true);
    foreach ($affirmations as $affirmation) {
      echo "- $affirmation\n";
    }
  } elseif ($input === 'categories') {
    // Make a GET request for all categories
    $ch = curl_init('http://localhost:8000/api/affirmations?categories');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Print the response
    $categories = json_decode($response, true);
    foreach ($categories as $category) {
      echo "- $category\n";
    }
  } elseif ($input === 'add') {
    // Prompt the user for the affirmation message and category
    echo "Enter an affirmation message: ";
    $affirmation = trim(fgets(STDIN));
    echo "Enter a category: ";
    $category = trim(fgets(STDIN));

    // Make a POST request to add the affirmation
    $ch = curl_init('http://localhost:8000/api/affirmations');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
      'affirmation' => $affirmation,
      'category' => $category
    )));
    $response = curl_exec($ch);
    curl_close($ch);

    // Print the response
    $result = json_decode($response, true);
    echo $result['message'] . "\n";
  } elseif ($input === 'exit') {
    // Exit the loop and terminate the script
    break;
  } else {
    // Invalid command, print an error message
    echo "Invalid command.\n";
  }
}
