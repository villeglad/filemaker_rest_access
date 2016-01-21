<?php
require 'vendor/autoload.php';

//TODO: AUTHENTICATION API KEY headers
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

use App\FmRestAccess;
use Soliant\SimpleFM\Adapter;
use Soliant\SimpleFM\HostConnection;
use Soliant\SimpleFM\Result\FmResultSet;

$app = new \Slim\Slim();

$fmRestAccess = new FmRestAccess;

// index
$app->get('/:db/:layout', function ($db, $layout) use ($fmRestAccess) {
 
    $fmRestAccess->makeAdapter($_ENV['HOST'], $db, $_ENV['ACCOUNT'], $_ENV['PASSWORD']);
    $adapter = $fmRestAccess->getAdapter();

    $adapter->setLayoutName($layout);
    $adapter->setCommandString('-findall');
    $result = $adapter->execute();

    $url          = $result->getDebugUrl();
    $errorCode    = $result->getErrorCode();
    $errorMessage = $result->getErrorMessage();
    $errorType    = $result->getErrorType();
    $count        = null;
    $fetchSize    = null;
    $rows         = [];
    if ($result instanceof FmResultSet) {
        $count        = $result->getCount();
        $fetchSize    = $result->getFetchSize();
        $rows         = $result->getRows();
    }
    echo json_encode($rows);

});

// show by recid
$app->get('/:db/:layout/:recid', function ($db, $layout, $recid) use ($fmRestAccess) {
    
    $fmRestAccess->makeAdapter($_ENV['HOST'], $db, $_ENV['ACCOUNT'], $_ENV['PASSWORD']);
    $adapter = $fmRestAccess->getAdapter();

    $adapter->setLayoutName($layout);
    $adapter->setCommandString('-recid='. $recid . '&-find');
    $result = $adapter->execute();

    $url          = $result->getDebugUrl();
    $errorCode    = $result->getErrorCode();
    $errorMessage = $result->getErrorMessage();
    $errorType    = $result->getErrorType();
    $count        = null;
    $fetchSize    = null;
    $rows         = [];
    if ($result instanceof FmResultSet) {
        $count        = $result->getCount();
        $fetchSize    = $result->getFetchSize();
        $rows         = $result->getRows();
    }
    echo json_encode($rows);

});

// find by params
$app->get('/:db/:layout/:params+', function ($db, $layout, $params) use ($fmRestAccess) {
    
    $fmRestAccess->makeAdapter($_ENV['HOST'], $db, $_ENV['ACCOUNT'], $_ENV['PASSWORD']);
    $adapter = $fmRestAccess->getAdapter();

    $adapter->setLayoutName($layout);

    
    $commandArray = [];
    $lastKey;
    foreach ($params as $key => $param) {
        if ($key % 2 == 0) {
            $lastKey = $param;
        } else {
            $commandArray[$lastKey] = $param;
        }
    }

    $commandArray["-find"] = null;
    $adapter->setCommandArray($commandArray);
    
    $result = $adapter->execute();

    $url          = $result->getDebugUrl();
    $errorCode    = $result->getErrorCode();
    $errorMessage = $result->getErrorMessage();
    $errorType    = $result->getErrorType();
    $count        = null;
    $fetchSize    = null;
    $rows         = [];
    if ($result instanceof FmResultSet) {
        $count        = $result->getCount();
        $fetchSize    = $result->getFetchSize();
        $rows         = $result->getRows();
    }
    echo json_encode($rows);

});

$app->run();
