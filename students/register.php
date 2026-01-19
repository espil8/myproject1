<?php include('functions.php'); ?>






<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
	<h2>Εγγραφή Νέου Φοιτητή</h2>
</div>
<form method="post" action="register.php">
	<?php echo display_error(); ?>
	<div class="input-group">
		<label>Αριθμός Μητρώου</label>
		<input type="text" name="am" value="<?php echo $am; ?>">
	</div>
	<div class="input-group">
		<label>Email</label>
		<input type="email" name="email" value="<?php echo $email; ?>">
	</div>
	<div class="input-group" style="position: relative;">
    <label>Κωδικός</label>
    <input type="password" name="password" id="password">
    <span id="togglePassword" 
          style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        🔐
    </span>
</div>

	<div class="input-group" style="position: relative;">
    <label>Επιβεβαίωση Κωδικού</label>
    <input type="password" name="password_2" id="password2">
    <span id="togglePassword2" 
          style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        🔐
    </span>
</div>
	<div class="input-group">
		<button type="submit" class="btn" name="register_btn">Εγγραφή</button>
	</div>
	<p>
		Έχετε ήδη εγγραφεί; <a href="login.php">Πατήστε Εδω!</a>
	</p>
</form>


<script>
const password = document.getElementById('password');
const toggle = document.getElementById('togglePassword');
toggle.addEventListener('click', () => {
    if (password.type === 'password') {
        password.type = 'text';
        toggle.textContent = '🔓';
    } else {
        password.type = 'password';
        toggle.textContent = '🔐';
    }
});

const password2 = document.getElementById('password2');
const toggle2 = document.getElementById('togglePassword2');
toggle2.addEventListener('click', () => {
    if (password2.type === 'password') {
        password2.type = 'text';
        toggle2.textContent = '🔓';
    } else {
        password2.type = 'password';
        toggle2.textContent = '🔐';
    }
});
</script>


</body>
</html>