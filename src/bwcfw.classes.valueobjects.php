<?php

/**
 * Status Value Object
 */
class StatusVO {

    /**
     * This function evaluates the variable @Status and returns a simple value
     * @return string 
     */
    public function getStatusStr() {
        return ($this->Status) ? "ok" : "error";
    }

    /**
     * 
     * @param boolean $Status This indicates if there is a problem or not
     */
    public function setStatus($Status) {
        $this->Status = $Status;
    }

    /**
     * 
     * @return boolean This indicates if there is a problem or not
     */
    public function getStatus() {
        return $this->Status;
    }

    /**
     * 
     * @param string $StatusCode This provides a more detailed status of what went wrong
     */
    public function setStatusCode($StatusCode) {
        $this->StatusCode = $StatusCode;
    }

    /**
     * 
     * @return string This provides a more detailed status of what went wrong
     */
    public function getStatusCode() {
        return $this->StatusCode;
    }

    /**
     * 
     * @param string $ExtendedStatusCode This provides a more detailed status of what went wrong
     */
    public function setExtendedStatusCode($ExtendedStatusCode) {
        $this->ExtendedStatusCode = $ExtendedStatusCode;
    }

    /**
     * 
     * @return string This provides a more detailed status of what went wrong
     */
    public function getExtendedStatusCode() {
        return $this->ExtendedStatusCode;
    }

    /**
     * 
     * @param string $Line This indicates the line where the error occured
     */
    public function setLine($Line) {
        $this->Line = $Line;
    }

    /**
     * 
     * @return string This indicates the line where the error occured
     */
    public function getLine() {
        return $this->Line;
    }

    public function __construct() {
        $this->Status = true;
        $this->StatusCode = "N/A";
        $this->ExtendedStatusCode = "N/A";
        $this->Line = "N/A";
    }

}

class FileRecordVO {

    /**
     *
     * @var string the name of the file that was called 
     */
    private $filename;

    /**
     *
     * @var string This is the path of where the file is located
     */
    private $filepath;

    /**
     *
     * @var string This is the file extention of where the file is located
     */
    private $fileext;

    /**
     *
     * @var string the name of the file that was called without the extention
     */
    private $filenamebase;

    /**
     *
     * @var array The min role required in order to use this page 
     */
    private $roles_required = array();

    /**
     *
     * @var string
     */
    private $section;

    /**
     *
     * @var string The min
     */
    private $level;

    /**
     *
     * @var string Chart/table/user 
     */
    private $area;

    /**
     *
     * @var string 
     */
    private $type;

    /**
     *
     * @var string add/edit/delete/get/post 
     */
    private $action;

    /**
     *
     * @var string Any optional parameters that were called 
     */
    private $params;

    /**
     *
     * @var boolean Set this 
     */
    private $issystemlevel;

    /**
     *
     * @var array 
     */
    private $pageinfoarray;

    /**
     *
     * @var \WebPageVO 
     */
    public $PageWebStatus;

    /**
     *
     * @var type 
     */
    public $PageActions;

