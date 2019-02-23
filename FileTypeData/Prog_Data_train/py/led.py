from flask import Flask, render_template
from flask.ext.responses import json_response, xml_response, auto_response
import RPi.GPIO as GPIO


app = Flask(__name__)


@app.route(“/toggle”, methods=[‘POST’])
def togle():
 GPIO.setmode(GPIO.BOARD) ## Use board pin numbering
 GPIO.setup(7, GPIO.OUT) ## Setup GPIO Pin 7 to OUT
 state = not GPIO.input(7)
 GPIO.output(7,state) ## Turn on GPIO pin 7
 
 return json_response({“status”: state}, status_code=201)


if __name__ == “__main__”:
 app.run(host=’0.0.0.0', port=80, debug=True)