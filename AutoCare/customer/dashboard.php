<?php
require_once __DIR__ . '/../config/app.php';
require_role('customer');
$title = 'Paneli im';

$result = stmt($conn, 'SELECT COUNT(*) AS total FROM vehicles WHERE user_id = ?', 'i', [$_SESSION['user_id']])->get_result();
$vehicleCount = (int) $result->fetch_assoc()['total'];

$result = stmt($conn, 'SELECT COUNT(*) AS total FROM appointments WHERE user_id = ?', 'i', [$_SESSION['user_id']])->get_result();
$appointmentCount = (int) $result->fetch_assoc()['total'];

$rows = stmt($conn, "
    SELECT a.*, v.brand, v.model, v.plate_number, s.service_name
    FROM appointments AS a
    INNER JOIN vehicles AS v ON v.id = a.vehicle_id
    INNER JOIN services AS s ON s.id = a.service_id
    WHERE a.user_id = ?
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
    LIMIT 8
", 'i', [$_SESSION['user_id']])->get_result();

include __DIR__ . '/../includes/customer_header.php';
?>
<div class="welcome">
    <h2>Mirë se vini, <?= e($_SESSION['full_name'] ?? 'Klienti') ?></h2>
    <p>Menaxhoni automjetet dhe terminet tuaja në një vend.</p>
</div>

<div class="stats">
    <div class="stat-card"><div class="stat-top"><span>Automjetet e mia</span><span class="stat-icon">▣</span></div><h2><?= e($vehicleCount) ?></h2><small>Automjete të regjistruara</small></div>
    <div class="stat-card"><div class="stat-top"><span>Terminet</span><span class="stat-icon">▦</span></div><h2><?= e($appointmentCount) ?></h2><small>Të gjitha kërkesat për servis</small></div>
</div>

<div class="section-head"><h2>Terminet e fundit</h2><a class="btn" href="book.php">Rezervo servis</a></div>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Automjeti</th><th>Shërbimi</th><th>Data</th><th>Statusi</th></tr></thead>
        <tbody>
        <?php if (!$rows->num_rows): ?>
            <tr><td colspan="4" class="empty">Nuk keni ende termine. Mund të dërgoni kërkesën tuaj për servis.</td></tr>
        <?php else: while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><b><?= e($row['brand'] . ' ' . $row['model']) ?></b><br><span class="muted"><?= e($row['plate_number']) ?></span></td>
                <td><?= e($row['service_name']) ?></td>
                <td><?= e(date('d M Y, H:i', strtotime($row['appointment_date'] . ' ' . $row['appointment_time']))) ?></td>
                <td><span class="badge <?= e($row['status']) ?>"><?= e(['pending'=>'Në pritje','confirmed'=>'Konfirmuar','in_progress'=>'Në proces','completed'=>'Përfunduar','cancelled'=>'Anuluar'][$row['status']] ?? $row['status']) ?></span></td>
            </tr>
        <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>

<div class="section-head"><h2>Automjetet e mia</h2><a class="btn btn-light" href="vehicles.php">Menaxho automjetet</a></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
