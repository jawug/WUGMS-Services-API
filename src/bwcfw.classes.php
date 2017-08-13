<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bwcfw
 *
 * @author ThatOneNeji
 */

/**
 * This is the main pattern used for const across the project
 */
class BWCFWDecoratorPattern {

    private $server_base;

    /**
     *
     * @var string This is the external URL 
     */
    private $baseurl;

    /**
     *
     * @var string This is the internal URL 
     */
    private $baseurlinternal;

    /**
     *
     * @var string This is the footer seen at the bottom of the pages 
     */
    private $footercopy;

    /**
     *
     * @var string This is the basic site name 
     */
    private $homeURL;

    /**
     *
     * @var string This is the company's name in long format 
     */
    private $longname;

    /**
     *
     * @var string This is the company's name in short format 
     */
    private $shortname;

    /**
     *
     * @var string This is the admin's email address 
     */
    private $adminemail;

    /**
     *
     * @var string This is the long site name 
     */
    private $sitetitle;

    /**
     * 
     */
    private $log_path;

    /**
     * 
     * @return string This gets the internal variable
     */
    function getBaseURL() {
        return $this->baseurl;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setBaseURL($value) {
        $this->baseurl = $value;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getBaseURLInternal() {
        return $this->baseurlinternal;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setBaseURLInternal($value) {
        $this->baseurlinternal = $value;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getHomeURL() {
        return $this->homeURL;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setHomeURL($value) {
        $this->homeURL = $value;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getLongName() {
        return $this->longname;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setLongName($value) {
        $this->longname = $value;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getShortName() {
        return $this->ShortName;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setShortName($value) {
        $this->ShortName = $value;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getAdmineMail() {
        return $this->adminemail;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setAdmineMail($value) {
        $this->adminemail = $value;
    }

    /**
     * 
     * @return string This gets the internal variable
     */
    function getSiteTitle() {
        return $this->sitetitle;
    }

    /**
     * 
     * @param string $value This sets the internal variable
     */
    function setSiteTitle($value) {
        $this->sitetitle = $value;
    }

    /**
     * 
     * @return string This returns the file base for where all files are located
     */
    function getServerBase() {
        return $this->server_base;
    }

    /**
     * 
     * @return string the suffix of the logging path
     */
    function getLogPath() {
        return $this->log_path;
    }

    /**
     * 
     * @return String Footer copyright
     */
    function getFooterCopy() {
        return $this->footer_copy;
    }

    /**
     * 
     * @param string $config
     */
    function setConfigPath($config) {
        $this->config_path = $config;
    }

    /**
     * 
     * @return string
     */
    function getConfigPath() {
        return $this->config_path;
    }

    /**
     * 
     * @param string $php_min
     */
    function setPHPMinVersion($php_min) {
        $this->php_min = $php_min;
    }

    /**
     * 
     * @param string $php_max
     */
    function setPHPMaxVersion($php_max) {
        $this->php_max = $php_max;
    }

    /**
     * 
     * @return string
     */
    function getPHPMinVersion() {
        return $this->php_min;
    }

    /**
     * 
     * @return string
     */
    function getPHPMaxVersion() {
        return $this->php_max;
    }

    /**
     * Main constructor function
     */
    function __construct() {
        date_default_timezone_set('Africa/Johannesburg');
        //$this->server_base = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        $this->server_base = __DIR__;
        $this->log_path = $this->server_base . "/../logging/";
        $this->config_path = $this->server_base . "/../config/";
        $this->footer_copy = "&copy; " . $this->getLongName() . " " . date("Y");
        $string = file_get_contents($this->config_path . "settings.json");
        $json_a = json_decode($string, true);
        $this->setPHPMinVersion($json_a["MiscVO"]["php_min"]);
        $this->setPHPMaxVersion($json_a["MiscVO"]["php_max"]);
        $this->setAdmineMail($json_a["application"]["adminemail"]);
        $this->setBaseURL($json_a["application"]["baseurl"]);
        $this->setBaseURLInternal($json_a["application"]["baseurlinternal"]);
        $this->setHomeURL($json_a["application"]["homeURL"]);
        $this->setLongName($json_a["application"]["longname"]);
        $this->setShortName($json_a["application"]["shortname"]);
        $this->setSiteTitle($json_a["application"]["sitetitle"]);
    }

}

class DBVO {

    /**
     *
     * @var string Database parameter - username
     */
    private $username = "";

    /**
     *
     * @var string Database parameter - password
     */
    private $password = "";

    /**
     *
     * @var string Database parameter - hostname/IP. try to always use an IP. Hostnames take time to resolve
     */
    private $host = "";

    /**
     *
     * @var integer Database parameter - port
     */
    private $port = 3306;

    /**
     *
     * @var string Database parameter - database name
     */
    private $database = "";

    /**
     *
     * @var array Database parameter - PDO options
     */
    private $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    /**
     *
     * @var string Database parameter - character set used 
     */
    private $charset = "utf8";

    function getUserName() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getHost() {
        return $this->host;
    }

    function getPort() {
        return $this->port;
    }

    function getDatabase() {
        return $this->database;
    }

    function getDataBaseOptions() {
        return $this->options;
    }

    function getCharset() {
        return $this->charset;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setHost($host) {
        $this->host = $host;
    }

    function setPort($port) {
        $this->port = $port;
    }

    function setDatabase($database) {
        $this->database = $database;
    }

    function setOptions($options) {
        $this->options = $options;
    }

    function setCharset($charset) {
        $this->charset = $charset;
    }

    function __construct() {
        $this->server_base = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
        $this->config_path = $this->server_base . "/../config/";
        $string = file_get_contents($this->config_path . "settings.json");
        $json_a = json_decode($string, true);
        $this->setUsername($json_a["DBVO"]["username"]);
        $this->setPassword($json_a["DBVO"]["password"]);
        $this->setDatabase($json_a["DBVO"]["schema"]);
        $this->setHost($json_a["DBVO"]["host"]);
        $this->setPort($json_a["DBVO"]["port"]);
    }

}

/**
 * 
 */
class LoggingService extends BWCFWDecoratorPattern {

    /**
     *
     * @var \StatusVO  
     */
    public $PageActions;

    /**
     *
     * @var \FileRecordVO 
     */
    public $PageData;

    /**
     *
     * @var \Logger 
     */
    public $logger;

    /**
     *
     * @var integer The logging level to be used 
     */
    private $logging_level;

    /**
     * 
     */
    public function LogEntry($level) {
        switch ($level) {
            case 1:
                /* info */
                $this->logger->info('[' . $this->PageData->getFileName() . '] -> ' . $this->PageActions->getStatusStr() . ' ;; Status: ' . $this->PageActions->getStatusCode());
                break;
            case 2:
                /* debug */
                $this->logger->debug('[' . $this->PageData->getFileName() . '] -> ' . $this->PageActions->getStatusStr() . ' ;; Status: ' . $this->PageActions->getStatusCode() . ' ;; Extended Status: ' . $this->PageActions->getExtendedStatusCode() . ' ;; Line: ' . $this->PageActions->getLine());
                break;
            case 3:
                /* error */
                $this->logger->error('[' . $this->PageData->getFileName() . '] -> ' . $this->PageActions->getStatusStr() . ' ;; Status: ' . $this->PageActions->getStatusCode() . ' ;; Extended Status: ' . $this->PageActions->getExtendedStatusCode() . ' ;; Line: ' . $this->PageActions->getLine());
                break;
            default:
                /* debug */
                $this->logger->debug('[' . $this->PageData->getFileName() . '] -> ' . $this->PageActions->getStatusStr() . ' ;; Status: ' . $this->PageActions->getStatusCode() . ' ;; Extended Status: ' . $this->PageActions->getExtendedStatusCode() . ' ;; Line: ' . $this->PageActions->getLine());
        }
    }

    /**
     *
     * @var \DAO_Service 
     */
    var $DAO_Service;

    /**
     *
     * @var \audit_handover 
     */
    var $page_metric;

    /**
     * This starts up the logging sub section
     */
    public function __construct($fn = FALSE, $isSystemLevel = FALSE, $isEnableDAO = TRUE) {
        parent::__construct();
        require_once('../vendor/log4php/Logger.php');
        Logger::configure(array(
            'rootLogger' => array(
                'appenders' => array('default'),
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderRollingFile',
                    'layout' => array(
                        'class' => 'LoggerLayoutPattern',
                        'params' => array(
                            'conversionPattern' => '%date{Y-m-d H:i:s,u} [%logger] %-5level %msg%n'
                        )
                    ),
                    'params' => array(
                        'file' => $this->getLogPath() . strtolower($this->getShortName()) . '.log',
                        'append' => true,
                        'maxFileSize' => '1MB',
                        'maxBackupIndex' => 10
                    )
                )
            )
        ));
        $this->PageActions = new StatusVO();
        $this->PageData = new FileRecordVO($fn, $isSystemLevel);
        $this->logger = Logger::getLogger($this->getShortName());
        $this->DAO_Service = new DAO_Service($isEnableDAO);
        $this->page_metric = new audit_handover();
    }

}

/*  test   */

final class Email {

    private $email;

    private function __construct(string $email) {
        $this->ensureIsValidEmail($email);

        $this->email = $email;
    }

    public static function fromString(string $email): self {
        return new self($email);
    }

    public function __toString(): string {
        return $this->email;
    }

    private function ensureIsValidEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
            sprintf(
                    '"%s" is not a valid email address', $email
            )
            );
        }
    }

}

/*  test   */

include_once 'bwcfw.classes.valueobjects.php';
include_once 'bwcfw.classes.entity.validation.php';
include_once 'bwcfw.classes.service.dao.php';
include_once 'bwcfw.classes.service.util.php';
include_once 'bwcfw.classes.service.router.php';
