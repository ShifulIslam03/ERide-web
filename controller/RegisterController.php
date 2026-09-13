<?php
session_start();
require_once '../model/User.php';

$_SESSION['phoneErrMsg'] = "";
$_SESSION['nameErrMsg'] = "";
$_SESSION['dobErrMsg'] = "";
$_SESSION['genderErrMsg'] = "";
$_SESSION['areaErrMsg'] = "";
$_SESSION['roadErrMsg'] = "";
$_SESSION['blockErrMsg'] = "";
$_SESSION['passwordErrMsg'] = "";
$_SESSION['confirmPasswordErrMsg'] = "";
$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";

$_SESSION['phone'] = "";
$_SESSION['name'] = "";
$_SESSION['dob'] = "";
$_SESSION['gender'] = "";
$_SESSION['area'] = "";
$_SESSION['road'] = "";
$_SESSION['block'] = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $phone = htmlspecialchars(trim($_POST['phone']));
    $name = htmlspecialchars(trim($_POST['name']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $area = htmlspecialchars(trim($_POST['area']));
    $road = htmlspecialchars(trim($_POST['road']));
    $block = htmlspecialchars(trim($_POST['block']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $createAs = isset($_POST['createAs']) ? htmlspecialchars(trim($_POST['createAs'])) : 'Customer';
    if (!in_array($createAs, ['Customer', 'Manager'])) {
        $createAs = 'Customer'; // Admin accounts can never be self-registered
    }

    $flag = true;

    if (!preg_match('/^01[0-9]{9}$/', $phone)) {
        $flag = false;
        $_SESSION['phoneErrMsg'] = "Phone number must be 11 digits starting with 01";
    } else {
        $_SESSION['phone'] = $phone;
        if (phoneExists($phone)) {
            $flag = false;
            $_SESSION['phoneErrMsg'] = "This phone number is already registered";
        }
    }

    if (!preg_match('/^[a-zA-Z .]{2,100}$/', $name)) {
        $flag = false;
        $_SESSION['nameErrMsg'] = "Please enter a valid name";
    } else {
        $_SESSION['name'] = $name;
    }

    if (empty($dob) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
        $flag = false;
        $_SESSION['dobErrMsg'] = "Please select a valid date of birth";
    } else {
        $_SESSION['dob'] = $dob;
    }

    if (empty($gender)) {
        $flag = false;
        $_SESSION['genderErrMsg'] = "Please select a gender";
    } else {
        $_SESSION['gender'] = $gender;
    }

    if (empty($area)) {
        $flag = false;
        $_SESSION['areaErrMsg'] = "Please fill up the area properly";
    } else {
        $_SESSION['area'] = $area;
    }

    if (empty($road)) {
        $flag = false;
        $_SESSION['roadErrMsg'] = "Please fill up the road properly";
    } else {
        $_SESSION['road'] = $road;
    }

    if (empty($block)) {
        $flag = false;
        $_SESSION['blockErrMsg'] = "Please fill up the block properly";
    } else {
        $_SESSION['block'] = $block;
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password)) {
        $flag = false;
        $_SESSION['passwordErrMsg'] = "Password must be at least 6 characters and include a letter and a number";
    }

    if ($password !== $confirmPassword) {
        $flag = false;
        $_SESSION['confirmPasswordErrMsg'] = "Passwords do not match";
    }

    if ($flag) {
        $isRegistered = register($phone, $name, $dob, $gender, $area, $road, $block, $password);
        if ($isRegistered) {
            if ($createAs === 'Manager') {
                requestManagerRole($phone);
                $_SESSION['successMsg'] = "Registration successful. Your Manager request has been submitted for Admin approval. Please login as Customer for now.";
            } else {
                $_SESSION['successMsg'] = "Registration successful. Please login.";
            }
            header("Location: ../view/login.php");
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Registration failed. Please try again.";
            header("Location: ../view/register.php");
            exit();
        }
    } else {
        $_SESSION['globalErrMsg'] = "Please check the form and try again.";
        header("Location: ../view/register.php");
        exit();
    }
} else {
    $_SESSION['globalErrMsg'] = "Something went wrong.";
    header("Location: ../view/register.php");
    exit();
}
?>
