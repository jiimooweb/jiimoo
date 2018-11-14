<?php 

use EasyWeChat\Factory;

$config = [
    'app_id' => 'wx8d65b1fa98bbf7d1',
    'secret' => '492a72af7012e9b5d201176c855c6c7a',
    'token' => 'rdoorweb',
    'response_type' => 'array',
    //...
];

$app = Factory::officialAccount($config);

$response = $app->server->serve();

return $response;