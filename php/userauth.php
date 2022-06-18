<?php

session_start();
require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();
   //check if user with this email already exist in the database
    $email_check = mysqli_query($conn, "SELECT * FROM students WHERE email = '$email'");
    if(mysqli_num_rows($email_check) > 0){
        echo "<script> alert('User with this email already exist') </script>";
        
    }else{
        //insert the user details into the database
        $query = "INSERT INTO students(`full_names`, `country`, `email`, `gender`, `password`) VALUES 
            ('$fullnames', '$country', '$email', '$gender', '$password')";
        if(mysqli_query($conn, $query)){
            echo "<script> alert('User Successfully registered') </script>";
            var_dump(mysqli_query($conn, $query));
        }else{
            echo "<script> alert('Error') </script>";
        };
        
    }
    // header("Location: ../forms/register.html");
}

//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    // echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
     $email_check = mysqli_query($conn, "SELECT * FROM students WHERE email = '$email'");
    //if it does, check if the password is the same with what is given
    $pass = null;
    if(mysqli_num_rows($email_check) > 0){
        while ($row = mysqli_fetch_assoc($email_check)) {
            $pass = $row['password'];
        }
    }
    //if true then set user session for the user and redirect to the dasbboard
    if($pass == $password){
        $_SESSION['username'] = $email;
        header("Location: ../dashboard.php");
    }else{
        echo "<script> alert('Password and Username do not match') </script>";
        header("Location: ../forms/login.html");
    }
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    $email_check = mysqli_query($conn, "SELECT * FROM students WHERE email = '$email'");
    //if it does, replace the password with $password given
    if(mysqli_num_rows($email_check) > 0){
        $sql = "UPDATE students SET `password` = '$password' WHERE `email` = '$email'";
        if(mysqli_query($conn, $sql)){
            echo "<script> alert('Password Change Successfully') </script>";
            header("Location: ../forms/login.html");
        }
    }else{
        echo "<script> alert('User does not exist') </script>";
    };
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     $sql = "DELETE FROM students WHERE `id` = '$id'";
     if(mysqli_query($conn, $sql)){
        header("location: action.php?all=");
        echo "<script> alert('User Deleted Successfully') </script>";
     };
 }
