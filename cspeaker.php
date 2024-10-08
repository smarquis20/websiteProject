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

    <!-- Include jQuery and jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- JavaScript to initialize the Datepicker -->
    <script>
    $(function() {
        $("#speakingDate").datepicker({
            dateFormat: 'yy-mm-dd',  // Format date as yyyy-mm-dd
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    });
    </script>
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
                <div class="memberSearch-h1">Speakers By Month</div>
                <div class="memberSearchForm">
                    <form name="memberSearch-Form" class="memberSearch-form" action="cspeaker.php" method="post">
                        <input class="msearch-text-field" name="speakingDate" placeholder="Enter Starting Date" type="text" id="speakingDate">
			<button type="submit" value="Search">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <table class="center">
	   <thead>
            <tr>
                <th class="collapsable">Member ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th class="collapsable">Age</th>
                <th class="collapsable">Phone Number</th>
                <th>Speaking Date</th>
                <th>Speaking Topic</th>
            </tr>
	   </thead>
            <?php
            if ($_SERVER['REQUEST_METHOD']=='POST') {
                $servername = "localhost";
                $username = "admin";
                $password = "admin";
                $dbname = "tiffanyspringsward";
                $talk_date = $_POST["speakingDate"];

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query to fetch member details and their last talk date, sorted by talk_date DESC
                $sql = "SELECT members.member_id, members.last_name, members.first_name, members.age, members.phone_number, DATE_FORMAT(talks.talk_date, '%c/%d/%Y') AS formatted_talk_date, talks.topic FROM members 
                        JOIN talks ON members.member_id = talks.member_id 
                        WHERE talks.talk_date >= '$talk_date'
                        ORDER BY talks.talk_date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td class=\"collapsable\">" . $row["member_id"] . "</td>
                                <td>" . $row["last_name"] . "</td>
                                <td>" . $row["first_name"] . "</td>
                                <td class=\"collapsable\">" . $row["age"] . "</td>
                                <td class=\"collapsable\">" . $row["phone_number"] . "</td>
                                <td>" . $row["formatted_talk_date"] . "</td>
                                <td>" . $row["topic"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
            }
            ?>
        </table>
    </main>
    <footer>
    </footer>
    <script src="script.js"></script>
</body>
</html>
