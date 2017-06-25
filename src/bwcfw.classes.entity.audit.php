<?php

class audit_handover {

    /**
     *
     * @var array 
     */
    private $post_params;

    /**
     * We package the information as we need it and then send it off to the fire and forget solution
     * 
     * @param \$_SERVER $CServerArray
     * @param \$_SESSION $CSession
     * @param \StatusVO $PageActions
     * @param string $sessionID
     * @param array $PageInfo
     */
    function page_metric($CServerArray, $CSession, $PageActions, $sessionID, $PageInfo) {
        if (array_key_exists('username', $CSession)) {
            $this->post_params["username"] = $CSession["username"];
        } else {
            $this->post_params["username"] = "no_user";
        }
        if (array_key_exists('id', $CSession)) {
            $this->post_params["user_id"] = $CSession["id"];
        } else {
            $this->post_params["user_id"] = 0;
        }
        $this->post_params["session_id"] = $sessionID;
        $this->post_params["user_ip"] = $CServerArray["REMOTE_ADDR"];
        $this->post_params["section"] = $PageInfo["section"];
        $this->post_params["area"] = $PageInfo["area"];
        $this->post_params["level"] = $PageInfo["level"];
        $this->post_params["type"] = $PageInfo["type"];
        $this->post_params["action"] = $PageInfo["action"];
        $this->post_params["page"] = $PageInfo["pagename"];
        $this->post_params["status"] = ($PageActions->Status) ? "ok" : "error";
        $this->post_params["msg"] = $PageActions->StatusCode;
        $this->post_params["browser_agent"] = $CServerArray['HTTP_USER_AGENT'];
        $this->post_params["extended_status"] = $PageActions->ExtendedStatusCode;
        $this->post_params["line"] = $PageActions->Line;
        $this->page_metric_post();
    }

    /**
     * Function for fire and forget items to the auditing sub system
     */
    private function page_metric_post() {
        foreach ($this->post_params as $key => &$val) {
            $post_params[] = $key . '=' . urlencode($val);
        }
        $url_host = 'bwcfw.local';
        $url_path = '/bwcfw/audit/audit_page.php';
        $url_port = '443';
        $post_string = implode('&', $post_params);
        $output = "POST " . $url_path . " HTTP/1.1\r\n";
        $output .= "Host: " . $url_host . "\r\n";
        $output .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $output .= "Content-Length: " . strlen($post_string) . "\r\n";
        $output .= "Connection: Close\r\n\r\n";
        $output .= isset($post_string) ? $post_string : '';
        $fp = @fsockopen($url_host, $url_port, $errno, $errstr, 0.01);
        /*        $fp = fsockopen($url_host, $url_port, $errno, $errstr, 0.01); */
        fwrite($fp, $output);
        fclose($fp);
    }

    public function __construct() {
        
    }

}

class audit_entity {

    /**
     *
     * @var \PDO 
     */
    var $cdb;

    /**
     *
     * @var \$_SERVER 
     */
    var $ServerArray;

    /**
     * Get the server and execution environment information
     */
    function setServerEnvironment($value) {
        $this->ServerArray = $value;
    }

    /**
     * 
     * @return array Return the server and execution environment information
     */
    function getServerEnvironment() {
        return $this->ServerArray;
    }

    /**
     * 
     * @return string Return the HTTP User Agent string
     */
    function getHTTPUserAgent() {
        return $this->ServerArray['HTTP_USER_AGENT'];
    }

