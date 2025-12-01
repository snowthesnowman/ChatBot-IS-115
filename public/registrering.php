<?php
session_start();

$success = '';
$error = '';


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

// Validation functions
function validerEpost(string $epost): array {
    if (filter_var($epost, FILTER_VALIDATE_EMAIL) !== false) {
        return ['valid' => true, 'error' => ''];
    }
    return ['valid' => false, 'error' => 'Ikke en gyldig e-postadresse'];
}

function validerPassord(string $passord): array {
    $feil = [];
    
    if (strlen($passord) < 9) $feil[] = "minst 9 tegn";
    if (!preg_match('/[A-ZÆØÅ]/', $passord)) $feil[] = "minst en stor bokstav";
    if (preg_match_all('/[0-9]/', $passord) < 2) $feil[] = "minst to tall";
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $passord)) $feil[] = "minst ett spesialtegn";
    
    if (empty($feil)) {
        return ['valid' => true, 'error' => ''];
    }
    return ['valid' => false, 'error' => "Passord må ha: " . implode(", ", $feil)];
}

function validerMobilnummer(string $mobilnummer): array {
    $mobilnummer = str_replace(' ', '', $mobilnummer);
    $feil = [];
    
    if (strlen($mobilnummer) !== 8) $feil[] = "8 sifre";
    if (!ctype_digit($mobilnummer)) $feil[] = "kun tall";
    if (strlen($mobilnummer) === 8 && !in_array($mobilnummer[0], ['4', '9'])) $feil[] = "starte med 4 eller 9";
    
    if (empty($feil)) {
        return ['valid' => true, 'error' => ''];
    }
    return ['valid' => false, 'error' => "Mobilnummer må: " . implode(", ", $feil)];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $phone    = trim($_POST["phone"] ?? "");

    // Validate all inputs
    $epostValidering = validerEpost($email);
    $passordValidering = validerPassord($password);
    $tlfValidering = validerMobilnummer($phone);

    if (!$epostValidering['valid']) {
        $error = $epostValidering['error'];
    } elseif (!$passordValidering['valid']) {
        $error = $passordValidering['error'];
    } elseif (!$tlfValidering['valid']) {
        $error = $tlfValidering['error'];
    } elseif (strlen($username) < 3) {
        $error = "Brukernavn må være minst 3 tegn";
    } else {
        // All validation passed - insert into DB
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, phone)
                VALUES (?, ?, ?, ?)";

        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $phone);

        if ($stmt->execute()) {
            $success = "Konto opprettet! Du kan nå logge inn.";
        } else {
            // Check for duplicate username/email
            if (strpos($stmt->error, 'Duplicate') !== false) {
                $error = "Brukernavn eller e-post finnes allerede";
            } else {
                $error = "Feil ved registrering: " . $stmt->error;
            }
        }

        $stmt->close();
    }
    
    $connect->close();
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrer deg - IS-115 PHP Prosjekt</title>
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
          <a href="login.php">Logg inn</a>
        </nav>
      </div>
    </header>
    
    <div class="form-container">
        <h2>Opprett konto</h2>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="registrering.php" method="POST">
            <label>Brukernavn (minst 3 tegn):</label>
            <input type="text" name="username" required minlength="3">

            <label>E-post:</label>
            <input type="email" name="email" required>

            <label>Passord (minst 9 tegn, 1 stor bokstav, 2 tall, 1 spesialtegn):</label>
            <input type="password" name="password" required minlength="9">

            <label>Telefonnummer (8 siffer, start med 4 eller 9):</label>
            <input type="text" name="phone" required pattern="[49][0-9]{7}" placeholder="4XXXXXXX eller 9XXXXXXX">

            <button type="submit">Registrer</button>
        </form>
        
        <p style="text-align:center;margin-top:20px;color:#4b5563;">
            Har du allerede konto? <a href="login.php" style="color:#3b82f6;font-weight:700;text-decoration:none;">Logg inn</a>
        </p>
    </div>
</body>
</html>