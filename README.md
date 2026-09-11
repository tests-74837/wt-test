To run the project, firstly create .env file:

``` 
cp .env.example .env
```

Install dependencies:

```
composer install
```

Run docker environment

``` 
./vendor/bin/sail up
```

Run migrations and seeds:

``` 
./vendor/bin/sail php artisan migrate
```

``` 
./vendor/bin/sail php artisan db:seed SuppliersSeeder
```

Run queue worker:

``` 
./vendor/bin/sail php artisan queue:work
```

Now you can access the API on http://localhost

Application uses Redis Lock mechanism to prevent concurrent creation of the reservation. Before a reservation is created, the offer is locked. The offer is updated, checked to ensure it meets the conditions, and then a reservation is created in transaction. Only after that is the offer unlocked. 
