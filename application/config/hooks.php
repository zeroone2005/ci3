<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

//环境变量支持
$hook['pre_system'] = function () {
    $envFile = HOME . DIRECTORY_SEPARATOR . '.env';
    if (file_exists($envFile)) {
        $dotenv = Dotenv\Dotenv::createUnsafeImmutable(HOME);
        $dotenv->load();
    }
};

//继承模板支持
$hook['display_override'] = [
    'filepath' => 'hooks',
    'filename' => 'ViewCompiler.php',
    'class'    => 'ViewCompiler',
    'function' => 'compile'
];
