<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST');


// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  // Load the existing affirmations from the JSON file
  $affirmations = json_decode(file_get_contents('affirmations.json'), true);

  // Check if the request is for all affirmations
  if (isset($_GET['affirmations'])) {

    // Get all affirmations from all categories
    $allAffirmations = array();
    foreach ($affirmations['categories'] as $category => $affirmationList) {
      $allAffirmations = array_merge($allAffirmations, $affirmationList);
    }

    // Send the response
    http_response_code(200);
    echo json_encode($allAffirmations);

  } elseif (isset($_GET['categories'])) {

    // Get all categories
    $categories = array_keys($affirmations['categories']);

    // Send the response
    http_response_code(200);
    echo json_encode($categories);

  } else {

    // Send an error response if the query parameter is not provided
    http_response_code(400);
    echo json_encode(array('error' => 'Query parameter required.'));

  }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get the affirmation message and category from the request body
  $affirmation = $_POST['affirmation'] ?? null;
  $category = $_POST['category'] ?? null;

  // Make sure the affirmation and category are not empty
  if (!empty($affirmation) && !empty($category)) {

    // Load the existing affirmations from the JSON file
    $affirmations = json_decode(file_get_contents('affirmations.json'), true);

    // Check if the category already exists
    if (isset($affirmations['categories'][$category])) {

      // Add the affirmation to the existing category
      $affirmations['categories'][$category][] = $affirmation;

    } else {

      // Create a new category and add the affirmation
      $affirmations['categories'][$category] = [$affirmation];

    }

    // Save the updated affirmations to the JSON file
    file_put_contents('affirmations.json', json_encode($affirmations));

    // Send a success response
    http_response_code(200);
    echo json_encode(array('message' => 'Affirmation added successfully.'));

  } else {

    // Send an error response if the affirmation or category is empty
    http_response_code(400);
    echo json_encode(array('error' => 'Affirmation and category are required fields.'));

  }

} else {

  // Send an error response if the request method is not GET or POST
  http_response_code(405);
  echo json_encode(array('error' => 'Method not allowed.'));

}
