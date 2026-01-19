<?php 
ob_start(); // για να αποφύγουμε header errors
include('../functions.php') 
?>

<?php
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
    exit();
}

if (isset($_POST['admin_register_btn'])) {

    // Προστασία από undefined index
    $am         = isset($_POST['am']) ? trim($_POST['am']) : '';
    $email      = isset($_POST['email']) ? trim($_POST['email']) : '';
    $user_type  = isset($_POST['user_type']) ? trim($_POST['user_type']) : '';
    $password_1 = isset($_POST['password_1']) ? $_POST['password_1'] : '';
    $password_2 = isset($_POST['password_2']) ? $_POST['password_2'] : '';

    $errors = [];

    // Έλεγχος υποχρεωτικών πεδίων
    if (empty($am))         { $errors[] = "Το ΑΜ είναι υποχρεωτικό"; }
    if (empty($email))      { $errors[] = "Το email είναι υποχρεωτικό"; }
    if (empty($user_type))  { $errors[] = "Ο τύπος λογαριασμού είναι υποχρεωτικός"; }
    if (empty($password_1)) { $errors[] = "Ο κωδικός είναι υποχρεωτικός"; }
    if ($password_1 !== $password_2) { $errors[] = "Οι κωδικοί δεν ταιριάζουν"; }

   if (count($errors) == 0) {

    // === Έλεγχος αν υπάρχει ίδιο ΑΜ ===
    $am_check_query = "SELECT am FROM users WHERE am='$am' LIMIT 1";
    $result = mysqli_query($db, $am_check_query);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Υπάρχει ήδη χρήστης με αυτό το ΑΜ!";
    }

    // === Έλεγχος αν ο κωδικός χρησιμοποιείται ήδη ===
    $password_check = mysqli_query($db, "SELECT password FROM users");

    while ($row = mysqli_fetch_assoc($password_check)) {
        if (password_verify($password_1, $row['password'])) {
            $errors[] = "Ο κωδικός χρησιμοποιείται ήδη από άλλον χρήστη!";
            break;
        }
    }
}

if (count($errors) == 0) {
    // Δεν υπάρχει διπλότυπο, κάνουμε INSERT
    $password = password_hash($password_1, PASSWORD_DEFAULT);
    mysqli_query(
        $db,
        "INSERT INTO users (am, email, user_type, password)
         VALUES ('$am', '$email', '$user_type', '$password')"
    );
    $_SESSION['success'] = "Ο χρήστης δημιουργήθηκε επιτυχώς!";
    header("Location: view_users.php");
    exit();
} else {
    // Εμφάνιση των errors
    $_SESSION['errors'] = $errors;
    $_SESSION['am'] = $am;
    $_SESSION['email'] = $email;
    $_SESSION['user_type'] = $user_type;
    header("Location: create_user.php");
    exit();
      }

}


?>




<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL - Δημιουργία Χρήστη</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		.header {
			background: #003366;
		}
		button[name=register_btn] {
			background: #003366;
		}

       

	</style>
</head>

<body>
	<div class="header">
		<h2>Διαχειριστής - Δημιουργία Λογαριασμού</h2>
	</div>
	


	<form method="post" action="create_user.php">

                 <br><br>
       <a href="home.php" class="btn-back-admin" style="
            display:inline-block;
            padding:7px 10px;
            background:#7274ba; 
            color:#fff;
            text-decoration:none;
            border-radius:9px;
            font-weight:bold;
            transition:0.25s;
	
        ">
          ← Επιστροφή 
        </a>


		<?php 
         echo display_error(); 
        // Καθαρισμός session variables μετά την εμφάνιση
       unset($_SESSION['am']);
       unset($_SESSION['email']);
       unset($_SESSION['user_type']);
       ?>

		<div class="input-group">
			<label>ΑΜ</label>
			<input type="text" name="am" value="<?php echo isset($_SESSION['am']) ? $_SESSION['am'] : ''; ?>">
		</div>
		<div class="input-group">
			<label>email</label>
			<input type="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
		</div>
		<div class="input-group">
			<label>Τύπος λογαριασμού</label>
			<select name="user_type" id="user_type">
                <option value=""></option>
                <option value="admin" <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=='admin') echo 'selected'; ?>>Διαχειριστής</option>
                <option value="user" <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=='user') echo 'selected'; ?>>Χρήστης</option>
            </select>
		</div>
		<div class="input-group" style="position: relative;">
    <label>Κωδικός</label>
    <input type="password" name="password_1" id="password1">
    <span id="togglePassword1" 
          style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        🔐
    </span>
</div>
		<div class="input-group" style="position: relative;">
    <label>Επιβεβαίωση κωδικού</label>
    <input type="password" name="password_2" id="password2">
    <span id="togglePassword2" 
          style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        🔐
    </span>
</div>
		<div class="input-group">
			<button type="submit" class="btn" name="admin_register_btn"> + Δημιουργία χρήστη</button>
		</div>
	</form>

<script>
          const pw1 = document.getElementById('password1');
          const toggle1 = document.getElementById('togglePassword1');
          toggle1.addEventListener('click', () => {
              if (pw1.type === 'password') {
                pw1.type = 'text';
                toggle1.textContent = '🔓';
              } else {
                pw1.type = 'password';
                toggle1.textContent = '🔐';
                     }
                                 });

          const pw2 = document.getElementById('password2');
          const toggle2 = document.getElementById('togglePassword2');
          toggle2.addEventListener('click', () => {
            if (pw2.type === 'password') {
              pw2.type = 'text';
              toggle2.textContent = '🔓';
          } else {
             pw2.type = 'password';
            toggle2.textContent = '🔐';
             }
                      }); 
</script>



</body>
</html>