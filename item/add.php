<?php
include("../config/db.php");

$errors = [];
$success = "";

$categories = $conn->query("SELECT id, category FROM item_category");
$subcats    = $conn->query("SELECT id, sub_category FROM item_subcategory");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_code = trim($_POST['item_code']);
    $item_name = trim($_POST['item_name']);
    $item_category = $_POST['item_category'];
    $item_subcategory = $_POST['item_subcategory'];
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    // Validation
    if (empty($item_code)) $errors[] = "Item code is required.";
    if (empty($item_name)) $errors[] = "Item name is required.";
    if (empty($item_category)) $errors[] = "Item category is required.";
    if (empty($item_subcategory)) $errors[] = "Item subcategory is required.";
    if (!is_numeric($quantity)) $errors[] = "Quantity must be a number.";
    if (!is_numeric($unit_price)) $errors[] = "Unit price must be a number.";

    if (count($errors) === 0) {
        $sql = "INSERT INTO item (item_code, item_name, item_category, item_subcategory, quantity, unit_price)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $item_code, $item_name, $item_category, $item_subcategory, $quantity, $unit_price);

        if ($stmt->execute()) {
            $success = "Item added successfully!";
        } else {
            $errors[] = "Failed to add item.";
        }
    }
}
?>

<?php include("../partials/header.php"); ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Add Item</h2>
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
      <label>Item Code</label>
      <input type="text" name="item_code" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Item Name</label>
      <input type="text" name="item_name" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Item Category</label>
      <select name="item_category" class="form-select" required>
        <option value="">-- Select Category --</option>
        <?php while ($row = $categories->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= $row['category'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-2">
      <label>Item Subcategory</label>
      <select name="item_subcategory" class="form-select" required>
        <option value="">-- Select Subcategory --</option>
        <?php while ($row = $subcats->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= $row['sub_category'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-2">
      <label>Quantity</label>
      <input type="number" name="quantity" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Unit Price</label>
      <input type="number" step="0.01" name="unit_price" class="form-control" required>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Add Item
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
