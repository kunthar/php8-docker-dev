<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');

session_start();
$_SESSION["foo"] = "bar";


echo 'Test value to check from Redis  ' . $_SESSION["foo"] . "\n";
echo "<br>";
echo 'Test Redis keys with this command:' . "\n";
echo '<br>';
echo 'docker exec -it deploy_redis_1 /usr/local/bin/redis-cli KEYS *';
echo '<br><br>';
echo 'session.save_handler = ' . ini_get('session.save_handler') . "\n";
echo '<br>';
echo 'session_save_path = ' . ini_get('session.save_path') . "\n";

echo "<br>";
echo "================================================================================================================================";
echo "<br>";


phpinfo();
exit();