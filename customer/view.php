<?php
include("../config/db.php");
include("../partials/header.php");

// Fetch all customers
$sql = "
  SELECT c.*, d.district
  FROM customer c
  JOIN district d ON c.district = d.id
  ORDER BY c.id DESC
";
$result = $conn->query($sql);
?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-people-fill me-2"></i>Customer List</h2>
    <div>
      <a href="../index.php" class="btn btn-outline-primary me-2">
        <i class="bi bi-house-door me-1"></i>Home
      </a>
      <a href="add.php" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i>Add New Customer
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