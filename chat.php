<?php
include('config.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit; // Stop executing further if not logged in
}

// Retrieve user's ID and username from session
$user_id = $_SESSION['id'];
$username = $_SESSION['username'];

$id2 = $_SESSION['recipient_id'];
$user2 = $_SESSION['recipient_username'];

// Caesar Cipher function
function cipherText($str, $caesarShift, $multiplicativeKey) {
    $result = "";

    // Atbash Cipher
    $result = strrev($str);

    // Caesar Cipher
    $len = strlen($result);
    for ($i = 0; $i < $len; $i++) {
        if (ctype_alpha($result[$i])) {
            // Encrypt uppercase letters
            if (ctype_upper($result[$i])) {
                $result[$i] = chr(((ord($result[$i]) - 65 + $caesarShift) % 26) + 65);
            }
            // Encrypt lowercase letters
            else if (ctype_lower($result[$i])) {
                $result[$i] = chr(((ord($result[$i]) - 97 + $caesarShift) % 26) + 97);
            }
        }
    }

    // Multiplicative Cipher
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $mod = strlen($alphabet);
    $mod_inverse = 0;

    for ($i = 0; $i < $mod; $i++) {
        if (($multiplicativeKey * $i) % $mod == 1) {
            $mod_inverse = $i;
            break;
        }
    }

    for ($i = 0; $i < $len; $i++) {
        $char = $result[$i];
        if (ctype_alpha($char)) {
            $is_upper = ctype_upper($char);
            $char = strtolower($char);
            $index = strpos($alphabet, $char);
            if ($index !== false) {
                $index = ($index * $mod_inverse) % $mod;
                $result[$i] = $is_upper ? strtoupper($alphabet[$index]) : $alphabet[$index];
            }
        }
    }

    return $result;
}


// Insert message into database when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    // Retrieve message from form
    $message = $_POST['message'];
    
    // Encrypt message using Caesar Cipher
    $encrypted_message = cipherText($message, 3 ,7); // You can change the shift value as needed

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO message (user_id, sender_id, msg) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $id2, $encrypted_message);
    if ($stmt->execute()) {
        // Redirect to prevent form resubmission
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// Retrieve messages from the database
$stmt = $conn->prepare("SELECT * FROM message WHERE user_id = ? AND sender_id = ? OR user_id = ? AND sender_id = ?");
$stmt->bind_param("iiii", $user_id, $id2, $id2, $user_id);
$stmt->execute();
$result = $stmt->get_result();

function decipherText($str, $caesarShift, $multiplicativeKey) {
    $result = "";

    // Reverse Multiplicative Cipher
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $mod = strlen($alphabet);
    $mod_inverse = 0;

    // Find the multiplicative inverse
    for ($i = 0; $i < $mod; $i++) {
        if (($multiplicativeKey * $i) % $mod == 1) {
            $mod_inverse = $i;
            break;
        }
    }

    // Apply the multiplicative inverse
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $char = $str[$i];
        if (ctype_alpha($char)) {
            $is_upper = ctype_upper($char);
            $char = strtolower($char);
            $index = strpos($alphabet, $char);
            if ($index !== false) {
                $index = ($index * $mod_inverse) % $mod;
                $result .= $is_upper ? strtoupper($alphabet[$index]) : $alphabet[$index];
            }
        } else {
            $result .= $char;
        }
    }

    // Reverse Caesar Cipher
    for ($i = 0; $i < $len; $i++) {
        if (ctype_alpha($result[$i])) {
            // Decrypt uppercase letters
            if (ctype_upper($result[$i])) {
                $result[$i] = chr(((ord($result[$i]) - 65 - $caesarShift + 26) % 26) + 65);
            }
            // Decrypt lowercase letters
            else if (ctype_lower($result[$i])) {
                $result[$i] = chr(((ord($result[$i]) - 97 - $caesarShift + 26) % 26) + 97);
            }
        }
    }

    // Reverse Atbash Cipher
    $result = strrev($result);

    return $result;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rahul's Chat</title>
    <style>
        /* Font */
        body {
            font-family: 'Open Sans', sans-serif;
        }

        /* Background and Container */
        .background {
            background-image: url("https://d1tgh8fmlzexmh.cloudfront.net/ccbp-static-website/chatbg.png");
            height: 100vh;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Chat Container */
        .chat-container {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Chat Message */
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 10px;
        }

        /* Sender Message */
        .sender {
            background-color: #47a3f3;
            color: white;
            align-self: flex-end;
        }

        /* Receiver Message */
        .receiver {
            background-color: #52606d;
            color: white;
            align-self: flex-start;
        }

        /* Input Field and Button */
        .send {
            margin-top: 20px;
            width: 100%;
        }

        .send input[type="text"] {
            width: 92%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .send button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #47a3f3;
            color: white;
            cursor: pointer;
        }

        .send button:hover {
            background-color: #2e81d4;
        }
    </style>
</head>
<body>
    <div class="background">
        <center><h1 style="color: white;"><?php echo $user2 ; ?></h1></center>
        <div class="chat-container">
            <?php
                // Display each message
                while ($row = $result->fetch_assoc()) {
                    // Decrypt message using Caesar Cipher
                    $decrypted_message = decipherText($row['msg'], 3 , 15); // Decrypt with the opposite shift value

                    // Determine message class based on sender
                    $message_class = ($row['sender_id'] == $user_id) ? 'sender' : 'receiver';

                    // Display message with appropriate class
                    echo '<div class="message ' . $message_class . '">' . htmlspecialchars($decrypted_message) . '</div>';
                }
            ?>
        </div>
        <div class="send">
            <form method="post">
                <input type="text" name="message" placeholder="Type your message...">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</body>
</html>
