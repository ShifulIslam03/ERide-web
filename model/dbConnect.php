<?php
function connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "e_ride";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
?>
