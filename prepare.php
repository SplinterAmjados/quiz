<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 17/11/2016
 * Time: 20:22
 */

if (!isset($argv[1])){
    $env= 'dev';
}else{
    $env= $argv[1];
}

echo "php bin/console cache:clear --env=$env\n";
echo `php bin/console cache:clear --env=$env`;

echo "php bin/console doctrine:schema:update --env=$env\n";
echo `php bin/console doctrine:schema:update --env=$env`;

echo "php bin/console assets:install --env=$env\n";
echo `php bin/console assets:install --env=$env`;

echo "php bin/console fos:js-routing:dump --env=$env\n";
echo `php bin/console fos:js-routing:dump --env=$env`;

echo "php bin/console assetic:dump --env=$env\n";
echo `php bin/console assetic:dump --env=$env`;