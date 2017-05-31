<?php

namespace NDB\ACFQC;

/**
 * Makes the acf-qc wp-cli command available
 */
class Command extends \WP_CLI_Command{
  public function generate(array $args, array $args_assoc) : bool{
    \WP_CLI::info('Starting generation of WordPress objects.');
    return true;
  }
}