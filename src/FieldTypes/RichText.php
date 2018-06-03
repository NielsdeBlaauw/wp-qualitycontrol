<?php

namespace NDB\QualityControl\FieldTypes;

class RichText extends Base implements iFieldType{
  public function generate($post_id){
    $content = $this->faker->randomHtml(6,6);
    $document = new \DOMDocument();
    $document->loadHTML($content);
    $form = $document->getElementsByTagName('form');
    $form[0]->parentNode->removeChild($form[0]);
    $node = $document->getElementsByTagName('body');
    return $document->saveHTML($node[0]);
  }
}
