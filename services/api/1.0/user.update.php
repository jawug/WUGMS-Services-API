<?php

$EntryValidation = new entity_validation();

$PostArray = filter_input_array(INPUT_POST);
$page_data->PageData->PageWebStatus->setAPIResponse(7);
if (!$PostArray) {
    $page_data->PageActions->setStatus(FALSE);
    $page_data->PageActions->setStatusCode("No Parameters passed");
}

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
    if (array_key_exists('attribute', $PostArray)) {
        try {
            $user_attribute = $EntryValidation->validateStringRegex($PostArray['attribute'], $EntryValidation->expressions->getRegexPostAuthUserName());
        } catch (Exception $e) {
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode($e->getMessage());
            $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
            $page_data->PageActions->setLine($e->getLine());
        }
    } else {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("Attribute does not meet criteria");
    }
}

if ($page_data->PageActions->getStatus()) {
    if (array_key_exists('op', $PostArray)) {
        try {
            $user_op = $EntryValidation->validateStringRegex($PostArray['op'], $EntryValidation->expressions->getRegexRadCheckOP());
        } catch (Exception $e) {
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode($e->getMessage());
            $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
            $page_data->PageActions->setLine($e->getLine());
        }
    } else {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("op does not meet criteria");
    }
}

if ($page_data->PageActions->getStatus()) {
    if (array_key_exists('value', $PostArray)) {
        try {
            $user_value = $EntryValidation->validateStringLengthMinMax($PostArray['value'], 1, 253);
        } catch (Exception $e) {
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode($e->getMessage());
            $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
            $page_data->PageActions->setLine($e->getLine());
        }
    } else {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("value does not meet criteria");
    }
}

if ($page_data->PageActions->getStatus()) {
    if (array_key_exists('username', $PostArray)) {
        try {
            $user_name = $EntryValidation->validateStringRegex($PostArray['username'], $EntryValidation->expressions->getRegexPostAuthUserName());
        } catch (Exception $e) {
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode($e->getMessage());
            $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
            $page_data->PageActions->setLine($e->getLine());
        }
    } else {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("Username does not meet criteria");
    }
}


if ($page_data->PageActions->getStatus()) {
    $page_data->PageActions = $page_data->DAO_Service->initDAO();
    $page_data->PageData->PageWebStatus->setAPIResponse(10);
}

if ($page_data->PageActions->getStatus()) {
    /* SQL - Query */
    $get_user_details_query = "SELECT a.id, a.username, a.attribute, a.op, a.value FROM radius.radcheck a where a.id = :user_id";
    /* SQL - Params */
    $get_user_details_query_params = array(
        ':user_id' => $user_id
    );
    /* SQL - Exec */
    try {
        $get_user_details_stmt = $page_data->DAO_Service->DAO_Service->prepare($get_user_details_query);
        $get_user_details_result = $get_user_details_stmt->execute($get_user_details_query_params);
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("get_user_details_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $get_user_details_data_row = $get_user_details_stmt->fetch();
        if ($get_user_details_data_row) {
            $UserOrigData = $get_user_details_data_row;
        } else {
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

if ($page_data->PageActions->getStatus()) {
    /* SQL - Query */
    $check_for_dup_username_query = "SELECT 1 FROM radius.radcheck a where a.id <> :id AND upper(a.username) = :username;";
    /* SQL - Params */
    $check_for_dup_username_query_params = array(
        ':username' => strtoupper($user_name),
        ':id' => $user_id
    );
    /* SQL - Exec */
    try {
        $check_for_dup_username_stmt = $page_data->DAO_Service->DAO_Service->prepare($check_for_dup_username_query);
        $check_for_dup_username_result = $check_for_dup_username_stmt->execute($check_for_dup_username_query_params);
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("check_for_dup_username_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $check_for_dup_username_data_row = $check_for_dup_username_stmt->fetch();
        if ($check_for_dup_username_data_row) {
            /* There was no data */
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode("Duplicate Username");
            $page_data->PageData->PageWebStatus->setAPIResponse(5);
            $page_data->PageData->PageWebStatus->setAPIResponseData("Duplicate Username");
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
    $check_username_query = "UPDATE radius.radcheck SET username = :username , attribute = :attribute, op = :op, value = :value WHERE id =:id ;";
    /* SQL - Params */
    $check_username_query_params = array(
        ':username' => $user_name,
        ':attribute' => $user_attribute,
        ':op' => $user_op,
        ':value' => $user_value,
        ':id' => $user_id
    );
    /* SQL - Exec */
    try {
        $check_username_stmt = $page_data->DAO_Service->DAO_Service->prepare($check_username_query);
        $check_username_result = $check_username_stmt->execute($check_username_query_params);
    } catch (PDOException $ex) {
        /* SQL - Error Handling */
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode("check_username_stmt");
        $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
        $page_data->PageActions->setLine($ex->getLine());
    }
    if ($page_data->PageActions->getStatus()) {
        $page_data->PageData->PageWebStatus->setAPIResponse(1);
        $page_data->PageData->PageWebStatus->setAPIResponseData("User has been updated");
        $page_data->PageActions->setStatusCode("User has been updated");
    } else {
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