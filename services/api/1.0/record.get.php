<?php

$EntryValidation = new entity_validation();

if ($page_data->PageActions->getStatus()) {
    $page_data->PageData->PageWebStatus->setAPIResponse(7);
    if (array_key_exists('parameters', $wsapi_data)) {
        try {
            $record_id = $EntryValidation->validateCheckNumeric($wsapi_data['parameters'][1]);
        } catch (Exception $e) {
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode($e->getMessage());
            $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
            $page_data->PageActions->setLine($e->getLine());
        }
    } else {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("Missing Group ID Parameter");
    }
}

if ($page_data->PageActions->getStatus()) {
    $page_data->PageActions = $page_data->DAO_Service->initDAO();
    $page_data->PageData->PageWebStatus->setAPIResponse(10);
}

/* Seeing as there is no error then create the site entry */
if ($page_data->PageActions->getStatus()) {
    /* SQL - Query */
    $get_record_details_query = "SELECT a.id, a.domain_id, a.name, a.type, a.content, a.ttl, a.prio, a.change_date, a.disabled, a.ordername, a.auth FROM powerdns.records a where a.id = :record_id;";
    /* SQL - Params */
    $get_record_details_query_params = array(
        ':record_id' => $record_id
    );
    /* SQL - Exec */
    try {
        $get_record_details_stmt = $page_data->DAO_Service->DAO_Service->prepare($get_record_details_query);
        $get_record_details_result = $get_record_details_stmt->execute($get_record_details_query_params);
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("get_record_details_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $get_record_details_data_row = $get_record_details_stmt->fetchAll();
        if ($get_record_details_data_row) {
            $page_data->PageData->PageWebStatus->setAPIResponse(1);
            $page_data->PageData->PageWebStatus->setAPIResponseData($get_record_details_data_row);
            $page_data->PageActions->setStatusCode("Data Size: " . count($get_record_details_data_row) . " row(s)");
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

/* Auditing/logging */
$page_data->page_metric->page_metric($ServerArray, $_SESSION, $page_data->PageActions, session_id(), $page_data->PageData->getPageInfo());
/* Deliver results */
header('HTTP/1.1 ' . $page_data->PageData->PageWebStatus->getAPIResponseStatus() . ' ' . $page_data->PageData->PageWebStatus->getHTTPResponseCode());
header('Content-Type: application/json; charset=utf-8');
echo $json_response;
?>