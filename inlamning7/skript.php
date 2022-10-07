<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>skript</title>
</head>

<body>
    <form action="checklogin.php" method="post">
        <input type="submit" name="loggaut" value="logga ut">
    </form>
    <?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webbserverprogrammering";

    $conn = new mysqli($servername, $username, $password, $dbname);


    $sql = "SELECT * FROM users";
    $result_table_users = $conn->query($sql);

    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $filename = $_POST["file"];
    $password = $_POST["password"];

    function login_success($result, $firstname, $username, $lastname, $filename, $password)
    {
        if (!$username || !$firstname || !$lastname || !$filename || !$password) {
            return "info saknas";
        }
        $full_name = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (
                    $row["userId"] == $_POST["username"] &&
                    $row["passwd"] == $_POST["password"]
                ) {
                    $full_name = $row["firstname"] . " " . $row["lastname"];
                    return $full_name;
                }
            }
        } else {
            echo "Inloggning listan är tom!";
        }
    }

    $inloggning_check = login_success($result_table_users, $firstname, $username, $lastname, $filename, $password);


    $sql = "SELECT * FROM uploads";
    $result_table_uploads =  $conn->query($sql);

    if ($inloggning_check == "info saknas") {
        echo "Fyll gärna klart formuläret för att kunna logga in.";
    } else if (!$inloggning_check) {
        $sql = "INSERT INTO uploads VALUES ($result_table_uploads->num_rows, '$username', '$filename', NOW(), 'FALSE');";
        $result = $conn->query($sql);
        $sql = "INSERT INTO users VALUES ($result_table_users->num_rows+1, '$firstname', '$lastname','$username', '$password');";
        $result = $conn->query($sql);
        echo "En ny inloggning med användernamnet $username har lagt till. <br> <br>";
        while ($row = $result_table_uploads->fetch_assoc()) {
            echo $row['id'] . ": " . $row['user'] . ": " . $row['uploadtime'] . ": " .  $row['snuskig'] . "<br> <br>";
        }
    } else {
        echo "Välkommen tillbaka $inloggning_check, du är inloggad! <br> <br>";
        $check_lista = array();
        while ($row = $result_table_uploads->fetch_assoc()) {
            if ($row['filename'] == $filename) {
                array_push($check_lista, false);
            } else {
                array_push($check_lista, true);
            }
        }
    }
    if (in_array(true, $check_lista)) {
        $sql = "INSERT INTO uploads VALUES ($result_table_uploads->num_rows, '$username', '$filename', NOW(), 'FALSE');";
        $result = $conn->query($sql);
        while ($row = $result_table_uploads->fetch_assoc()) {
            echo $row['id'] . ": " . $row['user'] . ": " . $row['filename'] . ": " . $row['uploadtime'] . ": " .  $row['snuskig'] . "<br> <br>";
        }
    } else {
        while ($row = $result_table_uploads->fetch_assoc()) {
            echo $row['id'] . ": " . $row['user'] . ": " . $row['filename'] . ": " . $row['uploadtime'] . ": " .  $row['snuskig'] . "<br> <br>";
        }
    }
    $conn->close();

    if ($login_success) {
        session_start();
        $_SESSION["username"] = $username;
    }

    echo "<a href='localhost/uppdaterat_inlamning3/formular.php'>Ladda upp fil</a>";
    ?>
</body>

</html>