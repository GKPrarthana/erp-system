<?php
include("../config/db.php");
include("../partials/header.php");

// Fetch all customers
$sql = "SELECT * FROM customer ORDER BY id DESC";
$result = $conn->query($sql);
?>

  <h2>Customer List</h2>
  <a href="add.php" class="btn btn-success mb-3">+ Add New Customer</a>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Contact No</th>
        <th>District</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['title'] ?></td>
            <td><?= $row['first_name'] ?></td>
            <td><?= $row['middle_name'] ?></td>
            <td><?= $row['last_name'] ?></td>
            <td><a href="tel:<?= $row['contact_no'] ?>"><?= $row['contact_no'] ?></a></td>
            <td><?= $row['district'] ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="8">No customers found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

<?php include("../partials/footer.php"); ?>
