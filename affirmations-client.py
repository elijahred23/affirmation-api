import requests

data = {
    "category": "career",
    "affirmation": "I am a skilled and competent professional."
}
headers = {
    "Content-Type": "application/json",
    "Authorization": "Bearer <your_token_here>"
}

response = requests.post("http://localhost:5000/affirmations", json=data, headers=headers)
print(response.json())
