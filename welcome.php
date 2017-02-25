<!DOCTYPE>
<body>
<?php

/**
 * Example of old php script style
 * Don't do that in 2017, pretty please
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once __DIR__ . '/user.inc';

$accountId = $_GET['account-id'];
$account = find_account($accountId);

?>
<h1>Welcome, <?php echo $account['name'] ?></h1>
</body>
