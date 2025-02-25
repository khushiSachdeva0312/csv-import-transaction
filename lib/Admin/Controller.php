<?php

namespace WHMCS\Module\Addon\CsvImportTransaction\Admin;

use WHMCS\Database\Capsule;
use Smarty;
use WHMCS\Module\Addon\CsvImportTransaction\Helper;

class Controller
{

    public $tplFileName;
    public $params;
    public $smarty;
    public $tplVar = array();
    public $message = [];

    public function __construct($params)
    {
        global $CONFIG;
        $this->params = $params;
        $this->tplVar['rootURL'] = $CONFIG['SystemURL'];
        $this->tplVar['urlPath'] = $CONFIG['SyetemURL'] . "modules/addons/{$params['module']}/";
        $this->tplVar['_lang'] = $params['_lang'];
        $this->tplVar['moduleLink'] = $params['moduleLink'];
        $this->tplVar['module'] = $params['module'];
        $this->tplVar['license'] = $params['license'];
        $this->tplVar['license_key'] = $params['license_key'];
        $this->tplVar['version'] = $params['version'];
      
        $this->tplVar['tplDIR'] = ROOTDIR . "/modules/addons/{$params['module']}/templates/Admin/";
        $this->tplVar['header'] = ROOTDIR . "/modules/addons/{$params['module']}/templates/Admin/header.tpl";
        $this->tplVar['footer'] = ROOTDIR . "/module/addons/{$params['module']}/templates/Admin/footer.tpl";
        $this->tplVar['imagepath'] = ROOTDIR . "/module/addons/{$params['module']}/assets/img/";
       
    }

    public function csv_transaction($vars)
    {
        try {
            global $whmcs;
            $obj = new Helper();

            if (isset($_POST['submit']) && $_POST['submit'] == "Get Transactions") {
                /* Allowed mime types*/
                $fileMimes = array(
                    'text/x-comma-separated-values',
                    'text/comma-separated-values',
                    'application/octet-stream',
                    'application/vnd.ms-excel',
                    'application/x-csv',
                    'text/x-csv',
                    'text/csv',
                    'application/csv',
                    'text/plain',
                    'application/excel',
                    'application/x-csv',
                    'application/vnd.msexcel'
                );
                /* Validate whether selected file is a CSV file*/

                if (!empty($_FILES['csvfile']['name']) && in_array($_FILES['csvfile']['type'], $fileMimes)) {
                    $csvFile = fopen($_FILES['csvfile']['tmp_name'], 'r');
                    $listDomains = [];
                    $count = 0;
                    $getData = fgetcsv($csvFile, 100000, ",");
                    while (($getData = fgetcsv($csvFile, 100000, ",")) !== FALSE) {

                        $invoiceumber = Capsule::table('tblinvoices')->where('invoicenum', $getData[2])->value('id');
                        $listDomains[$count]['Transaction_ID'] = $getData[0];
                        $listDomains[$count]['Date'] = $getData[1];
                        $listDomains[$count]['InvoiceId'] = $invoiceumber;
                        $listDomains[$count]['Payment_amount'] = $getData[3];
                        $listDomains[$count]['Payment_method'] = $getData[4];
                        $results = $obj->update_invoice_transactions($listDomains[$count]);
                        $count = $count + 1;
                    }

                }


                if (empty($results)) {

                    $suceess = "All Transcations Migrate Sucessfully in WHMCS";
                } else {
                    $uniqueValueId = array_unique($results);
                    $uniqueValueId = implode(",", $results);
                    $error = "Error Occurs " . $uniqueValueId;
                }


            }
            $this->tplVar['error'] = $error;
            $this->tplVar['success'] = $suceess;
            $this->tplFileName = __FUNCTION__;
            $this->output();
        } catch (\Exception $e) {
            $error = 'An error occurred: ' . $e->getMessage();
            $this->tplVar['error'] = $error;
        }
    }

    public function output()
    {
        $this->smarty = new Smarty();
        $this->smarty->assign('tplVar', $this->tplVar);
        $this->smarty->assign('fileName', $this->tplFileName);
        $this->smarty->assign('lang', $this->params["_lang"]);
        if (!empty($this->tplFileName)) {

            $this->smarty->display($this->tplVar['tplDIR'] . $this->tplFileName . '.tpl');
        } else {
            $this->tplVar['errorMsg'] = 'not found';
            $this->smarty->display($this->tplFileName . 'error.tpl');
        }
    }
}
