<?php //stmt betyr statement
session_start();

$errormessage = "Ugyldig brukernavn eller passord.";
$error = false;

if ($_SESSION['logged_in'] ?? false) { //Gjør det ikke mulig å gå tilbake til siden dersom de er logget inn
    header("Location: index.php");
    exit;
}

// Database
$host = 'localhost';   
$db   = 'chatbot';      //  database name
$user = 'root';         //  MariaDB user
$pass = '123';          //  MariaDB password

// Create connection
$connect = new mysqli($host, $user, $pass, $db);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) { //Sjekker om brukernavn finnes
  $error = true;
} 
else 
{
    $user = $result->fetch_assoc(); //henter nermeste rad som matcher (brukernavn er unikt så det er bare en rad)
    $hashed_password = $user['password']; //får hashed password fra database, internt.

    if (password_verify($password, $hashed_password)) { 
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit;
        } 
}

$stmt->close();

$connect->close();

}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - IS-115 PHP Prosjekt</title>
    <link href="../css/welcome.css" rel="stylesheet"> 
    <link href="../css/behandling.css" rel="stylesheet">
</head>
<body class="welcome">
    <header class="header">
      <div class="wrap">
        <div class="brand">IS-115 PHP Prosjekt</div>
        <nav class="nav" aria-label="Hovednavigasjon">
          <a href="index.php">Hjem</a>
          <a href="manual_calc.php">Kalkulator</a>
          <a href="om-oss.php">Om oss</a>
          <a href="registrering.php">Registrer deg</a>
        </nav>
      </div>
    </header>
    
    <div class="form-container">
        <h2>Logg inn</h2>
        
        <?php if ($error) { ?>
            <div class="error-message"><?php echo htmlspecialchars($errormessage); ?></div>
        <?php } ?>

        <form action="login.php" method="POST">
            <label>Brukernavn:</label>
            <input type="text" name="username" required>

            <label>Passord:</label>
            <input type="password" name="password" required>

            <button type="submit">Logg inn</button>
        </form>
        
            <p style="text-align:center;margin-top:20px;color:#4b5563;">
            Har du ikke konto? <a href="registrering.php" style="color:#3b82f6;font-weight:700;text-decoration:none;">Registrer deg</a>
    </div>
        
</body>
</html>