    /**
     * 
     * @return string IP address of the user
     */
    function getClientIP() {
        if (isset($this->ServerArray)) {
            if (isset($this->ServerArray["HTTP_X_FORWARDED_FOR"])) {
                return $this->ServerArray["HTTP_X_FORWARDED_FOR"];
            }
            if (isset($this->ServerArray["HTTP_CLIENT_IP"])) {
                return $this->ServerArray["HTTP_CLIENT_IP"];
            }
            return $this->ServerArray["REMOTE_ADDR"];
        }
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        }
        if (getenv('HTTP_CLIENT_IP')) {
            return getenv('HTTP_CLIENT_IP');
        }
        return getenv('REMOTE_ADDR');
    }

    /**
     * This function initialises the DAO sub system
     */
    private function initDAO() {
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
     * @param type $sessionID
     * @param type $level
     * @param type $action
     * @param type $area
     * @param type $msg

     */
    function DAOUserAudit($username, $user_id, $session_id, $user_ip, $section, $level, $area, $type, $action, $status, $msg, $browser_agent, $page, $extended_status, $line) {
        if ($this->cdb) {
            /* SQL - Query */
            $insert_user_audit_query = "INSERT INTO tbl_base_user_audit(username, username_id, session_id, user_ip, section, level, area, type, action, status, msg, browser_agent, page, extended_status, line, sid) VALUES (:username, :username_id, :session_id, :user_ip, :section, :level, :area, :type, :action, :status, :msg, :browser_agent, :page, :extended_status, :line, :sid);";
            /* SQL - Params */
            $insert_user_audit_query_params = array(
                ':username' => $username,
                ':username_id' => $user_id,
                ':session_id' => $session_id,
                ':user_ip' => $user_ip,
                ':section' => $section,
                ':level' => $level,
                ':type' => $type,
                ':area' => $area,
                ':action' => $action,
                ':status' => $status,
                ':msg' => $msg,
                ':browser_agent' => $browser_agent,
                ':page' => $page,
                ':extended_status' => $extended_status,
                ':line' => $line,
                ':sid' => microtime(true)
            );
            /* SQL - Exec */
            try {
                $insert_user_audit_query_stmt = $this->cdb->prepare($insert_user_audit_query);
                $insert_user_audit_query_stmt->execute($insert_user_audit_query_params);
            } catch (PDOException $ex) {
                $this->ClassActions->setStatus(FALSE);
                $this->ClassActions->setStatusCode("insert_user_audit_query_stmt");
                $this->ClassActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
                $this->ClassActions->setLine($ex->getLine());
            }
        }
    }

    /**
     * 
     * @param string $session This is the $_session array
     * @param string $action This indicate login/logout
     * @param string $sessionid This is the session_id() varible 
     */
    function DAOUserSession($session, $action, $sessionid) {
        if ($this->cdb) {
            /* SQL - Query */
            $insert_user_session_audit_query = "INSERT INTO tbl_base_sessions(session_id, username_id, username, firstname, surname, ip_remote_addr, action, browser_agent, sid) VALUES (:session_id, :username_id, :username, :firstname, :surname, :ip_remote_addr, :action, :browser_agent, :sid);";
            /* SQL - Params */
            $insert_user_session_audit_query_params = array(
                ':session_id' => $sessionid,
                ':username_id' => $session["id"],
                ':username' => $session["username"],
                ':firstname' => $session["firstName"],
                ':surname' => $session["lastName"],
                ':ip_remote_addr' => $this->getClientIP(),
                ':action' => $action,
                ':browser_agent' => $this->getHTTPUserAgent(),
                ':sid' => microtime(true)
            );
            /* SQL - Exec */
            try {
                $insert_user_session_audit_query_stmt = $this->cdb->prepare($insert_user_session_audit_query);
                $insert_user_session_audit_query_stmt->execute($insert_user_session_audit_query_params);
            } catch (PDOException $ex) {
                $this->ClassActions->setStatus(FALSE);
                $this->ClassActions->setStatusCode("insert_user_session_audit_query");
                $this->ClassActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
                $this->ClassActions->setLine($ex->getLine());
            }
        }
        return $this->ClassActions;
    }

    function __construct($ServerArray = null) {
        $this->database_settings = new DBVO();
        $this->ClassActions = new StatusVO();
        $this->initDAO();
        $this->mailer_settings = new SMTPVO();
        if ($ServerArray) {
            $this->setServerEnvironment($ServerArray);
        }
    }

}
