
<?php
require_once __DIR__ . '/../config/app.php';

require_role('admin');

$title = 'Appointments';

$allowedStatuses = [
    'pending',
    'approved',
    'completed',
    'cancelled'
];

// Save appointment changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $mechanicId = (int)($_POST['mechanic_id'] ?? 0);

    if ($id <= 0 || !in_array($status, $allowedStatuses, true)) {
        flash('Invalid appointment information.', 'error');
        redirect('appointments.php');
    }

    try {

        // Check that appointment exists
        $check = stmt(
            $conn,
            "SELECT id FROM appointments WHERE id = ?",
            "i",
            [$id]
        );

        if (!$check->get_result()->fetch_assoc()) {
            flash('Appointment not found.', 'error');
            redirect('appointments.php');
        }

        // Check selected mechanic
        if ($mechanicId > 0) {

            $checkMechanic = stmt(
                $conn,
                "SELECT id FROM mechanics WHERE id = ?",
                "i",
                [$mechanicId]
            );

            if (!$checkMechanic->get_result()->fetch_assoc()) {
                flash('Selected mechanic does not exist.', 'error');
                redirect('appointments.php');
            }
        }

        // Update appointment
        stmt(
            $conn,
            "UPDATE appointments
             SET mechanic_id = NULLIF(?, 0),
                 status = ?
             WHERE id = ?",
            "isi",
            [$mechanicId, $status, $id]
        );

        flash('Appointment updated successfully.');

    } catch (Throwable $e) {

        error_log($e->getMessage());
        flash('Unable to update appointment. Please check the database.', 'error');
    }

    redirect('appointments.php');
}

// Get appointments
$rows = $conn->query("
    SELECT
        a.id,
        a.user_id,
        a.vehicle_id,
        a.service_id,
        a.mechanic_id,
        a.appointment_date,
        a.appointment_time,
        a.status,
        u.full_name AS customer_name,
        s.service_name,
        v.brand,
        v.model,
        v.plate_number,
        m.full_name AS mechanic_name

    FROM appointments a

    INNER JOIN users u
        ON u.id = a.user_id

    INNER JOIN services s
        ON s.id = a.service_id

    INNER JOIN vehicles v
        ON v.id = a.vehicle_id

    LEFT JOIN mechanics m
        ON m.id = a.mechanic_id

    ORDER BY
        a.appointment_date DESC,
        a.appointment_time DESC
");

// Get mechanics
$mechanics = $conn->query("
    SELECT id, full_name
    FROM mechanics
    ORDER BY full_name ASC
");

$mechanicList = $mechanics->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/../includes/admin_header.php';
?>

<div class="toolbar">
    <p class="muted">
        Review customer requests, assign a mechanic and update the work status.
    </p>

    <input
        class="search"
        placeholder="Search appointments..."
        data-search="#appointments"
    >
</div>

<div class="table-wrap">

    <table class="data-table" id="appointments">

        <thead>
            <tr>
                <th>Customer / Vehicle</th>
                <th>Service</th>
                <th>Appointment</th>
                <th>Mechanic</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!$rows->num_rows): ?>

            <tr>
                <td colspan="6" class="empty">
                    No appointments have been received yet.
                </td>
            </tr>

        <?php else: ?>

            <?php while ($row = $rows->fetch_assoc()): ?>

                <tr>

                    <td>
                        <b><?= e($row['customer_name']) ?></b>
                        <br>
                        <span class="muted">
                            <?= e(
                                $row['brand'] . ' ' .
                                $row['model'] . ' · ' .
                                $row['plate_number']
                            ) ?>
                        </span>
                    </td>

                    <td>
                        <?= e($row['service_name']) ?>
                    </td>

                    <td>
                        <?= e(date(
                            'd M Y, H:i',
                            strtotime(
                                $row['appointment_date'] . ' ' .
                                $row['appointment_time']
                            )
                        )) ?>
                    </td>

                    <td>
                        <?= e($row['mechanic_name'] ?: 'Not assigned') ?>
                    </td>

                    <td>
                        <span class="badge <?= e($row['status']) ?>">
                            <?= e(ucwords(
                                str_replace('_', ' ', $row['status'])
                            )) ?>
                        </span>
                    </td>

                    <td>

                        <form method="post" class="actions">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int)$row['id'] ?>"
                            >

                            <select
                                name="mechanic_id"
                                aria-label="Assign mechanic"
                                required
                            >

                                <option value="0">Unassigned</option>

                                <?php foreach ($mechanicList as $mechanic): ?>

                                    <option
                                        value="<?= (int)$mechanic['id'] ?>"
                                        <?= (
                                            (int)$row['mechanic_id'] ===
                                            (int)$mechanic['id']
                                        ) ? 'selected' : '' ?>
                                    >
                                        <?= e($mechanic['full_name']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <select
                                name="status"
                                aria-label="Appointment status"
                                required
                            >

                                <?php foreach ($allowedStatuses as $status): ?>

                                    <option
                                        value="<?= e($status) ?>"
                                        <?= $row['status'] === $status
                                            ? 'selected' : '' ?>
                                    >
                                        <?= e(ucwords(
                                            str_replace('_', ' ', $status)
                                        )) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <button type="submit" class="btn btn-sm">
                                Save
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
