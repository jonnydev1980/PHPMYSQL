<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$q = trim($_GET['q'] ?? '');
if ($q !== '') { $s=$conn->prepare('SELECT * FROM movies WHERE movie_name LIKE :q OR movie_desc LIKE :q ORDER BY id DESC'); $s->execute(['q'=>'%'.$q.'%']); $movies=$s->fetchAll(); }
else { $movies=$conn->query('SELECT * FROM movies ORDER BY id DESC')->fetchAll(); }
page_start('Zbulo filmat','home.php');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"><p class="text-secondary mb-0">Zgjidh filmin tënd të preferuar dhe rezervo biletat në pak hapa.</p><form class="d-flex search-box" method="get"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Kërko film..."><button class="btn btn-primary ms-2">Kërko</button></form></div>
<div class="row g-4"><?php foreach($movies as $m): ?><div class="col-sm-6 col-lg-4 col-xl-3"><article class="movie-card"><img class="movie-poster" src="movie_images/<?= rawurlencode(basename($m['movie_image'])) ?>" alt="<?= e($m['movie_name']) ?>" onerror="this.src='assets/no-poster.svg'"><div class="movie-body"><div class="d-flex justify-content-between align-items-start gap-2"><h2 class="movie-title mb-2"><?= e($m['movie_name']) ?></h2><span class="badge text-bg-light"><?= e($m['movie_quality']) ?></span></div><p class="movie-desc"><?= e(mb_strimwidth($m['movie_desc'],0,135,'...')) ?></p><div class="d-flex justify-content-between align-items-center"><span class="movie-meta">Vlerësimi: <?= (int)$m['movie_rating'] ?>/10</span><a class="btn btn-primary btn-sm" href="details.php?id=<?= (int)$m['id'] ?>">Shiko dhe rezervo</a></div></div></article></div><?php endforeach; ?><?php if(!$movies): ?><div class="col-12"><div class="panel empty-state"><div class="symbol">⌕</div><h3>Nuk u gjet asnjë film</h3><p>Provo me një emër tjetër.</p></div></div><?php endif; ?></div>
<?php page_end(); ?>
