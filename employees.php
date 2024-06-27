<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['SaveEmployee'])) {
        $employee_name = $_POST['employee_name'];
        $employee_email = $_POST['employee_email'];
        $employee_city = $_POST['employee_city'];
        $employee_position = $_POST['employee_position'];
        $employee_gender = $_POST['employee_gender'];
        $employee_number = $_POST['employee_number'];
        $employee_adress = $_POST['employee_adress'];
        $employee_salary = $_POST['employee_salary'];
        $employee_probation_start = $_POST['employee_probation_start'];
        $employee_probation_end = $_POST['employee_probation_end'];

        $picture = $_FILES['employee_picture']['name'];
        $target = "uploads/" . basename($picture);
        move_uploaded_file($_FILES['employee_picture']['tmp_name'], $target);

        $sql = "INSERT INTO employees (EMPLOYEE_NAME, EMPLOYEE_EMAIL, EMPLOYEE_PICTURE, EMPLOYEE_CITY, EMPLOYEE_POSITION, EMPLOYEE_GENDER, EMPLOYEE_NUMBER, EMPLOYEE_ADRESS, EMPLOYEE_SALARY, EMPLOYEE_PROBATION_START, EMPLOYEE_PROBATION_END) 
                VALUES ('$employee_name', '$employee_email', '$picture', '$employee_city', '$employee_position', '$employee_gender', '$employee_number', '$employee_adress', '$employee_salary', '$employee_probation_start', '$employee_probation_end')";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['UpdateEmployee'])) {
        $id = $_POST['employee_id'];
        $employee_name = $_POST['employee_name'];
        $employee_email = $_POST['employee_email'];
        $employee_city = $_POST['employee_city'];
        $employee_position = $_POST['employee_position'];
        $employee_gender = $_POST['employee_gender'];
        $employee_number = $_POST['employee_number'];
        $employee_adress = $_POST['employee_adress'];
        $employee_salary = $_POST['employee_salary'];
        $employee_probation_start = $_POST['employee_probation_start'];
        $employee_probation_end = $_POST['employee_probation_end'];

        $sql = "UPDATE employees SET EMPLOYEE_NAME='$employee_name', EMPLOYEE_EMAIL='$employee_email', EMPLOYEE_CITY='$employee_city', EMPLOYEE_POSITION='$employee_position', EMPLOYEE_GENDER='$employee_gender', EMPLOYEE_NUMBER='$employee_number', EMPLOYEE_ADRESS='$employee_adress', EMPLOYEE_SALARY='$employee_salary', EMPLOYEE_PROBATION_START='$employee_probation_start', EMPLOYEE_PROBATION_END='$employee_probation_end' WHERE employee_id=$id";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error updating employee: " . $conn->error;
        }
    }

    if (isset($_POST['DeleteEmployee'])) {
        $id = $_POST['employee_id'];

        $sql = "DELETE FROM employees WHERE employee_id=$id";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['search'])) {
        $searchTerm = $_POST['searchTerm'];
        $whereClause = " WHERE 
            employee_id LIKE '%$searchTerm%' OR 
            employee_name LIKE '%$searchTerm%' OR 
            employee_email LIKE '%$searchTerm%' OR 
            employee_city LIKE '%$searchTerm%' OR 
            employee_position LIKE '%$searchTerm%' OR 
            employee_adress LIKE '%$searchTerm%' OR 
            employee_salary LIKE '%$searchTerm%' OR 
            employee_gender LIKE '%$searchTerm%' OR 
            employee_number LIKE '%$searchTerm%' OR 
            employee_probation_start LIKE '%$searchTerm%' OR 
            employee_probation_end LIKE '%$searchTerm%'";
    }
}

$whereClause = isset($whereClause) ? $whereClause : "";
$sql = "SELECT employee_id, employee_name, employee_email, employee_city, employee_position, employee_adress, employee_salary, employee_gender, employee_number, employee_probation_start, employee_probation_end FROM employees" . $whereClause;

$employees = $conn->query($sql);



$employee_id = 1;  

$sql_delete_employee = "DELETE FROM employees WHERE employee_id = $employee_id";

if ($conn->query($sql_delete_employee) === TRUE) {
    $sql_delete_salaries = "DELETE FROM salary WHERE employee_id = $employee_id";
    $conn->query($sql_delete_salaries);
    $sql_delete_leaves = "DELETE FROM leaves WHERE employee_id = $employee_id";
    $conn->query($sql_delete_leaves);
    $sql_delete_probation = "DELETE FROM probation WHERE employee_id = $employee_id";
    $conn->query($sql_delete_probation);

   
   
}

else {
    echo "Error deleting employee: " . $conn->error;
}


$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal">Add Employee</button>
        </div>
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary" >< href="admin.Site.ph</button>
        </div>
        <div class="text-end mb-3">
    <form method="POST" class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" name="searchTerm" placeholder="Search by Name">
            <button type="submit" name="search" class="btn btn-outline-primary">Search</button>
        </div>
    </form>
