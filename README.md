#OLIF Module: Tables

Módulo para aplicar filtros, paginación y ordenación a los listados


## Usage

```php
$dev->getControllerModule('tables', 'ControllerTables', 'tables');

...

$allowedFilters = array(
    "name" => "AC.NAME",
    "cif" => "AC.CIF_DNI",
    "country" => "CC.NAME_ES",
    "prov" => "CP.NAME_ES"
);
$dev->tables->setOpFilters($allowedFilters);
$dev->tables->setNameSpace('CLIENTS');

$dev->tables->setFilters($dev->req->getVar('sortField'), $dev->req->getVar('sortOrder'));
....

$dev->tables->setPagination($results);

```

## Authors

[Jose Luis Represa](https://github.com/josex2r)

[Alberto Vara](https://github.com/avara1986)

##License

Olif-module-tables is released under the [MIT License](http://opensource.org/licenses/MIT).
