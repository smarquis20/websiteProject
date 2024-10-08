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

    <!-- JavaScript to initialize the Datepicker and load data -->
    <script>
    $(function() {
        $("#speakingDate").datepicker({
            dateFormat: 'yy-mm-dd',  // Format date as yyyy-mm-dd
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });

        // Function to load members data
        function loadMembers() {
            $.ajax({
                url: 'get_members.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var table = $('table.center tbody');
                    table.empty();
                    data.forEach(function(member) {
                        var row = `<tr>
                            <td>${member.member_id}</td>
                            <td>${member.last_name}</td>
                            <td>${member.first_name}</td>
                            <td>${member.age}</td>
                            <td>${member.phone_number}</td>
                        </tr>`;
                        table.append(row);
                    });
                },
                error: function(error) {
                    console.log('Error fetching members data:', error);
                }
            });
        }

        // Load members data when the page loads
        loadMembers();
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
                <div class="memberSearch-h1">Assign a Member to Speak</div>
                <div class="memberSearchForm">
                    <form name="memberSearch-Form" class="memberSearch-form" action="talks.php" method="post">
                        <input class="msearch-text-field" name="speakerID" placeholder="Enter the ID of the Speaker" type="text" id="speakerID" required>
                        <input class="msearch-text-field" name="speakingDate" placeholder="Enter the Date of the Talk" type="text" id="speakingDate" required>
                        <input class="msearch-text-field" name="speakingTopic" placeholder="Enter the Topic of the Talk" type="text" id="speakingTopic" required>
			<button type="submit" value="Assign">Assign</button>
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
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded here dynamically -->
            </tbody>
        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $servername = "localhost";
                $username = "admin";
                $password = "admin";
                $dbname = "tiffanyspringsward";
                $memberID = $_POST["speakerID"];
                $speakingDate = $_POST["speakingDate"];
                $speakingTopic = $_POST["speakingTopic"];

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and execute the statement to fetch last_name
                $stmt = $conn->prepare("SELECT last_name FROM members WHERE member_id = ?");
                $stmt->bind_param("i", $memberID);
                $stmt->execute();
                $stmt->bind_result($last_name);
                $stmt->fetch();
                $stmt->close();

                // Prepare and execute the statement to fetch first_name
                $stmt = $conn->prepare("SELECT first_name FROM members WHERE member_id = ?");
                $stmt->bind_param("i", $memberID);
                $stmt->execute();
                $stmt->bind_result($first_name);
                $stmt->fetch();
                $stmt->close();

                // Prepare and execute the statement to insert into talks
                $stmt = $conn->prepare("INSERT INTO talks (member_id, talk_date, last_name, first_name, topic) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $memberID, $speakingDate, $last_name, $first_name, $speakingTopic);
                $stmt->execute();
                $stmt->close();

                echo "$first_name $last_name has been assigned to speak on $speakingDate.";

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
