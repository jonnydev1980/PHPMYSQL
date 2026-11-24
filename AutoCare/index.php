<?php
require_once __DIR__ . '/config/app.php';
if (!empty($_SESSION['user_id'])) {
    redirect(($_SESSION['role'] === 'admin' ? 'admin/' : 'customer/') . 'dashboard.php');
}
$services = [
    ['01', 'Diagnostikim kompjuterik', 'Kontroll i sistemeve elektronike dhe identifikim i defekteve me pajisje diagnostikuese.', '⌁'],
    ['02', 'Servisim periodik', 'Ndërrim i vajit, filtrave dhe kontrolle të rregullta për funksionim të sigurt të automjetit.', '◉'],
    ['03', 'Sistemi i frenimit', 'Kontroll dhe mirëmbajtje e frenave, disqeve dhe elementeve përkatëse.', '◈'],
    ['04', 'Kontroll i motorit', 'Kontroll i gjendjes së motorit dhe vlerësim i problemeve në funksionim.', '⚙'],
    ['05', 'Pjesët e shasisë', 'Kontroll i amortizatorëve, drejtimit dhe pjesëve të ndërlidhura.', '▤'],
    ['06', 'Kontroll para udhëtimit', 'Kontroll i përgjithshëm i automjetit para rrugëve të gjata dhe udhëtimeve.', '✓'],
];
?>
<!doctype html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AutoCare - servis dhe mirëmbajtje e automjeteve. Rezervo termin dhe menaxho shërbimet online.">
    <title>AutoCare | Kujdes për çdo kilometër</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="public-page">
<header class="site-header">
    <div class="site-nav-wrap">
        <a class="site-logo" href="index.php">Auto<span>Care</span><small>SERVIS AUTOMJETESH</small></a>
        <button class="mobile-menu" type="button" aria-label="Hap menynë" onclick="document.querySelector('.site-nav').classList.toggle('open')">☰</button>
        <nav class="site-nav">
            <a class="current" href="#ballina">Ballina</a>
            <a href="#rreth-nesh">Rreth nesh</a>
            <a href="#sherbimet">Shërbimet</a>
            <a href="#pse-ne">Pse AutoCare</a>
            <a href="#kontakti">Kontakti</a>
        </nav>
        <div class="nav-actions">
            <a class="nav-login" href="login.php">Kyçu</a>
            <a class="btn" href="register.php">Krijo llogari</a>
        </div>
    </div>
</header>

<main>
<section class="hero" id="ballina">
    <div class="hero-shade"></div>
    <div class="hero-inner">
        <div class="hero-copy">
            <span class="hero-kicker"><i></i> KUJDES PROFESIONAL PËR AUTOMJETIN TUAJ</span>
            <h1>Udhëtoni të qetë.<br><span>Ne kujdesemi për pjesën tjetër.</span></h1>
            <p>Mirëmbajtje, diagnostikim dhe servis për automjetin tuaj, me komunikim të qartë dhe organizim të thjeshtë të termineve.</p>
            <div class="hero-buttons">
                <a class="btn btn-hero" href="register.php">Rezervo një termin <b>↗</b></a>
                <a class="btn-outline" href="#sherbimet">Shiko shërbimet</a>
            </div>
            <div class="hero-note"><span>✓</span> Terminet mund t’i kërkoni online, në çdo kohë.</div>
        </div>
        <div class="hero-card">
            <span class="hero-card-label">AUTOCARE · SHËRBIMI YNË</span>
            <strong>Kujdes i duhur,<br>në kohën e duhur.</strong>
            <p>Një vend për mirëmbajtjen dhe historikun e shërbimeve të automjetit tuaj.</p>
            <a href="login.php">Hyni në llogarinë tuaj <span>→</span></a>
        </div>
    </div>
    <div class="hero-bottom"><span>01 / 04</span><span>SHËRBIM · BESUESHMËRI · KUJDES</span></div>
</section>

<section class="intro-section section-pad" id="rreth-nesh">
    <div class="intro-image">
        <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=1100&q=85" alt="Punëtor duke kontrolluar automjetin në servis">
        <div class="image-caption"><b>AutoCare</b><span>Kujdes për automjetin tuaj</span></div>
    </div>
    <div class="intro-copy">
        <span class="section-kicker">RRETH KOMPANISË</span>
        <h2>Automjeti juaj meriton<br><em>kujdes të vazhdueshëm.</em></h2>
        <p>AutoCare është një platformë për organizimin e shërbimeve të autoservisit dhe komunikimin me klientët. Qëllimi ynë është ta bëjmë procesin e mirëmbajtjes më të qartë dhe më të lehtë.</p>
        <p>Përmes sistemit tonë, klientët mund të regjistrojnë automjetet, të kërkojnë termine dhe të përcjellin statusin e kërkesave të tyre. Ekipi i servisit i menaxhon kërkesat dhe organizon punët në një vend të vetëm.</p>
        <a class="text-link" href="register.php">Mëso si funksionon <span>→</span></a>
    </div>
</section>

<section class="services-section section-pad" id="sherbimet">
    <div class="section-heading">
        <div><span class="section-kicker">ÇFARË OFROJMË</span><h2>Shërbimet tona</h2></div>
        <p>Shërbime të organizuara për nevojat e përditshme të automjetit tuaj.</p>
    </div>
    <div class="service-grid">
        <?php foreach ($services as $service): ?>
        <article class="service-card">
            <div class="service-top"><span class="service-number"><?= e($service[0]) ?></span><span class="service-icon"><?= e($service[3]) ?></span></div>
            <h3><?= e($service[1]) ?></h3>
            <p><?= e($service[2]) ?></p>
            <a href="register.php" aria-label="Kërko termin për <?= e($service[1]) ?>">Kërko termin <span>↗</span></a>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="why-section section-pad" id="pse-ne">
    <div class="why-copy">
        <span class="section-kicker">PSE AUTOCARE</span>
        <h2>Më pak paqartësi.<br><em>Më shumë kontroll.</em></h2>
        <p>Ne besojmë se kujdesi për automjetin fillon me informim të qartë dhe organizim të mirë.</p>
    </div>
    <div class="why-list">
        <div><span>01</span><section><h3>Termine të organizuara</h3><p>Dërgoni kërkesën për termin online dhe përcillni përgjigjen nga servisi.</p></section></div>
        <div><span>02</span><section><h3>Të dhënat e automjetit në një vend</h3><p>Regjistroni automjetet tuaja dhe qasuni më lehtë në të dhënat e tyre.</p></section></div>
        <div><span>03</span><section><h3>Informim për statusin</h3><p>Shikoni nëse kërkesa është në pritje, konfirmuar apo përfunduar.</p></section></div>
    </div>
</section>

<section class="cta-section" id="kontakti">
    <div><span class="section-kicker">FILLONI SOT</span><h2>Koha për servisin e radhës?</h2><p>Krijoni llogarinë tuaj dhe dërgoni kërkesën për termin në disa hapa.</p></div>
    <a class="btn btn-hero" href="register.php">Regjistrohu tani <b>↗</b></a>
</section>
</main>

<footer class="site-footer">
    <a class="site-logo" href="index.php">Auto<span>Care</span><small>SERVIS AUTOMJETESH</small></a>
    <p>Mirëmbajtje e organizuar. Kujdes për çdo kilometër.</p>
    <div><a href="#ballina">Ballina</a><a href="#sherbimet">Shërbimet</a><a href="login.php">Kyçu</a><a href="register.php">Regjistrohu</a></div>
    <small>© <?= date('Y') ?> AutoCare. Të gjitha të drejtat e rezervuara.</small>
</footer>
</body>
</html>
