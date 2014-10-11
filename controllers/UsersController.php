<?php

require 'Controller.php';

class UsersController extends Controller{


    function __construct()
    {
        parent::__construct();
        $this->tableName = "users";
    }

    public function add()
    {
        $data = $_POST;
        $errors = $this->registerValidation($_POST);

        if(!empty($data) && empty($errors)) {

            $stmt = $this->pdo->prepare(
                    "INSERT INTO users (email, password, created, name, gender, birthDate)
                     VALUES ( :email, :password, :created, :name, :gender, :birthDate)
                    ");

            $stmt->execute(array(
                ':email' => $_POST['email'],
                ':password' => md5($_POST['password']),
                ':created'=>date('Y-m-d H:i:s'),
                ':name' => $data['name'],
                ':gender' => substr($data['gender'],0 ,1),
                ':birthDate' => $data['birth-year'] . '-' . $data['birth-month'] . '-' . $data['birth-day'],
            ));

            header('Location: /'.__APPNAME__.'/index.php?page=Posts');
            die();
        }

        echo $this->renderView('Users/add', compact('data', 'errors'));
    }

    public function login()
    {
        if(!empty($_POST)) {

            $user = $this->getAll(array('WHERE' => array('email' => $_POST['email'], 'password' => md5($_POST['password']))));

            if(!empty($user)) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['Auth'] = $user[0];
            }
            header('Location: /'.__APPNAME__.'/index.php?page=Posts');
            die();
        }
        echo $this->renderView('Users/login');
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header('Location: /'.__APPNAME__.'/index.php?page=Posts');
        die();
    }


    private function registerValidation( $data = array() ) 
    {
        if ( empty($data) )
            return array();

        $errors = array();

        if ( !empty($data['name']) && !preg_match('/^[a-z][a-z ]*$/i', $data['name']) )            
            $errors['name'] = true;

        if ( !empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL) )
            $errors['email'] = true;

        if ( !empty($data['gender']) && !( $data['gender'] === 'male' || $data['gender'] === 'female') )
            $errors['gender'] = true;

        if ( !empty($data['birth-day']) && ! ( $data['birth-day'] >=1 && $data['birth-day'] <= 31 ) )
            $errors['birth-day'] = true;

        if ( !empty($data['birth-month']) && ! ( $data['birth-month'] >=1 && $data['birth-month'] <= 12 ) )
            $errors['birth-month'] = true;

        if ( !empty($data['birth-year']) && ! ( $data['birth-year'] >= 1900 && $data['birth-year'] <= 2014 ) )
            $errors['birth-year'] = true;

        return $errors;
    }
}