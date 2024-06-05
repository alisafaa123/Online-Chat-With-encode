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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['open_chat'])) {
    // Retrieve username from form submission
    $entered_username = $_POST['username'];

    // Query to check if the entered username exists
    $sql = "SELECT id, username FROM login WHERE username='$entered_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username exists, save its ID and username in session
        $row = $result->fetch_assoc();
        $_SESSION['recipient_id'] = $row['id'];
        $_SESSION['recipient_username'] = $row['username'];
        // Redirect to chat page or perform any other action
        header("location: chat.php");
        exit; // Stop executing further
    } else {
        echo "Username does not exist.";
    }
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
    <center><h1 style="color: white;">Welcome <?php echo $username ; ?> to the secret chat web</h1></center>
    <center><h2 style="color: white;">Enter the recipient's username</h2></center>
    <hr style="width: 100%;">
    <div class="send">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <center> <input type="text" name="username" placeholder="Recipient's username" required></center>
        <br>
        <br>
        <center><button type="submit" name="open_chat">Open Chat</button></center>
      </form>
    </div>
  </div>
</body>
</html>
