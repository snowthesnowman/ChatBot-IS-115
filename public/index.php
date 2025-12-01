<?php
session_start();
if ($_SESSION['logged_in'] ?? false) {
$username = $_SESSION['username'] ?? 'Guest';

}
?>
<!doctype html>
<html lang="no">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IS-115 — PHP Prosjekt</title>
<link href="../css/welcome.css" rel="stylesheet">  </head>
  <body class="welcome" style="overflow:hidden;">
    <header class="header">
      <div class="wrap">
        <div class="brand">IS-115 PHP Prosjekt</div>
        <nav class="nav" aria-label="Hovednavigasjon">
         <a href="manual_calc.php">Kalkulator</a>
         <a href="om-oss.php">Om oss</a>
          <?php if (!($_SESSION['logged_in'] ?? false)) {
          echo '<a href="registrering.php">Registrer deg</a>';
          echo '<a href="login.php">Logg inn</a>';
          }
          else 
          {
            echo '<a href="../backend/logout.php"> Logg Ut</a>'; 
          }
          ?>

        </nav>
      </div>
    </header>

    <section class="hero">
      <div>
        
        <?php 
        if (isset($username)) {
            echo "<h1 class=\"title\">Velkommen, " . htmlspecialchars($username) . "!</h1>";
            echo "<p class=\"lead\">Klar for å regne litt? Chatboten din venter.</p>";
        } else {
            echo "<h1 class=\"title\">Velkommen til vårt IS-115 prosjekt</h1>";
            echo "<p class=\"lead\">En enkel kalkulator med AI-assistent for matematikk.</p>";
        }
        ?>

        <div class="cta">
          <?php    if (!($_SESSION['logged_in'] ?? false)) { //Denne kan egentlig byttes om til å ha hvis de ER logget inn men endte opp sånn her og gidder ikke endre
         echo '<a class="btn btn-primary" href="registrering.php">Registrer deg</a>'; 
         echo '<a class="btn btn-ghost" href="login.php">Logg inn</a>';
         echo '<a class="btn btn-ghost" href="manual_calc.php">Prøv kalkulator</a>';
        }
        else 
        {
         echo '<a class="btn btn-primary" href="manual_calc.php">Åpne kalkulator</a>';
         echo '<a class="btn btn-ghost" href="../backend/logout.php"> Logg Ut</a>'; 
        }

         ?>

        </div>
        
      </div>
    </section>

    <main class="main">
      <section class="section">
        <h2>Hva kan du gjøre her?</h2>
        <p>
          <strong>Kalkulator:</strong> Utfør matematiske beregninger med støtte for grunnleggende og avanserte operasjoner som potenser og parenteser.
        </p>
        <p style="margin-top:12px;">
          <strong>AI-assistent:</strong> Logg inn for å få tilgang til vår chatbot som kan hjelpe deg med matematiske spørsmål og forklaringer.
        </p>
      </section>
    </main>
  </body>
</html>
