<?php

namespace NDB\QualityControl;

use NDB\QualityControl\FieldTypes\Image;

class UserRole implements iContext{
  public $post_type = null;
  public $process_order = 100;
  public function __construct(\WP_Role $role, Generator $generator){
    $this->generator = $generator;
    $this->role = $role;
    $this->process_order = $generator->config->get("user_roles.{$this->role->name}.process_order", 100);
    $this->nb_posts = $generator->config->get("user_roles.{$this->role->name}.nb_posts", 5);
  }

  public function get_name() : string{
    return 'User role ' . $this->role->name;
  }

  public function generate(){
    $parent = 0;
    $user_id = wp_insert_user(array(
      'role'=>$this->role->name,
      'first_name'=>$this->generator->faker->firstname(),
      'last_name'=>$this->generator->faker->lastname,
      'user_login'=>$this->generator->faker->userName,
      'user_email'=>$this->generator->faker->safeEmail,
      'description'=>$this->generator->faker->paragraphs(2, true),
      'user_url'=>$this->generator->faker->url,

    ));
    update_user_meta($user_id, Generator::META_IDENTIFIER_KEY, '1');

    $this->fill_acf_fields($user_id);
  }

  protected function fill_acf_fields($user_id){
    $fieldgroups = acf_get_field_groups(array('user_id'=>$user_id));
    foreach($fieldgroups as $fieldgroup){
      $fields = acf_get_fields_by_id($fieldgroup['ID']);
      if(!empty($fields)){
        foreach($fields as $fieldData){
          $field = FieldFactory::create_field($fieldData, $this);
          $field->direct_insert('user_' . $user_id);
        }
      }
    }
  }

  public static function clean(){
    $users = get_users(array(
      'meta_key'=>Generator::META_IDENTIFIER_KEY,
      'meta_value'=>'1',
      'fields'=>'ids',
    ));
    $progress = \WP_CLI\Utils\make_progress_bar( 'Cleaning generated users', count($users) );
    foreach($users as $user){
      wp_delete_user($user);
      $progress->tick();
    }
    $progress->finish();
  }
}