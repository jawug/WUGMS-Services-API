<?php

if (!isset($_SESSION)) {
    session_start();
}
$ServerArray = filter_input_array(INPUT_SERVER);
require_once($ServerArray['DOCUMENT_ROOT'] . '/../src/bwcfw.classes.php');
$page_data = new LoggingService(__FILE__, FALSE, FALSE);

if ($ServerArray['QUERY_STRING']) {
    $route_config = new Router_Service();
    $wsapi_data = $route_config->CheckForRoute($ServerArray['QUERY_STRING']);
    $filename = $ServerArray['DOCUMENT_ROOT'] . '/../services/api/' . $wsapi_data['version'] . '/' . $wsapi_data['filename'] . '.php';
    if (file_exists($filename)) {
        $page_data->PageData->setFileRecord($wsapi_data['filename'] . '.php');
        require_once($filename);
    } else {
        require_once($ServerArray['DOCUMENT_ROOT'] . '/../services/404.html');
    }
}
?>