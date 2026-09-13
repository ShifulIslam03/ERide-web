<?php
session_start();
require_once '../model/User.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";
$adminPhone = $_SESSION['phone'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'approveManager') {
        $phone = htmlspecialchars(trim($_POST['phone']));
        approveManager($phone, $adminPhone);
        $_SESSION['successMsg'] = "Manager request approved";
        header("Location: ../view/admin/managerRequests.php");
        exit();

    } elseif ($action === 'rejectManager') {
        $phone = htmlspecialchars(trim($_POST['phone']));
        rejectManager($phone, $adminPhone);
        $_SESSION['successMsg'] = "Manager request rejected";
        header("Location: ../view/admin/managerRequests.php");
        exit();

    } elseif ($action === 'updateRole') {
        $phone = htmlspecialchars(trim($_POST['phone']));
        $newRole = htmlspecialchars(trim($_POST['role']));
        if (in_array($newRole, ['Customer', 'Manager', 'Admin'])) {
            updateUserRole($phone, $newRole);
            $_SESSION['successMsg'] = "Role updated successfully";
        } else {
            $_SESSION['globalErrMsg'] = "Invalid role";
        }
        header("Location: ../view/admin/users.php");
        exit();

    } elseif ($action === 'deleteUser') {
        $phone = htmlspecialchars(trim($_POST['phone']));
        deleteUser($phone);
        $_SESSION['successMsg'] = "User account deleted";
        header("Location: ../view/admin/users.php");
        exit();

    } else {
        $_SESSION['globalErrMsg'] = "Unknown action requested";
        header("Location: ../view/admin/dashboard.php");
        exit();
    }
} else {
    header("Location: ../view/admin/dashboard.php");
    exit();
}
?>