</div>

        <table class="table">
        <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">City</th>
        <th scope="col">Position</th>
        <th scope="col">Address</th> 
        <th scope="col">Salary</th>
          <th scope="col">Number</th>
        <th scope="col">Gender</th> 
        <th scope="col">Start</th>
        <th scope="col">End</th>
        <th scope="col">Actions</th>
    </tr>
</thead>
            <tbody>
            <?php while ($row = $employees->fetch_assoc()): ?>
         <tr>
        <th scope="row"><?php echo $row['employee_id']; ?></th>
        <td><?php echo $row['employee_name']; ?></td>
        <td><?php echo $row['employee_email']; ?></td>
        <td><?php echo $row['employee_city']; ?></td>
        <td><?php echo $row['employee_position']; ?></td>
        <td><?php echo $row['employee_adress']; ?></td>
        <td><?php echo $row['employee_salary']; ?></td>
        <td><?php echo $row['employee_number']; ?></td>
        <td><?php echo $row['employee_gender']; ?></td>
        <td><?php echo $row['employee_probation_start']; ?></td>
        <td><?php echo $row['employee_probation_end']; ?></td>

        <td>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['employee_id']; ?>">Edit</button>
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['employee_id']; ?>">Delete</button>
        </td>
    </tr>
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $row['employee_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
                                        <div class="mb-3">
                                            <label for="employee_name" class="form-label">Employee Name</label>
                                            <input name="employee_name" type="text" class="form-control" value="<?php echo $row['employee_name']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_email" class="form-label">Email</label>
                                            <input name="employee_email" type="email" class="form-control" value="<?php echo $row['employee_email']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_city" class="form-label">City</label>
                                            <input name="employee_city" type="text" class="form-control" value="<?php echo $row['employee_city']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_position" class="form-label">Position</label>
                                            <input name="employee_position" type="text" class="form-control" value="<?php echo $row['employee_position']; ?>" required>
                                        </div>
                                     
                                        <div class="mb-3">
                                            <label for="employee_gender" class="form-label">Gender</label>
                                            <input name="employee_gender" type="text" class="form-control" value="<?php echo $row['employee_gender']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_gender" class="form-label">Gender</label>
                                            <input name="employee_gender" type="text" class="form-control" value="<?php echo $row['employee_gender']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_number" class="form-label">Number</label>
                                            <input name="employee_number" type="number" class="form-control" value="<?php echo $row['employee_number']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_adress" class="form-label">Address</label>
                                            <input name="employee_adress" type="text" class="form-control" value="<?php echo $row['employee_adress']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_salary" class="form-label">Salary</label>
                                            <input name="employee_salary" type="number" class="form-control" value="<?php echo $row['employee_salary']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_probation_start" class="form-label">Probation Start</label>
                                            <input name="employee_probation_start" type="date" class="form-control" value="<?php echo $row['employee_probation_start']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="employee_probation_end" class="form-label">Probation End</label>
                                            <input name="employee_probation_end" type="date" class="form-control" value="<?php echo $row['employee_probation_end']; ?>" required>
                                        </div>
                                        <input type="submit" name="UpdateEmployee" value="Update Employee" class="btn btn-warning">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $row['employee_id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
                                        <p>Are you sure you want to delete this employee?</p>
                                        <input type="submit" name="DeleteEmployee" value="Delete Employee" class="btn btn-danger">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Employee Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="employee_name" class="form-label">Employee Name</label>
                            <input name="employee_name" type="text" class="form-control" id="employee_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_email" class="form-label">Email</label>
                            <input name="employee_email" type="email" class="form-control" id="employee_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_picture" class="form-label">Picture</label>
                            <input name="employee_picture" type="file" class="form-control" id="employee_picture" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_city" class="form-label">City</label>
                            <input name="employee_city" type="text" class="form-control" id="employee_city" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_position" class="form-label">Position</label>
                            <input name="employee_position" type="text" class="form-control" id="employee_position" required>
                        </div>
                     
                        <div class="mb-3">
                            <label for="employee_number" class="form-label">Number</label>
                            <input name="employee_number" type="number" class="form-control" id="employee_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_adress" class="form-label">Address</label>
                            <input name="employee_adress" type="text" class="form-control" id="employee_adress" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_salary" class="form-label">Salary</label>
                            <input name="employee_salary" type="number" class="form-control" id="employee_salary" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_gender" class="form-label">Gender</label>
                            <input name="employee_gender" type="text" class="form-control" id="employee_gender" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_probation_start" class="form-label">Probation Start</label>
                            <input name="employee_probation_start" type="date" class="form-control" id="employee_probation_start" required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_probation_end" class="form-label">Probation End</label>
                            <input name="employee_probation_end" type="date" class="form-control" id="employee_probation_end" required>
                        </div>
                        <input type="submit" name="SaveEmployee" value="Save Employee" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>