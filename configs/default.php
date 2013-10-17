<?php

//define all paths first, this is in parallel with directory structure
define('API_PATH', 'mbcmessaging');
define('API_VERSION', 'v1');
//index file, can be removed by htaccess, with trailing slash
define('INDEX_FILE', '');
//build the basepath for file inclusions
define('BASEPATH', $_SERVER['DOCUMENT_ROOT'].'/'.API_PATH.'/'.API_VERSION.'/'.INDEX_FILE);
define('SITEPATH', 'http://'.$_SERVER['SERVER_NAME'].'/'.API_PATH.'/'.API_VERSION.'/'.INDEX_FILE);

//digest authentication
define('AUTH_REALM', 'MBC Messaging API');

//number of hours until session expires for resources with authorization check
define('EXPIRE_ACCESS_HOURS', 180);

//core php functions to check
$core['functions'] = array(
     'json_encode',
     'mysql_real_escape_string',
);
//core php classes to check
$core['classes'] = array(
     'OAuth',
);