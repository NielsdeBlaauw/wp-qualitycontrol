<?php

namespace NDB\QualityControl;

class ProgressBar{
    public function __construct($name, $amount){
        $this->progress = \NDB\QualityControl\Environment::$instance->make_progress_bar($name, $amount);
    }
    public function tick(){
        $this->progress->tick();
    }
    public function finish(){
        $this->progress->finish();
    }
}