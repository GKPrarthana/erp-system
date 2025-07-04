<?php
include("../config/db.php");
include("../partials/header.php");

$data = [];
$errors = [];
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$start_date || !$end_date) {
        $errors[] = "Both start and end dates are required.";
    } else {
      $sql = "
        SELECT
          i.invoice_no,
          i.date,
          CONCAT(c.first_name,' ',c.last_name) AS customer_name,
          it.item_code,
          it.item_name,
          ic.category AS item_category,
          im.unit_price
        FROM invoice i
        JOIN customer       c  ON i.customer       = c.id
        JOIN invoice_master im ON i.invoice_no     = im.invoice_no
        JOIN item           it ON im.item_id       = it.id
        JOIN item_category  ic ON it.item_category = ic.id
        WHERE i.date BETWEEN ? AND ?
        ORDER BY i.date DESC
      ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}
?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-bag-check me-2"></i>Invoice Item Report</h2>
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
      <a href="invoice_item_report.php" class="btn btn-secondary">
        <i class="bi bi-arrow-clockwise me-1"></i>Reset
      </a>
    </div>
  </form>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <?php if ($data): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Invoice No</th>
          <th>Invoice Date</th>
          <th>Customer Name</th>
          <th>Item Code</th>
          <th>Item Name</th>
          <th>Item Category</th>
          <th>Unit Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['invoice_no']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['item_code']) ?></td>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['item_category']) ?></td>
            <td><?= htmlspecialchars($row['unit_price']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="alert alert-info">No invoice items found for the selected date range.</div>
  <?php endif; ?>

<?php include("../partials/footer.php"); ?>

<button id="exportCsvBtn" class="btn btn-sm btn-outline-secondary mb-3">Export to CSV</button>