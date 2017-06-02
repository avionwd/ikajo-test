# Ikajo test application

To run this stack on your machine, you need at least:

- Operating System: Windows, Linux, or OS X
- [Docker Engine](https://docs.docker.com/installation/) >= 1.10.0
- [Docker Compose](https://docs.docker.com/compose/install/) >= 1.6.2

## Usage

Clone this repository:
```
git clone https://github.com/avionwd/ikajo-test.git 
```

Prepare environment variables for containers with following command inside project root:
```
cp variables.env.example variables.env
``` 

Build the containers with following command inside project root:
```
docker-compose build
```

To start application and run containers in the background, use following command inside project root:
```
docker-compose up -d
```

Open application in your favorite browser [http://127.0.0.1](http://127.0.0.1) 