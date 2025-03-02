<?php
namespace Deployer;

require 'recipe/codeigniter4.php';

// Cargar variables del archivo .env si no están en el entorno
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorar comentarios
        putenv($line);
    }
}
// Config

set('repository', getenv('DEPLOY_REPO') ?: 'git@github.com:baubyte/codeigniter4-ddev-deployer.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host(getenv('DEPLOY_HOST') ?: 'default-host')
    ->set('remote_user', getenv('DEPLOY_USER') ?: 'default-user')
    ->set('deploy_path', getenv('DEPLOY_PATH') ?: '/default/path');

// Hooks

after('deploy:failed', 'deploy:unlock');
