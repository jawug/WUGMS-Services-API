<?php

$EntryValidation = new entity_validation();

if ($page_data->PageActions->getStatus()) {
    $page_data->PageData->PageWebStatus->setAPIResponse(7);
    if (array_key_exists('parameters', $wsapi_data)) {
        try {

            $user_id = $EntryValidation->validateCheckNumeric($wsapi_data['parameters'][1]);
        } catch (Exception $e) {
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode($e->getMessage());
            $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
            $page_data->PageActions->setLine($e->getLine());
        }
    } else {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("Missing User ID Parameter");
    }
}

if ($page_data->PageActions->getStatus()) {
    $page_data->PageActions = $page_data->DAO_Service->initDAO();
    $page_data->PageData->PageWebStatus->setAPIResponse(10);
}

if ($page_data->PageActions->getStatus()) {
    /* SQL - Query */
    $confirm_user_exists_query = "SELECT 1 FROM radius.radcheck a where a.id = :user_id";
    /* SQL - Params */
    $confirm_user_exists_query_params = array(
        ':user_id' => $user_id
    );
    /* SQL - Exec */
    try {
        $confirm_user_exists_stmt = $page_data->DAO_Service->DAO_Service->prepare($confirm_user_exists_query);
        $confirm_user_exists_result = $confirm_user_exists_stmt->execute($confirm_user_exists_query_params);
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("confirm_user_exists_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $confirm_user_exists_data_row = $confirm_user_exists_stmt->fetchAll();
        if (!$confirm_user_exists_data_row) {
            /* There was no data */
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode("User does not exist");
            $page_data->PageData->PageWebStatus->setAPIResponse(5);
            $page_data->PageData->PageWebStatus->setAPIResponseData("User does not exist");
        }
    } else {
        /* If there was a SQL error */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageData->PageWebStatus->setAPIResponse(10);
    }
}

/* Seeing as there is no error then create the site entry */
if ($page_data->PageActions->getStatus()) {
    /* SQL - Query */
    $delete_user_query = "DELETE FROM radius.radcheck WHERE id = :user_id;";
    /* SQL - Params */
    $delete_user_query_params = array(
        ':user_id' => $user_id
    );
    /* SQL - Exec */
    try {
        $delete_user_stmt = $page_data->DAO_Service->DAO_Service->prepare($delete_user_query);
        $delete_user_result = $delete_user_stmt->execute($delete_user_query_params);
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("delete_user_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $page_data->PageData->PageWebStatus->setAPIResponse(1);
        $page_data->PageData->PageWebStatus->setAPIResponseData("User Deleted");
        $page_data->PageActions->setStatusCode("User Deleted. UID(" . $user_id . ")");
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