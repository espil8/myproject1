<?php
include('functions.php');

if (!isset($_SESSION['reset_am'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['reset_pass'])) {
    $password_1 = e($_POST['password']);
    $password_2 = e($_POST['password_2']);
    $am = $_SESSION['reset_am'];

    if (empty($password_1)) {
        $errors[] = "Ο κωδικός είναι υποχρεωτικός";
    }

    if ($password_1 !== $password_2) {
        $errors[] = "Οι κωδικοί δεν ταιριάζουν";
    }

    // Έλεγχος αν ο κωδικός χρησιμοποιείται ήδη από άλλον φοιτητή
    $res = mysqli_query($db, "SELECT password FROM users");
    while ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($password_1, $row['password'])) {
            $errors[] = "Ο κωδικός χρησιμοποιείται ήδη";
            break;
        }
    }

    if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header("Location: reset_password.php");
    exit();
   } else {
        $hashed = password_hash($password_1, PASSWORD_DEFAULT);
        mysqli_query($db, "UPDATE users SET password='$hashed' WHERE am='$am'");

        unset($_SESSION['reset_am']);
        $_SESSION['success'] = "Ο κωδικός άλλαξε επιτυχώς!";
        header("Location: login.php");
        exit();
     }
  } 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Νέος Κωδικός</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h2>Ορισμός Νέου Κωδικού</h2>
</div>

<form method="post">
    <?php echo display_error(); ?>

    <div class="input-group" style="position: relative;">
    <label>Νέος Κωδικός</label>
    <input type="password" name="password" id="password1">
    <span id="togglePassword1"
          style="position: absolute; right: 10px; top: 50%;
                 transform: translateY(-50%); cursor: pointer;">
        🔐
     </span>
</div>

<div class="input-group" style="position: relative;">
    <label>Επιβεβαίωση Κωδικού</label>
    <input type="password" name="password_2" id="password2">
    <span id="togglePassword2"
          style="position: absolute; right: 10px; top: 50%;
                 transform: translateY(-50%); cursor: pointer;">
        🔐
    </span>
 </div>

    <div class="input-group">
        <button type="submit" class="btn" name="reset_pass">
            Αλλαγή Κωδικού
        </button>

         <div style="margin-top:10px;">
           <a href="forgot_password.php" class="btn" style="background:#5bc0de;">
           ⬅ Πίσω (ΑΜ)
          </a>
         </div>

        <div style="margin-top:15px;">
          <a href="login.php" class="btn" style="background:#777;">
            ⬅ Επιστροφή στην Είσοδο
          </a>
       </div>
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