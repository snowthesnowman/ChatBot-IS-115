<?php
session_start();

$result = null; 
$error = null;  
$expr = ''; //Hva brukeren skrev inn
$isLoggedIn = $_SESSION['logged_in'] ?? false;
$username = $_SESSION['username'] ?? 'Guest';

// Initialiser historikk array hvis det ikke finnes
if ($isLoggedIn && !isset($_SESSION['calc_history'])) {
    $_SESSION['calc_history'] = [];
}

// Håndter "tøm historikk" knapp
if ($isLoggedIn && isset($_POST['clear_history'])) {
    $_SESSION['calc_history'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['clear_history'])) {

    // Hent inn input fra skjemaet og fjern whitespace i start/slutt
    $expr = $_POST['expr'] ?? '';
    $expr = trim($expr);


    // Regler for validering av input 

    // 1. Tomt uttrykk er ikke lov
    if ($expr === '') {
        $error = 'Tomt uttrykk';
    }

    // 2. For langt uttrykk (beskytter mot miskbruk, typ ddos)
    elseif (strlen($expr) > 200) {
        $error = 'Uttrykk for langt';
    }

    // 3. Kun tillatte tegn
    elseif (!preg_match('#^[0-9+\-*/^().\s]+$#', $expr)) {
        $error = 'Uttrykket inneholder ugyldige tegn';
    }

    // 4. Hvis alt er OK, prøv å regn ut mattestykket
    else {

        // Bytt ut ^ med ** for potens (PHP eval bruker **)
        $calculate = str_replace('^', '**', $expr);

        // Evaluer uttrykket i et try/catch-blokk for å fange feil
        $evalCode = '$r = ' . $calculate . ';';
        set_error_handler(function($errstr) {

            // Kaster en Exception slik at try/catch fanger PHP-feil under eval
            throw new Exception($errstr);
        });
        try {

            eval($evalCode); // <-- DENNE LINJEN GJØR MATTEN
            if (isset($r)) {
                $result = $r; // Resultat lagres
                
                // Lagre i historikk hvis innlogget
                if ($isLoggedIn) {
                    $_SESSION['calc_history'][] = [
                        'expression' => $expr,
                        'result' => $result,
                        'timestamp' => date('H:i:s')
                    ];
                    
                    // Behold kun de siste 10 beregningene
                    if (count($_SESSION['calc_history']) > 10) {
                        array_shift($_SESSION['calc_history']);
                    }
                }
                
                unset($r); // Fjerner resultatet etter bruk
            } 
            else 
            {
                $error = 'Eval feilet'; 
            }
        } 

        catch (Throwable $e) { // $e fanger alle feil 
            $error = 'Feil under beregning: ' . $e->getMessage();
        } 
        finally 
        {
            restore_error_handler();
        }
    }
}

// Escape uttrykket for sikkerhetsgrunner te visning i HTML
$display_value = htmlspecialchars($expr, ENT_QUOTES, 'UTF-8');

// Bygg HTML for resultatet
$result_html = '';

if ($result !== null) { //Basic Escape, hjelper mot XSS og lignende
$result_html = '<div class="result"><strong>Resultat:</strong><div>' . htmlspecialchars((string)$result, ENT_QUOTES, 'UTF-8') . '</div></div>';
}

// Bygg HTML for feilmelding hvis det finnes
$error_html = '';
if ($error !== null) {
    $error_html = '<div class="result" style="background:#fee2e2;border-color:#fecaca;"><strong>Feil:</strong><div>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div></div>';
}



?>
<!doctype html>
<html lang="nb">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kalkulator - IS-115 PHP Prosjekt</title>
    <link href="../css/welcome.css" rel="stylesheet">
    <link href="../css/manual_calc.css" rel="stylesheet">
