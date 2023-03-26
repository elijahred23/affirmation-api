from flask import Flask, request, jsonify
import jwt
import os
import json

app = Flask(__name__)

app.config['SECRET_KEY'] = 'mysecretkey'  # Replace with a secure secret key

# Create JSON file to store affirmations if it doesn't exist
if not os.path.exists('affirmations.json'):
    with open('affirmations.json', 'w') as f:
        json.dump({"general": [], "health": [], "wealth": [], "relationships": []}, f)

# Load affirmations from JSON file
with open('affirmations.json', 'r') as f:
    affirmations = json.load(f)

# Add affirmation to category (requires JWT token)
@app.route('/affirmations', methods=['POST'])
def add_affirmation():
    token = request.headers.get('Authorization')
    if not token:
        return jsonify({'error': 'No token provided'}), 401
    try:
        payload = jwt.decode(token, app.config['SECRET_KEY'], algorithms=['HS256'])
    except jwt.ExpiredSignatureError:
        return jsonify({'error': 'Token expired'}), 401
    except jwt.InvalidTokenError:
        return jsonify({'error': 'Invalid token'}), 401

    category = request.json['category']
    affirmation = request.json['affirmation']

    # Create category if it doesn't exist
    if category not in affirmations:
        affirmations[category] = []

    affirmations[category].append(affirmation)

    # Write affirmations to JSON file
    with open('affirmations.json', 'w') as f:
        json.dump(affirmations, f)

    return jsonify({'message': 'Affirmation added successfully'})

# Get affirmations in category (requires JWT token)
@app.route('/affirmations/<category>', methods=['GET'])
def get_affirmations(category):
    token = request.headers.get('Authorization')
    if not token:
        return jsonify({'error': 'No token provided'}), 401
    try:
        payload = jwt.decode(token, app.config['SECRET_KEY'], algorithms=['HS256'])
    except jwt.ExpiredSignatureError:
        return jsonify({'error': 'Token expired'}), 401
    except jwt.InvalidTokenError:
        return jsonify({'error': 'Invalid token'}), 401

    return jsonify({'affirmations': affirmations.get(category, [])})

# Generate JWT token (requires username and password)
@app.route('/login', methods=['POST'])
def login():
    username = request.json.get('username')
    password = request.json.get('password')

    # Replace with your own authentication logic
    if username == 'admin' and password == 'password':
        payload = {'username': username}
        token = jwt.encode(payload, app.config['SECRET_KEY'], algorithm='HS256')
        return jsonify({'token': token})
    else:
        return jsonify({'error': 'Invalid username or password'}), 401

# Get JWT token (requires username and password)
@app.route('/token', methods=['POST'])
def get_token():
    username = request.json.get('username')
    password = request.json.get('password')

    # Replace with your own authentication logic
    if username == 'admin' and password == 'password':
        payload = {'username': username}
        token = jwt.encode(payload, app.config['SECRET_KEY'], algorithm='HS256')
        return jsonify({'token': token})
    else:
        return jsonify({'error': 'Invalid username or password'}), 401
    
# Add category (requires JWT token)
@app.route('/category', methods=['POST'])
def add_category():
    token = request.headers.get('Authorization')
    if not token:
        return jsonify({'error': 'No token provided'}), 401
    try:
        payload = jwt.decode(token, app.config['SECRET_KEY'], algorithms=['HS256'])
    except jwt.ExpiredSignatureError:
        return jsonify({'error': 'Token expired'}), 401
    except jwt.InvalidTokenError:
        return jsonify({'error': 'Invalid token'}), 401

    category = request.json.get('category')
    if not category:
        return jsonify({'error': 'Category name not provided'}), 400

    if category in affirmations:
        return jsonify({'error': 'Category already exists'}), 400

    affirmations[category] = []

    # Write affirmations to JSON file
    with open('affirmations.json', 'w') as f:
        json.dump(affirmations, f)

    return jsonify({'message': 'Category added successfully'})

if __name__ == '__main__':
    app.run(debug=True)