<?php

namespace NDB\QualityControl;

use NDB\QualityControl\FieldTypes\Image;

class UserRole implements iContext{
  public $post_type = null;
  public $process_order = 100;
  public function __construct(\WP_Role $role){
    $this->role = $role;
    $this->process_order = \NDB\QualityControl\Configuration::get_instance()->get("user_roles.{$this->role->name}.process_order", 100);
    $this->nb_posts = \NDB\QualityControl\Configuration::get_instance()->get("user_roles.{$this->role->name}.nb_posts", 5);
    $this->faker = \Faker\Factory::create();
  }

  public function get_name() : string{
    return 'User role ' . $this->role->name;
  }

  public function generate(){
    $parent = 0;
    $user_id = wp_insert_user(array(
      'role'=>$this->role->name,
      'first_name'=>$this->faker->firstname(),
      'last_name'=>$this->faker->lastname,
      'user_login'=>$this->faker->userName,
      'user_email'=>$this->faker->safeEmail,
      'description'=>$this->faker->paragraphs(2, true),
      'user_url'=>$this->faker->url,
      'user_pass'=>"password"
    ));
    update_user_meta($user_id, Generator::META_IDENTIFIER_KEY, '1');

    $this->fill_acf_fields($user_id);
    $this->fill_custom_fields($user_id);
  }

  protected function fill_acf_fields($user_id){
    $fieldgroups = acf_get_field_groups(array('user_id'=>$user_id));
    foreach($fieldgroups as $fieldgroup){
      $fields = acf_get_fields_by_id($fieldgroup['ID']);
      if(!empty($fields)){
        foreach($fields as $fieldData){
          $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\ACF($fieldData);
          $field = FieldFactory::create_field($fieldDefinition, $this);
          $field->direct_insert('user_' . $user_id);
        }
      }
    }
  }

  protected function fill_custom_fields($user_id){
    $fields = \NDB\QualityControl\Configuration::get_instance()->get("user_roles.{$this->role->name}.fields", array());
    if(!empty($fields)){
      foreach($fields as $fieldData){
        $fieldDefinition = new \NDB\QualityControl\FieldDefinitions\Custom($fieldData);
        $field = FieldFactory::create_field($fieldDefinition, $this);
        $field->custom_meta_insert($user_id);
      }
    }
  }

  public function insert_meta(int $id, $key, $value){
    update_user_meta($id, $key, $value);
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