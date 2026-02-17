<?php
// user_signup.php - handles signup POST
require_once __DIR__.'/inc/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
if($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password)>=6){
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
try{
$stmt->execute([$name,$email,$hash]);
header('Location: user_login.php?msg=signup_success'); exit;
}catch(PDOException $e){
$err = 'User with that email may already exist.';
}
} else {
$err = 'Please fill valid details (password min 6 chars).';
}
}
?><!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport"content="width=device-width,initial-scale=1"><title>Sign Up</title><link rel="stylesheet" href="assets/styles.css"></head>
<body>
<main style="padding:2rem;max-width:480px;margin:2rem auto;background:rgba(255,255,255,0.02);border-radius:12px;">
<h2>Create an account</h2>
<?php if(!empty($err)) echo '<p style="color:#ffb4b4;">'.htmlspecialchars($err).'</p>';?>
<form method="post">
<label>Name<br><input name="name" required></label><br><br>
<label>Email<br><input name="email" type="email" required></label><br><br>
<label>Password<br><input name="password" type="password" required></label><br><br>
<button type="submit">Sign Up</button>
</form>
<p>Already a user? <a href="user_login.php">Login</a></p>
</main>
</body>
</html>