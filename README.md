# Test technique

Ce dépot fournit un squelette vide d'application AngularJS et Symfony.

## Frontend

    $ cd frontend
    $ npm install gulp -g
    $ npm install
    $ gulp
    $ gulp serve

Browse to the `ttp://localhost:8080` URL.

## Backend

    $ cd backend
    $ composer install
    $ php app/console server:run command.
    
Browse to the `http://localhost:8000` URL.
