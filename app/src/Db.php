<?php
namespace Megagroup\SmartReplace;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 20:08
 */
class Db {

    private static $pdo;
    public static $logger;


    public static function connect()
    {
        self::$pdo = Application::getInstance()->getBdConnect();
    }


    public static function insert(array $query, string $table, string $where = null, $last_id = null) {
        /**
        $query - это массив со значениями. [название колонки => [значение, тип]]
         */
        self::$pdo || self::connect();

        $fieldsBool = null;

        foreach ($query as $field => $val) {
            $fields[] = $field;
            $fieldsVal[] = $val;
        }

        $fields = implode(',', $fields);
        $fieldsHolder = str_repeat('?,', count($fieldsVal));
        $fieldsHolder = substr($fieldsHolder, 0, -1);

        $sql = "INSERT INTO $table ($fields) VALUES ( $fieldsHolder )";

        if ($where) {
            $sql .= " where " . $where;
        }
        self::$logger = Application::getInstance()->getLogger();
        //self::$logger->info($sql);

        $stmt = self::$pdo->prepare($sql);

        $i = 1;
        if ( count($fieldsVal) == 1 ) {
            if ( strtolower($fieldsVal[0][1]) == 's' ) {
                $fieldsVal[0][1] = \PDO::PARAM_STR;
            } else if ( strtolower($fieldsVal[0][1]) == 'i' ) {
                $fieldsVal[0][1] = \PDO::PARAM_INT;
            }
            $stmt->bindParam($i, $fieldsVal[0][0], $fieldsVal[0][1]);
        } else {
            foreach($fieldsVal as $val) {
               // self::$logger->addWarning('val',$val);
                if ( strtolower($val[1]) == 's' ) {
                    $val[1] = \PDO::PARAM_STR;
                } else if ( strtolower($val[1]) == 'i' ) {
                    $val[1] = \PDO::PARAM_INT;
                }

                $stmt->bindParam($i, $val[0], $val[1]);
                $i++;
            }
        }


        if ($last_id) {
            $stmt->execute();
            return self::$pdo->lastInsertId();
        } else {
            return $stmt->execute();
        }

    }

    public static function select(string $query) {
        self::$pdo || self::connect();
        self::$logger = Application::getInstance()->getLogger();
        $result = self::$pdo->query($query);
        if (is_object($result)) {
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        }

    }

    public static function update(array $query,string $table_name,string $where) {
        /**
         * $query = [column_name=>some_query]
         *
         * column_name - название колонки которуюу надо обновить
         * some_query - новое значение
         *
         * */

        self::$pdo || self::connect();
        self::$logger = Application::getInstance()->getLogger();

        foreach($query as $name=>$value){
            $request[]= $name." = '".$value."'";
        }

        if ( count($request) > 1 ) {
            $request = implode(' ,' , $request);
        } else {
            $request = $request[0];
        }

        if ($where) {
            $request .= " where " . $where;
        }
        $sql = "UPDATE $table_name SET $request";
        //self::$logger->info($sql);

        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute();
    }

    public static function delete(string $table_name,string $where,int $limit = null) {
        self::$pdo || self::connect();

        self::$logger = Application::getInstance()->getLogger();

        $sql = "DELETE FROM $table_name WHERE $where";
        if ($limit) {
            $sql = $sql . " LIMIT $limit";
        }
        //self::$logger->info($sql);
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute();
    }
}

