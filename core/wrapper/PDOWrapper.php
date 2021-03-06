<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - getPDO()
* Classes list:
* - PDOWrapper
*/
namespace core\wrapper;

class PDOWrapper
{
    static public $pdo;

    static public function initialize()
    {
        try {
            $db = require __ROOT__.'/config/database.'.__ENVIRONMENT__.'.config.php';
            $con = new \PDO('mysql:host=' . $db['host'] . '; dbname=' . $db['name'], $db['user'], $db['password']);
            $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $con->exec("SET CHARACTER SET utf8");
            self::$pdo = $con;
        }
        catch(PDOException $err) {
            echo "THE CONNECTION HAS FAILED : ";
            $err->getMessage() . "<br/>";
            file_put_contents('PDOErrors.txt', $err, FILE_APPEND);
            die();
        }
    }
}
