<?php
return [
    'access' => [
        // cource,
        'list-course' => 'course_list',
        'add-course' => 'course_add',
        'edit-course' => 'course_edit',
        'delete-course' => 'course_delete',

        // cource_category,
        'list-cource-category' => 'cource_category_list',
        'add-cource-category' => 'cource_category_add',
        'edit-cource-category' => 'cource_category_edit',
        'delete-cource-category' => 'cource_category_delete',
        
        // teacher,
        'list-teacher' => 'teacher_list',
        'add-teacher' => 'teacher_add',
        'edit-teacher' => 'teacher_edit',
        'delete-teacher' => 'teacher_delete',

        // student,
        'list-student' => 'student_list',
        'add-student' => 'student_add',
        'edit-student' => 'student_edit',
        'delete-student' => 'student_delete',

        // user,
        'list-user' => 'user_list',
        'add-user' => 'user_add',
        'edit-user' => 'user_edit',
        'delete-user' => 'user_delete',

        // role,
        'list-role' => 'role_list',
        'add-role' => 'role_add',
        'edit-role' => 'role_edit',
        'delete-role' => 'role_delete',

        // class,
        'list-class' => 'class_list',
        'add-class' => 'class_add',
        'edit-class' => 'class_edit',
        'delete-class' => 'class_delete',
        // 
        
         // dang_ky,
         'list-dang-ky' => 'dang_ky_list',
         'add-dang-ky' => 'dang_ky_add',
         'edit-dang-ky' => 'dang_ky_edit',
         'delete-dang-ky' => 'dang_ky_delete',
         // 

         // ca,
         'list-ca' => 'ca_list',
         'add-ca' => 'ca_add',
         'edit-ca' => 'ca_edit',
         'delete-ca' => 'ca_delete',
         // 

        // cơ sở trung tâm,
         'list-dia-diem' => 'dia_diem_list',
         'add-dia-diem' => 'dia_diem_add',
         'edit-dia-diem' => 'dia_diem_edit',
         'delete-dia-diem' => 'dia_diem_delete',
         // 


    ],

    'table_module' => [
        'course',
        'cource_category',
        'teacher',
        'student',
        'user',
        'role',
        'class',
        'dangKy',
        'ca',
        'dia_diem',
    ],

    'module_children' => [
        'list',
        'add',
        'edit',
        'delete'
    ],
];
