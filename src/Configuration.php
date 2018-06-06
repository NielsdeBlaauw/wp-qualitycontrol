<?php

namespace NDB\QualityControl;

class Configuration{
    protected static $instance = null;

    protected function __construct(){}
    protected function __clone(){}
    protected function __wakeup(){}

    public static function get_instance(): \Noodlehaus\Config{
        if(is_null(self::$instance)){
            $pre_config = new \Noodlehaus\Config(array('?' . realpath(__DIR__ . '/../../../../qualitycontrol.dist.json')));
            $config_files = array_merge(
            array('?' . realpath(__DIR__ . '/../../../../qualitycontrol.dist.json')), 
            self::parse_config_files($pre_config->get('settings.files', array())),
            array('?' . realpath(__DIR__ . '/../../../../qualitycontrol.json'))
            );

            self::$instance = new \Noodlehaus\Config($config_files);
        }
        return self::$instance;
    }

    protected static function parse_config_files($paths){
        $real_paths = array();
        foreach($paths as $path){
            $real_path = realpath(__DIR__ . '/../../../../' . $path);
            if(empty($real_path)){
                \WP_CLI::warning('Config file not found: ' . $path);
            }else{
                $real_paths[] = $real_path;
            }
        }
        return $real_paths;
    }

}