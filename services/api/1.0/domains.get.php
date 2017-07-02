<?php

$EntryValidation = new entity_validation();

if ($page_data->PageActions->getStatus()) {
    $page_data->PageActions = $page_data->DAO_Service->initDAO();
    $page_data->PageData->PageWebStatus->setAPIResponse(10);
}

/* Seeing as there is no error then create the site entry */
if ($page_data->PageActions->getStatus()) {
    /* SQL - Query */
    $get_domains_list_query = "SELECT a.id, a.name, a.master, a.last_check, a.type, a.notified_serial, a.account FROM powerdns.domains a;";
    /* SQL - Exec */
    try {
        $get_domains_list_stmt = $page_data->DAO_Service->DAO_Service->prepare($get_domains_list_query);
        $get_domains_list_result = $get_domains_list_stmt->execute();
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("get_domains_list_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $get_domains_list_data_row = $get_domains_list_stmt->fetchAll();
        if ($get_domains_list_data_row) {
            $page_data->PageData->PageWebStatus->setAPIResponse(1);
            $page_data->PageData->PageWebStatus->setAPIResponseData($get_domains_list_data_row);
            $page_data->PageActions->setStatusCode("Data Size: " . count($get_domains_list_data_row) . " row(s)");
        } else {
            /* There was no data */
            $page_data->PageActions->setStatusCode("No Data");
            $page_data->PageData->PageWebStatus->setAPIResponse(1);
            $page_data->PageData->PageWebStatus->setAPIResponseData("");
        }
    } else {
        /* If there was a SQL error */
        $page_data->PageData->PageWebStatus->setAPIResponse(10);
    }
}

$page_data->LogEntry((($page_data->PageActions->getStatus()) ? 1 : 3));
$json_response = json_encode($page_data->PageData->PageWebStatus->getAPIResponse(), JSON_NUMERIC_CHECK);

/* Deliver results */
header('HTTP/1.1 ' . $page_data->PageData->PageWebStatus->getAPIResponseStatus() . ' ' . $page_data->PageData->PageWebStatus->getHTTPResponseCode());
header('Content-Type: application/json; charset=utf-8');
echo $json_response;
?>