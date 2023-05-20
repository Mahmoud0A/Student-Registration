<!DOCTYPE html>
<head>
    <title>Student Registration Form</title>
</head>
<body>
<h1>Student Registration Form</h1>

<?php
if (isset($_POST['submit'])) {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    if (empty($fullname) || empty($email) || empty($gender)) {
        echo "<div class='notification error-message'>Error: Please fill in all required fields.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='notification error-message'>Error: Invalid email address.</div>";
    } else {
        $conn = mysqli_connect("localhost", "root", "", "student_database");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO students (full_name, email, gender) VALUES ('$fullname', '$email', '$gender')";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='notification success-message'>Student registered successfully.</div>";
        } else {
            echo "<div class='notification error-message'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</div>";
        }

        mysqli_close($conn);
    }
}
?>

<form method="post" action="index.php">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" required><br>

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required><br>

    <label>Gender:</label>
    <input type="radio" id="male" name="gender" value="Male" required>
    <label for="male">Male</label>
    <input type="radio" id="female" name="gender" value="Female" required>
    <label for="female">Female</label><br>

    <input type="submit" name="submit" value="Submit">
</form>

<h2>Registered Students</h2>
<?php
$conn = mysqli_connect("localhost", "root", "", "student_database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table class='student-list'>";
    echo "<tr><th>#</th><th>Name</th><th>Email</th><th>Gender</th></tr>";
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $count . "</td>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['gender'] . "</td>";
        echo "</tr>";
        $count++;
    }
    echo "</table>";
} else {
    echo "<p>No students registered yet.</p>";
}

mysqli_close($conn);
?>

</body>