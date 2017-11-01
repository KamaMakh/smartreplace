<?php
namespace Megagroup\SmartReplace\Controllers;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 20:08
 */
class Db {

    private static $pdo;
    protected static $host     = DB_CONF['host'];
    protected static $database = DB_CONF['database'];
    protected static $user     = DB_CONF['user'];
    protected static $password = DB_CONF['password'];
    protected static $charset  = DB_CONF['charset'];

    private static function connect() {
        self::$pdo = new \PDO("mysql:host=" . self::$host . ";dbname=" . self::$database. ";charset=" . self::$charset, self::$user, self::$password);
    }

    public static function insert(array $query, string $table, string $where = null) {
        self::$pdo || self::connect();

        $fieldsBool = null;

        foreach ($query as $field => $val) {

//            if ( is_array($val) ) {
//
//                if ($fieldsBool !== false) $fieldsBool = true;
//
//                foreach ($val as $val_key => $item) {
//                    if ( $fieldsBool ) {
//                        $fields[] = $val_key;
//                    }
//                    $fieldsVal[] = $item;
//                }
//
//                if ( $fieldsBool ) $fieldsBool = false;
//
//                continue;
//            }

            $fields[] = $field;
            $fieldsVal[] = $val;
        }
        //echo count($fieldsVal);
        //echo count($fields);
        $fields = implode(',', $fields);
        $fieldsHolder = str_repeat('?,', count($fieldsVal));
        $fieldsHolder = substr($fieldsHolder, 0, -1);

        $sql = "INSERT INTO $table ($fields) VALUES ($fieldsHolder)";

        if ($where) {
            $sql .= " " . $where;
        }

        $stmt = self::$pdo->prepare($sql);

        $i = 1;
        foreach($fieldsVal as $val) {
            if ( strtolower($val[1]) == 's' ) {
                $val[1] = \PDO::PARAM_STR;
            } else if ( strtolower($val[1]) == 'i' ) {
                $val[1] = \PDO::PARAM_INT;
            }
            $stmt->bindParam($i, $val[0], $val[1]);
            $i++;
        }

        return $stmt->execute();

    }

    public static function select(string $query) {
        self::$pdo || self::connect();

        $result = self::$pdo->query($query);
        if (is_object($result)) {
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        }

    }

    public static function update(array $query,string $table_name,string $where) {
        self::$pdo || self::connect();

        foreach($query as $name=>$value){
            $request[]= $name.'='.$value;
        }

        $request = implode(" ," , $request);
        if ($where) {
            $request = " " . $where;
        }

        $sql = "UPDATE $table_name $request";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute();
    }

    public static function delete(string $table_name,string $where,int $limit = null) {
        self::$pdo || self::connect();

        $sql = "DELETE FROM $table_name WHERE $where";
        if ($limit) {
            $sql = $sql . " LIMIT $limit";
        }
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute();
    }
}

