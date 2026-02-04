<?php

//Todoist連携の開始プログラム

require '../../db_info.php';
require '../../cookie.php';

// Todoist App Consoleで取得した値をここに入れる
$client_id = '981379b022cc41d3b24239a6cd9306da'; 
$redirect_uri = 'https://fixhome.me/search/todoist/todoist_token/callback.php';
$scope = 'data:read_write';
$state = bin2hex(random_bytes(16)); // セキュリティ用のランダム文字列

$url = "https://todoist.com/oauth/authorize?" . http_build_query([
    'client_id' => $client_id,
    'scope' => $scope,
    'state' => $state,
    'redirect_uri' => $redirect_uri
]);

header("Location: $url");
exit;