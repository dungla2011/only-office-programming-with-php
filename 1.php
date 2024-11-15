<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once 'vendor/autoload.php';


$domainAndPort = '10.0.0.28:3000';
$documentServerAndPort = '10.0.0.211:8080';


$link = "http://$domainAndPort/1.xlsx";

$keyFile = "s" . filemtime(__DIR__ . '\1.xlsx');

// echo $keyFile;
// return;


$filename = basename($link);

$fileType = 'xlsx';

$uid = 1;
$uEmail = 'user1@gmail.com';
$docType = 'cell';

$config = [
    'document' => [
        'fileType' => "$fileType",
        'key' => $keyFile,
        'title' => "$filename",
        'url' => "$link",
        "permissions" => [
            "comment" => true,
            "copy" => true,
            "download" => true,
            "edit" => true,
            "print" => true,
            "fillForms" => true,
            "modifyFilter" => true,
            "modifyContentControl" => true,
            "review" => true,
            "chat" => true,
            "reviewGroups" => null,
            "commentGroups" => [],
            "userInfoGroups" => null,
            "protect" => true
        ],
    ],
    "editorConfig" => [
        //This call back is used to save new file content (after changed) from DocumentServer save it to your server, and some other task if need...
        "callbackUrl" => "http://$domainAndPort/callback.php",
        "user" => [
            "id" => "$uid",
            "email"=> "$uEmail",
            "name" => "$uEmail",
            "group" => "",
        ],
        'customization' => [
//            'forcesave' => true,
            'autosave' => true,
        ]
    ],
    'documentType' => "$docType",
];


//Get key from:  etc/onlyoffice/documentserver/local.json
//..... secret": { "inbox": {"string": "a5s5ToK5x5VilrDJX6dT4L7wZlbWTmAu"

$jwt_key = "a5s5ToK5x5VilrDJX6dT4L7wZlbWTmAu";
$tk = JWT::encode($config, $jwt_key, 'HS256');

$config['token'] = $tk;

$js_config = json_encode($config, JSON_PRETTY_PRINT );
?>

<title>
    <?php

    echo "Edit DEMO";

    ?>

</title>

<style>
    /*reset css*/
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
    }

</style>
<div id="placeholder"></div>

<script type="text/javascript" src="http://10.0.0.211:8080/web-apps/apps/api/documents/api.js"></script>

<script>

    var innerAlert = function (message, inEditor) {
        if (console && console.log)
            console.log(message);
        if (inEditor && docEditor)
            docEditor.showMessage(message);
    };

    // the application is loaded into the browser
    var onAppReady = function () {
        innerAlert(" ---- Document editor ready");
    };


    var config = <?php echo $js_config; ?>;

    config.events = {
        'onAppReady': onAppReady,
        'onSave': function () {
            innerAlert(" ++++ onSave editor ready");
        },
        'onRequestSaveAs': function () {
            innerAlert(" ++++ onRequestSaveAs editor ready");
        }
    };
    // config.events['onRequestRename'] = onRequestRename;

    const docEditor = new DocsAPI.DocEditor("placeholder", config);
</script>
