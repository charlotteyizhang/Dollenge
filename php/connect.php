<?php
if(!isset($_SESSION)){
    session_start();
}
/**
 * Created by CharlotteZhang.
 * User: user
 * Date: 2016/1/29
 * Time: 10:16
 */
function getConnect(){
    $conn = new mysqli("localhost", "s1520365", "mYD5YSWCnx","s1520365");
    if (mysqli_connect_errno())
    {
        echo "MySQLi Connection was not established:" . mysqli_connect_error();
    }
    return $conn;
}

?>