</head>
<body class="welcome">
    <header class="header">
      <div class="wrap">
        <div class="brand">IS-115 PHP Prosjekt</div>
        <nav class="nav" aria-label="Hovednavigasjon">
          <a href="index.php">Hjem</a>
          <a href="om-oss.php">Om oss</a>
          <?php if ($isLoggedIn) {
           echo '<a href="../backend/logout.php">Logg ut</a>';
          }
          else {
            echo '<a href="registrering.php">Registrer deg</a>';
            echo '<a href="login.php">Logg inn</a>';
        }
          ?>
        </nav>
      </div>
    </header>
    
    <main class="main">
        <div class="calc-page-layout">
            <!-- Calculator Section -->
            <section class="calc">
                <h2 style="text-align:center;margin:0 0 20px;color:#111827;">Kalkulator</h2>
                <div>
                    <input id="display" class="display" type="text" name="expr" value="<?php echo $display_value; ?>" readonly>
                </div>

                <div class="keys">
                    <button type="button" data-value="7">7</button>
                    <button type="button" data-value="8">8</button>
                    <button type="button" data-value="9">9</button>
                    <button type="button" data-value="/">/</button>

                    <button type="button" data-value="4">4</button>
                    <button type="button" data-value="5">5</button>
                    <button type="button" data-value="6">6</button>
                    <button type="button" data-value="*">*</button>

                    <button type="button" data-value="1">1</button>
                    <button type="button" data-value="2">2</button>
                    <button type="button" data-value="3">3</button>
                    <button type="button" data-value="-">-</button>

                    <button type="button" data-value="0">0</button>
                    <button type="button" data-value=".">.</button>
                    <button type="button" id="btn-clear">C</button>
                    <button type="button" data-value="+">+</button>

                    <button type="button" data-value="(">(</button>
                    <button type="button" data-value=")">)</button>
                    <button type="button" data-value="^">^</button>
                    <button type="button" id="btn-back">DEL</button>
                </div>

                <!-- Skjema for å sende inn uttrykket til serveren -->
                <form id="calc-form" method="post" action="manual_calc.php">
                    <input type="hidden" id="expr-hidden" name="expr" value="<?php echo $display_value; ?>">
                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:10px">= Beregn</button>
                </form>

                <?php echo $result_html; ?>
                <?php echo $error_html; ?>
            </section>

            <aside class="chatbot-info">
                <div class="info-card">
                    <h3>Trenger hjelp?</h3>
                    <p>Vår AI-assistent kan hjelpe deg med:</p>
                    <ul>
                        <li>✓ Forklare matematiske operasjoner</li>
                        <li>✓ Svare på spørsmål om kalkulatoren</li>
                        <li>✓ Hjelpe med komplekse utregninger</li>
                    </ul>
                    <p>
                        Klikk på chatbot-ikonet nederst til høyre for å starte en samtale! <br>
                        (Kun tilgjengelig for innloggede brukere.)
                    </p>
                </div>
                
                <?php if ($isLoggedIn && !empty($_SESSION['calc_history'])): ?>
                    <div class="info-card history-card">
                        <div class="history-header">
                            <h3>Historikk</h3>
                            <form method="post" action="manual_calc.php">
                                <button type="submit" name="clear_history" class="history-clear-btn">Tøm</button>
                            </form>
                        </div>
                        <?php foreach (array_reverse($_SESSION['calc_history']) as $item): ?>
                            <div class="history-item">
                                <span class="history-time"><?php echo htmlspecialchars($item['timestamp']); ?></span><br>
                                <strong class="history-expression"><?php echo htmlspecialchars($item['expression']); ?></strong> = 
                                <span class="history-result"><?php echo htmlspecialchars($item['result']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </aside>

        </div>
    </main>

    <script src="../js/manual_calc.js"></script>
    <?php
    if ($isLoggedIn) {
        echo '<script id="chatbotkit-widget" src="https://static.chatbotkit.com/integrations/widget/v2.js" data-widget="cmim2a2ildgsbzye8yuuyah7b"></script>';
    }
    ?>
   
</body>
</html>