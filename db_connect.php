<?php


$db_host = "localhost";
if (strpos($_SERVER["HTTP_HOST"], 'localhost') !== false){
    $db_user = "root";
    $db_password = "root";
    $db_name = "hrbs";
}else{
    $db_user = "deepahom_hrms";
    $db_password = "deepahome12345";
    $db_name = "deepahom_hrms";
}

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

function insert_return ($conn){
    $people_id = $_POST["usr"];
    $remain_to_return = $_POST["remain_to_return"];
    $query = "INSERT INTO left_to_return(remain_to_give, people_id) VALUES ('$remain_to_return', '$people_id')";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }

}

function insert_advance ($conn){
    $people_id = $_POST["usr"];
    $advance = $_POST["advance"];
    $query = "INSERT INTO advance_payment(advance, people_id) VALUES ($advance, $people_id)";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }

}

function insert_return_f ($conn, $people_id, $remain_to_return){
    $query = "INSERT INTO left_to_return(remain_to_give, people_id) VALUES ($remain_to_return,$people_id)";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }
}

function insert_people($conn){
    $name = $_POST["people_name"];
    $rent = $_POST["rent"];
    $rent_date = $_POST["rent_date"];
    $email = $_POST["email"];
    $query = "INSERT INTO people(name, rent, rent_date, email) VALUES ('$name', '$rent', '$rent_date', '$email')";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }
}

function getPeople($conn){
    $query = "SELECT * FROM people ORDER BY enabled DESC";
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
    $stmt=$conn->prepare('Select * from people where id=?');
    $stmt->bind_param('i',$usr);
    if($stmt->execute()){
        $result=$stmt->get_result();
        if($result->num_rows >0){
            echo json_encode(mysqli_fetch_assoc($result));

        }
    }

}
function insert_rent($conn){
    $people_id = $_POST["usr"];
    $usr=get_people_info($conn, $people_id)["name"];
    $year=$_POST['year'];
    $rent=$_POST['rent'];
    $month=$_POST['month'];
    $previous_electricity_unit=$_POST['previous_electricity_unit'];
    $current_electricity_unit=$_POST['current_electricity_unit'];
    $water_cost=$_POST['water_cost'];
    $maintenance_cost=$_POST['maintenance_cost'];
    $electricity_bill = ($current_electricity_unit-$previous_electricity_unit)*get_electricity_price($conn);
    $previous_rent = $_POST['previous_rent'];
    $remarks = $_POST['remarks'];
    $query = "INSERT INTO rent_record(name,year,month,rent,previous_electricity_unit,current_electricity_unit,electricity_bill,water_cost, previous_rent, people_id, maintenance_cost, remarks) 
              VALUES ('$usr',$year,'$month',$rent,$previous_electricity_unit,$current_electricity_unit,$electricity_bill,$water_cost, $previous_rent, $people_id, $maintenance_cost, '$remarks')";
    if ($conn->query($query)){
        return true;
    }
    return false;

}

function get_people_info($conn, $id){
    $stmt=$conn->prepare('Select * from people where id=?');
    $stmt->bind_param('i',$id);
    if($stmt->execute()){
        $result=$stmt->get_result();
        if($result->num_rows >0){
            return mysqli_fetch_assoc($result);
        }
    }
    return 0;
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
            echo json_encode(mysqli_fetch_assoc($result));
            return;
        }
    }
    return[];
}
if(isset($_POST['get_previous_electricity'])){
    $id = $_POST['usr'];
    $query = "SELECT *FROM rent_record WHERE people_id=$id ORDER by current_electricity_unit DESC LIMIT 1";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        echo mysqli_fetch_assoc($result)["current_electricity_unit"];
        return;
    }
    echo 0;
}
function update_return($conn){
    $id=$_POST['id'];
    $people_id = $_POST["name"];
    $remain_to_return=$_POST["remain_to_return"];
    $query = "UPDATE left_to_return SET people_id=$people_id,remain_to_give=$remain_to_return WHERE id=$id";
    if ($conn->query($query)){
        return true;
    }
    return false;
}

