<?php
include('config.php');
session_start();

// Caesar Cipher Key
$cipher_key = 3; // Shift each letter by 3 positions (can be any integer)

// Function to encrypt plaintext using Caesar cipher
function caesar_encrypt($plaintext, $key) {
    $ciphertext = "";
    $length = strlen($plaintext);
    for ($i = 0; $i < $length; $i++) {
        // Encrypt uppercase letters
        if (ctype_upper($plaintext[$i])) {
            $ciphertext .= chr((ord($plaintext[$i]) + $key - 65) % 26 + 65);
        }
        // Encrypt lowercase letters
        else if (ctype_lower($plaintext[$i])) {
            $ciphertext .= chr((ord($plaintext[$i]) + $key - 97) % 26 + 97);
        }
        // Keep non-alphabetic characters unchanged
        else {
            $ciphertext .= $plaintext[$i];
        }
    }
    return $ciphertext;
}

// Function to decrypt ciphertext using Caesar cipher
function caesar_decrypt($ciphertext, $key) {
    return caesar_encrypt($ciphertext, 26 - $key); // Decryption is shifting in reverse
}

// Signup Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Encrypt password using Caesar cipher
    $encrypted_password = caesar_encrypt($password, $cipher_key);

    $sql = "INSERT INTO login (username, email, password) VALUES ('$username', '$email', '$encrypted_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Sign up successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Login Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Decrypt password using Caesar cipher and verify
        $decrypted_password = caesar_decrypt($row['password'], $cipher_key);
        if ($decrypted_password === $password) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("location:search.php");
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "User not found";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login & Signup</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    
    .container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 400px;
    }
    
    .form-header {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    
    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    
    .form-group button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }
    
    .form-group button:hover {
        background-color: #0056b3;
    }
    
    .form-group .switch-form {
        text-align: center;
    }
    
    .form-group .switch-form a {
        color: #007bff;
        text-decoration: none;
    }
    
    .form-group .switch-form a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <div class="form-header">
        <h2>Login</h2>
    </div>
    <form id="login-form" action="" method="post">
        <div class="form-group">
            <label for="login-email">Email</label>
            <input type="email" id="login-email" name="email" required>
        </div>
        <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" name="login">Login</button>
        </div>
        <div class="form-group switch-form">
            <span>Don't have an account? <a href="#" id="signup-link">Sign Up</a></span>
        </div>
    </form>
</div>

<div class="container" style="margin-left: 20px;">
    <div class="form-header">
        <h2>Sign Up</h2>
    </div>
    <form id="signup-form" action="" method="post">
        <div class="form-group">
            <label for="signup-name">Name</label>
            <input type="text" id="signup-name" name="username" required>
        </div>
        <div class="form-group">
            <label for="signup-email">Email</label>
            <input type="email" id="signup-email" name="email" required>
        </div>
        <div class="form-group">
            <label for="signup-password">Password</label>
            <input type="password" id="signup-password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" name="signup">Sign Up</button>
        </div>
        <div class="form-group switch-form">
            <span>Already have an account? <a href="#" id="login-link">Login</a></span>
        </div>
    </form>
</div>

<script>
    document.getElementById('login-form').style.display = 'none';
        document.getElementById('signup-form').style.display = 'block';
    document.getElementById('signup-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('signup-form').style.display = 'block';
    });

    document.getElementById('login-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('signup-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    });
</script>
</body>
</html>
