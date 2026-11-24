<?php
require_once __DIR__ . '/../config/app.php';
require_role('customer');
$title = 'Book a Shërbimi';
$error = '';

$vehicles = stmt($conn, 'SELECT id, brand, model, plate_number FROM vehicles WHERE user_id = ? ORDER BY brand, model', 'i', [$_SESSION['user_id']])->get_result()->fetch_all(MYSQLI_ASSOC);
$services = $conn->query('SELECT id, service_name, price, duration FROM services ORDER BY service_name')->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    $validAutomjeti = stmt($conn, 'SELECT id FROM vehicles WHERE id = ? AND user_id = ?', 'ii', [$vehicleId, $_SESSION['user_id']])->get_result()->num_rows > 0;
    $validShërbimi = stmt($conn, 'SELECT id FROM services WHERE id = ?', 'i', [$serviceId])->get_result()->num_rows > 0;
    $appointmentTimestamp = strtotime($date . ' ' . $time);

    if (!$validAutomjeti || !$validShërbimi) {
        $error = 'Zgjidhni një nga automjetet tuaja dhe një shërbim.';
    } elseif (!$date || !$time || $appointmentTimestamp === false || $appointmentTimestamp < time()) {
        $error = 'Zgjidhni një datë dhe orë në të ardhmen.';
    } else {
        stmt($conn, 'INSERT INTO appointments (user_id, vehicle_id, service_id, appointment_date, appointment_time, notes) VALUES (?, ?, ?, ?, ?, ?)', 'iiisss', [$_SESSION['user_id'], $vehicleId, $serviceId, $date, $time, $notes]);
        flash('Kërkesa juaj për termin u dërgua.');
        redirect('dashboard.php');
    }
}

include __DIR__ . '/../includes/customer_header.php';
?>
<div class="form-card">
    <h3>Detajet e terminit</h3>
    <p class="muted">Zgjidhni automjetin, shërbimin dhe kohën e përshtatshme. Servisi do ta konfirmojë kërkesën tuaj.</p>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

    <?php if (!$vehicles): ?>
        <div class="alert error">Regjistro automjet before booking. <a href="vehicles.php">Shto automjet</a></div>
    <?php elseif (!$services): ?>
        <div class="alert error">Për momentin nuk ka shërbime të disponueshme.</div>
    <?php else: ?>
        <form method="post">
            <div class="form-grid">
                <div class="field full">
                    <label>Automjeti</label>
                    <select name="vehicle_id" required>
                        <option value="">Zgjidhni automjetin tuaj</option>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= (int) $vehicle['id'] ?>"><?= e($vehicle['brand'] . ' ' . $vehicle['model'] . ' · ' . $vehicle['plate_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field full">
                    <label>Shërbimi</label>
                    <select name="service_id" required>
                        <option value="">Zgjidhni shërbimin</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= (int) $service['id'] ?>"><?= e($service['service_name']) ?> — €<?= number_format((float) $service['price'], 2) ?> (<?= (int) $service['duration'] ?> min)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field"><label>Data</label><input type="date" name="appointment_date" min="<?= e(date('Y-m-d')) ?>" required></div>
                <div class="field"><label>Ora</label><input type="time" name="appointment_time" required></div>
                <div class="field full"><label>Shënime shtesë</label><textarea name="notes" placeholder="Përshkruani shkurtimisht çfarë i nevojitet automjetit..."></textarea></div>
            </div>
            <div class="form-actions"><button class="btn">Dërgo kërkesën për termin</button><a class="btn btn-light" href="dashboard.php">Anulo</a></div>
        </form>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
