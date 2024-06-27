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
        leave_title LIKE '%$searchTerm%' OR 
        leave_description LIKE '%$searchTerm%' OR 
        number_of_days LIKE '%$searchTerm%'";
}

// Construct the SELECT query with the WHERE clause
$sql = "SELECT leave_id, leave_title, leave_description, number_of_days, employee_id FROM leaves" . $whereClause;

// Execute query to fetch leaves
$leaves = $conn->query($sql);

// Fetch employees for the dropdown
$employeesResult = $conn->query("SELECT employee_id, employee_name FROM employees");
$employees = [];
if ($employeesResult->num_rows > 0) {
    while ($row = $employeesResult->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Handle the Save Leave form submission
if (isset($_POST['SaveLeave'])) {
    $leave_title = $_POST['leave_title'];
    $leave_description = $_POST['leave_description'];
    $number_of_days = $_POST['number_of_days'];
    $employee_id = $_POST['employee_id'];

    $sql = "INSERT INTO leaves (leave_title, leave_description, number_of_days, employee_id) VALUES ('$leave_title', '$leave_description', '$number_of_days', '$employee_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Leave added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle the Update Leave form submission
if (isset($_POST['UpdateLeave'])) {
    $leave_id = $_POST['leave_id'];
    $leave_title = $_POST['leave_title'];
    $leave_description = $_POST['leave_description'];
    $number_of_days = $_POST['number_of_days'];
    $employee_id = $_POST['employee_id'];

    $sql = "UPDATE leaves SET 
            leave_title='$leave_title', 
            leave_description='$leave_description', 
            number_of_days='$number_of_days',
            employee_id='$employee_id'
            WHERE leave_id='$leave_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Leave updated successfully";
    } else {
        echo "Error updating leave: " . $conn->error;
    }
}

// Handle the Delete Leave form submission
if (isset($_POST['DeleteLeave'])) {
    $leave_id = $_POST['leave_id'];

    $sql = "DELETE FROM leaves WHERE leave_id='$leave_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Leave deleted successfully";
    } else {
        echo "Error deleting leave: " . $conn->error;
    }
}

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaves Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leavesModal">Add Leave</button>
    </div>

    <div class="text-end mb-3">
        <form method="POST" class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" name="searchTerm" placeholder="Search by Title, Description, etc.">
                <button type="submit" name="search" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Leave Title</th>
                <th scope="col">Leave Description</th>
                <th scope="col">Number of Days</th>
                <th scope="col">Employee</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($leaves->num_rows > 0): ?>
                <?php while ($row = $leaves->fetch_assoc()): ?>
                    <tr>
                        <th scope="row"><?= $row['leave_id'] ?></th>
                        <td><?= $row['leave_title'] ?></td>
                        <td><?= $row['leave_description'] ?></td>
                        <td><?= $row['number_of_days'] ?></td>
                        <td>
                            <?php 
                                foreach ($employees as $employee) {
                                    if ($employee['employee_id'] == $row['employee_id']) {
                                        echo $employee['employee_name'];
                                        break;
                                    }
                                }
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editLeaveModal" data-id="<?= $row['leave_id'] ?>" data-title="<?= $row['leave_title'] ?>" data-description="<?= $row['leave_description'] ?>" data-days="<?= $row['number_of_days'] ?>" data-employee="<?= $row['employee_id'] ?>">Edit</button>
                            <form method="POST" style="display:inline-block">
                                <input type="hidden" name="leave_id" value="<?= $row['leave_id'] ?>">
                                <button type="submit" name="DeleteLeave" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No leaves found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Leave Modal -->
<div class="modal fade" id="leavesModal" tabindex="-1" aria-labelledby="leavesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leavesModalLabel">Add Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="leave_title" class="form-label">Leave Title</label>
                        <input name="leave_title" type="text" class="form-control" id="leave_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="leave_description" class="form-label">Leave Description</label>
                        <input name="leave_description" type="text" class="form-control" id="leave_description" required>
                    </div>
                    <div class="mb-3">
                        <label for="number_of_days" class="form-label">Number of Days</label>
                        <input name="number_of_days" type="number" class="form-control" id="number_of_days" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" class="form-control" id="employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['employee_id'] ?>"><?= $employee['employee_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input name="SaveLeave" type="submit" value="Save Leave" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Leave Modal -->
<div class="modal fade" id="editLeaveModal" tabindex="-1" aria-labelledby="editLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeaveModalLabel">Edit Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="leave_id" id="edit_leave_id">
                    <div class="mb-3">
                        <label for="edit_leave_title" class="form-label">Leave Title</label>
                        <input name="leave_title" type="text" class="form-control" id="edit_leave_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_leave_description" class="form-label">Leave Description</label>
                        <input name="leave_description" type="text" class="form-control" id="edit_leave_description" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_number_of_days" class="form-label">Number of Days</label>
                        <input name="number_of_days" type="number" class="form-control" id="edit_number_of_days" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_employee_id" class="form-label">Employee</label>
                        <select name="employee_id" class="form-control" id="edit_employee_id" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['employee_id'] ?>"><?= $employee['employee_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input name="UpdateLeave" type="submit" value="Update Leave" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var editModal = document.getElementById('editLeaveModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var leaveId = button.getAttribute('data-id');
        var leaveTitle = button.getAttribute('data-title');
        var leaveDescription = button.getAttribute('data-description');
        var leaveDays = button.getAttribute('data-days');
        var leaveEmployee = button.getAttribute('data-employee');

        var modalTitle = editModal.querySelector('.modal-title');
        var modalBodyInputId = editModal.querySelector('#edit_leave_id');
        var modalBodyInputTitle = editModal.querySelector('#edit_leave_title');
        var modalBodyInputDescription = editModal.querySelector('#edit_leave_description');
        var modalBodyInputDays = editModal.querySelector('#edit_number_of_days');
        var modalBodyInputEmployee = editModal.querySelector('#edit_employee_id');

        modalTitle.textContent = 'Edit Leave';
        modalBodyInputId.value = leaveId;
        modalBodyInputTitle.value = leaveTitle;
        modalBodyInputDescription.value = leaveDescription;
        modalBodyInputDays.value = leaveDays;
        modalBodyInputEmployee.value = leaveEmployee;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
