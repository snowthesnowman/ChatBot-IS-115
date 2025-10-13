<!doctype html>
<html lang="no">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IS-115 — PHP Prosjekt</title>
<link href="css/welcome.css" rel="stylesheet">  </head>
  <body class="welcome">
    <header class="header">
      <div class="wrap">
        <div class="brand">IS-115 PHP Prosjekt</div>
        <nav class="nav" aria-label="Hovednavigasjon">
          <a href="{{ url('/') }}">Hjem</a>
          <a href="">Kalkulator</a>
          <a href="#om-oss">Om oss</a>
        </nav>
      </div>
    </header>

    <section class="hero">
      <div>
        <h1 class="title">Velkommen til vårt IS-115 PHP prosjekt</h1>
        <p class="lead">Et lite, ryddig oppsett med en manuell kalkulator og en enkel chatbot som er laget i PHP.</p>
        <div class="cta">
         <a class="btn btn-primary" href="">Chat med boten</a>
         <a class="btn btn-ghost" href="/manual_calc.php">Prøv kalkulator</a>
        </div>
      </div>
    </section>

    <main class="main">
      <section class="section">
        <h2>Om prosjektet</h2>
        <p>Dette er et undervisningsprosjekt i emnet IS-115. Målet er å vise en enkel, oversiktlig struktur i PHP, med fokus på funksjonelle sider og ryddig kode.</p>
      </section>

      <section class="grid">
        <div class="col-6">
          <div class="section">
            <h2>Funksjoner</h2>
            <div class="grid">
              <div class="col-6">
                <div class="card">
                  <h3>Legg til alle våre kule funksjoner her!</h3>
                  <p>Regn ut addisjon, subtraksjon, multiplikasjon og divisjon direkte i nettleseren, enten med vår chatbot eller alene! :D</p>
              </div>
            </div>
          </div>
        </div>

        <div id="om-oss" class="col-6">
          <div class="section">
            <h2>Om oss</h2>
            <p>Vi er to IS-115-studenter: <strong>Mathias</strong> og <strong>Martin</strong>.</p>
            <div class="grid">
              <div class="col-12">
                <div class="card">
                  <ul class="list">
                    <li>Mathias — 21 år, Sandnes</li>
                    <li>Martin — Kul student og utvikler</li>
                  </ul>
                </div>
              </div>
            </div>
            <p class="lead" style="margin-top:10px">Mål: levere et ryddig og funksjonelt IS-115 prosjekt.</p>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      IS-115 Prosjekt
    </footer>
  </body>
</html>
