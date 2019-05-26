<?php

$sDbUser = "cm_portal_user";
$sDbPassword = "Ie5q5#v8";
$sDb = "suitecrm_30";
$sHostName = "172.24.16.132";

$filename = dirname(__FILE__) . "/backup-" . date("d-m-Y") . ".sql";

$cmd = "mysqldump -h=$sHostName --user=$sDbUser --password=$sDbPassword $sDb > $filename";

exec( $cmd );

exit(0);
