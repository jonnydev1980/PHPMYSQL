<?php
require_once __DIR__ . '/../config/app.php';
require_role('admin');
$title = 'Paneli kryesor';

$counts = [];
$queries = [
    'Klientët' => "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'",
    'Automjetet' => "SELECT COUNT(*) AS total FROM vehicles",
    'Shërbimet' => "SELECT COUNT(*) AS total FROM services",
    'Terminet' => "SELECT COUNT(*) AS total FROM appointments"
];

foreach ($queries as $label => $sql) {
    $result = $conn->query($sql);
    $counts[$label] = (int) $result->fetch_assoc()['total'];
}

$recent = $conn->query("
    SELECT a.id, a.appointment_date, a.appointment_time, a.status,
           u.full_name, s.service_name, v.plate_number
    FROM appointments AS a
    INNER JOIN users AS u ON u.id = a.user_id
    INNER JOIN services AS s ON s.id = a.service_id
    INNER JOIN vehicles AS v ON v.id = a.vehicle_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
    LIMIT 6
");

include __DIR__ . '/../includes/admin_header.php';
?>
<div class="welcome">
    <h2>Mirë se u kthyet, <?= e($_SESSION['full_name'] ?? 'Administrator') ?></h2>
    <p>Këtu mund të shihni përmbledhjen e servisit dhe kërkesat e fundit.</p>
</div>

<div class="stats">
    <?php
    $icons = ['♙', '▣', '⚙', '▦'];
    $index = 0;
    foreach ($counts as $label => $number):
    ?>
        <div class="stat-card">
            <div class="stat-top">
                <span><?= e($label) ?></span>
                <span class="stat-icon"><?= e($icons[$index++]) ?></span>
            </div>
            <h2><?= e($number) ?></h2>
            <small>Të dhëna të regjistruara</small>
        </div>
    <?php endforeach; ?>
</div>

<div class="quick-grid">
    <div class="panel">
        <div class="panel-head">
            <h3>Terminet e fundit</h3>
            <a class="btn btn-light btn-sm" href="appointments.php">Shiko të gjitha</a>
        </div>
        <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data-table">
                <thead>
                    <tr><th>Klienti</th><th>Targa</th><th>Shërbimi</th><th>Data</th><th>Statusi</th></tr>
                </thead>
                <tbody>
                <?php if (!$recent->num_rows): ?>
                    <tr><td colspan="5" class="empty">Ende nuk ka kërkesa për termine.</td></tr>
                <?php else: ?>
                    <?php while ($row = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['full_name']) ?></td>
                            <td><?= e($row['plate_number']) ?></td>
                            <td><?= e($row['service_name']) ?></td>
                            <td><?= e(date('d M Y, H:i', strtotime($row['appointment_date'] . ' ' . $row['appointment_time']))) ?></td>
                            <td><span class="badge <?= e($row['status']) ?>"><?= e(['pending'=>'Në pritje','confirmed'=>'Konfirmuar','in_progress'=>'Në proces','completed'=>'Përfunduar','cancelled'=>'Anuluar'][$row['status']] ?? $row['status']) ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head"><h3>Veprime të shpejta</h3></div>
        <div class="link-list">
            <a href="customers.php">Menaxho klientët <b>→</b></a>
            <a href="vehicles.php">Menaxho automjetet <b>→</b></a>
            <a href="services.php">Menaxho shërbimet <b>→</b></a>
            <a href="appointments.php">Shiko terminet <b>→</b></a>
            <a href="mechanics.php">Menaxho mekanikët <b>→</b></a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
