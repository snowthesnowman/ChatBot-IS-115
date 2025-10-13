<?php
// Manuell kalkulator 
// Denne filen håndterer all server logikk for den manuelle kalkulatoren, ikke veldig relevant til selve prosjektet menmen

$result = null; 
$error = null;  
$expr = ''; //Hva brukeren skrev inn

// Sjekk om brukeren har sendt inn et uttrykk via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    // 3. Kun tillatte tegn (tall, operatorer, parenteser, punktum, mellomrom)
    elseif (!preg_match('#^[0-9+\-*/^().\s]+$#', $expr)) {
        $error = 'Uttrykket inneholder ugyldige tegn';
    }

    // 4. Hvis alt er OK, prøv å evaluere uttrykket
    else {

        // Bytt ut ^ med ** for potens (PHP eval bruker **)
        $calc = str_replace('^', '**', $expr);

        // Evaluer uttrykket i et try/catch-blokk for å fange feil
        $evalCode = '$_r = ' . $calc . ';';
        set_error_handler(function($errno, $errstr) {

            // Kaster en Exception slik at try/catch fanger PHP-feil under eval
            throw new Exception($errstr);
        });
        try {

            // Forsøk å evaluere uttrykket
            eval($evalCode);
            if (isset($_r)) {
                $result = $_r; // Resultat lagres
                unset($_r);
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

// Escape uttrykket for sikker visning i HTML
$display_value = htmlspecialchars($expr, ENT_QUOTES, 'UTF-8');

// Bygg HTML for resultatet hvis vi har et resultat
$result_html = '';

if ($result !== null) { //Basic Escape
$result_html = '<div class="result"><strong>Resultat:</strong><div>' . htmlspecialchars((string)$result, ENT_QUOTES, encoding: encoding: 'UTF-8') . '</div></div>';
}

// Bygg HTML for feilmelding hvis det finnes
$error_html = '';
if ($error !== null) {
    $error_html = '<div class="result" style="background:#fee;"><strong>Feil:</strong><div>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div></div>';
}

// CSRF token for Laravel POST, Laravel krever dette for å godkjenne POST-forespørsler
$csrf = function_exists('csrf_token') ? csrf_token() : '';

// All HTML sendes ut i 1 echo-blokk for enkelhetens skyld
echo <<<HTML
<!doctype html>
<html lang="nb">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manuell kalkulator</title>
    <link href="/css/welcome.css" rel="stylesheet">
    <link href="/css/manual_calc.css" rel="stylesheet">
</head>
<body class="welcome">
    <header class="header">
      <div class="wrap">
        <div class="brand">IS-115 PHP Prosjekt</div>
        <nav class="nav" aria-label="Hovednavigasjon">
          <a href="/">Hjem</a>
          <a href="/manual_calc.php">Kalkulator</a>
          <a href="/chatbot.php">Chatbot</a>
        </nav>
      </div>
    </header>
    <main class="main">
        <section class="calc">
            <div>
                <input id="display" class="display" type="text" name="expr" value="{$display_value}" readonly>
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

                <button type="button" data-value="(" style="grid-column: span 1;">(</button>
                <button type="button" data-value=")">)</button>
                <button type="button" data-value="^">^</button>
                <button type="button" id="btn-back">DEL</button>
            </div>

            <!-- Skjema for å sende inn uttrykket til serveren -->
            <!-- En del av den CSRF token er nødvendig for Laravel -->
            <form id="calc-form" method="post" action="/manual_calc.php">
            <input type="hidden" name="_token" value="{$csrf}">

                <input type="hidden" id="expr-hidden" name="expr" value="{$display_value}">
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:10px">= Beregn</button>
            </form>

            {$result_html}

            {$error_html}

        </section>
    </main>
    <footer class="footer"> © IS-115 PHP Prosjekt</footer>
    <script src="/js/manual_calc.js"></script>
</body>
</html>
HTML;