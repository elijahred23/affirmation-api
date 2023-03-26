<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Affirmations</title>
</head>
<body>
  <h1>View Affirmations</h1>
  <button id="get-affirmations">Get All Affirmations</button>
  <button id="get-categories">Get All Categories</button>
  <div id="response"></div>
  <script>
  const getAffirmationsButton = document.getElementById('get-affirmations');
  const getCategoriesButton = document.getElementById('get-categories');
  const response = document.getElementById('response');

  getAffirmationsButton.addEventListener('click', () => {
    fetch('http://localhost:8000/api/affirmations?affirmations')
    .then(response => response.json())
    .then(data => {
      const affirmationsList = data.map(message => `<li>${message}</li>`).join('');
      response.innerHTML = `<ul>${affirmationsList}</ul>`;
    })
    .catch(error => {
      console.error(error);
      response.innerHTML = `<p>Something went wrong. Please try again later.</p>`;
    });
  });

  getCategoriesButton.addEventListener('click', () => {
    fetch('http://localhost:8000/api/affirmations?categories')
    .then(response => response.json())
    .then(data => {
     // Create a list of categories
const categoriesList = data.map(category => <li>${category}</li>).join('');
response.innerHTML = <ul>${categoriesList}</ul>;
})
.catch(error => {
console.error(error);
response.innerHTML = <p>Something went wrong. Please try again later.</p>;
});
});
</script>

</body>
</html>
