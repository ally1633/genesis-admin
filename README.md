# Genesis package

This php package can be used in Laravel Applications to generate CRUD endpoints and create an Admin Dynamic Dashboard where data can be altered easily. 

##Requirements
To se the Genesis package your Laravel application will require:
*>PHP 7.1
*the file structure bellow
*the naming standards bellow


####File structure

yourapplication
    app
        Models
            Transformers
        DTO
        Controllers
        Services
    config
    routes
        api.php
    tests
        Unit
        Feature
        TestCase.php
    
####Naming standards
*All table names are plural
*All tables have a column called id
*All foreign keys follow the pattern fk_(name)_id

#Installation

!!CAUTION!!

If your application already utilizes home.blade.php, it will be overwritten by the package

add this to your composer.json:
```
"genesis": "dev-master",
```
add this to your config/app.php:
```
Genesis\GenesisServiceProvider::class,
```

publish config file:
```
php artisan vendor:publish --provider="Genesis\GenesisServiceProvider"
```

#Customization

To add/remove table from the Admin Dashboard just remove the object from the generators.php