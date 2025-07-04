<?php
include("../config/db.php");
include("../partials/header.php");

// Fetch all items
$sql = "
  SELECT i.*, ic.category, isc.sub_category
  FROM item i
  JOIN item_category    ic  ON i.item_category    = ic.id
  JOIN item_subcategory isc ON i.item_subcategory = isc.id
  ORDER BY i.id DESC
";
$result = $conn->query($sql);
?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-box-seam me-2"></i>Item List</h2>
    <div>
      <a href="../index.php" class="btn btn-outline-primary me-2">
        <i class="bi bi-house-door me-1"></i>Home
      </a>
      <a href="add.php" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>Add New Item
      </a>
    </div>
  </div>

  <div class="mb-3">
    <input
      type="text"
      id="searchInput"
      class="form-control"
      placeholder="Search table..."
      autocomplete="off"
    />
  </div>

  <table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Category</th>
        <th>Subcategory</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['item_code'] ?></td>
            <td><?= $row['item_name'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= $row['sub_category'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['unit_price'] ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8">No items found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

<?php include("../partials/footer.php"); ?>