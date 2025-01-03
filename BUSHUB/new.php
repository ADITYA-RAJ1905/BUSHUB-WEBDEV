<?php
if (isset($_POST['submit'])) {
    // Database connection
    $connection = mysqli_connect("localhost", "root", "Vidyad@1905", "mycompany");

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get data from form
    $id = $_POST['userid'];
    $name = $_POST['username'];
    $count = $_POST['usercount'];

    // Sanitize inputs to prevent SQL injection
    $id = mysqli_real_escape_string($connection, $id);
    $name = mysqli_real_escape_string($connection, $name);
    $count = mysqli_real_escape_string($connection, $count);
    $validate="select * from sales where id=$id and name='$name'";
    $result=mysqli_query($connection,$validate);
    if(mysqli_num_rows($result)>0){
        echo "<h1>"." BSDK Phirse login karke maa na chudwa"."</h1>";
    }
    else{
        // Query to insert data into the 'sales' table
        $query = "INSERT INTO sales (id, name, count) VALUES ($id, '$name', $count)";

    // Execute query
        if (mysqli_query($connection, $query)) {
            echo "Record inserted successfully!";
        } else {
            echo "Error: " . mysqli_error($connection);
        }

    }
    // Close connection
    mysqli_close($connection);
}
?>
