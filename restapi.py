import socket
from flask import Flask, jsonify

app = Flask(__name__)
@app.route("/ping")
def ping():
    return jsonify({'message': 'pong'})

@app.route("/ip")
def getIP():
    ip = socket.gethostbyname(socket.gethostname())
    return jsonify({'ip': ip})
