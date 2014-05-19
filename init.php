<?php

session_start();

if ( !defined("__DIR__")) define("__DIR__", dirname(__FILE__));
if ( !defined("__ROOT__")) define("__ROOT__", __DIR__);

$router = array();

//Setting up the router
if(!empty($_GET['page'])){
    $router['controller'] = $_GET['page'].'Controller';
    unset($_GET['page']);
}
//Default Value
else {
    $router['controller'] = 'HomepageController';
}

if(!empty($_GET['action']))
{
    $router['action']=$_GET['action'];
    unset($_GET['action']);
}
else
    $router['action']='index';

if(!empty($_GET['arg']))
    $router['arg'] = $_GET['arg'];