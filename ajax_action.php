<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "ajax_crud");

// Check if the connection is established
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Data send to the database
$action = $_POST["action"];

if ($action == "Insert") {
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $gender = mysqli_real_escape_string($con, $_POST["gender"]);
    $contact = mysqli_real_escape_string($con, $_POST["contact"]);

    $sql = "INSERT INTO users (NAME, GENDER, CONTACT) VALUES ('{$name}', '{$gender}', '{$contact}')";
    
    if ($con->query($sql)) {
        $last_id = mysqli_insert_id($con); // Get the last inserted ID
        echo "
            <tr uid='{$last_id}'>
                <td>{$name}</td>
                <td>{$gender}</td>
                <td>{$contact}</td>
                <td><a href='#' class='btn btn-primary edit'>Edit</a></td>
                <td><a href='#' class='btn btn-danger delete'>Delete</a></td>
            </tr>
        ";
    } else {
        echo false;
    }

} elseif ($action == "Update") {
    $id = mysqli_real_escape_string($con, $_POST["id"]);
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $gender = mysqli_real_escape_string($con, $_POST["gender"]);
    $contact = mysqli_real_escape_string($con, $_POST["contact"]);

    $sql = "UPDATE users SET NAME='{$name}', GENDER='{$gender}', CONTACT='{$contact}' WHERE ID='{$id}'";
    
    if ($con->query($sql)) {
        echo "
            <td>{$name}</td>
            <td>{$gender}</td>
            <td>{$contact}</td>
            <td><a href='#' class='btn btn-primary edit'>Edit</a></td>
            <td><a href='#' class='btn btn-danger delete'>Delete</a></td>
        ";
    } else {
        echo false;
    }
} else if ($action == "Delete"){
    $id=$_POST["uid"];
    $sql="delete from users where ID='{$id}'";
    if ($con->query($sql)) {
        echo true;
    }else{
        echo false;
    }
}
?>
