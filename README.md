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

Install required libraries with composer:
```
composer install
```
NOTE! Composer not included into this repo, it must be installed on you local machine. See [composer install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) instructions. 

Build the containers with following command inside project root:
```
docker-compose build
```

To start application and run containers in the background, use following command inside project root:
```
docker-compose up -d
```

Open application in your favorite browser [http://127.0.0.1](http://127.0.0.1) 