<?php
session_start();
require_once '../model/User.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['nameErrMsg'] = "";
$_SESSION['dobErrMsg'] = "";
$_SESSION['genderErrMsg'] = "";
$_SESSION['areaErrMsg'] = "";
$_SESSION['roadErrMsg'] = "";
$_SESSION['blockErrMsg'] = "";
$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";

$phone = $_SESSION['phone'];
$role = strtolower($_SESSION['role']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $action = isset($_POST['action']) ? $_POST['action'] : 'updateProfile';

    if ($action === 'updateProfile') {
        $name = htmlspecialchars(trim($_POST['name']));
        $dob = htmlspecialchars(trim($_POST['dob']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $area = htmlspecialchars(trim($_POST['area']));
        $road = htmlspecialchars(trim($_POST['road']));
        $block = htmlspecialchars(trim($_POST['block']));
        $flag = true;

        if (!preg_match('/^[a-zA-Z .]{2,100}$/', $name)) {
            $flag = false;
            $_SESSION['nameErrMsg'] = "Please enter a valid name";
        }
        if (empty($dob) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
            $flag = false;
            $_SESSION['dobErrMsg'] = "Please select a valid date of birth";
        }
        if (empty($gender)) {
            $flag = false;
            $_SESSION['genderErrMsg'] = "Please select a gender";
        }
        if (empty($area)) {
            $flag = false;
            $_SESSION['areaErrMsg'] = "Please fill up the area properly";
        }
        if (empty($road)) {
            $flag = false;
            $_SESSION['roadErrMsg'] = "Please fill up the road properly";
        }
        if (empty($block)) {
            $flag = false;
            $_SESSION['blockErrMsg'] = "Please fill up the block properly";
        }

        if ($flag) {
            updateUser($phone, $name, $dob, $gender, $area, $road, $block);
            $_SESSION['name'] = $name;
            $_SESSION['successMsg'] = "Profile updated successfully";
        } else {
            $_SESSION['globalErrMsg'] = "Please check the form and try again.";
        }
        header("Location: ../view/$role/editProfile.php");
        exit();

    } elseif ($action === 'changePassword') {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];
        $flag = true;

        if (!verifyPassword($phone, $currentPassword)) {
            $flag = false;
            $_SESSION['globalErrMsg'] = "Current password is incorrect";
        }
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $newPassword)) {
            $flag = false;
            $_SESSION['globalErrMsg'] = "New password must be at least 6 characters and include a letter and a number";
        }
        if ($newPassword !== $confirmPassword) {
            $flag = false;
            $_SESSION['globalErrMsg'] = "New passwords do not match";
        }

        if ($flag) {
            changePassword($phone, $newPassword);
            $_SESSION['successMsg'] = "Password changed successfully";
        }
        header("Location: ../view/$role/editProfile.php");
        exit();

    } elseif ($action === 'requestManager') {
        requestManagerRole($phone);
        $_SESSION['successMsg'] = "Manager request submitted. Please wait for Admin approval.";
        header("Location: ../view/$role/profile.php");
        exit();

    } elseif ($action === 'deleteAccount') {
        deleteUser($phone);
        session_destroy();
        header("Location: ../view/login.php");
        exit();
    }
} else {
    header("Location: ../view/$role/profile.php");
    exit();
}
?>