    /**
     * 
     * @param string $name This sets the internal variable
     */
    function setName($name) {
        $this->filename = basename($name);
        $this->filepath = dirname($name);
        $this->fileext = pathinfo($name, PATHINFO_EXTENSION);
        $this->filenamebase = basename($name, "." . $this->fileext);
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getFileName() {
        return $this->filename;
    }

    /**
     * 
     * @return string returns the path of the file
     */
    function getFilePath() {
        return $this->filepath;
    }

    /**
     * 
     * @param string $role This sets the internal variable
     */
    function setRolesRequired($role) {
        array_push($this->roles_required, $role);
    }

    /**
     * 
     * @return array This gets the internal variable
     */
    function getRolesRequired() {
        return $this->roles_required;
    }

    function setSystemLevel($systemlevel) {
        $this->issystemlevel = $systemlevel;
    }

    /**
     * 
     * @param string $systemlevel This would be the user's Company_id
     * @return boolean 
     */
    function checkSystemLevel($systemlevel) {
        if ($this->issystemlevel) {
            return ($systemlevel === '1') ? TRUE : FALSE;
        }
        return TRUE;
    }

    /**
     * 
     * @param array $roles This contains an array with the list of roles that needs to be checked
     * @param string $akey This optional parameter is in case the array been sent has sub keys
     * @return boolean True if the roles overlap, false if they do not
     */
    function hasRoles($roles, $akey = null) {
        if ($akey) {
            $tmp_array = array();
            foreach ($roles as $item) {
                array_push($tmp_array, trim($item[$akey]));
            }
            $result = array_intersect(array_map('strtolower', $this->roles_required), array_map('strtolower', $tmp_array));
        } else {
            $result = array_intersect(array_map('strtolower', $this->roles_required), array_map('strtolower', $roles));
        }
        return ($result) ? true : false;
    }

    /**
     * 
     * @param array $roles This contains an array with the list of roles that needs to be checked
     * @param string $required_role  This is the var which contains the singular role that needs to be checked.
     * @param type $akey  This optional parameter is in case the array been sent has sub keys
     * @return boolean True if the role overlaps, false if it does not
     */
    function hasRoleInArray($roles, $required_role, $akey = null) {
        $req_role_array = array();
        array_push($req_role_array, trim($required_role));
        if ($akey) {
            $tmp_array = array();
            foreach ($roles as $item) {
                array_push($tmp_array, trim($item[$akey]));
            }
            $result = array_intersect(array_map('strtolower', $req_role_array), array_map('strtolower', $tmp_array));
        } else {
            $result = array_intersect(array_map('strtolower', $req_role_array), array_map('strtolower', $roles));
        }
        return ($result) ? true : false;
    }

    /**
     * 
     * @param string $section This is the section of the application that is been used. 
     */
    function setSection($section) {
        $this->section = $section;
    }

    /**
     * 
     * @return string
     */
    function getSection() {
        return $this->section;
    }

    /**
     * 
     * @param string $level This sets the internal variable
     */
    function setLevel($level) {
        $this->level = $level;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getParams() {
        return $this->params;
    }

    /**
     * 
     * @param string $params This sets the internal variable
     */
    function setParams($params) {
        $this->params = $params;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getLevel() {
        return $this->level;
    }

    /**
     * 
     * @param string $area This sets the internal variable
     */
    function setArea($area) {
        $this->area = $area;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getType() {
        return $this->type;
    }

    /**
     * 
     * @param string $type This sets the internal variable
     */
    function setType($type) {
        $this->type = $type;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getArea() {
        return $this->area;
    }

    /**
     * 
     * @param string $action This sets the internal variable
     */
    function setAction($action) {
        $this->action = $action;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getAction() {
        return $this->action;
    }

    /**
     * 
     * @return string
     */
    function getPageWebStatusDebugMsg() {
        return '[' . $this->getFileName() . '] ' . 'Status: ' . $this->PageWebStatus->getAPIResponseStatus() . '; http_response_code: ' . $this->PageWebStatus->getHTTPResponseCode() . '; Status: ' . $this->PageWebStatus->getAPIResponseMessage() . '; Message: ' . $this->PageActions->StatusCode . '; Extended Status: ' . $this->PageActions->extended_StatusCode;
    }

    /**
     * 
     * @return type
     */
    function getPageWebStatusInfoMsg() {
        return $this->action;
    }

    /**
     *
     * @var type 
     */
    var $ClientIP;

    /**
     * 
     * @return type
     */
    function getClientIP() {
        return $this->ClientIP;
    }

    /**
     * 
     */
    function setClientIP() {
        $this->ClientIP = getenv('REMOTE_ADDR');
    }

    function setRoleRequired($role) {
        array_push($this->roles_required, trim(strtoupper($role)));
    }

    private function packPageInfo() {
        $this->pageinfoarray = array("section" => $this->getSection(),
            "area" => $this->getArea(),
            "level" => $this->getLevel(),
            "type" => $this->getType(),
            "action" => $this->getAction(),
            "pagename" => $this->getFileName());
    }

    /**
     * 
     * @return array
     */
    function getPageInfo() {
        return $this->pageinfoarray;
    }

    private function postPageAudit() {
        /* Build the URL */
        $query_string = 'username=' . urlencode($foo);
        $query_string .= '&uid=' . urlencode($uid);
        $query_string .= '&level=' . urlencode($this->getLevel());
        $query_string .= '&action=' . urlencode($this->getAction());
        $query_string .= '&area=' . urlencode($this->getArea());
        $query_string .= '&type=' . urlencode($this->getType());
        $query_string .= '&file=' . urlencode($this->getFileName());

        $query_string .= '&file=' . urlencode($this->getFileName());
        $query_string .= '&file=' . urlencode($this->getFileName());
        //$parts = parse_url($url);

        $fp = fsockopen("127.0.0.1", 80);

        if (!$fp) {
            return false;
        } else {
            $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
            $out .= "Host: " . $parts['host'] . "\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: " . strlen($parts['query']) . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            if (isset($parts['query']))
                $out .= $parts['query'];

            fwrite($fp, $out);
            fclose($fp);
            return true;
        }
    }

    function setFileRecord($fn = FALSE) {
        if ($fn) {
            $this->setName($fn);
            $fnb = explode(".", $this->filenamebase);
            if (sizeof($fnb) == 5) {
                $this->section = $fnb[0];
                $this->area = $fnb[1];
                $this->level = $fnb[2];
                $this->type = $fnb[3];
                $this->action = $fnb[4];
                $this->setRoleRequired($fnb[2]);
            } else {
                $this->section = "general";
                $this->area = "global";
                $this->level = "n/a";
                $this->type = "index";
                $this->action = "display";
            }
            $this->packPageInfo();
        }
    }

    /**
     * 
     */
    public function __construct($fn = FALSE, $isSystemLevel = FALSE) {
        $this->PageWebStatus = new WebPageVO();
        $this->PageActions = new StatusVO();
        $this->setRoleRequired("admin");
        $this->issystemlevel = $isSystemLevel;
        $this->setClientIP();
        $this->setFileRecord($fn);
    }

}

class WebPageVO {

    /**
     *
     * @var array 
     */
    /* var $APIResponse = array('status' => 0,
      'message' => 'message',
      'data' => 'data'
      ); */
    var $APIResponse = array('status' => 0,
        'message' => 'message',
        'data' => ''
    );

    /**
     *
     * @var type 
     */
    var $HTTPResponseCode;

    /**
     * 
     * @return type
     */
    function getAPIStatusCode() {
        return $this->APIStatusCode;
    }

    /**
     * 
     * @return type
     */
    function getAPIStatusMsg() {
        return $this->APIStatusMsg;
    }

    /**
     * 
     * @return type
     */
    function getAPIData() {
        return $this->APIData;
    }

    /**
     * 
     * @return type
     */
    function getAPIResponseCode($id) {
        return $this->APIResponseCode[$id];
    }

    /**
     * 
     * @return type
     */
    function getHTTPResponseCode() {
        return $this->HTTPResponseCode;
    }

    /**
     * 
     * @return type
     */
    function getAPIResponse() {
        return $this->APIResponse;
    }

    /**
     * 
     * @param type $APIStatusCode
     */
    function setAPIStatusCode($APIStatusCode) {
        $this->APIStatusCode = $APIStatusCode;
    }

    /**
     * 
     * @param type $HTTPResponseCode
     */
    function setHTTPResponseCode($HTTPResponseCode) {
        $this->HTTPResponseCode = $HTTPResponseCode;
    }

    /**
     * 
     * @param integer $APIResponse
     */
    function setAPIResponse($APIResponse = 1) {
        $this->APIResponse['status'] = $this->APIResponseCode[$APIResponse]['HTTP Response'];
        $this->APIResponse['message'] = $this->APIResponseCode[$APIResponse]['Message'];
        $this->HTTPResponseCode = $this->HTTPResponseCodes[$this->APIResponse['status']];
        switch ($APIResponse) {
            /* New entries */
            case 1:
                /*  so far do nothing */
                break;
            /* Default value */
            default:
                $this->APIResponse['data'] = $this->APIResponseCode[$APIResponse]['Data'];
        }
    }

    /**
     * 
     * @return type
     */
    function getAPIResponseStatus() {
        return $this->APIResponse['status'];
    }

    /**
     * 
     * @return type
     */
    function getAPIResponseMessage() {
        return $this->APIResponse['message'];
    }

    function setAPIResponseData($data) {
        $this->APIResponse['data'] = $data;
    }

    function setAPIResponseFeedback($feedback) {
        $this->APIResponse['feedback'] = $feedback;
    }

    /**
     * 
     * @return type
     */
    function getAPIResponseData() {
        return $this->APIResponse['data'];
    }

    /**
     * 
     */
    function __construct() {
        $this->APIResponseCode = array(
            0 => array(
                'HTTP Response' => 400,
                'Message' => 'Unknown Error',
                'Data' => 'The server cannot or will not process the request due to an apparent client error.'
            ),
            1 => array(
                'HTTP Response' => 200,
                'Message' => 'Success',
                'Data' => ''
            ),
            2 => array(
                'HTTP Response' => 403,
                'Message' => 'HTTPS Required',
                'Data' => 'The request was a valid request, but the server is refusing to respond to it. The user might be logged in but does not have the necessary permissions for the resource.'
            ),
            3 => array(
                'HTTP Response' => 401,
                'Message' => 'Authentication Required',
                'Data' => 'User does not have sufficient permissions'
            ),
            4 => array(
                'HTTP Response' => 401,
                'Message' => 'Authentication Failed',
                'Data' => 'Unauthorized'
            ),
            5 => array(
                'HTTP Response' => 404,
                'Message' => 'Invalid Request',
                'Data' => 'The requested resource could not be found but may be available in the future'
            ),
            6 => array(
                'HTTP Response' => 400,
                'Message' => 'Invalid Response Format',
                'Data' => 'The server cannot or will not process the request due to an apparent client error'
            ),
            7 => array(
                'HTTP Response' => 400,
                'Message' => 'Invalid Request',
                'Data' => 'Invalid parameters'
            ),
            8 => array(
                'HTTP Response' => 204,
                'Message' => 'No Content',
                'Data' => 'The server successfully processed the request and is not returning any content'
            ),
            9 => array(
                'HTTP Response' => 405,
                'Message' => 'Method Not Allowed',
                'Data' => ''
            ),
            10 => array(
                'HTTP Response' => 500,
                'Message' => 'Internal Server Error',
                'Data' => 'The server lacks the ability to fulfill the request.'
            )
        );
        $this->HTTPResponseCodes = array(
            200 => 'OK',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
        $this->setAPIResponse(1);
    }

}
