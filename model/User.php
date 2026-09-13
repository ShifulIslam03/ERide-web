<?php
require_once 'dbConnect.php';

function phoneExists($phone) {
    $conn = connect();
    $sql = "SELECT Phone_Number FROM users WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows === 1;
    $stmt->close();
    $conn->close();
    return $exists;
}


function register($phone, $name, $dob, $gender, $area, $road, $block, $password) {
    $conn = connect();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (Phone_Number, Name, Date_of_Birth, Gender, Area, Road, Block, Password, Role, Manager_Request_Status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Customer', 'None')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $phone, $name, $dob, $gender, $area, $road, $block, $hashedPassword);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function login($phone, $password) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        if (password_verify($password, $user['Password'])) {
            return $user;
        }
        return false;
    }

    $stmt->close();
    $conn->close();
    return false;
}


function getUser($phone) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}


function getAllUsers($role = null) {
    $conn = connect();
    if ($role) {
        $sql = "SELECT Phone_Number, Name, Date_of_Birth, Gender, Area, Road, Block, Role, Manager_Request_Status FROM users WHERE Role = ? ORDER BY Name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $role);
    } else {
        $sql = "SELECT Phone_Number, Name, Date_of_Birth, Gender, Area, Road, Block, Role, Manager_Request_Status FROM users ORDER BY Name";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $users;
}
// Update profile fields (not password, not role)
function updateUser($phone, $name, $dob, $gender, $area, $road, $block) {
    $conn = connect();
    $sql = "UPDATE users SET Name = ?, Date_of_Birth = ?, Gender = ?, Area = ?, Road = ?, Block = ? WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $dob, $gender, $area, $road, $block, $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function changePassword($phone, $newPassword) {
    $conn = connect();
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET Password = ? WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function verifyPassword($phone, $password) {
    $conn = connect();
    $sql = "SELECT Password FROM users WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    if (!$row) return false;
    return password_verify($password, $row['Password']);
}


function deleteUser($phone) {
    $conn = connect();
    $sql = "DELETE FROM users WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function updateUserRole($phone, $role) {
    $conn = connect();
    $sql = "UPDATE users SET Role = ? WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $role, $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function requestManagerRole($phone) {
    $conn = connect();
    $sql = "UPDATE users SET Manager_Request_Status = 'Pending', Manager_Request_Date = NOW() WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function getManagerRequests() {
    $conn = connect();
    $sql = "SELECT Phone_Number, Name, Area, Manager_Request_Date FROM users WHERE Manager_Request_Status = 'Pending' ORDER BY Manager_Request_Date";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $requests;
}


function approveManager($phone, $adminPhone) {
    $conn = connect();
    $sql = "UPDATE users SET Role = 'Manager', Manager_Request_Status = 'Approved', Manager_Approved_By = ?, Manager_Decision_Date = NOW() WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $adminPhone, $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function rejectManager($phone, $adminPhone) {
    $conn = connect();
    $sql = "UPDATE users SET Manager_Request_Status = 'Rejected', Manager_Approved_By = ?, Manager_Decision_Date = NOW() WHERE Phone_Number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $adminPhone, $phone);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
?>
