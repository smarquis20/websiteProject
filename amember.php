<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Bishopric Assistant</title>
    <meta name="description" content="This site is to assist in choosing speakers and tracking talks.">
    <meta name="author" content="Shaun Marquis">
    <link rel="stylesheet" href="styles/bsa.css">
</head>
<body>
    <header>
    <nav>
            <div class="navbar">
                <button class="navbar-toggle" id="navbar-toggle">☰</button>
                <ul class="navbar-menu" id="navbar-menu">
                    <li><a href="index.php">Member Search</a></li>
                    <li><a href="speakerl.php">Speaker List</a></li>
                    <li><a href="cspeaker.php">Current Speakers</a></li>
                    <li><a href="talks.php">Assign Talk</a></li>
                    <li><a href="amember.php">Add Member</a></li>
                    <li><a href="rmember.php">Remove Member</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div class="section-form-memberSearch">
            <div class="memberSearch-container">
                <div class="memberSearch-h1">Add a New Member</div>
                <div class="memberSearchForm">
                    <form name="memberSearch-Form" class="memberSearch-form" action="amember.php" method="post">
                        <input class="msearch-text-field" name="speakerFname" placeholder="Enter the First Name of the Member" type="text" id="speakerFname" required>
                        <input class="msearch-text-field" name="speakerLname" placeholder="Enter the Last Name of the Member" type="text" id="speakerLname" required>
                        <input class="msearch-text-field" name="speakerAge" placeholder="Enter the Age of the Member" type="text" id="speakerAge">
                        <input class="msearch-text-field" name="speakerPhone" placeholder="Enter the Phone Number of the Member (555-555-5555)" type="text" id="speakerPhone">
                        <input class="msearch-text-field" name="speakerEmail" placeholder="Enter the Email of the Member" type="text" id="speakerEmail">
			<button type="submit" value="Add Member">Add Member</button>
                    </form>
                </div>
            </div>
        </div>
        <table class="center" id="table">
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Age</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $servername = "localhost";
                    $username = "admin";
                    $password = "admin";
                    $dbname = "tiffanyspringsward";
                    $first_name = $_POST["speakerFname"];
                    $last_name = $_POST["speakerLname"];
                    $age = $_POST["speakerAge"];
                    $phone = $_POST["speakerPhone"];
                    $email = $_POST["speakerEmail"];

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Prepare and execute the statement to insert into members
                    $stmt = $conn->prepare("INSERT INTO members (last_name, first_name, age, phone_number, email) VALUES (?, ?, ?, ?, ?)");
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }
                    $stmt->bind_param("ssiss", $last_name, $first_name, $age, $phone, $email);

                    if ($stmt->execute()) {
                        echo "$first_name $last_name has been added to the members list.";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();

                    // Query to fetch member details
                    $sql = "SELECT member_id, last_name, first_name, age, phone_number, email FROM members WHERE first_name = ? AND last_name = ?";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }
                    $stmt->bind_param("ss", $first_name, $last_name);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["member_id"] . "</td>
                                    <td>" . $row["last_name"] . "</td>
                                    <td>" . $row["first_name"] . "</td>
                                    <td>" . $row["age"] . "</td>
                                    <td>" . $row["phone_number"] . "</td>
                                    <td>" . $row["email"] . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No results found</td></tr>";
                    }
                    $stmt->close();
                    $conn->close();
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
    </footer>
    <script src="script.js"></script>
</body>
</html>
