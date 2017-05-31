<?php

if(defined('WP_CLI') && WP_CLI){
  \WP_CLI::add_command('acf-qc', '\NDB\ACFQC\Command');
}