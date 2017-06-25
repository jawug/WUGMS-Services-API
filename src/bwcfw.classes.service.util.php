<?php

class bwcfw_util {

    /**
     *
     * @var \DAO_Service
     */
    private $DAO;

    /**
     *
     * @var \BWCFWDecoratorPattern 
     */
    private $DP;

    /**
     *
     * @var array 
     */
    private $DAOTableInformation;

    /**
     *
     * @var datetime 
     */
    private $rdate;

    /**
     *
     * @var datetime 
     */
    private $pdate;

    /**
     *
     * @var \LoggingService 
     */
    var $class_data;

    /**
     * 
     * @return datetime
     */
    function getRDate() {
        return $this->rdate;
    }

    /**
     * 
     * @return datetime
     */
    function getPDate() {
        return $this->pdate;
    }

    /**
     * 
     */
    private function setProcessingDates() {
        $d = new DateTime();
        $e = (new \DateTime())->modify('-1 hour');
        $this->rdate = $d->format('Y-m-d H:00:00');
        $this->pdate = $e->format('Y-m-d H:00:00');
    }

    function checkMySQL() {
        $attributes = array(
            "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
            "ORACLE_NULLS", "PERSISTENT", "SERVER_INFO", "SERVER_VERSION");

        foreach ($attributes as $val) {
            $checkMySQL["$val"] = $this->DAO->DAO_Service->getAttribute(constant("PDO::ATTR_$val"));
        }
        return $checkMySQL;
    }

