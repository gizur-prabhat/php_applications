<?php

require_once __DIR__ . '/NoSQLInterface.php';

require_once __DIR__ . '/../../../vendor/predis/predis/autoload.php';

class RedisNoSQLClass implements NoSQLInterface
{

    private $redis = null;

    function __construct($host = '127.0.0.1', $port = 6379)
    {
        Predis\Autoloader::register();
        $this->redis = new Predis\Client(array(
            'scheme' => 'tcp',
            'host' => $host,
            'port' => $port,
        ));
    }

    public function scan($table, $attributesToGet, $clientId)
    {
        $var = $this->redis->hgetall($table . ":" . $clientId);
        return $m = array_intersect_key($var, array_flip($attributesToGet));
    }
    
    public function create($table, $hashkey, $params) {
        
        $result = $this->redis->hmset($table . ':' . $hashkey, $params);
        
        if ($result) 
            return true;
        else
            return false;
    }
    
    public function get_item($table, $attributesToGet, $keyId, $keyValue)
    {
        $var = $this->redis->hgetall($table . ":" . $keyValue);
        return $m = array_intersect_key($var, array_flip($attributesToGet));
    }

}

?>