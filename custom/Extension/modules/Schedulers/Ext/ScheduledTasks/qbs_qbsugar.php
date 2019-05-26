<?php
array_push($job_strings, 'qbs_qbsugar');
function qbs_qbsugar()
{
    $startTime = microtime(true);
    require_once('include/entryPoint.php');
    require_once('include/MVC/SugarApplication.php');
    require_once('modules/qbs_QBSugar/HandleQBQueue.php');
    $app = new SugarApplication();

    // error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED); ini_set('display_errors', 'On');

    global $sugar_version, $db, $dictionary, $current_user;

    $QBQueueList = array();
    $HQBQueue = new HandleQBQueue();
    $QBQueueList = $HQBQueue->getQBQueue();

    $QBQueueListCount = count($QBQueueList);
    if($QBQueueListCount > 0)   {
        for($i = 0; $i < $QBQueueListCount ; $i++)  {
            global $db;
            $functionNames = array('getContactsfromQB', 'sendContactstoQB', 'getInvoicefromQB', 'sendInvoicetoQB', 'getQuotesfromQB', 'sendQuotestoQB', 'getProductsfromQB', 'sendProductstoQB');

            if(in_array($QBQueueList[$i]['id'], $functionNames))    {
                $flow = 'Quickbooks => Suite';
                $vtigermodules = array('QBCustomer', 'QBQuotes','QBInvoice', 'QBItem');
                if(in_array($QBQueueList[$i]['module'], $vtigermodules))    {
                    $flow = 'Suite => Quickbooks';
                }

                $finalcall = $HQBQueue->ExecuteManualQBQueue($QBQueueList[$i]['module']);
                continue;
            }
            else    {
                $HQBQueue->ExecuteQBQueue($QBQueueList[$i]['id'], $QBQueueList[$i]['module'], $QBQueueList[$i]['source'], $QBQueueList[$i]['queuemode']);
            }
        }
    }
    return true;
}
