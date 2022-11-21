<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

//ЗАПОЛНИ РЕПОЗИТОРИЙ
set('repository', 'РЕПОЗИТОРИЙ');

add('shared_files', ['/public/my-sitemap.xml']);
add('shared_dirs', []);
add('writable_dirs', ['storage', 'bootstrap/cache']);

set('allow_anonymous_stats', false);

set('default_stage', 'prod');
set('deploy_path', '/app/www/project');
set('http_user', 'root');

//ЗАПОЛНИ ИМЯ ХОСТА
localhost('ИМЯ_ХОСТА')
        ->set('labels', ['stage' => 'prod']);

// Hosts

//host('')
//    ->set('remote_user', 'deployer')
//    ->set('deploy_path', '~/www');

// Hooks

after('deploy:failed', 'deploy:unlock');
