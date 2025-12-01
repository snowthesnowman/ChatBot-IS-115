<?php 
session_start(); 
?>
<!doctype html>
<html lang="no">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Om oss - IS-115 PHP Prosjekt</title>
    <link href="../css/welcome.css" rel="stylesheet">
  </head>
  <body class="welcome">
    <header class="header">
      <div class="wrap">
        <div class="brand">IS-115 PHP Prosjekt</div>
        <nav class="nav" aria-label="Hovednavigasjon">
         <a href="index.php">Hjem</a>
         <a href="manual_calc.php">Kalkulator</a>
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

    <main class="main">
      <section class="section">
        <h2>Om prosjektet</h2>
        <p>
          Dette er et webprosjekt utviklet som del av emnet IS-115 ved Universitetet i Agder. 
          Målet med prosjektet er å lage en funksjonell nettside som kombinerer brukerbehandling, 
          databaser og AI-integrasjon.
        </p>
        <p style="margin-top:12px;">
          Nettsiden inneholder en manuell kalkulator hvor brukere kan utføre matematiske beregninger, 
          samt en AI-chatbot widget som kan svare på spørsmål om matematikk og kalkulatoren. 
          Chatboten er kun tilgjengelig for påloggede brukere.
        </p>
      </section>

      <section class="section" style="margin-top:20px;">
        <h2>Teamet</h2>
        <div class="grid">
          <div class="col-6">
            <div class="card">
              <h3>Mathias Thorsell</h3>
              <p>Student ved UiA</p>
              <p style="margin-top:12px;color:#4b5563;">
                21 år gammel fra Sandnes, liker å trene og spille på fritiden.
              </p>
            </div>
          </div>
          
          <div class="col-6">
            <div class="card">
              <h3>Martin Brådland</h3>
              <p>Også student ved UiA</p>
              <p style="margin-top:12px;color:#4b5563;">
                Litt eldre kar, kul fyr, liker også gaming.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="section" style="margin-top:20px;">
        <h2>Teknologi</h2>
        <p>Prosjektet er bygget med følgende teknologier:</p>
        <ul class="list">
          <li><strong>PHP</strong> - Backend-logikk og brukerbehandling</li>
          <li><strong>MySQL/MariaDB</strong> - Database for brukere og data</li>
          <li><strong>HTML/CSS</strong> - Frontend og design</li>
          <li><strong>JavaScript</strong> - Kalkulator-interaktivitet</li>
          <li><strong>ChatbotKit</strong> - AI-chatbot integrasjon</li>
        </ul>
      </section>
    </main>

  </body>
</html>