function update_advance($conn){
    $id=$_POST['id'];
    $people_id = $_POST["name"];
    $advance=$_POST["advance"];
    $query = "UPDATE advance_payment SET people_id=$people_id,advance=$advance WHERE id=$id";
    if ($conn->query($query)){
        return true;
    }
    return false;
}

function update_advance_by_people($conn, $people_id, $advance){
    $query = "UPDATE advance_payment SET advance=$advance WHERE people_id=$people_id";
    if ($conn->query($query)){
        return true;
    }
    return false;
}

function delete_return($conn, $id){
    $query = "DELETE FROM left_to_return WHERE id=$id";
    if($conn->query($query)){
        return true;
    }
    return false;

}

function delete_advance($conn, $id){
    $query = "DELETE FROM advance_payment WHERE id=$id";
    if($conn->query($query)){
        return true;
    }
    return false;

}

function update_rent($conn){
    $id=$_POST['id'];
    $people_id = $_POST["usr"];
    $usr=get_people_info($conn, $people_id)["name"];
    $year=$_POST['year'];
    $rent=$_POST['rent'];
    $month=$_POST['month'];
    $previous_electricity_unit=$_POST['previous_electricity_unit'];
    $current_electricity_unit=$_POST['current_electricity_unit'];
    $water_cost=$_POST['water_cost'];
    $maintenance_cost=$_POST['maintenance_cost'];
    $electricity_bill = ($current_electricity_unit-$previous_electricity_unit)*get_electricity_price($conn);
    $previous_rent = $_POST["previous_rent"];
    $remarks = $_POST["remarks"];
    $query = "UPDATE rent_record SET name='$usr',year=$year,month='$month', 
              rent=$rent,previous_electricity_unit=$previous_electricity_unit,current_electricity_unit=$current_electricity_unit,
              water_cost=$water_cost, electricity_bill=$electricity_bill, previous_rent=$previous_rent, people_id = $people_id, maintenance_cost = $maintenance_cost, remarks = '$remarks' WHERE id=$id";
    if ($conn->query($query)){
        return true;
    }
    return false;
}

function update_status($conn, $id){
    $query = "UPDATE rent_record set status=1 WHERE id=$id";
    if($conn->query($query)){
        return true;
    }
    return false;
}

function get_advance($conn, $people_id){
    $stmt=$conn->prepare('Select * from advance_payment where people_id=?');
    $stmt->bind_param('i',$people_id);
    if($stmt->execute()){
        $result=$stmt->get_result();
        if($result->num_rows >0){
            return mysqli_fetch_assoc($result)["advance"];
        }
    }
    return 0;
}

function delete_rent($conn, $id){
    $query = "DELETE FROM rent_record WHERE id=$id";
    if($conn->query($query)){
        return true;
    }
    return false;
}


function update_people($conn){
    $name = $_POST["people_name"];
    $rent = $_POST["rent"];
    $rent_date = $_POST["rent_date"];
    $id = $_POST["id"];
    $email = $_POST["email"];
    $query = "UPDATE people set name='$name', rent='$rent', rent_date='$rent_date', email = '$email' WHERE id= $id";
    if($conn->query($query)){
        return true;
    }else{
        return false;
    }
}

function get_electricity_price($conn){
    $query = "SELECT *FROM electricity_charge LIMIT 1";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        return mysqli_fetch_assoc($result)["rate"];
    }
    return 13;
}
function getLeftTOReturn($conn){
    $query = "SELECT * FROM left_to_return";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

function getAdvance($conn){
    $query = "SELECT * FROM advance_payment";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}


function get_rent($conn, $id){
    $query = "SELECT *FROM rent_record WHERE id=$id LIMIT 1";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function redirectIfNotLoggedIn(){
    if(!isset($_SESSION["uname"])){
        header("Location:login.php");
    }
}

function validateLogin(){
    session_start();
    $uname = $_POST["uname"];
    $pw = $_POST["pw"];

    if($uname == "hrms" && $pw = "dallas604342"){
        $_SESSION["uname"] = "hrms";
        return true;
    }
    return false;
}