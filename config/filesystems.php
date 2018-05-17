<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'qiniu' => [
            'driver'  => 'qiniu',
            'domains' => [
                'default'   => env('QINIU_DOMAIN', 'p8r2g6z46.bkt.clouddn.com'), 
                'https'     => '',         //你的HTTPS域名
                'custom'    => '', 
            ],
            'access_key'=> env('QINIU_ACCESS_KEY', 'UkqtQ4uH8hG0Ungulygbr6BwBbvrMhyz37T21zPx'),  //AccessKey
            'secret_key'=> env('QINIU_SECRET_KEY', 'a9p_5iiuGTFh4dBPhoK9mmuDoqwqcfMm72ajVOlz'),  //SecretKey
            'bucket'    => env('QINIU_BUCKET', 'rdoorweb'),  //Bucket名字
            'notify_url'=> '',  //持久化处理回调地址
        ],

    ],

];
