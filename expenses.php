<?php


// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$expenses = [];

function fetchExpenses($conn) {
    $expenses = [];
    $sql = "SELECT expenses_id, expense_title, expense_description, expense_amount FROM expenses";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }
    }
    return $expenses;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['SaveExpense'])) {
    $expense_title = $_POST['expense_title'];
    $expense_description = $_POST['expense_description'];
    $expense_amount = $_POST['expense_amount'];

    $sql = "INSERT INTO expenses (expense_title, expense_description, expense_amount) 
            VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $expense_title, $expense_description, $expense_amount);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['UpdateExpense'])) {
    $expenses_id = $_POST['expenses_id'];
    $expense_title = $_POST['expense_title'];
    $expense_description = $_POST['expense_description'];
    $expense_amount = $_POST['expense_amount'];

    $sql = "UPDATE expenses SET 
            expense_title=?, 
            expense_description=?, 
            expense_amount=? 
            WHERE expenses_id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $expense_title, $expense_description, $expense_amount, $expenses_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error updating expense: " . $conn->error;
    }

    $stmt->close();
}

// Handle Delete Expense form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['DeleteExpense'])) {
    $expenses_id = $_POST['expenses_id'];

    $sql = "DELETE FROM expenses WHERE expenses_id=?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $expenses_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error deleting expense: " . $conn->error;
    }

    $stmt->close();
}

$expenses = fetchExpenses($conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal">Add Expense</button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expenses)) : ?>
                    <?php foreach ($expenses as $row) : ?>
                        <tr>
                            <th scope="row"><?= $row['expenses_id'] ?></th>
                            <td><?= $row['expense_title'] ?></td>
                            <td><?= $row['expense_description'] ?></td>
                            <td><?= $row['expense_amount'] ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editExpenseModal" data-id="<?= $row['expenses_id'] ?>" data-title="<?= $row['expense_title'] ?>" data-description="<?= $row['expense_description'] ?>" data-amount="<?= $row['expense_amount'] ?>">Edit</button>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="expenses_id" value="<?= $row['expenses_id'] ?>">
                                    <button type="submit" name="DeleteExpense" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">No expenses found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="expense_title" class="form-label">Expense Title</label>
                            <input name="expense_title" type="text" class="form-control" id="expense_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="expense_description" class="form-label">Expense Description</label>
                            <input name="expense_description" type="text" class="form-control" id="expense_description" required>
                        </div>
                        <div class="mb-3">
                            <label for="expense_amount" class="form-label">Expense Amount</label>
                            <input name="expense_amount" type="number" class="form-control" id="expense_amount" required>
                        </div>
                        <input name="SaveExpense" value="Save Expense" type="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Expense Modal -->
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="expenses_id" id="edit_expenses_id">
                        <div class="mb-3">
                            <label for="edit_expense_title" class="form-label">Expense Title</label>
                            <input name="expense_title" type="text" class="form-control" id="edit_expense_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_expense_description" class="form-label">Expense Description</label>
                            <input name="expense_description" type="text" class="form-control" id="edit_expense_description" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_expense_amount" class="form-label">Expense Amount</label>
                            <input name="expense_amount" type="number" class="form-control" id="edit_expense_amount" required>
                        </div>
                        <input name="UpdateExpense" value="Update Expense" type="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    var editModal = document.getElementById('editExpenseModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var description = button.getAttribute('data-description');
        var amount = button.getAttribute('data-amount');

        var modalBodyInputId = editModal.querySelector('#edit_expenses_id');
        var modalBodyInputTitle = editModal.querySelector('#edit_expense_title');
        var modalBodyInputDescription = editModal.querySelector('#edit_expense_description');
        var modalBodyInputAmount = editModal.querySelector('#edit_expense_amount');

        modalBodyInputId.value = id;
        modalBodyInputTitle.value = title;
        modalBodyInputDescription.value = description;
        modalBodyInputAmount.value = amount;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
