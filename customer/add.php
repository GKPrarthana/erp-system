<?php
include("../config/db.php");

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $contact_no = trim($_POST['contact_no']);
    $district = trim($_POST['district']);

    // Validation
    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name)) $errors[] = "Last name is required.";
    if (!preg_match('/^[0-9]{10}$/', $contact_no)) $errors[] = "Contact number must be 10 digits.";
    if (empty($district)) $errors[] = "District is required.";

    if (count($errors) === 0) {
        $sql = "INSERT INTO customer (title, first_name, middle_name, last_name, contact_no, district) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $title, $first_name, $middle_name, $last_name, $contact_no, $district);
        if ($stmt->execute()) {
            $success = "Customer added successfully!";
        } else {
            $errors[] = "Failed to add customer.";
        }
    }
}
?>

<?php include("../partials/header.php"); ?>

  <h2>Add Customer</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($errors as $error) echo "<li>$error</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-2">
      <label>Title</label>
      <select name="title" class="form-select">
        <option>Mr</option>
        <option>Mrs</option>
        <option>Miss</option>
        <option>Dr</option>
      </select>
    </div>
    <div class="mb-2">
      <label>First Name</label>
      <input type="text" name="first_name" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Middle Name</label>
      <input type="text" name="middle_name" class="form-control">
    </div>
    <div class="mb-2">
      <label>Last Name</label>
      <input type="text" name="last_name" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Contact Number</label>
      <input type="text" name="contact_no" class="form-control" maxlength="10" required>
    </div>
    <div class="mb-2">
      <label>District</label>
      <input type="text" name="district" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Customer</button>
    <a href="view.php" class="btn btn-secondary">View Customers</a>
  </form>

<?php include("../partials/footer.php"); ?>
