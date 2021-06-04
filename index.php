<?php
/**
 * Created by PhpStorm.
 * User: 91799
 * Date: 4/6/21
 * Time: 12:09 PM
 */

require_once 'class.ratelimit.redix.php';

$rl = new RateLimit();
$waitfor = $rl->getSleepTime($_SERVER['REMOTE_ADDR']);
if ($waitfor>0) {
    echo 'Rate limit exceeded, please try again';
    exit;
}

// Your API response
echo 'API response';




?>