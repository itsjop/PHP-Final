<?php

// Connecting to the MySQL database
$username = 'crandallj2';
$password = '6xXu6fqe';
$servername = 'csweb.hh.nku.edu';
$database = new PDO("mysql:host=$servername;dbname=db_spring17_crandallj2", $username, $password);

//start session
session_start();
// if customerID is not set in the session
if(!array_key_exists('customerID', $_SESSION)  ){

    //split these two statements because they were always triggering false


    //and current URL not login.php redirect to login page
    /*
    if(!strpos($_SERVER['REQUEST_URI'], "login")){

        header('location: login.php');

    }
*/


}
// Else if session key customerID is set get $customer from the database
else{


    $sql = file_get_contents('sql/getCustomer.sql');
    $params = array(
        'customerid' => $_SESSION['customerID'],
    );
    $statement = $database->prepare($sql);
    $statement->execute($params);
    $customer = $statement->fetchAll(PDO::FETCH_ASSOC);



    $_SESSION['name'] = $customer[0]['name'];
    $_SESSION['address'] = $customer[0]['address'];
    $_SESSION['city'] = $customer[0]['city'];

}