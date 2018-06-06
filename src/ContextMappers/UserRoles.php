<?php

namespace NDB\QualityControl\ContextMappers;

class UserRoles{
    public function map() : array{
        $wp_roles = wp_roles();
        $contexts = array();
        foreach($wp_roles->role_objects as $role){
            $contexts[] = new \NDB\QualityControl\UserRole($role);
        }
        return $contexts;
    }
}