<?php

$db_user = "root";
$db_host = "localhost";
$db_password = "";
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
function getPeopleList($conn,$enabled){
    $stmt=$conn->prepare('SELECT * FROM people WHERE enabled=?');
    $stmt->bind_param('i',$enabled);
    if($stmt->execute()){
        $result= $stmt->get_result();
        if($result->num_rows > 0){
            return mysqli_fetch_all($result,MYSQLI_ASSOC);

        }
    }
    return[];

}

function disable_people($conn, $id){
    $query = "UPDATE people set enabled=0 WHERE id = $id";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }
}
if(isset($_POST['get_rent'])){
    $usr=$_POST['usr'];
    $stmt=$conn->prepare('Select * from people where name=?');
    $stmt->bind_param('s',$usr);
    if($stmt->execute()){
        $result=$stmt->get_result();
        if($result->num_rows >0){
            echo json_encode(mysqli_fetch_assoc($result));

        }
    }

}
function insert_rent($conn){
    $usr=$_POST['usr'];
    $year=$_POST['year'];
    $rent=$_POST['rent'];
    $month=$_POST['month'];
    $previous_electricity_unit=$_POST['previous_electricity_unit'];
    $current_electricity_unit=$_POST['current_electricity_unit'];
    $water_cost=$_POST['water_cost'];

    $stmt=$conn->prepare('INSERT INTO rent_record(name,year,month,rent,previous_electricity_unit,current_electricity_unit,water_cost) VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('sisiiii',$usr,$year,$month,$rent,$previous_electricity_unit,$current_electricity_unit,$water_cost);
    if($stmt->execute()){
        return true;
    }
    return false;

}
function getRentList($conn){
    $query = "SELECT * FROM rent_record ORDER by id DESC ";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}
function getElectricityRate($conn){
    $id=1;
    $stmt=$conn->prepare('SELECT * FROM electricity_charge WHERE id=?');
    $stmt->bind_param('i',$id);
    if($stmt->execute()){
        $result= $stmt->get_result();
        if($result->num_rows > 0){
            return mysqli_fetch_assoc($result);

        }
    }
    return[];
}
if(isset($_POST['edit_rent'])){
    $id=$_POST['id'];
    $stmt=$conn->prepare('SELECT * FROM rent_record WHERE id=?');
    $stmt->bind_param('i',$id);
    if($stmt->execute()){
        $result= $stmt->get_result();
        if($result->num_rows > 0){
            echo json_encode(mysqli_fetch_assoc($result)) ;

        }
    }
    return[];
}
function update_rent($conn){
    var_dump($_POST);
    $id=$_POST['id'];
    $usr=$_POST['usr'];
    $year=$_POST['year'];
    $rent=$_POST['rent'];
    $month=$_POST['month'];
    $previous_electricity_unit=$_POST['previous_electricity_unit'];
    $current_electricity_unit=$_POST['current_electricity_unit'];
    $water_cost=$_POST['water_cost'];

    $stmt=$conn->prepare('UPDATE rent_record SET name=?,year=?,month=?,rent=?,previous_electricity_unit=?,current_electricity_unit=?,water_cost=? WHERE id=?');
    $stmt->bind_param('sisiiiii',$usr,$year,$month,$rent,$previous_electricity_unit,$current_electricity_unit,$water_cost,$id);
    if($stmt->execute()){
        return true;
    }
    return false;


}