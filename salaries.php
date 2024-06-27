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

// Initialize a variable to hold the WHERE clause
$whereClause = "";

// Check if the form is submitted with a search query
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    // Construct the WHERE clause for filtering across multiple fields
    $whereClause = " WHERE 
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

// Construct the SELECT query with the WHERE clause
$sql = "SELECT employee_id, employee_name, employee_email, employee_city, employee_position, employee_adress, employee_salary, employee_gender, employee_number, employee_probation_start, employee_probation_end FROM employees" . $whereClause;

// Execute query to fetch employees
$employees = $conn->query($sql);

// Handle Salary Payment Form Submission
if (isset($_POST['SaveSalary'])) {
    $employee_id = $_POST['employee_id'];
    $salary_amount = $_POST['salary_amount'];
    $pay_date = $_POST['pay_date'];

    $sql = "INSERT INTO salary (employee_id, salary_amount, pay_date) VALUES ('$employee_id', '$salary_amount', '$pay_date')";

    if ($conn->query($sql) === TRUE) {
        // Redirect after successful insertion
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Salary Update Form Submission
if (isset($_POST['UpdateSalary'])) {
    $salary_id = $_POST['salary_id'];
    $salary_amount = $_POST['salary_amount'];
    $pay_date = $_POST['pay_date'];

    $sql = "UPDATE salary SET salary_amount='$salary_amount', pay_date='$pay_date' WHERE salary_id='$salary_id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect after successful update
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error updating salary: " . $conn->error;
    }
}

// Handle Salary Deletion Form Submission
if (isset($_POST['DeleteSalary'])) {
    $salary_id = $_POST['salary_id'];

    $sql = "DELETE FROM salary WHERE salary_id='$salary_id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect after successful deletion
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error deleting salary: " . $conn->error;
    }
}

// Fetch salaries
$salaries = $conn->query("SELECT s.salary_id, e.employee_name, s.salary_amount, s.pay_date FROM salary s JOIN employees e ON s.employee_id = e.employee_id");

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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#salaryModal">Pay Salaries</button>
    </div>

    <div class="text-end mb-3">
        <form method="POST" class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" name="searchTerm" placeholder="Search by Name, Email, City, etc.">
                <button type="submit" name="search" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
    </div>

    <!-- Salary Modal -->
    <div class="modal fade" id="salaryModal" tabindex="-1" aria-labelledby="salaryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salaryModalLabel">Add Salary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" class="form-control" required>
                                <?php
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                $employees = $conn->query("SELECT employee_id, employee_name FROM employees");
                                while ($employee = $employees->fetch_assoc()) {
                                    echo "<option value='{$employee['employee_id']}'>{$employee['employee_name']}</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="salaryAmount" class="form-label">Salary Amount</label>
                            <input name="salary_amount" type="number" class="form-control" id="salaryAmount" required>
                        </div>
                        <div class="mb-3">
                            <label for="pay_date" class="form-label">Pay Date</label>
                            <input name="pay_date" type="date" class="form-control" id="pay_date" required>
                        </div>
                        <input name="SaveSalary" type="submit" value="Save Salary" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Salary Amount</th>
                <th>Pay Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($salary = $salaries->fetch_assoc()): ?>
            <tr>
                <td><?php echo $salary['employee_name']; ?></td>
                <td><?php echo $salary['salary_amount']; ?></td>
                <td><?php echo $salary['pay_date']; ?></td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSalaryModal<?php echo $salary['salary_id']; ?>">Edit</button>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="salary_id" value="<?php echo $salary['salary_id']; ?>">
                        <input type="submit" name="DeleteSalary" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                </td>
            </tr>

            <!-- Edit Salary Modal -->
            <div class="modal fade" id="editSalaryModal<?php echo $salary['salary_id']; ?>" tabindex="-1" aria-labelledby="editSalaryModalLabel<?php echo $salary['salary_id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSalaryModalLabel<?php echo $salary['salary_id']; ?>">Edit Salary</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <input type="hidden" name="salary_id" value="<?php echo $salary['salary_id']; ?>">
                                <div class="mb-3">
                                    <label for="salary_amount<?php echo $salary['salary_id']; ?>" class="form-label">Salary Amount</label>
                                    <input name="salary_amount" type="number" class="form-control" id="salary_amount<?php echo $salary['salary_id']; ?>" value="<?php echo $salary['salary_amount']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pay_date<?php echo $salary['salary_id']; ?>" class="form-label">Pay Date</label>
                                    <input name="pay_date" type="date" class="form-control" id="pay_date<?php echo $salary['salary_id']; ?>" value="<?php echo $salary['pay_date']; ?>" required>
                                </div>
                                <input name="UpdateSalary" type="submit" value="Update Salary" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
