<?php
session_start();
require_once '../model/User.php';

$_SESSION['phoneErrMsg'] = "";
$_SESSION['passwordErrMsg'] = "";
$_SESSION['globalErrMsg'] = "";
$_SESSION['phone'] = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = $_POST['password'];
    $loginAs = isset($_POST['loginAs']) ? htmlspecialchars(trim($_POST['loginAs'])) : 'Customer';
    $flag = true;

    if (empty($phone) || !preg_match('/^01[0-9]{9}$/', $phone)) {
        $flag = false;
        $_SESSION['phoneErrMsg'] = "Please enter a valid 11-digit phone number";
    } else {
        $_SESSION['phone'] = $phone;
    }

    if (empty($password)) {
        $flag = false;
        $_SESSION['passwordErrMsg'] = "Please fill up the password properly";
    }

    if ($flag) {
        $user = login($phone, $password);
        if ($user && $user['Role'] !== $loginAs) {
            $_SESSION['globalErrMsg'] = "This phone number is not registered as $loginAs. Please choose the correct role.";
            header("Location: ../view/login.php");
            exit();
        }
        if ($user) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['phone'] = $user['Phone_Number'];
            $_SESSION['name'] = $user['Name'];
            $_SESSION['role'] = $user['Role'];

            if (isset($_POST['rememberMe'])) {
                setcookie("remembered_phone", $phone, time() + (86400 * 30), "/");
            } else {
                if (isset($_COOKIE['remembered_phone'])) {
                    setcookie("remembered_phone", "", time() - 3600, "/");
                }
            }

            if ($user['Role'] === 'Admin') {
                header("Location: ../view/admin/dashboard.php");
            } elseif ($user['Role'] === 'Manager') {
                header("Location: ../view/manager/dashboard.php");
            } else {
                header("Location: ../view/customer/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Phone number or password does not match";
            header("Location: ../view/login.php");
            exit();
        }
    } else {
        header("Location: ../view/login.php");
        exit();
    }
} else {
    $_SESSION['globalErrMsg'] = "Something went wrong.";
    header("Location: ../view/login.php");
    exit();
}
?>
