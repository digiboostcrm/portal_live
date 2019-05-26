<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class UT_RightSignatureController extends SugarController {
    protected function action_editview() {
        $url = "index.php?module=UT_RightSignature&action=SendDocumentRS";
        SugarApplication::redirect($url);
    }
}