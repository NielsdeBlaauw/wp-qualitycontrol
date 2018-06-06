<?php

namespace NDB\QualityControl;

class Generator{
  const META_IDENTIFIER_KEY = 'wp-qc-generated';

  public $contexts = array();

  public function __construct($contextMappers = array()){
    foreach($contextMappers as $contextmapper){
      $this->contexts = array_merge($this->contexts, $contextmapper->map());
    }
  }

  public function generate() : bool{
    $nb_posts_total = array_reduce($this->contexts, function($val, $item){
      return $val += $item->nb_posts;
    },  0);
    usort($this->contexts, function($a, $b){
      if ($a->process_order == $b->process_order) {
        return 0;
      }
      return ($a->process_order < $b->process_order) ? -1 : 1;
    });
    foreach($this->contexts as $context){
      $progress = new \NDB\QualityControl\ProgressBar("Creating items for {$context->get_name()}", $context->nb_posts);
      for($i = 0; $i < $context->nb_posts; $i += 1){
        $context->generate();
        $progress->tick();
      }
      $progress->finish();
    }
    return true;
  }
}