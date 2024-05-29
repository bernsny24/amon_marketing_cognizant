<?php
$conn = new mysqli('localhost', 'root', '', 'myapp');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $loginPassword = $_POST['Login_Password'];
    $confirmLoginPassword = $_POST['confirm_Login_Password'];
    $withdrawPassword = $_POST['Withdraw_Password'];
    $invitation_code = $_POST['invitation_code'];

    if ($loginPassword !== $confirmLoginPassword) {
        echo json_encode(['success' => false, 'message' => "Passwords don't match"]);
        exit();
    }

    if ($loginPassword === $withdrawPassword) {
        echo json_encode(['success' => false, 'message' => "Withdraw password must be different from login password"]);
        exit();
    }

    $hashedLoginPassword = password_hash($loginPassword, PASSWORD_DEFAULT);
    $hashedWithdrawPassword = password_hash($withdrawPassword, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, phone_number, password, withdraw_password, invitation_code)
            VALUES ('$username', '$phone_number', '$hashedLoginPassword', '$hashedWithdrawPassword', '$invitation_code')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'username' => $username, 'invitation_code' => $invitation_code]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error: " . $sql . "<br>" . $conn->error]);
    }
}
?>
