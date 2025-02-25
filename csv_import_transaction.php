<?php

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\CsvImportTransaction\Admin\AdminDispatcher;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function csv_import_transaction_config()
{
    return [
        // Display name for your module
        'name' => 'CSV Import Transaction',
        // Description the admin interfacen displayed withi
        'description' => 'This is a module for CSV Import Transaction',
        // Module author name
        'author' => '<a href="http://whmcsglobalservices.com/" target="_blank"><img src="/modules/addons/csv_import_transaction/assests/img/whmcsglobalservices.svg" alt="WHMCS GLOBAL SERVICES"  width="150"></a>',
        // Default language
        'language' => 'english',
        // Version number
        'version' => '1.0',
        'fields' => [
            'delete_db' => [
                'FriendlyName' => 'Delete Database Table',
                'Type' => 'yesno',
                'Description' => 'Tick this box to delete the addon module database table when deactivating the module.',
            ]
        ]
    ];
}

function csv_import_transaction_output($vars){
    $whmcs = WHMCS\Application::getInstance();
    $action = !empty($whmcs->get_req_var("action")) ? $whmcs->get_req_var("action") : "csv_transaction";
    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action, $vars);
}