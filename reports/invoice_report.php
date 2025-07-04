<?php
include("../config/db.php");

$errors = [];
$invoices = [];
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$start_date || !$end_date) {
        $errors[] = "Both start and end dates are required.";
    } else if ($start_date > $end_date) {
        $errors[] = "Start date cannot be after end date.";
    } else {
      $sql = "
        SELECT
          i.invoice_no,
          i.date,
          CONCAT(c.first_name,' ',c.last_name) AS customer_name,
          d.district                            AS customer_district,
          i.item_count,
          i.amount
        FROM invoice i
        JOIN customer c ON i.customer = c.id
        JOIN district d ON c.district = d.id
        WHERE i.date BETWEEN ? AND ?
        ORDER BY i.date DESC
      ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
    }
}
?>

<?php include("../partials/header.php"); ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-file-earmark-text me-2"></i>Invoice Report</h2>
    <div>
      <a href="../index.php" class="btn btn-outline-primary">
        <i class="bi bi-house-door me-1"></i>Home
      </a>
    </div>
  </div>

  <form method="POST" class="row g-3 mb-4">
    <div class="col-auto">
      <label>Start Date</label>
      <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" class="form-control" required>
    </div>
    <div class="col-auto">
      <label>End Date</label>
      <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" class="form-control" required>
    </div>
    <div class="col-auto align-self-end">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-search me-1"></i>Search
      </button>
      <a href="invoice_report.php" class="btn btn-secondary">
        <i class="bi bi-arrow-clockwise me-1"></i>Reset
      </a>
    </div>
  </form>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <?php if ($invoices): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Invoice Number</th>
          <th>Date</th>
          <th>Customer Name</th>
          <th>Customer District</th>
          <th>Item Count</th>
          <th>Invoice Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($invoices as $inv): ?>
          <tr>
            <td><?= htmlspecialchars($inv['invoice_no']) ?></td>
            <td><?= htmlspecialchars($inv['date']) ?></td>
            <td><?= htmlspecialchars($inv['customer_name']) ?></td>
            <td><?= htmlspecialchars($inv['customer_district']) ?></td>
            <td><?= htmlspecialchars($inv['item_count']) ?></td>
            <td><?= htmlspecialchars($inv['amount']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="alert alert-info">No invoices found for the selected date range.</div>
  <?php endif; ?>

<?php include("../partials/footer.php"); ?>

<button id="exportCsvBtn" class="btn btn-sm btn-outline-secondary mb-3">Export to CSV</button>