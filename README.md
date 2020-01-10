PHP-SDK for DigitalDocs certified connection
=======================

Library for calling https://digitaldocs.com.mx/storage/servidor.xml WSDL.

Easy way to conect with Digital Docs WSDL implementing the security fields. Just install and call (Easy as DigitalDocs).

Requirements
============

* PHP >= 7.0.0
* SoapClient Extension

Installation
============
```bash
composer require ddocs/php-sdk
```

Usage
=====
```php
use DDocs\SOAP as DDSoap;

class Example {
    public function stamp(array $params) {
        $u = 'YOUR_USER';
        $k = 'Key_fe239e5985321499ae656b5139d11ce1';
        $conection = DDSoap::getConection($u, $k);
        die(
            $conection->do('stamp', $params)
            //or just
            ,\DDocs\SOAP::getConection($u, $k)->do('stamp', $params)
        );
    }
}
```

The 'do' method can fail, so we recomend use a try-catch block.
```php
try {
    \DDocs\SOAP::getConection($u, $k)->do('welcome', 'visitor');
    //"Hola : Bienvenido visitor a los servicios de timbrado DigitalDocs"
} catch (\Exception $e) {
    echo 'Cant conect:'.$e->getMessage();
}
```

Other methods
```php
$conection->getMethods();
//Array [
//  0 => "string welcome(string $user, string $pss, string $key, string $name)"
//]

$conection->getRequest();
//StdClass {
//  headers => string
//    POST /ws/webService HTTP/1.1
//    Host: digitaldocs.com.mx
//    Connection: Keep-Alive
//    User-Agent: PHPSoapClient
//    Content-Type: text/xml; charset=utf-8
//    SOAPAction: "welcome"
//    Content-Length: 412    
//
//  ,body => string
//      <?xml version="1.0" encoding="UTF-8"?>
//    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Body><user>96a7e87c38abde431c4eb64bcedac3b2c65b36b6921b41bab67215d702957bb8</user><pss>96a7e87c38abde431c4eb64bcedac3b2c65b36b6921b41bab67215d702957bb8</pss><key>fdd347050b63f31b8542d5b8ac1ec5e7c43568312f6f327a9140fd783ed8bd26</key><name>visitor</name></SOAP-ENV:Body></SOAP-ENV:Envelope>
//}

$conection->getResponse();
//StdClass {
//  headers => string
//      HTTP/1.1 200 OK
//    Date: Fri, 10 Jan 2020 06:07:59 GMT
//    Server: Apache
//    Content-Length: 265
//    Keep-Alive: timeout=5, max=100
//    Connection: Keep-Alive
//    Content-Type: text/xml; charset=utf-8
//
//  ,body => string
//      <?xml version="1.0" encoding="UTF-8"?>
//      <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Body><welcomeResponse>Hola : Bienvenido visitor a los servicios de timbrado DigitalDocs</welcomeResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>
//}
```


Credits
=======
* Jesus Alvarez