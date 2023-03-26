const axios = require('axios');
const readline = require('readline');

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

async function getAllAffirmations() {
  try {
    const response = await axios.get('http://localhost:8000/api/affirmations?affirmations=1');
    const affirmations = response.data;
    console.log('All affirmations:');
    affirmations.forEach((affirmation) => console.log(`- ${affirmation}`));
  } catch (error) {
    console.error(error.response.data);
  }
}

async function getAllCategories() {
  try {
    const response = await axios.get('http://localhost:8000/api/affirmations?categories=1');
    const categories = response.data;
    console.log('All categories:');
    categories.forEach((category) => console.log(`- ${category}`));
  } catch (error) {
    console.error(error.response.data);
  }
}

async function addAffirmation() {
  rl.question('Enter an affirmation message: ', async (affirmation) => {
    rl.question('Enter a category: ', async (category) => {
      try {
        const response = await axios.post('http://localhost:8000/api/affirmations', {
          affirmation,
          category,
        });
        console.log(response.data.message);
      } catch (error) {
        console.error(error.response.data);
      } finally {
        start();
      }
    });
  });
}

async function start() {
  rl.question(
    'Enter a command (affirmations, categories, add, exit): ',
    async (command) => {
      switch (command) {
        case 'affirmations':
          await getAllAffirmations();
          start();
          break;
        case 'categories':
          await getAllCategories();
          start();
          break;
        case 'add':
          await addAffirmation();
          break;
        case 'exit':
          rl.close();
          break;
        default:
          console.error('Invalid command.');
          start();
          break;
      }
    }
  );
}

start();
