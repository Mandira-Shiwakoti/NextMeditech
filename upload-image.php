<?php
// ===== DB Connection =====
$host = 'localhost';
$db   = 'nextmedtech';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("❌ DB Connection Failed: " . $e->getMessage());
}

// ===== Handle Form Submission =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cat_id'])) {
    $cat_id = $_POST['cat_id'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['images']['name'][$key]);
            $target_file = $upload_dir . time() . "_" . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $stmt = $pdo->prepare("INSERT INTO product_images (image, cat_id) VALUES (:image, :cat_id)");
                $stmt->execute([
                    ':image' => $target_file,
                    ':cat_id' => $cat_id
                ]);
            }
        }
        echo "<script>alert('✅ Images uploaded successfully!'); window.location.href='';</script>";
        exit;
    }
}

// ===== Fetch All Subcategories =====
$stmt = $pdo->query("SELECT id, subcat_name FROM tbl_esubcat ORDER BY subcat_name ASC");
$subcategories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subcategory Image Upload</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f8fa; }
        .container { max-width: 900px; margin: 30px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; }
        a.button { background: #2d89ef; color: white; padding: 6px 10px; text-decoration: none; border-radius: 5px; cursor: pointer; }
        a.button:hover { background: #1b5fc0; }
        /* Popup Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto;
                 background-color: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; border-radius: 8px; width: 400px; position: relative; }
        .close { position: absolute; top: 10px; right: 15px; color: #aaa; font-size: 24px; cursor: pointer; }
        .close:hover { color: black; }
        button { margin-top: 15px; width: 100%; background: #2d89ef; color: white; padding: 10px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
        button:hover { background: #1b5fc0; }
    </style>
</head>
<body>

<div class="container">
    <h2>Subcategory List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Subcategory Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($subcategories as $sub): ?>
            <tr>
                <td><?= htmlspecialchars($sub['id']) ?></td>
                <td><?= htmlspecialchars($sub['subcat_name']) ?></td>
                <td><a class="button" onclick="openModal(<?= $sub['id'] ?>)">Add Image</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- Popup Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Upload Images</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="cat_id" name="cat_id">
            <input type="file" name="images[]" multiple accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</div>

<script>
function openModal(catId) {
    document.getElementById('cat_id').value = catId;
    document.getElementById('imageModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
}
window.onclick = function(event) {
    let modal = document.getElementById('imageModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

</body>
</html>
