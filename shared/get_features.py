from catboost import CatBoostRegressor
import joblib
from pathlib import Path


current_path = Path(__file__)
parent_dir = Path(__file__).parent
landscaping_model = parent_dir / "models" / "driveway_installation.pkl"

model = joblib.load(landscaping_model)

# Get feature names
feature_names = model.feature_names_
print(feature_names)