# 🎓 Σύστημα Διαχείρισης Εργαστηρίων Φοιτητών (PHP / MySQL)

Το παρόν project είναι ένα **web-based σύστημα διαχείρισης εργαστηρίων** ανεπτυγμένο σε **PHP & MySQL**, σχεδιασμένο να λειτουργεί σε περιβάλλον **XAMPP (localhost)**.

Η εφαρμογή προσομοιάζει βασικές λειτουργίες πλατφορμών τύπου **eClass**, επιτρέποντας τη διαχείριση:
- χρηστών (φοιτητών & διαχειριστή),
- εργαστηρίων,
- εγγραφών,
- βαθμολογιών,
- και εκπαιδευτικού υλικού (PDF).

---

## 👥 Ρόλοι Χρηστών

### 👨🏻‍💻 Διαχειριστής (Admin)
Ο διαχειριστής έχει πλήρη έλεγχο του συστήματος και μπορεί να:
- Δημιουργεί και διαγράφει χρήστες
- Δημιουργεί εργαστήρια
- Ορίζει μέγιστο αριθμό φοιτητών ανά εργαστήριο
- Προβάλλει όλους τους χρήστες και τις εγγραφές τους
- Αφαιρεί φοιτητές από συγκεκριμένα εργαστήρια
- Διαγράφει χρήστες εξολοκλήρου (εκτός admin)
- Δημιουργεί φακέλους εγγράφων ανά εργαστήριο
- Ανεβάζει και διαγράφει αρχεία PDF
- Καταχωρεί και τροποποιεί βαθμολογίες φοιτητών

---

### 🙋 Φοιτητής (User)
Ο φοιτητής μπορεί να:
- Δημιουργεί λογαριασμό και να συνδέεται
- Εγγράφεται σε διαθέσιμα εργαστήρια
- Προβάλλει πληροφορίες εργαστηρίων
- Προβάλλει το εκπαιδευτικό υλικό (PDF)
- Βλέπει τη βαθμολογία που του έχει καταχωρηθεί

> ⚠ Ο φοιτητής **δεν έχει δικαίωμα** να ανεβάζει, να επεξεργάζεται ή να διαγράφει αρχεία.

---

## 📂 Διαχείριση Εγγράφων (τύπου eClass)

Για κάθε εργαστήριο:
- Ο admin μπορεί να δημιουργεί φακέλους (π.χ. «Διαλέξεις», «Ασκήσεις»)
- Κάθε φάκελος μπορεί να περιέχει αρχεία **PDF**
- Υποστηρίζεται:
  - διαγραφή φακέλου (μαζί με όλα τα αρχεία του)
  - διαγραφή μεμονωμένου PDF
- Οι φοιτητές έχουν **μόνο δικαίωμα προβολής**

---

## ✍🏼 Βαθμολογία

- Ο admin καταχωρεί βαθμολογίες ανά φοιτητή και εργαστήριο
- Ο φοιτητής έχει πρόσβαση **μόνο στην προβολή** της βαθμολογίας του
- Δεν επιτρέπεται τροποποίηση από τον φοιτητή

---

## 🛠 Τεχνολογίες

- PHP 8.x
- MySQL / MariaDB
- HTML5 / CSS3
- JavaScript 
- XAMPP (Apache, MySQL, PHP)
- phpMyAdmin


---

## 📁 Δομή Project
myproject1/
    README.md
    students/
        admin/
            create_user.php
            home.php
            sections.php
            view_users.php
        uploads/
            labs/
        dashboard.php
        forgot_password.php
        functions.php
        grades.php
        index.php
        lab.php
        login.php
        register.php
        register_lab.php
        reset_password.php
        style.css
    SQL/
        m_users.sql
    screenshots/


## ⚙ Οδηγίες Εγκατάστασης

1. Κατεβάστε ή κλωνοποιήστε το repository.
2. Μεταφέρετε τον φάκελο `students` στον φάκελο `htdocs` του XAMPP.
3. Εκκινήστε **Apache** και **MySQL**.
4. Ανοίξτε το **phpMyAdmin**.
5. Δημιουργήστε βάση δεδομένων με όνομα: m_users
6. Εισάγετε το αρχείο: m_users.sql 
7. Ανοίξτε τον browser:http://localhost/students 


## 🔐 Demo Λογαριασμοί

**Διαχειριστής**
AM: admin
password: Admin123! (όπως ορίζεται στη βάση)

**Φοιτητής/ές**
AM: student58990
password: student

AΜ: student99999
password: students 



## 📷 Screenshots

### Login,Register,password reset
![Login](screenshots/screen1.png)
![Login](screenshots/screen2.png)
![Register](screenshots/screen3.png)
![Register](screenshots/screen4.png)
![Register](screenshots/screen5.png)
![Password reset](screenshots/screen6.png)
![Password reset](screenshots/screen7.png)
![Password reset](screenshots/screen8.png)


### Admin
![Admin Dashboard](screenshots/screen9.png)
![Admin Dashboard](screenshots/screen10.png)
![Admin Dashboard](screenshots/screen11.png)
![Add user](screenshots/screen12.png)
![Add user](screenshots/screen13.png)
![Add user](screenshots/screen14.png) 

![Added user](screenshots/screen15.png)
![Admin userlist](screenshots/screen16.png)
![Admin userlist](screenshots/screen28.png)
![Delete user](screenshots/screen29.png)
![Delete user from lab](screenshots/screen30.png)
![Upload pdf](screenshots/screen17.png)
![Upload pdf](screenshots/screen18.png)
![Upload pdf](screenshots/screen19.png)
![grades](screenshots/screen20.png)
![grades](screenshots/screen21.png)

### Φοιτητής
![dashboard](screenshots/screen23.png)
![register lab](screenshots/screen24.png)
![dashboard](screenshots/screen26.png)
![dashboard](screenshots/screen27.png)
![dashboard](screenshots/screen25.png)
![grades](screenshots/screen22.png)

### SQL Database
![m_users database](screenshots/screen31.png)
![users](screenshots/screen32.png)
![sections](screenshots/screen33.png)
![registrations](screenshots/screen34.png)
![lab_folders](screenshots/screen35.png)
![lab_files](screenshots/screen36.png)


---



## 👨‍💻 Author

Ανάπτυξη: *[Ε.Σπηλιώτης]*  
GitHub: @espil8
