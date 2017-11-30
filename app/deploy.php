<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'admin-tenis');

// Project repository
set('repository', 'https://github.com/martinbobbio/davinci-tennisstar-backend.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('www.tennis-star.com')->port(65002)->user('u277128184')
    ->set('deploy_path', '~/{{application}}');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

