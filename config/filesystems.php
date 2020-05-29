<?php
// 需求：需要通过 header 参数修改访问的 PATH_ROOT
// 有关ACL：
// ACL 主要针对用户进行权限限制，与PATH_ROOT没有联系
// ACL 通过身份识别(用户ID)，可以限制某些特定目录，某些类型的文件，是否允许访问加以限制
// 解决办法一：
// 阅读 Alexusmai\LaravelFileManager 源码发现随处可见的 $request->input('path') 获取访问路径，依赖传参
// 此方式无法直接修改 input('path')的值，通过阅读laravel框架核心代码发现函数`data_set`可以满足需要
// 修改的地方比较多，并想到方法二。
// 解决办法二：
// 直接通过 header 参数修改 storage 对接路径，侵入性最小，问题想复杂了，走了弯路，不妨碍将来组件升级。
$FORCE_LFMROOT_PREFIX = '';
if (isset($_SERVER['HTTP_LFM_ROOT'])) { // 没有传递 header LFM_ROOT，则拒绝访问。
    $FORCE_LFMROOT_PREFIX = $_SERVER['HTTP_LFM_ROOT'];
} else {
    die('Forbidden');
}

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
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/'.$FORCE_LFMROOT_PREFIX),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'files' => [
            'driver' => 'local',
            'root' => storage_path('app/files/'.$FORCE_LFMROOT_PREFIX),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'files',
        ],

        'images' => [
            'driver' => 'local',
            'root' => storage_path('app/images/'.$FORCE_LFMROOT_PREFIX),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
