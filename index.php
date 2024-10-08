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
                <div class="memberSearch-h1">Search for Member</div>
                <div class="memberSearchForm">
                    <form name="memberSearch-Form" class="memberSearch-form" action="index.php" method="post">
                        <input class="msearch-text-field" name="memberSearchFirst" placeholder="Enter First Name" type="text" id="memberSearchFirst">
                        <input class="msearch-text-field" name="memberSearchLast" placeholder="Enter Last Name" type="text" id="memberSearchLast">
			<button type="submit" value="Search">Search</button>
                    </form>
                </div>
            </div>
        </div>
	<div class="tableWrapper">
        <table class="center">
	   <thead>
            <tr>
                <th>Member ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th class="collapsable">Age</th>
                <th class="collapsable">Phone Number</th>
                <th class="collapsable">Email</th>
                <th>Date Last Spoken</th>
	    </tr>
	   </thead>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $servername = "localhost";
                $username = "admin";
                $password = "admin";
                $dbname = "tiffanyspringsward";
                $first_name = $_POST["memberSearchFirst"];
                $last_name = $_POST["memberSearchLast"];

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare the SQL query based on input fields
                $sql = "SELECT DISTINCT members.member_id, members.last_name, members.first_name, members.age, members.phone_number, members.email, DATE_FORMAT(max_talk_date, '%c/%d/%Y') AS formatted_max_talk_date
                        FROM members
                        LEFT JOIN (
                            SELECT member_id, MAX(talk_date) AS max_talk_date
                            FROM talks
                            GROUP BY member_id
                        ) AS talks ON members.member_id = talks.member_id";

                if (!empty($first_name) && !empty($last_name)) {
                    $sql .= " WHERE members.first_name = '$first_name' AND members.last_name = '$last_name'";
                } elseif (!empty($last_name)) {
                    $sql .= " WHERE members.last_name = '$last_name'";
                } elseif (!empty($first_name)) {
                    $sql .= " WHERE members.first_name = '$first_name'";
                } else {
                    echo "Please enter at least one search criteria.";
                    exit;
                }

                // Execute the SQL query
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        $maxTalkDate = ($row["formatted_max_talk_date"] !== null) ? $row["formatted_max_talk_date"] : "Has Not Spoken";
                        echo "<tr>
                                <td>" . $row["member_id"] . "</td>
                                <td>" . $row["last_name"] . "</td>
                                <td>" . $row["first_name"] . "</td>
                                <td class=\"collapsable\">" . $row["age"] . "</td>
                                <td class=\"collapsable\">" . $row["phone_number"] . "</td>
                                <td class=\"collapsable\">" . $row["email"] . "</td>
                                <td>" . $maxTalkDate . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>0 results</td></tr>";
                }

                $conn->close();
            }
            ?>
        </table>
	</div>
    </main>
    <footer>
    </footer>
    <script src="../script.js"></script>
</body>
</html>
