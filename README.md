PHP-SDK for DigitalDocs certified connection
=======================

Library for calling https://digitaldocs.com.mx/storage/servidor.xml WSDL.

Easy way to conect with Digital Docs WSDL implementing the security fields. Just install and call (Easy as DigitalDocs).

Requirements
============

* PHP >= 5.5.*
* SoapClient Extension

Installation
============
```bash
    composer require DigitalDocs-mx\php-sdk
```

Usage
=====
```php
use DD\SOAP as DDSoap;

class Example {
    public function stamp(array $params) {
        $u = 'YOUR_USER';
        $k = 'key_test';
        $conection = DDSoap::getConection($u, $k);
        die(
            $conection->do('stamp', $params)
            //or just
            ,\DD\SOAP::getConection($u, $k)->do('stamp', $params)
        );
        //
    }
}
```

Other methods
```php
$conection->getMethods(); // \DD\SOAP::getConection($u, $k)->getMethods();
//string

$conection->getRequest();
//StdClass {
//  headers => {
//      
//  }
//  ,body => {
//
//  }
//}

$conection->getResponse();
//StdClass {
//  headers => {
//      
//  }
//  ,body => {
//
//  }
//}
```

The do method can fail, so we recomend use a try-catch block.
```php
try {
    \DD\SOAP::getConection($u, $k)->do('stamp', $params)
} catch (\Exception $e) {
    echo 'Cant stamp your bill:'.$e->getMessage();
}
```


Credits
=======
* Jesus Alvarez