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
                    <div class="memberSearch-h1">Create Speaker List</div>
                    <div class="memberSearchForm">
                        <form name="memberSearch-Form" class="memberSearch-form" action="speakerl.php" method="post">
                            <input class="msearch-text-field" name="numberMonths" placeholder="Enter number of months to look back" type="text" id="numberMonths" required>
                            <select class="msearch-text-field" name="youthOnly" id="youthOnly">
                                <option value="all">Adult Speakers</option>
                                <option value="youth">Youth Speakers</option>
                            </select>
                            <button type="submit" value="Create List">Create List</button>
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
                    <th>Last Talk</th>
                    <th>Months Since Spoken</th>
                </tr>
               </thead>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $servername = "localhost";
                    $username = "admin";
                    $password = "admin";
                    $dbname = "tiffanyspringsward";
                    $date = $_POST["numberMonths"];
                    $youthOnly = $_POST["youthOnly"];

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Calculate the talk_date by subtracting numberMonths from today's date
                    $sql = "SELECT DATE_SUB(CURDATE(), INTERVAL $date MONTH) AS talk_date";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $talk_date = $row['talk_date'];

                    // Base query
                    $sql = "SELECT members.member_id, members.last_name, members.first_name, members.age, members.phone_number, 
                            DATE_FORMAT(max_talk_date, '%c/%d/%Y') AS formatted_max_talk_date, 
                            TIMESTAMPDIFF(MONTH, max_talk_date, CURDATE()) AS months_since_spoken
                    FROM members
                    LEFT JOIN (SELECT member_id, MAX(talk_date) AS max_talk_date
                    FROM talks
                    GROUP BY member_id) AS talks ON members.member_id = talks.member_id
                    WHERE (max_talk_date < '$talk_date' OR max_talk_date IS NULL) ";

                    // Add condition for youth only speakers if selected
                    if ($youthOnly == "youth") {
                        $sql .= "AND members.age BETWEEN 12 AND 18 ";
                    } else {
                        $sql .= "AND members.age > 18 ";
                    }

                    // Order by talk_date DESC
                    $sql .= "ORDER BY max_talk_date DESC";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row["formatted_max_talk_date"] === null) {
                                $last_spoke = "Has Not Spoken";
                            } else {
                                $last_spoke = $row["months_since_spoken"] . " months ago";
                            }

                            echo "<tr>
                                    <td class=\"collapsable\">" . $row["member_id"] . "</td>
                                    <td>" . $row["last_name"] . "</td>
                                    <td>" . $row["first_name"] . "</td>
                                    <td class=\"collapsable\">" . $row["age"] . "</td>
                                    <td class=\"collapsable\">" . $row["phone_number"] . "</td>
                                    <td>" . ($row["formatted_max_talk_date"] ?? "") . "</td>
                                    <td>" . $last_spoke . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>0 results</td></tr>";
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
