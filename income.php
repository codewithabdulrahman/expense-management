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

$whereClause = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $whereClause = " WHERE 
        income_source LIKE '%$searchTerm%' OR 
        total_amount LIKE '%$searchTerm%' OR 
        date_received LIKE '%$searchTerm%'";
}

$sql = "SELECT income_id, income_source, total_amount, date_received FROM income" . $whereClause;

$incomes = $conn->query($sql);

if (isset($_POST['SaveIncome'])) {
    $income_source = $_POST['income_source'];
    $total_amount = $_POST['total_amount'];
    $date_received = $_POST['date_received'];

    $sql = "INSERT INTO income (income_source, total_amount, date_received) VALUES ('$income_source', '$total_amount', '$date_received')";

    if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['UpdateIncome'])) {
    $id = $_POST['id'];
    $income_source = $_POST['income_source'];
    $total_amount = $_POST['total_amount'];
    $date_received = $_POST['date_received'];

    $sql = "UPDATE income SET 
            income_source='$income_source', 
            total_amount='$total_amount', 
            date_received='$date_received' 
            WHERE income_id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error updating income: " . $conn->error;
    }
}

// Handle the Delete Income form submission
if (isset($_POST['DeleteIncome'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM income WHERE income_id='$id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to prevent form resubmission
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error deleting income: " . $conn->error;
    }
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Income Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#incomeModal">Add Income</button>
    </div>

    <div class="text-end mb-3">
        <form method="POST" class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" name="searchTerm" placeholder="Search by Source, Amount, Date">
                <button type="submit" name="search" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Source</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Date Received</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($incomes && $incomes->num_rows > 0): ?>
                <?php while ($row = $incomes->fetch_assoc()): ?>
                    <tr>
                        <th scope="row"><?= $row['income_id'] ?></th>
                        <td><?= $row['income_source'] ?></td>
                        <td><?= $row['total_amount'] ?></td>
                        <td><?= $row['date_received'] ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editIncomeModal" data-id="<?= $row['income_id'] ?>" data-source="<?= $row['income_source'] ?>" data-total="<?= $row['total_amount'] ?>" data-date="<?= $row['date_received'] ?>">Edit</button>
                            <form method="POST" style="display:inline-block">
                                <input type="hidden" name="id" value="<?= $row['income_id'] ?>">
                                <button type="submit" name="DeleteIncome" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No income records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Income Modal -->
<div class="modal fade" id="incomeModal" tabindex="-1" aria-labelledby="incomeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incomeModalLabel">Add Income</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="income_source" class="form-label">Source</label>
                        <input name="income_source" type="text" class="form-control" id="income_source" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input name="total_amount" type="number" class="form-control" id="total_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_received" class="form-label">Date Received</label>
                        <input name="date_received" type="date" class="form-control" id="date_received" required>
                    </div>
                    <input name="SaveIncome" value="Save Income" type="submit" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Income Modal -->
<div class="modal fade" id="editIncomeModal" tabindex="-1" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIncomeModalLabel">Edit Income</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_income_source" class="form-label">Source</label>
                        <input name="income_source" type="text" class="form-control" id="edit_income_source" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_total_amount" class="form-label">Total Amount</label>
                        <input name="total_amount" type="number" class="form-control" id="edit_total_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_date_received" class="form-label">Date Received</label>
                        <input name="date_received" type="date" class="form-control" id="edit_date_received" required>
                    </div>
                    <input name="UpdateIncome" value="Update Income" type="submit" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var editModal = document.getElementById('editIncomeModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var source = button.getAttribute('data-source');
        var total = button.getAttribute('data-total');
        var date = button.getAttribute('data-date');

        var modalTitle = editModal.querySelector('.modal-title');
        var modalBodyInputId = editModal.querySelector('#edit_id');
        var modalBodyInputSource = editModal.querySelector('#edit_income_source');
        var modalBodyInputTotal = editModal.querySelector('#edit_total_amount');
        var modalBodyInputDate = editModal.querySelector('#edit_date_received');

        modalTitle.textContent = 'Edit Income';
        modalBodyInputId.value = id;
        modalBodyInputSource.value = source;
        modalBodyInputTotal.value = total;
        modalBodyInputDate.value = date;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
