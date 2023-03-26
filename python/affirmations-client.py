import requests

# Define the base URL for the API
base_url = 'http://localhost:5000'

# Prompt the user for their username and password
username = input('Enter your username: ')
password = input('Enter your password: ')

# Get a JWT token from the server
response = requests.post(f'{base_url}/token', json={'username': username, 'password': password})

print(response.status_code, response.json());

if response.status_code == 200:
    token = response.json()['token']
    print('Token obtained successfully')
else:
    print('Error:', response.json()['error'])
    exit()

# Main loop
while True:
    # Print the available options
    print('\nAvailable options:')
    print('1. Add an affirmation to a category')
    print('2. Get affirmations in a category')
    print('3. Add a new category')
    print('4. Quit')

    # Prompt the user for their choice
    choice = input('Enter your choice: ')
    if choice == '1':
        # Add an affirmation to a category
        category = input('Enter the category: ')
        affirmation = input('Enter the affirmation: ')
        data = {'category': category, 'affirmation': affirmation}
        headers = {'Authorization': f'Bearer {token}'}
        response = requests.post(f'{base_url}/affirmations', json=data, headers=headers)
        if response.status_code == 200:
            print('Affirmation added successfully')
        else:
            print('Error:', response.json()['error'])
    elif choice == '2':
        # Get affirmations in a category
        category = input('Enter the category: ')
        headers = {'Authorization': f'Bearer {token}'}
        response = requests.get(f'{base_url}/affirmations/{category}', headers=headers)
        if response.status_code == 200:
            affirmations = response.json()['affirmations']
            if affirmations:
                print(f'Affirmations in {category}:')
                for a in affirmations:
                    print(f'- {a}')
            else:
                print(f'No affirmations in {category}')
        else:
            print('Error:', response.json()['error'])
    elif choice == '3':
        # Add a new category
        category = input('Enter the new category: ')
        data = {'category': category}
        headers = {'Authorization': f'Bearer {token}'}
        response = requests.post(f'{base_url}/category', json=data, headers=headers)
        if response.status_code == 200:
            print('Category added successfully')
        else:
            print('Error:', response.json()['error'])
    elif choice == '4':
        # Quit the program
        print('Goodbye!')
        break
    else:
        # Invalid choice
        print('Invalid choice')
