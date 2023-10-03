<!DOCTYPE html>
<html>
<head>
    <title>insert</title>
</head>
<body>
    <center>
        <?php
        $conn = mysqli_connect("localhost", "anouar", "", "assessment");

        // Check connection
        if($conn === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        // Taking all 5 values from the form data(input)
        $name = $_REQUEST['name'];
        $id = $_REQUEST['id'];
        $function = $_REQUEST['function'];
        $salary = $_REQUEST['salary'];

        // Attempt update query
        $sql = "UPDATE people SET name='$name', function='$function', salary='$salary' WHERE id='$id'";



        $sql = "INSERT INTO people (name, id, function, salary) VALUES ('$name', '$id', '$function', '$salary')";
        
        if(mysqli_query($conn, $sql)){
            echo "<h3>Data stored in database successfully."
                . " Please browse your localhost php my admin"
                . " to view the updated data</h3>";

            echo nl2br("\n$name\n $id\n "
                . "$function\n $salary\n");

            // Redirect user to the main page
            header("Location: assessment-1.php");
        } else{
            echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
        }


        // Close connection
        mysqli_close($conn);
        ?>
        