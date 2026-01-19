<?php
include('functions.php');

// έλεγχος login
if (!isLoggedIn()) {
    header('location: login.php');
    exit();
}

// admin δεν μπαίνει εδώ
if (isAdmin()) {
    header('location: admin/home.php');
    exit();
}

// ΕΠΕΞΕΡΓΑΣΙΑ ΦΟΡΜΑΣ ΜΟΝΟ ΕΔΩ
if (isset($_POST['register_Lab'])) {
    registerLab();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Εγγραφή σε Εργαστήριο</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h2>Εγγραφή Φοιτητή σε Εργαστήριο</h2>
</div>

<div class="content">

    <?php echo display_error(); ?>

    <div class="profile_info">
        <img src="user_icon.png">

        <div>
            <strong><?php echo $_SESSION['user']['am']; ?></strong>
            <br><br>

            <?php getAllLabs(); ?>

            <form method="post" action="register_lab.php">

                <input type="hidden" name="am"
                       value="<?php echo $_SESSION['user']['am']; ?>">

                <div class="input-group">
                    <label>Επιλογή Εργαστηρίου</label>
                    <select name="section_id">
                        <option value="">-- Επιλέξτε Εργαστήριο --</option>
                        <?php
                        $labs = getAllSections();
                        while ($lab = mysqli_fetch_assoc($labs)) {
                            $lab_name = isset($lab['name']) ? htmlspecialchars($lab['name']) : "—"; // ασφάλεια
                            echo "<option value='{$lab['id']}'>
                                    {$lab_name} - {$lab['day']} {$lab['time']}
                                  </option>";
                                }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn" name="register_Lab">
                        Εγγραφή
                    </button>
                </div>
            </form>

            <br>
            <a href="dashboard.php">⬅ Επιστροφή στο Dashboard</a>
            <br>
            <a href="register_lab.php?logout='1'" style="color:red;">
                Αποσύνδεση
            </a>

        </div>
    </div>
</div>




<canvas id="oceanSurface" style="position: fixed; top:0; left:0; width:100%; height:100%; z-index:-2;"></canvas>
<script>
const canvas = document.getElementById('oceanSurface');
const ctx = canvas.getContext('2d');

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

let time = 0;

// Παράμετροι κυμάτων
const waves = [
    {amplitude: 10, wavelength: 200, speed: 0.02, phase: 0},
    {amplitude: 6, wavelength: 120, speed: 0.015, phase: Math.PI/2},
    {amplitude: 4, wavelength: 80, speed: 0.01, phase: Math.PI}
];

function drawOcean() {
    ctx.clearRect(0,0,canvas.width,canvas.height);

    // Gradient για το χρώμα της θάλασσας
    let gradient = ctx.createLinearGradient(0,0,0,canvas.height);
    gradient.addColorStop(0, 'rgba(173,216,230,0.25)'); // ανοιχτό γαλάζιο πάνω
    gradient.addColorStop(1, 'rgba(0,191,255,0.25)'); // πιο έντονο κάτω
    ctx.fillStyle = gradient;
    ctx.fillRect(0,0,canvas.width,canvas.height);

    // Overlay waves για την κίνηση
    ctx.fillStyle = 'rgba(255,255,255,0.05)';
    ctx.beginPath();
    for(let y=0; y<canvas.height; y+=2){
        let offset = 0;
        waves.forEach(w => {
            offset += Math.sin((y/w.wavelength) + time*w.speed + w.phase) * w.amplitude;
        });
        ctx.fillRect(0, y + offset, canvas.width, 2);
    }

    time += 1;
    requestAnimationFrame(drawOcean);
}

drawOcean();
</script>






</body>
</html>