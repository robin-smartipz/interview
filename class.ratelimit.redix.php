<?php
/**
 * Created by PhpStorm.
 * User: 91799
 * Date: 4/6/21
 * Time: 12:10 PM
 */

require_once __DIR__.'/vendor/autoload.php';
Predis\Autoloader::register();

class RateLimit {

    private $redis;
    const RATE_LIMIT_SECS = 5; // allow 1 request every x seconds

    public function __construct() {
        $this->redis = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'localhost', // or the server IP on which Redix is running
            'port'   => 6380
        ]);
    }

    /**
     * Returns the number of seconds to wait until the next time the IP is allowed
     * @param ip {String}
     */


    public function getSleepTime($ip) {

        $rate_api = 3;   // number of requests after X seconds

        $value_count = $this->redis->get("counter5".$ip);

        if(empty($value_count))
        {
            $value_count = 1;
            $this->redis->set("counter5".$ip, 1, self::RATE_LIMIT_SECS*1000 );  // expires after X seconds

            // error  but not expiring :-)
//            $now = time(); // current timestamp
//            $this->redis->expireAt("counter4".$ip, $now + 3);

        }else{

            $this->redis->incr("counter5".$ip); // 1
            $value_count = $this->redis->get("counter5".$ip);

            $arr = (array)$value_count;
            $arr_values = array_values($arr);
            $value_count = $arr_values[0];
        }

        if( $value_count > $rate_api ){
            return 1;
        }else{
            return 0;
        }

    }

}

?>