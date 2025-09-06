@echo off

REM Set up virtual environment
print("setting up virtual environment")
python -m venv env
print("setting up virtual environment......... done")

REM Activate environment
print("activating environment")
call env\Scripts\activate.bat
print("environment activated")

REM Upgrade pip and install dependencies
print("Upgrading pip and install dependencies")
pip install --upgrade pip
print("installing requirements")
pip install -r ./requirements.txt
