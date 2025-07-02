<?php
include("../config/db.php");

// Fetch all items
$sql = "SELECT * FROM item ORDER BY id DESC";
$result = $conn->query($sql);
?>

<?php include("../partials/header.php"); ?>

  <h2>Item List</h2>
  <a href="add.php" class="btn btn-success mb-3">+ Add New Item</a>

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
            <td><?= $row['item_category'] ?></td>
            <td><?= $row['item_subcategory'] ?></td>
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
