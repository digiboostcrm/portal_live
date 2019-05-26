<?php
class custom_notification{
    function makeAccountNamelink(&$bean){
        $bean->retrieve($bean->id);
        $bean->name = "<a href='index.php?action=DetailView&module={$bean->parent_type}&record={$bean->parentid}'>{$bean->name}</a>";
    }
}
?>