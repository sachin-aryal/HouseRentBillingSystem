<?php

$db_user = "root";
$db_host = "localhost";
$db_password = "root";
$db_name = "hrbs";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

function insert_people($conn){
    $name = $_POST["people_name"];
    $rent = $_POST["rent"];
    $rent_date = $_POST["rent_date"];
    $query = "INSERT INTO people(name, rent, rent_date) VALUES ('$name', '$rent', '$rent_date')";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }

}

function getPeople($conn){
    $query = "SELECT *FROM people ORDER BY added_date desc";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

function disable_people($conn, $id){
    $query = "UPDATE people set enabled=0 WHERE id = $id";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }
}