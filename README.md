# Fractalizer
Create and share fractals (L-Systems) with your friends !

## Sitemap
    /             Hot page
    /new.php      New fractals
    /connect.php        Log in / Sign in
    /fractalize.php     Create a fractal
    /user.php?id=%userid%          User page
    /fractal.php?id=%fractalid%    Fractal page

## Installation
Rename `db/db_info-example.php` to `db/db_info.php`
and fill it with your credentials.

## Database
```php
// Example of use

require_once("../db/db_connect.php");
require_once("../db/UsersManager.php");

$um = new UsersManager($db);
$users = $um->hydrate($um->find());

$uf = new FractalsManager($db);
$fractals = $uf->hydrate($uf->find());
```

## Documentation
Documentation for the php classes used by the database connection is located in the `doc` folder.

