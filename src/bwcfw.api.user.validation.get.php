<?php

$page_data->PageData->setFileRecord(__FILE__);
/* Check to see if there is a session var ID */
if (isset($_SESSION["id"])) {
    $EntryValidation = new entity_validation();
    /* Make sure that the session var "id" is a numeric */
    try {
        $user_id = $EntryValidation->validateCheckNumeric($_SESSION["id"]);
    } catch (Exception $e) {
        $page_data->PageActions->setStatus(FALSE);
        $page_data->PageActions->setStatusCode($e->getMessage());
        $page_data->PageActions->setExtendedStatusCode("Sub function located in " . $e->getFile());
        $page_data->PageActions->setLine($e->getLine());
    }

    if ($page_data->PageActions->getStatus()) {
        /* Enable the DAO as we will need it */
        $page_data->DAO_Service->initDAO();
        /* SQL - Query */
        $check_user_session_query = "SELECT 1 FROM tbl_base_sessions WHERE session_id = :sessionid and username_id = :username_id;";
        /* SQL - Parameters */
        $check_user_session_query_params = array(
            ':username_id' => $user_id,
            ':sessionid' => session_id()
        );

        /* SQL - Exec */
        try {
            $check_user_session_query_stmt = $page_data->DAO_Service->DAO_Service->prepare($check_user_session_query);
            $check_user_session_query_stmt->execute($check_user_session_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode("check_user_session_query_stmt");
            $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $page_data->PageActions->setLine($ex->getLine());
        }
        if ($page_data->PageActions->getStatus()) {
            $check_user_session_row = $check_user_session_query_stmt->fetch();
            if (!$check_user_session_row) {
                $page_data->PageActions->setStatus(FALSE);
                $page_data->PageActions->setStatusCode("User not logged on");
            }
        }
    }
    if ($page_data->PageActions->getStatus()) {
        /* SQL - Query */
        $get_user_roles_query = "SELECT b.role_name as role, b.role_description as comment FROM tbl_ae_user_roles a, tbl_base_roles b WHERE a.fk_user = :username_id AND a.fk_role = b.id_role;";
        /* SQL - Parameters */
        $get_user_roles_query_params = array(
            ':username_id' => $user_id
        );
        /* SQL - Exec */
        try {
            $get_user_roles_query_stmt = $page_data->DAO_Service->DAO_Service->prepare($get_user_roles_query);
            $get_user_roles_query_stmt->execute($get_user_roles_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode("get_user_roles_query_stmt");
            $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $page_data->PageActions->setLine($ex->getLine());
        }
        if ($page_data->PageActions->getStatus()) {
            $get_user_roles_rows = $get_user_roles_query_stmt->fetchall();
            if ($get_user_roles_rows) {
                $_SESSION["roles"] = $get_user_roles_rows;
            }
        }
    }
    if ($page_data->PageActions->getStatus()) {
        /* SQL - Query */
        $get_user_teams_query = "SELECT b.role_name as role, b.role_description as comment FROM tbl_ae_user_roles a, tbl_base_roles b WHERE a.fk_user = :username_id AND a.fk_role = b.id_role;";
        /* SQL - Parameters */
        $get_user_teams_query_params = array(
            ':username_id' => $user_id
        );
        /* SQL - Exec */
        try {
            $get_user_teams_query_stmt = $page_data->DAO_Service->DAO_Service->prepare($get_user_teams_query);
            $get_user_teams_query_stmt->execute($get_user_teams_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $page_data->PageActions->setStatus(FALSE);
            $page_data->PageActions->setStatusCode("get_user_roles_query_stmt");
            $page_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $page_data->PageActions->setLine($ex->getLine());
        }
        if ($page_data->PageActions->getStatus()) {
            $get_user_teams_rows = $get_user_teams_query_stmt->fetchall();
            if ($get_user_teams_rows) {
                $_SESSION["teams"] = $get_user_teams_rows;
            } else {
                $page_data->PageActions->setStatus(FALSE);
                $page_data->PageActions->setStatusCode("User has no roles assigned to them");
            }
        }
    }
    /* Set conditions if there was an error */
    if (!$page_data->PageActions->getStatus()) {
        $page_data->LogEntry(3);
        $page_data->PageData->PageWebStatus->setAPIResponse(10);
        $page_data->PageData->PageWebStatus->setAPIResponseData("Internal Server Error");
    }
} else {
    $page_data->PageActions->setStatus(FALSE);
    $page_data->PageData->PageWebStatus->setAPIResponse(3);
    $page_data->PageActions->setStatusCode("User not authorised");
    $page_data->PageData->PageWebStatus->setAPIResponseData($page_data->PageActions->getStatusCode());
}
?>