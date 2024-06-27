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

// Handle the Save Probation form submission
if (isset($_POST['SaveProbation'])) {
  $employee_id = $_POST['employee_id'];
  $result = $_POST['result'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];

  $sql = "INSERT INTO probation (employee_id, result, start_date, end_date) VALUES ('$employee_id', '$result', '$start_date', '$end_date')";

  if ($conn->query($sql) === TRUE) {
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}


if (isset($_POST['UpdateProbation'])) {
  $id = $_POST['id'];
  $employee_id = $_POST['employee_id'];
  $result = $_POST['result'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];

  $sql = "UPDATE probation SET 
            employee_id='$employee_id',
            result='$result', 
            start_date='$start_date', 
            end_date='$end_date' 
            WHERE probation_id='$id'";

  if ($conn->query($sql) === TRUE) {
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
  } else {
    echo "Error updating probation: " . $conn->error;
  }
}

if (isset($_POST['DeleteProbation'])) {
  $id = $_POST['id'];

  $sql = "DELETE FROM probation WHERE probation_id='$id'";

  if ($conn->query($sql) === TRUE) {
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
  } else {
    echo "Error deleting probation: " . $conn->error;
  }
}

$sql = "SELECT p.probation_id, p.result, p.start_date, p.end_date, e.employee_name 
        FROM probation p 
        JOIN employees e ON p.employee_id = e.employee_id";
$probations = $conn->query($sql);

// Fetch all employees for dropdown
$sql = "SELECT employee_id, employee_name FROM employees";
$employees = $conn->query($sql);

$conn->close();
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Probation Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-5">
    <div class="text-end mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#probationModal">Add
        Probation</button>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Employee Name</th>

          <th scope="col">Result</th>
          <th scope="col">Start Date</th>
          <th scope="col">End Date</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($probations && $probations->num_rows > 0): ?>
          <?php while ($row = $probations->fetch_assoc()): ?>
            <tr>
              <th scope="row"><?= $row['probation_id'] ?></th>
              <td><?= $row['employee_name'] ?></td>

              <td><?= $row['result'] ?></td>
              <td><?= $row['start_date'] ?></td>
              <td><?= $row['end_date'] ?></td>
              <td>
                <button type="button" class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal"
                  data-bs-target="#editProbationModal" data-id="<?= $row['probation_id'] ?>"
                  data-result="<?= $row['result'] ?>" data-start="<?= $row['start_date'] ?>"
                  data-end="<?= $row['end_date'] ?>">Edit</button>
                <form method="POST" style="display:inline-block">
                  <input type="hidden" name="id" value="<?= $row['probation_id'] ?>">
                  <button type="submit" name="DeleteProbation" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No probation records found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <!-- Add Probation Modal -->
  <div class="modal fade" id="probationModal" tabindex="-1" aria-labelledby="probationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="probationModalLabel">Add Probation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="mb-3">
              <label for="employee_id" class="form-label">Select Employee</label>
              <select name="employee_id" class="form-select" required>
                <option value="">Select Employee</option>
                <?php while ($row = $employees->fetch_assoc()): ?>
                  <option value="<?= $row['employee_id'] ?>"><?= $row['employee_name'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="result" class="form-label">Probation Result</label>
              <input name="result" type="text" class="form-control" id="result" required>
            </div>
            <div class="mb-3">
              <label for="start_date" class="form-label">Start Date</label>
              <input name="start_date" type="date" class="form-control" id="start_date" required>
            </div>
            <div class="mb-3">
              <label for="end_date" class="form-label">End Date</label>
              <input name="end_date" type="date" class="form-control" id="end_date" required>
            </div>
            <input name="SaveProbation" value="Save Probation" type="submit" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Probation Modal -->
  <div class="modal fade" id="editProbationModal" tabindex="-1" aria-labelledby="editProbationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProbationModalLabel">Edit Probation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="mb-3">
              <label for="employee_id" class="form-label">Select Employee</label>
              <select name="employee_id" class="form-select" required>
                <option value="">Select Employee</option>
                <?php while ($row = $employees->fetch_assoc()): ?>
                  <option value="<?= $row['employee_id'] ?>"><?= $row['employee_name'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
              <label for="edit_result" class="form-label">Probation Result</label>
              <input name="result" type="text" class="form-control" id="edit_result" required>
            </div>
            <div class="mb-3">
              <label for="edit_start_date" class="form-label">Start Date</label>
              <input name="start_date" type="date" class="form-control" id="edit_start_date" required>
            </div>
            <div class="mb-3">
              <label for="edit_end_date" class="form-label">End Date</label>
              <input name="end_date" type="date" class="form-control" id="edit_end_date" required>
            </div>
            <input name="UpdateProbation" value="Update Probation" type="submit" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    var editModal = document.getElementById('editProbationModal');
    editModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var result = button.getAttribute('data-result');
      var start = button.getAttribute('data-start');
      var end = button.getAttribute('data-end');

      var modalTitle = editModal.querySelector('.modal-title');
      var modalBodyInputId = editModal.querySelector('#edit_id');
      var modalBodyInputResult = editModal.querySelector('#edit_result');
      var modalBodyInputStart = editModal.querySelector('#edit_start_date');
      var modalBodyInputEnd = editModal.querySelector('#edit_end_date');

      modalTitle.textContent = 'Edit Probation';
      modalBodyInputId.value = id;
      modalBodyInputResult.value = result;
      modalBodyInputStart.value = start;
      modalBodyInputEnd.value = end;
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>