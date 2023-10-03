<!DOCTYPE html>
<html>

<head>
    <title>assessment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <style>

        body {
            background-color: lightgreen
        }
        .name {           
            border-collapse: collapse;
            width: 100%;
            color: black;
            font-family: monospace;
            font-size: 60px;
            text-align: center;
        }
        
        
        table {
            border-collapse: collapse;
            width: 100%;
            color: black;
            font-family: monospace;
            font-size: 25px;
            text-align: left;
        }

        th {
            background-color: #588c7e;
            color: black;
        }

        tr:nth-child(even) td {
            background-color: lightgreen;
        }

        td {
            padding: 10px;
        }

        .total {
            color: orange;
            text-decoration-color: black;

        }
    </style>
</head>

<body>
   <div class="name">Employee List</div>
    <table>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Function</th>
            <th>Salary</th>
            <th>Remove</th>
            <th>Update</th>
        </tr>

        <?php
        $host = "localhost";
        $user = "anouar";
        $password = null;
        $database = "assessment";
        $conn = mysqli_connect($host, $user, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // input to update data in the database
        if (isset($_POST['update_id'])) {
            $id = $_POST['update_id'];
            $name = $_POST['name'];
            $function = $_POST['function'];
            $salary = $_POST['salary'];
            $new_id = $_POST['id'];
            $sql = "UPDATE people SET name='$name', id='$new_id', function='$function', salary='$salary' WHERE id='$id'";

            if (mysqli_query($conn, $sql)) {
                header("Location: assessment-1.php");
                exit();
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }
        }

        // Handle form input to remove data from the database
        if (isset($_POST['remove_id'])) {
            $id = $_POST['remove_id'];
            $sql = "DELETE FROM people WHERE id='$id' LIMIT 1";
            if ($conn->query($sql) === TRUE) {
                header("Location: assessment-1.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }

        // Query the database and display the data
        $sql = "SELECT name, id, function, salary FROM people";
        $result = $conn->query($sql);
        $total_salary = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["id"] . "</td><td>" . $row["function"] . "</td><td>" . $row["salary"] . "</td>";
                echo "<td>";
                echo "<form method='post'><input type='hidden' name='remove_id' value='" . $row["id"] . "'><input type='submit' value='Remove'></form>";
                echo "</td>";
                echo "<td>";
                // Button to show update modal
                echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#myModal_" . $row["id"] . "'>Update</button>";
                // Update modal
                echo "<div class='modal fade' id='myModal_" . $row["id"] . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
                echo "<div class='modal-dialog' role='document'>";
                echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                echo "<h5 class='modal-title' id='exampleModalLabel_" . $row["id"] . "'>Update Employee Data</h5>";
                echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo "</div>";
                echo "<div class='modal-body'>";
                // Form to update data
                echo "<form method='post'>";
                echo "<input type='hidden' name='update_id' value='" . $row["id"] . "' />";
                echo "<div class='mb-3'>";
                echo "<label for='name' class='form-label'>Name</label>";
                echo "<input type='text' class='form-control' id='name' name='name' value='" . $row["name"] . "'>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='id' class='form-label'>ID</label>";
                echo "<input type='number' class='form-control' id='id' name='id' value='" . $row['id'] . "'>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='function' class='form-label'>Function</label>";
                echo "<input type='text' class='form-control' id='function' name='function' value='" . $row["function"] . "'>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='salary' class='form-label'>Salary</label>";
                echo "<input type='number' class='form-control' id='salary' name='salary' value='" . $row["salary"] . "'>";
                echo "</div>";
                echo "</div>";
                echo "<div class='modal-footer'>";
                echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>";
                echo "<button type='submit' class='btn btn-primary'>Update</button>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</td>";
                $total_salary += $row["salary"];
            }
            echo "<tr class='total'><td colspan='3'>Total Salary:</td><td>" . $total_salary . "</td><td></td><td></td></tr>";
        } else {
            echo "<tr><td colspan='6'>0 results</td></tr>";
        }

        $conn->close();
        ?>
    </table>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
        +
    </button>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Toevoegen?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="insert.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label">Id</label>
                            <input type="number" class="form-control" id="number" name="number" placeholder="Enter your Id">
                        </div>
                        <div class="mb-3">
                            <label for="function" class="form-label">Function</label>
                            <input type="text" class="form-control" id="function" name="function" placeholder="Enter your function">
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter your Salary">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nee</button>
                            <button type="submit" class="btn btn-primary">Ja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>