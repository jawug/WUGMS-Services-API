<?php

class entity_metrics {

    /**
     *
     * @var object Database object 
     */
    var $cdb;

    /**
     *
     * @var \StatusVO
     */
    var $ClassActions;

    /**
     * This function inits the DB so that other functions can access the database and perform actions.
     * @return \StatusVO Results from usage
     */
    function init_db() {
        try {
            $this->cdb = new PDO("mysql:host={$this->database_settings->getHost()};dbname={$this->database_settings->getDataBase()};charset={$this->database_settings->getCharset()}", $this->database_settings->getUserName(), $this->database_settings->getPassword(), $this->database_settings->getDataBaseOptions());
        } catch (PDOException $ex) {
            $this->ClassActions->setStatus(FALSE);
            $this->ClassActions->setStatusCode("init DAO");
            $this->ClassActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->ClassActions->setLine($ex->getLine());
        }
        if ($this->ClassActions->getStatus()) {
            $this->cdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->cdb->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    /**
     * 
     * This function get the user's IP address based on the getenv('REMOTE_ADDR') value.
     * @return string This is the IP address that the user is currently using
     */
    function get_client_ip() {
        return getenv('REMOTE_ADDR');
    }

    /**
     * 
     * @param array $array Array to be searched
     * @param string $arrayitem Named array item
     * @param string $value Value to match in array
     * @return boolean If result found then true
     */
    function isValueInArray($array, $arrayitem, $value) {
        foreach ($array as $item) {
            if (strtoupper(strval($item[$arrayitem])) == strtoupper($value)) {
                return true;
            }
            if (strtoupper(strval($item[$arrayitem])) == "ADMIN") {
                return true;
            }
        }
        return false;
    }

    /**
     * This function is used in order to log the emails that get sent out. That information in turn is used for metrics and audting
     * 
     * @param string $email_type This is the "type" of email that was sent; "feedback" or "invite"
     * @param string $email_to This is the "to" field that is usually used in emails
     * @param string $email_cc This is the "cc" field that is usually used in emails
     * @param string $email_bcc This is the "bcc" field that is usually used in emails
     * @param string $email_size This is the size of the main body of the email
     * @param string $email_status This is the status of when the email was sent; "ok" or "Error"
     * @param string $email_extended_data This field represents the extended error information
     * @param string $ticket_id In cases where the email type is "feedback" this is used to determine the ticket ID
     * @param string $feedback_id In cases where the email type is "feedback" this is used to determine the feedback ID
     * @return \StatusVO This indicates the status
     */
    function metrics_email($email_type, $email_to, $email_cc, $email_bcc, $email_size, $email_status, $email_extended_data, $ticket_id, $feedback_id) {
        $metrics_email_status = new StatusVO();
        if ($this->init_db()) {
            /* SQL - Query */
            $metrics_email_query = "
	INSERT INTO tbl_base_metric_emails(email_type, email_to, email_cc, email_bcc, email_size, email_status, email_extended_data, sid, ticket_id, feedback_id)
	VALUES (:email_type, :email_to, :email_cc, :email_bcc, :email_size, :email_status, :email_extended_data, :sid, :ticket_id, :feedback_id);";
            /* SQL - Params */
            $metrics_email_query_params = array(
                ':email_type' => $email_type,
                ':email_to' => $email_to,
                ':email_cc' => $email_cc,
                ':email_bcc' => $email_bcc,
                ':email_size' => $email_size,
                ':email_status' => $email_status,
                ':email_extended_data' => $email_extended_data,
                ':sid' => microtime(true),
                ':ticket_id' => $ticket_id,
                ':feedback_id' => $feedback_id
            );
            /* SQL - Exec */
            try {
                $metrics_email_stmt = $this->cdb->prepare($metrics_email_query);
                $metrics_email_status->status = $metrics_email_stmt->execute($metrics_email_query_params);
            } catch (PDOException $ex) {
                /* SQL - Error(s) */
                $metrics_email_status->status_code = $ex->getMessage();
                $metrics_email_status->status = false;
            }
        } else {
            $metrics_email_status->status_code = $this->init_db()->status_code;
            $metrics_email_status->status = false;
        }
        return $metrics_email_status;
    }

    /**
     * 
     * @param string $audit_username The user's username
     * @param string $audit_user_id The ID of the user
     * @param string $audit_level The level, "admin/"user"
     * @param string $audit_action Action types "add"/"edit"/"delete"
     * @param string $audit_area What section was the action done in.
     * @param string $audit_msg Message from the action
     * @return \StatusVO
     */
    function user_audit($audit_username, $audit_user_id, $audit_level, $audit_action, $audit_area, $audit_msg, $page, $status, $extended_status = "-") {
        $start = microtime(true);
        $user_audit_status = new StatusVO();

        if ($this->init_db()) {
            /* Check username */
            if ($audit_username) {
                $currentuser = $audit_username;
            } else {
                $currentuser = "no_user_logged_in";
            }
            /* Check user ID */
            if ($audit_user_id) {
                $user_id = $audit_user_id;
            } else {
                $user_id = "no_user_id";
            }
            /* SQL - Query */
            $user_audit_query = "
	INSERT INTO tbl_base_user_audit(username, username_id, session_id, user_ip, level, area, action, msg, browser_agent, sid, page, extended_status, status)
	VALUES (:username, :username_id, :session_id, :userip, :level, :area, :action, :msg, :browser_agent, :sid, :page, :extended_status, :status);";
            /* SQL - Params */
            $user_audit_query_params = array(
                ':username' => $currentuser,
                ':username_id' => $user_id,
                ':session_id' => session_id(),
                ':browser_agent' => $_SERVER['HTTP_USER_AGENT'],
                ':userip' => $this->get_client_ip(),
                ':level' => $audit_level,
                ':area' => $audit_area,
                ':action' => $audit_action,
                ':msg' => $audit_msg,
                ':sid' => microtime(true),
                ':page' => $page,
                ':extended_status' => $extended_status,
                ':status' => $status
            );

            /* SQL - Exec */
            try {
                $user_audit_stmt = $this->cdb->prepare($user_audit_query);
                $user_audit_status->status = $user_audit_stmt->execute($user_audit_query_params);
            } catch (PDOException $ex) {
                /* SQL - Error(s) */
                $user_audit_status->status_code = $ex->getMessage();
                $this->cdb->rollBack();
            }
        } else {
            $user_audit_status->status_code = $this->init_db()->status_code;
            $user_audit_status->status = false;
        }
        return $user_audit_status;
    }

    function __construct() {
        $this->database_settings = new DBVO();
        $this->ClassActions = new StatusVO();
    }

}
