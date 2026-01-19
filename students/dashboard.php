<?php 
include('functions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

// Παίρνω τον ΑΜ του φοιτητή
$am = $_SESSION['user']['am'];

// Παίρνω τις εγγραφές του φοιτητή
$registrations = mysqli_query($db, "
    SELECT s.id, s.name, s.day, s.time 
    FROM registrations r
    JOIN sections s ON r.section_id = s.id
    WHERE r.am = '$am'
");

// Αν πατήθηκε το κουμπί απεγγραφής
if (isset($_POST['unregister_Lab'])) {
    unregisterLab();
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Σελίδα Φοιτητή</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .registrations-box {
            width: 60%;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #B0C4DE;
            border-radius: 5px;
            background: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #5F9EA0;
            color: white;
        }
        .btn {
            padding: 10px;
            font-size: 15px;
            color: white;
            background: #5F9EA0;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        
        
        .logout-btn {
           display: inline-block;
           padding: 8px 14px;
           margin-bottom: 15px;
           background: #a94442;
           color: white;
           text-decoration: none;
           border-radius: 4px;
           font-weight: bold;
         }

        .logout-btn:hover {
            background: #c9302c;
        }
           


    </style>
</head>
<body>
    
    <div class="header">
        <h2>Σελίδα Φοιτητή</h2>
    </div>

    <div class="registrations-box">

         <a href="dashboard.php?logout=1" class="logout-btn">
         🚪 Αποσύνδεση
         </a>

        <h3>Εγγραφές μου σε Εργαστήρια</h3>

        <?php if(mysqli_num_rows($registrations) > 0): ?>
            <table>
                <thead>
                    <tr> 
                        <th>Εργαστήριο</th>
                        <th>Ημέρα</th>
                        <th>Ώρα</th>
                        <th>Ενέργεια</th> <!-- Νέα στήλη για απεγγραφή -->
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($registrations)): ?>
                        <tr>
                            <td>
                                <a href="lab.php?id=<?php echo $row['id']; ?>">
                                <?= htmlspecialchars($row['name']) ?>
                                </a>
                            </td>
                            <td><?php echo $row['day']; ?></td>
                            <td><?php echo $row['time']; ?></td>
                            
                            <td>
                               <form method="POST" style="display:inline;">
                                <input type="hidden" name="lab_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="unregister_Lab" class="btn" style="background:#DC143C;">Απεγγραφή</button>
                               </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </tbody>

                         

            </table>




        <?php else: ?>
            <p>Δεν είστε εγγεγραμμένος σε κανένα εργαστήριο ακόμα.</p>
        <?php endif; ?>

        <br>
        <a href="register_lab.php" class="btn">Εγγραφή σε νέα εργαστήρια</a>
    </div>


   

    
    <canvas id="galaxy" style="position: fixed; top:0; left:0; z-index:-1;"></canvas>
<script>
const canvas = document.getElementById('galaxy');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

//background effect night sky
let stars = [];
for(let i=0;i<400;i++){
    stars.push({
        x: Math.random()*canvas.width,
        y: Math.random()*canvas.height,
        r: Math.random()*1.5,
        alpha: Math.random(),
        dAlpha: 0.002 + Math.random()*0.006 // πιο αργό flicker
    });
}

// Γαλαξίες
let galaxies = [];
for(let i=0;i<12;i++){
    let radius = 20 + Math.random()*80;
    let rotationAngle = Math.random()*2*Math.PI;
    let tilt = (Math.random() - 0.5) * Math.PI; // τυχαίο tilt ανά γαλαξία
    let speed = (Math.random() * 0.002) + 0.0002; // τυχαία ταχύτητα περιστροφής
    let type = i < 6 ? 'cluster' : 'spiral'; 

    let colorPick = Math.random();
    let color = '255,255,255';
    if(colorPick < 0.33) color = '255,240,200';
    else if(colorPick < 0.66) color = '255,200,200';

    let galaxy = {
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: radius,
        rotation: rotationAngle,
        tilt: tilt,
        speed: speed,
        type: type,
        color: color,
        particles: []
    };

    

    if(Math.random() < 0.5){
        galaxy.type = 'starlike';
        galaxy.radius = 1 + Math.random()*2;
    }

    galaxies.push(galaxy);
}

// Σχεδίαση
function draw(){
    ctx.fillStyle = "rgba(0,0,10,0.15)";
    ctx.fillRect(0,0,canvas.width,canvas.height);

    galaxies.forEach(g => {
        if(g.type === 'cluster'){
            let particleCount = g.radius*6;
            for(let i=0;i<particleCount;i++){
                let r = Math.random()*g.radius;
                let angle = Math.random()*2*Math.PI + g.rotation;
                let x = g.x + Math.cos(angle)*r;
                let y = g.y + Math.sin(angle)*r*Math.cos(g.tilt);
                let alpha = 0.05 + (1 - r/g.radius)*0.15;
                ctx.fillStyle = `rgba(${g.color},${alpha})`;
                ctx.fillRect(x, y, 1.5, 1.5);
            }
        } 
         
        
    });

    stars.forEach(s => {
        ctx.fillStyle = `rgba(255,255,255,${s.alpha})`;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI*2);
        ctx.fill();
        s.alpha += s.dAlpha;
        if(s.alpha > 1 || s.alpha < 0) s.dAlpha *= -1;
    });

    requestAnimationFrame(draw);
}

draw();

window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});
</script>



</body>
</html>