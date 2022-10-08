# Page Application
This application helps to import pages from csv file

## Functionalities
- `Import pages from csv file`

## Requirements
- PHP 8
- Symfony -version  6
- Docker

## Getting Started
1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` to startup application (the logs will be displayed in the current shell)
5. Run `docker compose down --remove-orphans` to stop the application (Docker containers).

## Usage
- Run `docker exec -it backend sh`
- Run `bin/console app:page:import-from-csv <file path>`
- Where file path can be absolute i.e /srv/app/tests/Resources/10pages.csv or relative i.e. tests/Resources/10pages.csv

## Note
- To import one million (1000000) dummy records
- A file has been provided which contains one million (1,000,000) page records
- Run `docker exec -it backend sh`
- Run `bin/console app:page:import-from-csv tests/Resources/1000000pages.csv`

## Running Test
- Run `docker exec -it backend sh`
- Run `bin/console doctrine:migrations:migrate`
- Run `bin/phpunit tests`
