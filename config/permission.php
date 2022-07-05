<?php
return [
    'access' => [
        //category
        'list-category' => 'category_list',
        'add-category' => 'category_add',
        'edit-category' => 'category_edit',
        'delete-category' => 'category_delete',

        //category
        'list-product' => 'product_list',
        'add-product' => 'product_add',
        'edit-product' => 'product_edit',
        'delete-product' => 'product_delete',

        //role
        'role-list'=>'role_list',
        'role-add'=>'role_add',
        'role-edit'=>'role_edit',
        'role-delete'=>'role_delete',
    ],

    'table_module' => [
        'category',
        'product',
        'slide',
        'menu',
        'setting',
        'user',
        'role',
    ],


    'module_children' => [
        'list',
        'add',
        'edit',
        'delete'
    ],
];

?>
