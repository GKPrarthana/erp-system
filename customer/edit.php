<?php
include("../config/db.php");

$errors = [];
$success = "";
$id = $_GET['id'] ?? 0;

// Fetch existing customer
$sql = "SELECT * FROM customer WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    die("Customer not found.");
}

/* --- fetch active districts once --- */
$districts = $conn->query("SELECT id, district FROM district WHERE active='yes'");

// Update on form submit
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
        $update_sql = "UPDATE customer SET title=?, first_name=?, middle_name=?, last_name=?, contact_no=?, district=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssssi", $title, $first_name, $middle_name, $last_name, $contact_no, $district, $id);

        if ($stmt->execute()) {
            $success = "Customer updated successfully!";
            // Refresh data
            $customer['title'] = $title;
            $customer['first_name'] = $first_name;
            $customer['middle_name'] = $middle_name;
            $customer['last_name'] = $last_name;
            $customer['contact_no'] = $contact_no;
            $customer['district'] = $district;
        } else {
            $errors[] = "Update failed.";
        }
    }
}
?>

<?php include("../partials/header.php"); ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-person-gear me-2"></i>Edit Customer</h2>
    <div>
      <a href="../index.php" class="btn btn-outline-primary me-2">
        <i class="bi bi-house-door me-1"></i>Home
      </a>
      <a href="view.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to List
      </a>
    </div>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul><?php foreach ($errors as $error) echo "<li>$error</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-2">
      <label>Title</label>
      <select name="title" class="form-select">
        <?php foreach (["Mr", "Mrs", "Miss", "Dr"] as $option): ?>
          <option <?= ($customer['title'] == $option ? "selected" : "") ?>><?= $option ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-2">
      <label>First Name</label>
      <input type="text" name="first_name" class="form-control" value="<?= $customer['first_name'] ?>" required>
    </div>
    <div class="mb-2">
      <label>Middle Name</label>
      <input type="text" name="middle_name" class="form-control" value="<?= $customer['middle_name'] ?>">
    </div>
    <div class="mb-2">
      <label>Last Name</label>
      <input type="text" name="last_name" class="form-control" value="<?= $customer['last_name'] ?>" required>
    </div>
    <div class="mb-2">
      <label>Contact Number</label>
      <input type="text" name="contact_no" class="form-control" maxlength="10" value="<?= $customer['contact_no'] ?>" required>
    </div>
    <div class="mb-2">
      <label>District</label>
      <select name="district" class="form-select" required>
        <option value="">-- Select District --</option>
        <?php while ($d = $districts->fetch_assoc()): ?>
          <option value="<?= $d['id'] ?>" <?= ($customer['district'] == $d['id'] ? "selected" : "") ?>><?= $d['district'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle me-1"></i>Update
      </button>
      <a href="view.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to List
      </a>
      <a href="../index.php" class="btn btn-outline-primary">
        <i class="bi bi-house-door me-1"></i>Home
      </a>
    </div>
  </form>

<?php include("../partials/footer.php"); ?>
