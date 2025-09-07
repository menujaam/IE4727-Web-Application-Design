<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>show get php</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>Name:</td>
            <td><?php echo $_GET["name"]; ?></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?php echo $_GET["email"]; ?></td>
        </tr>
        <tr>
            <td>Start Date:</td>
            <td><?php echo ($_GET["startDate"])? $_GET["startDate"]:'N/A'; ?></td>
        </tr>
        <tr>
            <td>Experience:</td>
            <td><?php echo $_GET["experience"]; ?></td>
        </tr>
    </table>
</body>
</html>