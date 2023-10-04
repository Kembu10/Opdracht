<!DOCTYPE html>
<html>

<head>
    <title>Employee List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: lightgreen;
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
        $user = "kembu";
        $password = "poes2006";
        $database = "assessment";
        $conn = mysqli_connect($host, $user, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // input to update data in the database
        if (isset($_POST['update_id'])) {
            $id = $_POST['update_id'];
            $name = $_POST['name'];
            $function = $_POST['function'];
            $salary = $_POST['salary'];
            $new_id = $_POST['id'];
            $sql = "UPDATE people SET name=?, id=?, function=?, salary=? WHERE id=?";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sisii", $name, $new_id, $function, $salary, $id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                echo "<script>window.location.href='assessment-1.php';</script>";
                exit();
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }
        }

        // Handle form input to remove data from the database
        if (isset($_POST['remove_id'])) {
            $id = $_POST['remove_id'];
            echo "<div class='modal fade' id='confirmationModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title'>Delete Employee Record</h5>";
            echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<p>Do you really want to delete this employee record?</p>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<form method='post' action='assessment-1.php'>";
            echo "<input type='hidden' name='remove_id' value='$id' />";
            echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>";
            echo "<button type='submit' class='btn btn-primary'>Delete</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<script>$('#confirmationModal').modal('show');</script>";
        }

        // Query the database and display the data
        $sql = "SELECT name, id, function, salary FROM people";
        $result = mysqli_query($conn, $sql);
        $total_salary = 0;

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
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

        mysqli_close($conn);

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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Close modal after submitting the form
            $(".modal form").submit(function() {
                $(this).closest(".modal").modal("hide");
            });
        });

        function confirmDelete(event) {
            event.preventDefault();

            var removeForm = $(event.target).closest("form");
            var removeId = removeForm.find("[name='remove_id']").val();

            $("#confirmationModal [name='remove_id']").val(removeId);
            $("#confirmationModal").modal("show");
        }
    </script>
</body>

</html>