FROM python:3.7-alpine
RUN pip install Flask
RUN mkdir /simple-restapi
WORKDIR /simple-restapi

EXPOSE 8888

COPY ./restapi.py /simple-restapi
CMD FLASK_APP=/simple-restapi/restapi.py flask run --host=0.0.0.0 --port=8888
