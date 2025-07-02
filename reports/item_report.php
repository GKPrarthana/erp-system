<?php
include("../config/db.php");

// Group items by name (distinct), with total quantity
$sql = "SELECT
  it.item_name,
  ic.category,
  isc.sub_category,
  SUM(it.quantity) AS total_quantity
FROM item it
JOIN item_category    ic  ON it.item_category    = ic.id
JOIN item_subcategory isc ON it.item_subcategory = isc.id
GROUP BY it.item_name, ic.category, isc.sub_category
ORDER BY it.item_name;
";

$result = $conn->query($sql);
?>

<?php include("../partials/header.php"); ?>

  <h2>Item Report</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Item Name</th>
          <th>Item Category</th>
          <th>Item Subcategory</th>
          <th>Total Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['sub_category']) ?></td>
            <td><?= htmlspecialchars($row['total_quantity']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No items found.</div>
  <?php endif; ?>

<?php include("../partials/footer.php"); ?>

<button id="exportCsvBtn" class="btn btn-sm btn-outline-secondary mb-3">Export to CSV</button>