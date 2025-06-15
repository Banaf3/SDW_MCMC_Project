<?php
// ...existing code for DB connection if needed...
$status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Replace with your actual DB connection code
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $inquiry = $conn->real_escape_string($_POST['inquiry']);
    $sql = "SELECT status FROM inquiries WHERE inquiry_id='$inquiry' OR email='$inquiry' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $status = "Your inquiry status: " . htmlspecialchars($row['status']);
    } else {
        $status = "Inquiry not found.";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Inquiry Progress</title>
</head>
<body>
    <h2>Check Your Inquiry Progress</h2>
    <form method="post">
        <label for="inquiry">Enter Inquiry ID or Email:</label>
        <input type="text" id="inquiry" name="inquiry" required>
        <button type="submit">Check Status</button>
    </form>
    <?php if ($status): ?>
        <p><?php echo $status; ?></p>
    <?php endif; ?>
</body>
</html>
