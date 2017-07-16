<?php

if(defined('WP_CLI') && WP_CLI){
  \WP_CLI::add_command('qualitycontrol', '\NDB\QualityControl\Command');
}