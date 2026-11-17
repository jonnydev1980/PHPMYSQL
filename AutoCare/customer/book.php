<?php
require_once __DIR__ . '/../config/app.php';
require_role('customer');
$title = 'Book a Service';
$error = '';

$vehicles = stmt($conn, 'SELECT id, brand, model, plate_number FROM vehicles WHERE user_id = ? ORDER BY brand, model', 'i', [$_SESSION['user_id']])->get_result()->fetch_all(MYSQLI_ASSOC);
$services = $conn->query('SELECT id, service_name, price, duration FROM services ORDER BY service_name')->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    $validVehicle = stmt($conn, 'SELECT id FROM vehicles WHERE id = ? AND user_id = ?', 'ii', [$vehicleId, $_SESSION['user_id']])->get_result()->num_rows > 0;
    $validService = stmt($conn, 'SELECT id FROM services WHERE id = ?', 'i', [$serviceId])->get_result()->num_rows > 0;
    $appointmentTimestamp = strtotime($date . ' ' . $time);

    if (!$validVehicle || !$validService) {
        $error = 'Choose one of your vehicles and an available service.';
    } elseif (!$date || !$time || $appointmentTimestamp === false || $appointmentTimestamp < time()) {
        $error = 'Choose a future date and time.';
    } else {
        stmt($conn, 'INSERT INTO appointments (user_id, vehicle_id, service_id, appointment_date, appointment_time, notes) VALUES (?, ?, ?, ?, ?, ?)', 'iiisss', [$_SESSION['user_id'], $vehicleId, $serviceId, $date, $time, $notes]);
        flash('Your appointment request has been sent.');
        redirect('dashboard.php');
    }
}

include __DIR__ . '/../includes/customer_header.php';
?>
<div class="form-card">
    <h3>Appointment details</h3>
    <p class="muted">Choose your vehicle, select a service and request a suitable time. The workshop will confirm your appointment.</p>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

    <?php if (!$vehicles): ?>
        <div class="alert error">Register a vehicle before booking. <a href="vehicles.php">Add vehicle</a></div>
    <?php elseif (!$services): ?>
        <div class="alert error">There are no services available at the moment.</div>
    <?php else: ?>
        <form method="post">
            <div class="form-grid">
                <div class="field full">
                    <label>Vehicle</label>
                    <select name="vehicle_id" required>
                        <option value="">Select your vehicle</option>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= (int) $vehicle['id'] ?>"><?= e($vehicle['brand'] . ' ' . $vehicle['model'] . ' · ' . $vehicle['plate_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field full">
                    <label>Service</label>
                    <select name="service_id" required>
                        <option value="">Select a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= (int) $service['id'] ?>"><?= e($service['service_name']) ?> — €<?= number_format((float) $service['price'], 2) ?> (<?= (int) $service['duration'] ?> min)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field"><label>Date</label><input type="date" name="appointment_date" min="<?= e(date('Y-m-d')) ?>" required></div>
                <div class="field"><label>Time</label><input type="time" name="appointment_time" required></div>
                <div class="field full"><label>Additional notes</label><textarea name="notes" placeholder="Tell us briefly what your vehicle needs..."></textarea></div>
            </div>
            <div class="form-actions"><button class="btn">Send appointment request</button><a class="btn btn-light" href="dashboard.php">Cancel</a></div>
        </form>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
