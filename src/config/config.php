<?php

return [

    /*
    |--------------------------------------------------------------------------
    | The singular resource words that will not be pluralized
    | For Example: $ php artisan generate:resource admin.bar
    | The url will be /admin/bars and not /admins/bars
    |--------------------------------------------------------------------------
    */

    'reserve_words' => ['app', 'website', 'admin'],

    /*
    |--------------------------------------------------------------------------
    | The default keys and values for the settings of each type to be generated
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'modules_namespace'   => 'Modules\\',
        'modules_path'        => './modules/',
        'namespace'           => '',
        'path'                => './app/',
        'prefix'              => '',
        'postfix'             => '',
        'file_type'           => '.php',
        'dump_autoload'       => false,
        'directory_format'    => '',
        'directory_namespace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Types of files that can be generated
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'view' => [
            'path'                => './resources/views/',
            'file_type'           => '.blade.php',
            'directory_format'    => 'strtolower',
            'directory_namespace' => true
        ],
        'model'        => [
            'namespace'           => '\Models', 
            'path'                => './app/Models/'
        ],
        'controller'   => [
            'namespace'           => '\Http\Controllers',
            'path'                => './app/Http/Controllers/',
            'postfix'             => 'Controller',
            'directory_namespace' => true,
            'dump_autoload'       => false,
            'repository_contract' => false,
        ],
        'request' => [
            'namespace'           => '\Http\Requests',
            'path'                => './app/Http/Requests/',
            'postfix'             => 'Request',
            'directory_namespace' => true,
        ],
        'seeder' => ['path' => './database/seeders/', 'postfix' => 'TableSeeder'],
        'migration'    => ['path' => './database/migrations/'],
        'notification' => [
            'directory_namespace' => true,
            'namespace'           => '\Notifications',
            'path'                => './app/Notifications/'
        ],
        'event'        => [
            'directory_namespace' => true,
            'namespace'           => '\Events',
            'path'                => './app/Events/'
        ],
        'listener'     => [
            'directory_namespace' => true,
            'namespace'           => '\Listeners',
            'path'                => './app/Listeners/'
        ],
        'trait'        => [
            'directory_namespace' => true,
        ],
        'job'          => [
            'directory_namespace' => true,
            'namespace'           => '\Jobs',
            'path'                => './app/Jobs/'
        ],
        'console'      => [
            'directory_namespace' => true,
            'namespace'           => '\Console\Commands',
            'path'                => './app/Console/Commands/'
        ],
        'exception'      => [
            'directory_namespace' => true,
            'namespace'           => '\Exceptions',
            'path'                => './app/Exceptions/'
        ],
        'middleware'   => [
            'directory_namespace' => true,
            'namespace'           => '\Http\Middleware',
            'path'                => './app/Http/Middleware/'
        ],
        'repository'   => [
            'directory_namespace' => true,
            'postfix'             => 'Repository',
            'namespace'           => '\Repositories',
            'path'                => './app/Repositories/'
        ],
        'contract'     => [
            'directory_namespace' => true,
            'namespace'           => '\Contracts',
            'postfix'             => 'Repository',
            'path'                => './app/Contracts/',
        ],
        'factory'      => [
            'postfix' => 'Factory',
            'path'    => './database/factories/',
        ],
        'test'         => [
            'directory_namespace' => true,
            'namespace'           => '\Tests',
            'postfix'             => 'Test',
            'path'                => './tests/',
        ],
        'service-provider' => [
            'namespace' => '\Providers', 
            'path' => './app/Providers/',
            'postfix' => 'ServiceProvider',
        ],
        'request' => [
            'directory_namespace' => true,
            'namespace' => '\Http\Requests', 
            'path' => './app/Http/Requests/',
            'postfix' => 'Request',
        ],
        'js' => [
            'directory_namespace' => true,
            'directory_format'    => 'strtolower',
            'path'                => './resources/js/',
            'file_type'           => '.js',
        ],
        'policy' => [
            'namespace' => '\Policies', 
            'path' => './app/Policies/'
        ],
        'route' => [
            'path' => './routes/'
        ],
        'addroute' => [
            'path' => './routes/'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Views [stub_key | name of the file]
    |--------------------------------------------------------------------------
    */

    'resource_views' => [
        'view_index_black'  => 'index',
        'view_create_black' => 'create',
        'view_edit_black'   => 'edit',
        'view_show'         => 'show',
        // 'view_create_edit' => 'create_edit',
    ],

     /*
    |--------------------------------------------------------------------------
    | Resource JS [stub_key | name of the file]
    |--------------------------------------------------------------------------
    */

    'resource_js' => [
        'js_index'       => 'index',
        'js_create'      => 'create',
        'js_edit'        => 'edit',
        // 'view_show'        => 'show',
        // 'view_create_edit' => 'create_edit',
    ],

     /*
    |--------------------------------------------------------------------------
    | Custom Requests [stub_key | name of the file]
    |--------------------------------------------------------------------------
    */

    'custom_requests' => [
        // 'request_index'       => 'index',
        // 'request_create'      => 'create',
        'request_store'      => 'store',
        // 'request_show'        => 'show',
        // 'request_edit'        => 'edit',
        'request_update'        => 'update',
        // 'request_delete'        => 'destroy',
    ],

    /*
    |--------------------------------------------------------------------------
    | Where the stubs for the generators are stored
    |--------------------------------------------------------------------------
    */

    'stubs' => [
        'example'                => base_path() . '/vendor/on3n3o/generators/resources/stubs/example.stub',
        'model'                  => base_path() . '/vendor/on3n3o/generators/resources/stubs/model.stub',
        'model_plain'            => base_path() . '/vendor/on3n3o/generators/resources/stubs/model.plain.stub',
        'migration'              => base_path() . '/vendor/on3n3o/generators/resources/stubs/migration.stub',
        'migration_plain'        => base_path() . '/vendor/on3n3o/generators/resources/stubs/migration.plain.stub',
        'controller'             => base_path() . '/vendor/on3n3o/generators/resources/stubs/controller.stub',
        'controller_plain'       => base_path() . '/vendor/on3n3o/generators/resources/stubs/controller.plain.stub',
        'controller_admin'       => base_path() . '/vendor/on3n3o/generators/resources/stubs/controller_admin.stub',
        'controller_repository'  => base_path() . '/vendor/on3n3o/generators/resources/stubs/controller_repository.stub',
        'request'                => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.stub',
        'pivot'                  => base_path() . '/vendor/on3n3o/generators/resources/stubs/pivot.stub',
        'seeder'                 => base_path() . '/vendor/on3n3o/generators/resources/stubs/seeder.stub',
        'seeder_plain'           => base_path() . '/vendor/on3n3o/generators/resources/stubs/seeder.plain.stub',
        'view'                   => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.stub',
        'view_index'             => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.index.stub',
        'view_indexb4'           => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.index.b4.stub',
        'view_index_black'       => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.index.black.stub',
        'view_show'              => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.show.stub',
        'view_showb4'            => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.show.b4.stub',
        'view_create'            => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.create.stub',
        'view_create_black'      => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.create.black.stub',
        'view_edit'              => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.edit.stub',
        'view_edit_black'        => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.edit.black.stub',
        'view_create_edit'       => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.create_edit.stub',
        'view_create_editb4'     => base_path() . '/vendor/on3n3o/generators/resources/stubs/view.create_edit.b4.stub',
        'schema_create'          => base_path() . '/vendor/on3n3o/generators/resources/stubs/schema_create.stub',
        'schema_change'          => base_path() . '/vendor/on3n3o/generators/resources/stubs/schema_change.stub',
        'notification'           => base_path() . '/vendor/on3n3o/generators/resources/stubs/notification.stub',
        'event'                  => base_path() . '/vendor/on3n3o/generators/resources/stubs/event.stub',
        'listener'               => base_path() . '/vendor/on3n3o/generators/resources/stubs/listener.stub',
        'many_many_relationship' => base_path() . '/vendor/on3n3o/generators/resources/stubs/many_many_relationship.stub',
        'trait'                  => base_path() . '/vendor/on3n3o/generators/resources/stubs/trait.stub',
        'job'                    => base_path() . '/vendor/on3n3o/generators/resources/stubs/job.stub',
        'console'                => base_path() . '/vendor/on3n3o/generators/resources/stubs/console.stub',
        'exception'              => base_path() . '/vendor/on3n3o/generators/resources/stubs/exception.stub',
        'middleware'             => base_path() . '/vendor/on3n3o/generators/resources/stubs/middleware.stub',
        'repository'             => base_path() . '/vendor/on3n3o/generators/resources/stubs/repository.stub',
        'contract'               => base_path() . '/vendor/on3n3o/generators/resources/stubs/contract.stub',
        'factory'                => base_path() . '/vendor/on3n3o/generators/resources/stubs/factory.stub',
        'test'                   => base_path() . '/vendor/on3n3o/generators/resources/stubs/test.stub',
        'service-provider'       => base_path() . '/vendor/on3n3o/generators/resources/stubs/service_provider.stub',
        'request_index'          => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.index.stub',
        'request_create'         => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.create.stub',
        'request_store'          => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.store.stub',
        'request_show'           => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.show.stub',
        'request_edit'           => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.edit.stub',
        'request_update'         => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.update.stub',
        'request_delete'         => base_path() . '/vendor/on3n3o/generators/resources/stubs/request.delete.stub',
        'js'                     => base_path() . '/vendor/on3n3o/generators/resources/stubs/js.stub',
        'js_index'               => base_path() . '/vendor/on3n3o/generators/resources/stubs/js.index.stub',
        'js_create'              => base_path() . '/vendor/on3n3o/generators/resources/stubs/js.create.stub',
        'js_edit'                => base_path() . '/vendor/on3n3o/generators/resources/stubs/js.edit.stub',
        'policy'                 => base_path() . '/vendor/on3n3o/generators/resources/stubs/policy.stub',
        'route'                  => base_path() . '/vendor/on3n3o/generators/resources/stubs/route.web.stub',
        'addroute'               => base_path() . '/vendor/on3n3o/generators/resources/stubs/routegroup.web.stub',
    ]
];
