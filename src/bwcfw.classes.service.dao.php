<?php

class DAO_Service {

    /**
     *
     * @var \PDO This the main DAO object 
     */
    var $DAO_Service;

    /**
     *
     * @var \StatusVO 
     */
    var $DAO_status;

    /**
     *
     * @var \DBVO 
     */
    private $DAO_Config;

    /**
     *
     * @var string This is the schema name that was used when we connected to the DB 
     */
    var $DAO_Schema;

    function checkDAO() {
        $datetime = "";
        $dao_test_connection_query = "SELECT now() as value from dual where 1 = :id";
        $dao_test_connection_query_params = array(
            ':id' => 1
        );
        $status = $this->DAO_Service->getAttribute(PDO::ATTR_CONNECTION_STATUS);
        /* SQL - Exec */
        try {
            $dao_test_connection_stmt = $this->DAO_Service->prepare($dao_test_connection_query);
            $dao_test_connection_stmt->execute($dao_test_connection_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->DAO_status->setStatus(false);
            $this->DAO_status->setStatusCode("dao_test_connection_stmt");
            $this->DAO_status->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->DAO_status->setLine($ex->getLine());
        }
        if ($this->DAO_status->getStatus()) {
            $dao_test_connection_row = $dao_test_connection_stmt->fetch();
            if ($dao_test_connection_row) {
                $datetime = $dao_test_connection_row["value"];
            }
        }
        $results = array("rstatus" => $this->DAO_status, "status" => $status, "datetime" => $datetime, "dbvo" => $this->DAO_Config);
        return $results;
    }

    /**
     * 
     * @return \StatusVO
     */
    function initDAO() {
        try {
            $this->DAO_Service = new PDO("mysql:host={$this->DAO_Config->getHost()};dbname={$this->DAO_Config->getDataBase()};charset={$this->DAO_Config->getCharset()}", $this->DAO_Config->getUserName(), $this->DAO_Config->getPassword(), $this->DAO_Config->getDataBaseOptions());
        } catch (PDOException $ex) {
            $this->DAO_status->setStatus(FALSE);
            $this->DAO_status->setStatusCode("init DAO");
            $this->DAO_status->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->DAO_status->setLine($ex->getLine());
        }
        if ($this->DAO_status->getStatus()) {
            $this->DAO_Service->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->DAO_Service->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->DAO_Schema = $this->DAO_Config->getDatabase();
        }
        return $this->DAO_status;
    }

    function __construct($init = TRUE) {
        $this->DAO_Config = new DBVO();
        $this->DAO_status = new StatusVO();
        if ($init) {
            $this->initDAO();
        }
    }

}
