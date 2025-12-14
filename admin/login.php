<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../classes/connect-db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['Role']);

    try {
        // Ensure password column can store hashed passwords
        $pdo->exec("ALTER TABLE users MODIFY Password VARCHAR(255)");
    } catch (PDOException $e) {
        // Ignore if already modified
    }

    // Fetch user by email and role
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ? AND Role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // ✅ Check if password is correct (hashed)
        if (password_verify($password, $user['Password'])) {
            // FIXED: Consistent session variable names
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['role'] = $user['Role']; // Changed from 'user_role' to 'role'

            // Debug: Check what we're setting
            error_log("Login successful - User ID: " . $user['Id'] . ", Role: " . $user['Role']);
            
            // ✅ Redirect based on role
            if (trim(strtolower($user['Role'])) === 'admin') {
                header("Location: index.php"); // Admin dashboard
            } else {
                header("Location: ../home.php"); // User homepage
            }
            exit();
        } 
        // ✅ Check if password is stored in plain text (old accounts)
        elseif ($password === $user['Password']) {
            // Update to hashed password for future security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET Password = ? WHERE Id = ?");
            $updateStmt->execute([$hashedPassword, $user['Id']]);

            // FIXED: Consistent session variable names
            // after verifying password
          $_SESSION['user_id'] = $user['Id'];
          $_SESSION['user_email'] = $user['Email'];
          $_SESSION['role'] = strtolower(trim($user['Role'])); // store role normalized

            if (trim(strtolower($user['Role'])) === 'admin') {
                header("Location: index.php");
            } else {
                header("Location: ../home.php");
            }
            exit();
        } else {
            $error = "❌ Invalid password! Please try again.";
        }
    } else {
        $error = "❌ Invalid email, password, or role!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    /* Your existing CSS remains the same */
    body { 
        font-family: Arial; 
        background: #f4f4f4; 
        padding: 60px; 
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .login-container {
        background: white; 
        padding: 40px; 
        max-width: 350px; 
        width: 100%;
        margin: auto; 
        border-radius: 10px; 
        box-shadow: 0 2px 20px rgba(0,0,0,0.1); 
    }
    h2 { 
        text-align: center; 
        color: #333; 
        margin-bottom: 30px;
        font-size: 24px;
    }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; color: #555; font-weight: bold; }
    input, select { 
        width: 100%; padding: 12px; border: 1px solid #ddd; 
        border-radius: 4px; font-size: 14px; 
        transition: border-color 0.3s;
    }
    input:focus, select:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
    }
    button { 
        width: 100%; padding: 12px; background: #007bff; 
        color: white; border: none; border-radius: 4px; 
        cursor: pointer; font-size: 16px; font-weight: bold;
        transition: background 0.3s;
    }
    button:hover { background: #0056b3; }
    .error { 
        color: #dc3545; text-align: center; margin-bottom: 20px; 
        padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb;
        border-radius: 4px;
    }
    .success { 
        color: #155724; text-align: center; margin-bottom: 20px; 
        padding: 10px; background: #d4edda; border: 1px solid #c3e6cb;
        border-radius: 4px;
    }
    .role-info {
        text-align: center; color: #6c757d; font-size: 12px;
        margin-top: 10px; font-style: italic;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <form method="POST">
      <h2>Login</h2>
      
      <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="success">Registration successful! Please login.</div>
      <?php endif; ?>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
      </div>

      <div class="form-group">
        <label for="Role">Login As</label>
        <select name="Role" required>
          <option value="">Select Role</option>
          <option value="user" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'user') ? 'selected' : ''; ?>>User</option>
          <option value="admin" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
        <div class="role-info">Select your role to access the correct dashboard</div>
      </div>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>