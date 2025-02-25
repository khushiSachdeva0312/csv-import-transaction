<?php

namespace WHMCS\Module\Addon\CsvImportTransaction;

use WHMCS\Database\Capsule;

require_once ROOTDIR . '/includes/gatewayfunctions.php';
require_once ROOTDIR . '/includes/invoicefunctions.php';
use DateTime;



class Helper
{
    public function update_invoice_transactions($data)
    {
        $gatewayName = $data['Payment_method'];
        $invoiceId = $data['InvoiceId'];
        $paymentAmount = $data['Payment_amount'];
        $transactionId = $data['Transaction_ID'];
        $date = $data['Date'];
        $timestamp = strtotime(str_replace('/', '-', $date));


        $formattedDateTime = date('Y-m-d H:i:s', $timestamp);


        // Format the date as a string for use as an index



        // $invoiceId = checkCbInvoiceID($invoiceId, $gatewayName);

        // checkCbTransID($transactionId);
        $results = addInvoicePayment(
            $invoiceId,
            strval($transactionId),
            floatval($paymentAmount),
            0.0,
            $gatewayName,
        );
        if ($results) {

            Capsule::table("tblaccounts")->where('invoiceid', $invoiceId)->update([
                "date" => $formattedDateTime
            ]);

        } else {
            echo "fff";
            die;
        }


        if (!$results) {

            $datas[] = "Error Occurs " . $invoiceId;

        }

        return $datas;
    }


}
