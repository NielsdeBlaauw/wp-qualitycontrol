<?php

require_once __DIR__ . '/../vendor/autoload.php';
WP_Mock::bootstrap();

if(!class_exists('\WP_CLI_Command')){
    class WP_CLI_Command{}
}