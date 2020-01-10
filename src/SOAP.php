<?php 
namespace DDocs;

use SoapClient;
use SoapFault;
use Exception;

/**
 * @author Jesus Alvarez [ Architecture, Design Develop and Structure]
 * @since 09/01/2020
 * @version 1.0
 */
class SOAP {
	private static $salt = 'd70a5677f20dd1fce69041782b863710bcc44f67f31a6ff70f8e34891cb7de9971a6ddd98e64826f1a608c22f4abc0bb285c97022a9e55e5f66df6b812aa4080e26259d57b887519cb948f8f2d19e837803f30379d2060bea04ea8ec708e6c20e8fbd9f93d031de2d21b3062e6404d654bd582ac07b3dc630b5276f61084684f';
	private $aws, $wsdl, $usr, $pss, $key;

    /** Obtiene una conexion al servidor
	 * @param string $usr: Usuario (Puede ser el RFC) para conectar al WS.
	 * @param string $key: Llave para autenticar al WS.
	 * @param string|null $wsdl=null: Url del web service.
     * @return DDocs\SOAP
	 */
    public static function getConection(string $usr, string $key, $wsdl=null):DDocs\SOAP {
        return new self($usr, $key, $wsdl);
    }

    /** Constructor
	 * @param string $usr: Usuario (Puede ser el RFC) para conectar al WS.
	 * @param string $key: Llave para autenticar al WS.
	 * @param string|null $wsdl=null: Url del web service.
	 */
	private function __construct(string $usr, string $key, $wsdl=null) {
        return $this->update($usr, $usr, $key, $wsdl);
    }

    /** Actualiza parametros del objeto.
     * @param string $usr: Usuario (Puede ser el RFC) para conectar al WS.
     * @param string $pss: Contraseña para conectar al WS.
     * @param string $key: Llave para autenticar al WS.
     * @param string|null $wsdl=null: Url del web service.
	 * @return DDocs\SOAP
     */
	public function update(string $usr, string $pss, string $key, string $wsdl=null):DDocs\SOAP {
        $this->usr = hash('sha256', self::$salt.$usr);
        $this->pss = hash('sha256', self::$salt.$pss);
        $this->key = hash('sha256', self::$salt.$key);
        unset($usr);
        unset($pss);
        unset($key);

        $this->wsdl = is_null($wsdl)?'https://digitaldocs.com.mx/storage/client-services.wsdl':$wsdl;

        libxml_disable_entity_loader(false);
        try {
            $this->aws = new SoapClient($this->wsdl, [
                'trace'=>1
                ,'exceptions'=>1
                ,'encoding'=>'UTF-8'
                ,'verifypeer'=>false
                ,'verifyhost'=>false
                ,'cache_wsdl'=>WSDL_CACHE_NONE
                ,'stream_context'=>stream_context_create([
                    'http'=>[
                        'user_agent'=>'PHPSoapClient'
                    ]
                    ,'ssl'=>[
                        'verify_peer'=>false
                        ,'verify_peer_name'=>false
                        ,'allow_self_signed'=>true
                    ]
                ])
            ]);
        } catch(SoapFault $e) {
            throw new Exception('Can\'t connect to '.$this->wsdl.': '.$e->faultstring);
        }

        return $this;
    }

    /** Funciones disponibles en el WS.
	 * @return string[] Funciones disponibles en el WS.
	 */
    public function getMethods():array {
	    return $this->aws->__getFunctions();
    }
    /** Detalles sobre la ultima peticion realizada.
	 * @return \StdClass {'headers', 'body'}
	 */
	public function getRequest():\stdClass {
        return (Object)[
            'headers'=>$this->aws->__getLastRequestHeaders()
            ,'body'=>$this->aws->__getLastRequest()
        ];
    }
    /** Detalles sobre la ultima respuesta recibida.
	 * @return \StdClass {'headers', 'body'}
	 */
    public function getResponse():\stdClass {
        return (Object)[
            'headers'=>$this->aws->__getLastResponseHeaders()
            ,'body'=>$this->aws->__getLastResponse()
        ];
    }

    /** Llamada al metodo del WS.
	 * @param string $action Nombre del metodo a llamar.
	 * @param array $params Parametros a enviar.
	 * @return any
	 */
	public function do($action, $params) {
        try {
            return $this->aws->__soapCall($action, [$this->usr, $this->pss, $this->key, $params]);
        } catch (SoapFault $e) {
            throw new Exception($e->faultstring.'____ Using:'
                .print_r($this->getRequest(),1).PHP_EOL
                .print_r($this->getResponse(),1)
            );
        }
    }
}