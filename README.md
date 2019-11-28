# swApi
SwApi is proyect preapred for use some features from [https://swapi.co/](https://swapi.co/) handeled with a symfony backend
and with vue frontal. Besides have graphql endpoint.

## Live version
- **Backend**: [swApi-Backend](http://swapi-backend.vascoframework.es/people) *You can use **page** param by GET or POST protocol. **This endpoint have a cache of 5 minutes. The first search is slow, please wait.
- **Frontend**: [swApi-Fronted](http://swapi-front.vascoframework.es/) 
- **Graphql**: You can use graphql for get some results. *This endpoint have a cache. The first search is slow, please wait.

```
curl 'http://swapi-backend.vascoframework.es/' -H 'Content-Type: application/json' --data-binary '{"query":"{peopleList(page: 1) { character {id name homeworld{name} birthYear gender height hairColor species {name classification language} mass skinColor created} page totalCount} }"}'
```

## Docker
To establish all the develop environment a docker has been prepared. You need to install Docker and Docker Compose.

- [https://docs.docker.com/install/](https://docs.docker.com/install/)
- [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)

#### Ports
**Location:** ./docker/.env

The ports must be available in your local machine. If they are not availaible, change them here for a proper functioning.

```
# PORTS
WEB_PORT=8008
APP_FRONT_PORT=4545

# APPLICATION
SYMFONY_APP_PATH=../symfony
APP_FRONT_PATH=../front
```

### Building Containers
The proyect have 2 containers and they have to be build.
- **swa_backend**:  This container have **php** mainly. Its function is to manage the backend.
-  **swa_front**:  This container have **node** mainly. Its function is to manage the front application.

#### Start Command
**Location:** ./docker/
**You need to be in the same directory that docker-compose.yml file.**

```
# BACKGROUND MODE
sudo docker-compose up -d 
# LOGGING MODE
sudo docker-compose up
```
#### Stop Command
**You need to be in the same directory that docker-compose.yml file.**

```
sudo docker-compose down
```

### Working with Containers
At all times, you can access in the container by bash mode.

```
sudo ocker exec -it {container_name} bash
```

## Symfony - Backend
When the docker is up. You can access to it http://localhost:8008/people
- *You can use **page** param by GET or POST protocol.
- The first search is slow, please wait.
- The cache expired time is set by default 300 seconds.

### Change Cache Expired Time
- **Location:** ./symfony/config/swapi.yaml

```
# This file containts the swapi configuration data
serviceUrl: https://swapi.co
peopleService: /api/people
planetService: /api/planets
speciesService: /api/species
cacheTime: 300 #seconds
```

### Launching Phpunit Tests
- **Tests Location:** ./symfony/tests
- To launch phpunit test you must be on ./symfony and launch the next command:
```
./bin/phpunit 
```

## Vue - Fronted
When the docker is up. You can access to it http://localhost:4545
- The first search is slow, please wait.
- The cache expired time is set by the backend.

### Change backend enpoint (You only need to do this if you backend is not on http://localhost:8008/people
- **Location:** ./front/.env

```
VUE_APP_ENVIRONMENT=dev
VUE_APP_TITLE=SwApi
VUE_APP_BACKEND_HOST="http://127.0.0.1:8008/people"
```

## Graphql - Backend
When the docker is up. You can access to it http://localhost:8008/ by **POST** Protocol.
- The query have the param **page**.
- The first search is slow, please wait.
- The nexts searches with the same page will go very faster.

### Test with all the query params
```
curl 'http://127.0.0.1:8008' -H 'Content-Type: application/json' --data-binary '{"query":"{peopleList(page: 1) { character {id name homeworld{name} birthYear gender height hairColor species {name classification language} mass skinColor created} page totalCount} }"}'
```

### Query Schema
```
query {
  peopleList (page: 1) {
    character {
      id
      name
      homeworld {
        name
      }
      birthYear
      gender
      height
      hairColor
      mass
      skinColor
      species {
        name
        classification
        language
      }
      created
    }
    page
    totalCount
  }
}
```

