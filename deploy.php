<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:yusifk88/ChefPilot.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('138.68.180.185')
    ->set('branch', 'API')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/var/www/chefPilot');

// Hooks

after('deploy:failed', 'deploy:unlock');