    function checkMySQLTables() {
        /* SQL - Query */
        $bwcfw_show_table_status_query = "SHOW TABLE STATUS;";
        /* SQL - Exec */
        try {
            $bwcfw_show_table_status_stmt = $this->DAO->DAO_Service->prepare($bwcfw_show_table_status_query);
            $bwcfw_show_table_status_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_show_table_status_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        $bwcfw_show_table_status_row = $bwcfw_show_table_status_stmt->fetchAll();
        if ($bwcfw_show_table_status_row) {
            return $bwcfw_show_table_status_row;
        }
    }

    function checkPageHits() {
        /* SQL - Query */
        $bwcfw_page_hits_last_day_query = "
            SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
            a.page,
            count(*) AS hits
            FROM tbl_base_user_audit a
            WHERE session_date > now() - INTERVAL 1 DAY AND a.page IS NOT NULL
            GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.page
            ORDER BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.page;";
        /* SQL - Exec */
        try {
            $bwcfw_page_hits_last_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_hits_last_day_query);
            $bwcfw_page_hits_last_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_hits_last_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_hits_last_day_row = $bwcfw_page_hits_last_day_stmt->fetchAll();
        if ($bwcfw_page_hits_last_day_row) {
            return $bwcfw_page_hits_last_day_row;
        }
    }

    function getPageHitsTop10CurrentHour($limit = 10) {
        /* SQL - Query */
        $bwcfw_page_hits_top10_last_hour_query = "SELECT a.page, a.hits FROM (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date, a.page, count(*) AS hits FROM tbl_base_user_audit a WHERE     session_date >= DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') AND a.page IS NOT NULL GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.page ORDER BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.page) AS a ORDER BY a.hits DESC, a.page LIMIT :limit;";
        /* SQL - Exec */
        try {
            $bwcfw_page_hits_top10_last_hour_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_hits_top10_last_hour_query);
            $bwcfw_page_hits_top10_last_hour_stmt->bindValue(':limit', intval($limit), PDO::PARAM_INT);
            $bwcfw_page_hits_top10_last_hour_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_hits_top10_last_hour_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_hits_top10_last_hour_row = $bwcfw_page_hits_top10_last_hour_stmt->fetchAll();
        if ($bwcfw_page_hits_top10_last_hour_row) {
            $rows = array();
            foreach ($bwcfw_page_hits_top10_last_hour_row as $x) {
                $row[0] = $x['page'];
                $row[1] = $x['hits'];
                array_push($rows, $row);
            }
            return $rows;
        }
    }

    function getPageHitsCurrentDay($limit = 25) {
        /* SQL - Query */
        $bwcfw_page_hits_current_day_query = "SELECT a.page, a.hits FROM (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date, a.page, count(*) AS hits FROM tbl_base_user_audit a WHERE     session_date >= DATE_FORMAT(now()- interval 24 hour, '%Y-%m-%d %H:00:00') AND a.page IS NOT NULL GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.page ORDER BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.page) AS a ORDER BY a.hits DESC, a.page LIMIT :limit;";
        /* SQL - Exec */
        try {
            $bwcfw_page_hits_current_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_hits_current_day_query);
            $bwcfw_page_hits_current_day_stmt->bindValue(':limit', intval($limit), PDO::PARAM_INT);
            $bwcfw_page_hits_current_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(FALSE);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_hits_current_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_hits_current_day_row = $bwcfw_page_hits_current_day_stmt->fetchAll();
        if ($bwcfw_page_hits_current_day_row) {
            $rows = array();
            foreach ($bwcfw_page_hits_current_day_row as $x) {
                $row[0] = $x['page'];
                $row[1] = $x['hits'];
                array_push($rows, $row);
            }
            return $rows;
        }
    }

    function getStatusRollingWindowData($area, $param, $interval_length = 24) {
        switch ($area) {
            /* New entries */
            case 'page_hits':
                /* SQL - Query */
                $bwcfw_status_rolling_window_data_query = "
SELECT round(((UNIX_TIMESTAMP(x.td) + 7200) * 1000), 0) idate,
       ifnull(y.hits, 0) AS hits
  FROM (SELECT x.td
          FROM (SELECT date_sub(DATE_FORMAT(now(), '%Y-%m-%d %H:00:00'), INTERVAL t.hour_val * 10 + u.hour_val HOUR) AS td
                  FROM tbl_base_hour u JOIN tbl_base_hour t
                 WHERE t.hour_val * 10 + u.hour_val < 24) AS x
        GROUP BY x.td
        ORDER BY x.td) AS x
       LEFT JOIN
       (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
               count(*) AS hits
          FROM tbl_base_user_audit a
         WHERE     session_date >= DATE_FORMAT(now() - INTERVAL :interval_length HOUR, '%Y-%m-%d %H:00:00')
               AND upper(a.page) = :param
        GROUP BY api_date, a.page
        ORDER BY api_date, a.page) AS y
          ON x.td = y.api_date
ORDER BY x.td;";
                $bwcfw_status_rolling_window_data_query_params = array(
                    ':param' => strtoupper($param),
                    ':interval_length' => $interval_length
                );
                break;

            case 'type_actions':
                /* SQL - Query */
                $bwcfw_status_rolling_window_data_query = "
SELECT round(((UNIX_TIMESTAMP(x.td) + 7200) * 1000), 0) idate,
       ifnull(y.hits, 0) AS hits
  FROM (SELECT x.td
          FROM (SELECT date_sub(DATE_FORMAT(now(), '%Y-%m-%d %H:00:00'),
                                INTERVAL t.hour_val * 10 + u.hour_val HOUR)
                          AS td
                  FROM tbl_base_hour u JOIN tbl_base_hour t
                 WHERE t.hour_val * 10 + u.hour_val < 24) AS x
        GROUP BY x.td
        ORDER BY x.td) AS x
       LEFT JOIN
       (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
       count(*) AS hits
  FROM tbl_base_user_audit a
 WHERE     a.session_date >=
              DATE_FORMAT(now() - INTERVAL :interval_length HOUR, '%Y-%m-%d %H:00:00')
       AND a.page IS NOT NULL
       AND upper(a.type) = :type
       AND upper(a.action) = :action
GROUP BY api_date, a.type, a.action
ORDER BY api_date, a.type, a.action) AS y
          ON x.td = y.api_date
ORDER BY x.td;";
                $bwcfw_status_rolling_window_data_query_params = array(
                    ':type' => strtoupper($param[0]),
                    ':action' => strtoupper($param[1]),
                    ':interval_length' => $interval_length
                );
                break;

            case 'user_actions':
                /* SQL - Query */
                $bwcfw_status_rolling_window_data_query = "
SELECT round(((UNIX_TIMESTAMP(x.td) + 7200) * 1000), 0) idate,
       ifnull(y.hits, 0) AS hits
  FROM (SELECT x.td
          FROM (SELECT date_sub(DATE_FORMAT(now(), '%Y-%m-%d %H:00:00'),
                                INTERVAL t.hour_val * 10 + u.hour_val HOUR)
                          AS td
                  FROM tbl_base_hour u JOIN tbl_base_hour t
                 WHERE t.hour_val * 10 + u.hour_val < 24) AS x
        GROUP BY x.td
        ORDER BY x.td) AS x
       LEFT JOIN
       (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
               count(*) AS hits
          FROM tbl_base_user_audit a
         WHERE     a.session_date >=
                      DATE_FORMAT(now() - INTERVAL :interval_length HOUR,
                                  '%Y-%m-%d %H:00:00')
               AND a.username_id = :id
        GROUP BY api_date, a.username_id) AS y
          ON x.td = y.api_date
ORDER BY x.td;";
                $bwcfw_status_rolling_window_data_query_params = array(
                    ':id' => strtoupper($param),
                    ':interval_length' => $interval_length
                );
                break;

            /* Default value */
            default:
                $this->class_data->PageActions->setStatus(FALSE);
                $this->class_data->PageActions->setStatusCode("Missing Parameter");
        }
        /* SQL - Exec */
        try {
            $bwcfw_status_rolling_window_data_stmt = $this->DAO->DAO_Service->prepare($bwcfw_status_rolling_window_data_query);
            $bwcfw_status_rolling_window_data_stmt->execute($bwcfw_status_rolling_window_data_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_status_rolling_window_data_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_status_rolling_window_data_row = $bwcfw_status_rolling_window_data_stmt->fetchAll();
        if ($bwcfw_status_rolling_window_data_row) {
            $rows = array();
            foreach ($bwcfw_status_rolling_window_data_row as $x) {
                $row[0] = $x['idate'];
                $row[1] = $x['hits'];
                array_push($rows, $row);
            }
            return $rows;
        }
    }

    function getTypeAcionsCurrentDay() {
        /* SQL - Query */
        $bwcfw_type_actions_current_day_query = "SELECT concat(a.type,
              '__',
              a.action)
          AS type_action,
       a.hits
  FROM (SELECT a.type, a.action, count(*) AS hits
          FROM tbl_base_user_audit a
         WHERE     session_date >=
                      DATE_FORMAT(now() - INTERVAL 24 HOUR,
                                  '%Y-%m-%d %H:00:00')
               AND a.page IS NOT NULL
        GROUP BY a.type, a.action
        ORDER BY a.type, a.action) AS a
ORDER BY a.hits DESC, a.action, a.type;";
        /* SQL - Exec */
        try {
            $bwcfw_type_actions_current_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_type_actions_current_day_query);
            $bwcfw_type_actions_current_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_type_actions_current_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_type_actions_current_day_row = $bwcfw_type_actions_current_day_stmt->fetchAll();
        if ($bwcfw_type_actions_current_day_row) {
            $rows = array();
            foreach ($bwcfw_type_actions_current_day_row as $x) {
                $row[0] = $x['type_action'];
                $row[1] = $x['hits'];
                array_push($rows, $row);
            }
            return $rows;
        }
    }

    function getUserAcionsCurrentDay() {
        /* SQL - Query */
        $bwcfw_user_actions_current_day_query = "SELECT a.hits, b.name
  FROM (SELECT a.username_id, count(*) AS hits
          FROM tbl_base_user_audit a
         WHERE a.session_date >=
                  DATE_FORMAT(now() - INTERVAL 24 HOUR, '%Y-%m-%d %H:00:00')
        GROUP BY a.username_id) AS a,
       (SELECT b.id_user,
               concat(b.user_firstname,
                      ' ',
                      b.user_lastname,
                      '____',
                      b.id_user)
                  AS name
          FROM tbl_base_users b) AS b
 WHERE a.username_id = b.id_user;";
        /* SQL - Exec */
        try {
            $bwcfw_user_actions_current_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_user_actions_current_day_query);
            $bwcfw_user_actions_current_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_user_actions_current_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_user_actions_current_day_row = $bwcfw_user_actions_current_day_stmt->fetchAll();
        if ($bwcfw_user_actions_current_day_row) {
            $rows = array();
            foreach ($bwcfw_user_actions_current_day_row as $x) {
                $row[0] = $x['name'];
                $row[1] = $x['hits'];
                array_push($rows, $row);
            }
            return $rows;
        }
    }

    function getPageErrorsCurrentHour() {
        /* SQL - Query */
        $bwcfw_page_action_type_last_day_query = "SELECT ifnull(x.items, 0) errors
  FROM (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
               count(*) AS items
          FROM tbl_base_user_audit a
         WHERE     a.session_date >= DATE_FORMAT(now(), '%Y-%m-%d %H:00:00')
               AND upper(a.status) <> 'OK') AS x;";
        /* SQL - Exec */
        try {
            $bwcfw_page_action_type_last_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_action_type_last_day_query);
            $bwcfw_page_action_type_last_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_action_type_last_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_action_type_last_day_row = $bwcfw_page_action_type_last_day_stmt->fetchAll();
        if ($bwcfw_page_action_type_last_day_row) {
            return $bwcfw_page_action_type_last_day_row;
        }
    }

    function getPageErrorsCurrentDay() {
        /* SQL - Query */
        $bwcfw_page_action_type_last_day_query = "
            SELECT ifnull(min(y.items), 0) AS min_errors,
                   ifnull(max(y.items), 0) AS max_errors,
                   ifnull(round(avg(y.items), 0), 0) AS avg_errors
              FROM (SELECT ifnull(x.items, 0) items
                      FROM (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00')
                                      AS api_date,
                                   count(*) AS items
                              FROM tbl_base_user_audit a
                             WHERE     a.session_date >= DATE_FORMAT(now() - INTERVAL 24 HOUR, '%Y-%m-%d %H:00:00')
                                   AND upper(a.status) <> 'OK'
                            GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'))
                           AS x) AS y;";
        /* SQL - Exec */
        try {
            $bwcfw_page_action_type_last_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_action_type_last_day_query);
            $bwcfw_page_action_type_last_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_action_type_last_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_action_type_last_day_row = $bwcfw_page_action_type_last_day_stmt->fetchAll();
        if ($bwcfw_page_action_type_last_day_row) {
            return $bwcfw_page_action_type_last_day_row;
        }
    }

    function checkPageActionType() {
        /* SQL - Query */
        $bwcfw_page_action_type_last_day_query = "SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
                                                        a.type,
                                                        a.action,
                                                        count(*) AS type_action
                                                   FROM tbl_base_user_audit a
                                                  WHERE session_date > now() - INTERVAL 1 DAY AND a.page IS NOT NULL
                                                 GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.type, a.action
                                                 ORDER BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00'), a.type, a.action;";
        /* SQL - Exec */
        try {
            $bwcfw_page_action_type_last_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_action_type_last_day_query);
            $bwcfw_page_action_type_last_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_action_type_last_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_action_type_last_day_row = $bwcfw_page_action_type_last_day_stmt->fetchAll();
        if ($bwcfw_page_action_type_last_day_row) {
            return $bwcfw_page_action_type_last_day_row;
        }
    }

    function checkPageErrorsCurrentHour() {
        /* SQL - Query */

        $bwcfw_page_errors_last_day_query = "SELECT x.api_date, IFNULL(y.items, 0) AS errors
                                              FROM (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date
                                                      FROM tbl_base_user_audit a
                                                     WHERE a.session_date > now() - INTERVAL 1 DAY
                                                    GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00')
                                                    ORDER BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00')) AS x
                                                   LEFT JOIN
                                                   (SELECT DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00') AS api_date,
                                                           a.status,
                                                           count(*) AS items
                                                      FROM tbl_base_user_audit a
                                                     WHERE     a.session_date > now() - INTERVAL 1 DAY
                                                           AND upper(a.status) <> 'OK'
                                                    GROUP BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00')
                                                    ORDER BY DATE_FORMAT(a.session_date, '%Y-%m-%d %H:00:00')) AS y
                                                      ON x.api_date = y.api_date;";
        /* SQL - Exec */
        try {
            $bwcfw_page_errors_last_day_stmt = $this->DAO->DAO_Service->prepare($bwcfw_page_errors_last_day_query);
            $bwcfw_page_errors_last_day_stmt->execute();
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_page_errors_last_day_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_page_errors_last_day_row = $bwcfw_page_errors_last_day_stmt->fetchAll();
        if ($bwcfw_page_errors_last_day_row) {
            return $bwcfw_page_errors_last_day_row;
        }
    }

    /**
     * 
     * @return array Returns the min, max, current PHP version and status
     */
    function checkPHP() {
        $PHPVersionStatus = 'Ok';
        if (version_compare(PHP_VERSION, $this->DP->getPHPMinVersion()) < 0) {
            $PHPVersionStatus = 'PHP version too old';
        }
        if (version_compare(PHP_VERSION, $this->DP->getPHPMaxVersion()) > 0) {
            $PHPVersionStatus = 'PHP version too young';
        }
        $checkPHP = array("PHPMin" => $this->DP->getPHPMinVersion(), "PHPVersion" => PHP_VERSION, "PHPMax" => $this->DP->getPHPMaxVersion(), "status" => $PHPVersionStatus);
        return $checkPHP;
    }

    function getDAOTableInformation() {
        /* SQL - Query */
        $bwcfw_dao_table_information_query = "
            SELECT table_name, engine, version AS engine_version, row_format, table_rows, avg_row_length, data_length, max_data_length, index_length, data_free, auto_increment, create_time, update_time, table_collation, (data_length + index_length) AS disk_size
            FROM information_schema.tables
            WHERE upper(table_schema) = :table_schema AND upper(table_type) = :table_type;";
        /* SQL - Params */
        $bwcfw_dao_table_information_query_params = array(
            ':table_schema' => strtoupper($this->DAO->DAO_Schema),
            ':table_type' => strtoupper("base table")
        );
        /* SQL - Exec */
        try {
            $bwcfw_dao_table_information_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_information_query);
            $bwcfw_dao_table_information_stmt->execute($bwcfw_dao_table_information_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_information_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_dao_table_information_row = $bwcfw_dao_table_information_stmt->fetchAll();
        if ($bwcfw_dao_table_information_row) {
            $this->DAOTableInformation = $bwcfw_dao_table_information_row;
            return $bwcfw_dao_table_information_row;
        }
    }

    function getDAOTableStatsInformationDD() {
        /* SQL - Query */
        $bwcfw_dao_table_stats_information_dd_query = "
            SELECT a.table_name, a.table_rows, a.data_length, a.index_length, a.disk_size
            FROM tbl_base_table_stats_dd a
            WHERE a.rdate >= DATE_FORMAT(now(), '%Y-%m-%d 00:00:00') AND upper(a.table_name) NOT LIKE '%_stats%' order by a.table_name;";
        /* SQL - Params */
        $bwcfw_dao_table_stats_information_dd_query_params = array(
            ':table_schema' => strtoupper($this->DAO->DAO_Schema),
            ':table_type' => strtoupper("base table")
        );
        /* SQL - Exec */
        try {
            $bwcfw_dao_table_stats_information_dd_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_stats_information_dd_query);
            $bwcfw_dao_table_stats_information_dd_stmt->execute($bwcfw_dao_table_stats_information_dd_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_information_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_dao_table_stats_information_dd_row = $bwcfw_dao_table_stats_information_dd_stmt->fetchAll();
        if ($bwcfw_dao_table_stats_information_dd_row) {
            return $bwcfw_dao_table_stats_information_dd_row;
        }
    }

    function getDAOAuditTableInformation() {
        /* SQL - Query */
        $bwcfw_dao_table_stats_information_dd_query = "SELECT session_date, username, username_id, session_id, user_ip, section, level, area, type, action, status, msg, browser_agent, page, extended_status, line, sid FROM tbl_base_user_audit;";
        /* SQL - Params */
        $bwcfw_dao_table_stats_information_dd_query_params = array(
            ':table_schema' => strtoupper($this->DAO->DAO_Schema),
            ':table_type' => strtoupper("base table")
        );
        /* SQL - Exec */
        try {
            $bwcfw_dao_table_stats_information_dd_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_stats_information_dd_query);
            $bwcfw_dao_table_stats_information_dd_stmt->execute($bwcfw_dao_table_stats_information_dd_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_information_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
        /* SQL - Result Handling */
        $bwcfw_dao_table_stats_information_dd_row = $bwcfw_dao_table_stats_information_dd_stmt->fetchAll();
        if ($bwcfw_dao_table_stats_information_dd_row) {
            return $bwcfw_dao_table_stats_information_dd_row;
        }
    }

    function loadDAOTableInformation() {
        foreach ($this->DAOTableInformation as $raw_data) {
            /* SQL - Query */
            $bwcfw_dao_table_information_query = "
            INSERT INTO tbl_base_table_stats(rdate, table_name, engine, engine_version, row_format, table_rows, avg_row_length, data_length, max_data_length, index_length, data_free, auto_increment, create_time, update_time, table_collation, disk_size)
            VALUES (:rdate, :table_name, :engine, :engine_version, :row_format, :table_rows, :avg_row_length, :data_length, :max_data_length, :index_length, :data_free, :auto_increment, :create_time, :update_time, :table_collation, :disk_size)
            ON DUPLICATE KEY UPDATE engine = :engine, engine_version = :engine_version, row_format = :row_format, table_rows = :table_rows, avg_row_length = :avg_row_length, data_length = :data_length, max_data_length = :max_data_length, index_length = :index_length, data_free = :data_free, auto_increment = :auto_increment, create_time = :create_time, update_time = :update_time, table_collation = :table_collation, disk_size = :disk_size, isprocessed = 'n' ;";
            /* SQL - Params */
            $bwcfw_dao_table_information_query_params = array(
                ':rdate' => $this->getRDate(),
                ':table_name' => $raw_data['table_name'],
                ':engine' => $raw_data['engine'],
                ':engine_version' => $raw_data['engine_version'],
                ':row_format' => $raw_data['row_format'],
                ':table_rows' => $raw_data['table_rows'],
                ':avg_row_length' => $raw_data['avg_row_length'],
                ':data_length' => $raw_data['data_length'],
                ':max_data_length' => $raw_data['max_data_length'],
                ':index_length' => $raw_data['index_length'],
                ':data_free' => $raw_data['data_free'],
                ':auto_increment' => $raw_data['auto_increment'],
                ':create_time' => $raw_data['create_time'],
                ':update_time' => $raw_data['update_time'],
                ':table_collation' => $raw_data['table_collation'],
                ':disk_size' => $raw_data['disk_size']
            );
            /* SQL - Exec */
            try {
                $bwcfw_dao_table_information_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_information_query);
                $bwcfw_dao_table_information_stmt->execute($bwcfw_dao_table_information_query_params);
            } catch (PDOException $ex) {
                /* SQL - Error Handling */
                $this->class_data->PageActions->setStatus(false);
                $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_information_stmt");
                $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
                $this->class_data->PageActions->setLine($ex->getLine());
                $this->class_data->LogEntry(3);
            }
        }
    }

    function loadDAOTableStats60() {
        /* SQL - Query */
        $bwcfw_dao_table_stats_summary_60_query = "
INSERT INTO tbl_base_table_stats_60(rdate,
                                    table_name,
                                    table_rows,
                                    data_length,
                                    index_length,
                                    disk_size)
   SELECT d.rdate,
          d.table_name,
          d.table_rows,
          d.data_length,
          d.index_length,
          d.disk_size
     FROM (SELECT x.rdate,
                  x.table_name,
                  IF(y.table_rows IS NULL, 0, x.table_rows - y.table_rows)
                     AS table_rows,
                  IF(y.data_length IS NULL, 0, x.data_length - y.data_length)
                     AS data_length,
                  IF(y.index_length IS NULL,
                     0,
                     x.index_length - y.index_length)
                     AS index_length,
                  IF(y.disk_size IS NULL, 0, x.disk_size - y.disk_size)
                     AS disk_size
             FROM (SELECT a.rdate,
                          a.table_name,
                          a.table_rows,
                          a.data_length,
                          a.index_length,
                          a.disk_size
                     FROM tbl_base_table_stats a
                    WHERE a.rdate = :rdate) AS x
                  LEFT JOIN (SELECT a.rdate,
                                    a.table_name,
                                    a.table_rows,
                                    a.data_length,
                                    a.index_length,
                                    a.disk_size
                               FROM tbl_base_table_stats a
                              WHERE a.rdate = :pdate) AS y
                     ON x.table_name = y.table_name) AS d
ON DUPLICATE KEY UPDATE table_rows = d.table_rows,
                        data_length = d.data_length,
                        index_length = d.index_length,
                        disk_size = d.disk_size;";
        /* SQL - Params */
        $bwcfw_dao_table_stats_summary_60_query_params = array(
            ':rdate' => $this->getRDate(),
            ':pdate' => $this->getPDate()
        );
        /* SQL - Exec */
        try {
            $bwcfw_dao_table_stats_summary_60_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_stats_summary_60_query);
            $bwcfw_dao_table_stats_summary_60_stmt->execute($bwcfw_dao_table_stats_summary_60_query_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_stats_summary_60_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
    }

    function loadDAOTableStatsDD() {
        /* SQL - Query */
        $bwcfw_dao_table_stats_summary_dd_query = "
INSERT INTO tbl_base_table_stats_dd(rdate,
                                    table_name,
                                    table_rows,
                                    data_length,
                                    index_length,
                                    disk_size,
                                    samples)
   SELECT d.ldate,
          d.table_name,
          d.table_rows,
          d.data_length,
          d.index_length,
          d.disk_size,
          d.samples
     FROM (SELECT DATE_FORMAT(a.rdate, '%Y-%m-%d 00:00:00') AS ldate,
                  a.table_name,
                  sum(a.table_rows) AS table_rows,
                  sum(a.data_length) AS data_length,
                  sum(a.index_length) AS index_length,
                  sum(a.disk_size) AS disk_size,
                  count(a.samples) AS samples
             FROM tbl_base_table_stats_60 a
            WHERE     a.rdate >= DATE_FORMAT(:rdate, '%Y-%m-%d 00:00:00')
                  AND a.rdate <  DATE_FORMAT(:rdate + INTERVAL 1 DAY, '%Y-%m-%d 00:00:00')
           GROUP BY ldate, a.table_name
           ORDER BY ldate, a.table_name) AS d
ON DUPLICATE KEY UPDATE table_rows = d.table_rows,
                        data_length = d.data_length,
                        index_length = d.index_length,
                        disk_size = d.disk_size,
                        samples = d.samples;";
        /* SQL - Params */
        $bwcfw_dao_table_stats_summary_dd_params = array(
            ':rdate' => $this->getRDate()
        );
        /* SQL - Exec */
        try {
            $bwcfw_dao_table_stats_summary_dd_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_stats_summary_dd_query);
            $bwcfw_dao_table_stats_summary_dd_stmt->execute($bwcfw_dao_table_stats_summary_dd_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_stats_summary_dd_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
    }

    function loadDAOTableStatsMO() {
        /* SQL - Query */
        $bwcfw_dao_table_stats_summary_mo_query = "
INSERT INTO tbl_base_table_stats_mo(rdate,
                                    table_name,
                                    table_rows,
                                    data_length,
                                    index_length,
                                    disk_size,
                                    samples)
   SELECT d.ldate,
          d.table_name,
          d.table_rows,
          d.data_length,
          d.index_length,
          d.disk_size,
          d.samples
     FROM (SELECT DATE_FORMAT(:rdate, '%Y-%m-01 00:00:00') AS ldate,
                  a.table_name,
                  sum(a.table_rows) AS table_rows,
                  sum(a.data_length) AS data_length,
                  sum(a.index_length) AS index_length,
                  sum(a.disk_size) AS disk_size,
                  count(a.samples) AS samples
             FROM tbl_base_table_stats_60 a
            WHERE     a.rdate >= DATE_FORMAT(:rdate, '%Y-%m-01 00:00:00')
                  AND a.rdate <  DATE_FORMAT(:rdate + INTERVAL 1 MONTH, '%Y-%m-01 00:00:00')
           GROUP BY ldate, a.table_name
           ORDER BY ldate, a.table_name) AS d
ON DUPLICATE KEY UPDATE table_rows = d.table_rows,
                        data_length = d.data_length,
                        index_length = d.index_length,
                        disk_size = d.disk_size,
                        samples = d.samples;";
        /* SQL - Params */
        $bwcfw_dao_table_stats_summary_mo_params = array(
            ':rdate' => $this->getRDate()
        );
        /* SQL - Exec */
        try {
            $bwcfw_dao_table_stats_summary_mo_stmt = $this->DAO->DAO_Service->prepare($bwcfw_dao_table_stats_summary_mo_query);
            $bwcfw_dao_table_stats_summary_mo_stmt->execute($bwcfw_dao_table_stats_summary_mo_params);
        } catch (PDOException $ex) {
            /* SQL - Error Handling */
            $this->class_data->PageActions->setStatus(false);
            $this->class_data->PageActions->setStatusCode("bwcfw_dao_table_stats_summary_mo_stmt");
            $this->class_data->PageActions->setExtendedStatusCode(htmlspecialchars(str_replace(PHP_EOL, '', $ex->getMessage())));
            $this->class_data->PageActions->setLine($ex->getLine());
            $this->class_data->LogEntry(3);
        }
    }

    function hasDAOTableInformation() {
        return ($this->DAOTableInformation) ? TRUE : FALSE;
    }

    function __construct() {
        $this->class_data = new LoggingService(__FILE__, TRUE);
        $this->DAO = new DAO_Service();
        $this->DP = new BWCFWDecoratorPattern();
        $this->setProcessingDates();
    }

}
