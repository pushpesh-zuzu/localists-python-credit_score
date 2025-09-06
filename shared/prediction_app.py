from flask import Flask, request, jsonify
from werkzeug.middleware.dispatcher import DispatcherMiddleware
from werkzeug.serving import run_simple
import joblib
import pandas as pd
import os

# Initialize Flask app
app = Flask(__name__)


# model paths
patio_services_model_path = './models/patio_services.pkl'
artificial_grass_model_path = './models/artificial_grass.pkl'
landscaping_model_path = './models/landscaping.pkl'
driveway_installation_model_path = './models/driveway_installation.pkl'
fence_and_gate_model_path = './models/fence_and_gate.pkl'



def get_prediction(data, model_path):
    """Preprocess input data for prediction."""
    #get complete model path
    model_path = os.path.join(os.path.dirname(__file__), model_path)
    # Convert input into a DataFrame
    input_encoded = pd.DataFrame([data])
    #load model
    model = joblib.load(open(model_path, 'rb'))
    # Align with training features
    missing_cols = [col for col in model.feature_names_ if col not in input_encoded.columns]
    for col in missing_cols:
        input_encoded[col] = 0
    input_encoded = input_encoded[model.feature_names_]
    # Get prediction
    prediction = model.predict(input_encoded)
    return prediction[0]


@app.route('/predict/patio_services', methods=['POST'])
def predict_patio_services():
    try:
        data = request.json
        prediction = get_prediction(data, patio_services_model_path)
        return jsonify({'success': True, 'prediction': prediction})
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)})

    
@app.route('/predict/artificial_grass', methods=['POST'])
def predict_artificial_grass():
    try:
        data = request.json
        prediction = get_prediction(data, artificial_grass_model_path)
        return jsonify({'success': True, 'prediction': prediction})
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)})
    

@app.route('/predict/landscaping', methods=['POST'])
def predict_landscaping():
    try:
        data = request.json
        prediction = get_prediction(data, landscaping_model_path)
        return jsonify({'success': True, 'prediction': prediction})
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)})


@app.route('/predict/driveway_installation', methods=['POST'])
def predict_driveway_installation():
    try:
        data = request.json
        prediction = get_prediction(data, driveway_installation_model_path)
        return jsonify({'success': True, 'prediction': prediction})
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)})
    

@app.route('/predict/fence_and_gate', methods=['POST'])
def predict_fence_and_gate():
    try:
        data = request.json
        prediction = get_prediction(data, fence_and_gate_model_path)
        return jsonify({'success': True, 'prediction': prediction})
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)})


@app.route('/', methods=['GET'])
def home():
    return jsonify({
        'message': 'Welcome to the Multi-Model Prediction API!',
        'routes': ['/predict/patio_services',
                   '/predict/artificial_grass',
                   '/predict/landscaping',
                   '/predict/driveway_installation',
                   '/predict/fence_and_gate'
                   ]
    })

if __name__ == '__main__':
    # Wrap Flask in DispatcherMiddleware so it works under subpath
    app_dispatch = DispatcherMiddleware(Flask('dummy_root'), {
        '/credit_score_prediction': app
    })
    run_simple('0.0.0.0', 7001, app_dispatch, use_reloader=False, use_debugger=False)
