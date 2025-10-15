<!doctype html>
<html lang="nb">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ChatBot</title>
    <link href="/css/welcome.css" rel="stylesheet">
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

    <div class="container">
        <h1>ChatBot</h1>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js"></script>
</body>
</html>
