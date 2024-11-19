<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $campusName = $_POST["campusName"];
  $numTeams = $_POST["numTeams"];

  // Database connection details (replace with your actual credentials)
  $servername = "your_database_host";
  $username = "your_database_user";
  $password = "your_database_password";
  $dbname = "your_database_name";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS pre_registration (
      id INT AUTO_INCREMENT PRIMARY KEY,
      campus_name VARCHAR(255),
      team_name VARCHAR(255),
      coach VARCHAR(255),
      team_members TEXT,
      timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    for ($i = 1; $i <= $numTeams; $i++) {
      $teamName = $_POST["teamName" . $i];
      $coach = $_POST["coach" . $i];
      $teamMembers = $_POST["teamMember" . $i];

      // Insert data into the database
      $stmt = $conn->prepare("INSERT INTO pre_registration (campus_name, team_name, coach, team_members) 
                              VALUES (:campusName, :teamName, :coach, :teamMembers)");
      $stmt->bindParam(':campusName', $campusName);
      $stmt->bindParam(':teamName', $teamName);
      $stmt->bindParam(':coach', $coach);
      $stmt->bindParam(':teamMembers', $teamMembers);
      $stmt->execute();
    }

    echo "Pre-registration successful!"; // You can replace this with a redirect to a success page

  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $conn = null;
}
?>
