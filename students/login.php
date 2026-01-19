<?php include('functions.php'); ?>





<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Είσοδος</h2>
	</div>
	<form method="post" action="login.php">

		<?php 
       echo display_error();   // Όποια λάθη υπάρχουν
       echo display_success(); // Μήνυμα επιτυχίας
     ?>

		<div class="input-group">
			<label>Αριθμός Μητρώου</label>
			<input type="text" name="am" >
		</div>
		<div class="input-group" style="position: relative;">
    <label>Κωδικός</label>
    <input type="password" name="password" id="password">
    <span id="togglePassword" 
          style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
         🔐
    </span>
</div>
		<div class="input-group">
			<button type="submit" class="btn" name="login_btn">Login</button>
		</div>
		<p>
			Δεν έχετε κάνει εγγραφή; <a href="register.php">Εγγραφή Τώρα</a>
		</p>
    <p style="margin-top:10px;">
      <a href="forgot_password.php">🕵Ξεχάσατε τον κωδικό;</a>
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
      </script>


      <div class="wave-bg">
  <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="rgba(173,216,230,0.3)" 
          d="M0,160 C360,100 1080,220 1440,160 L1440,320 L0,320 Z">
    </path>
  </svg>
</div>

<style>
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  overflow: hidden;
  font-family: sans-serif;
}

.wave-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background: linear-gradient(to bottom, #e0f7fa 0%, #ffffff 100%);
  overflow: hidden;
}

.wave-bg svg {
  position: absolute;
  bottom: -20px;
  width: 200%;
  height: 120%;
}

.wave-bg path {
  transform: translateX(-25%);
  transition: transform 2s ease-in-out;
}
</style>


</body>
</html>