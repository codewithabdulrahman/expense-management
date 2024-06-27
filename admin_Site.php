




<?php
session_start();

function checLogOut() {
    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php"); 
        exit(); 
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "expense_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recentItems = [];

$result = $conn->query("SELECT employee_id, employee_name, employee_email, employee_city, employee_position, employee_adress, employee_salary, employee_gender, employee_number, employee_probation_start, employee_probation_end FROM employees ORDER BY employee_id DESC LIMIT 1");
$recentItems['employee'] = $result->fetch_assoc();

$result = $conn->query("SELECT expenses_id, expense_title, expense_description, expense_amount FROM expenses ORDER BY expenses_id DESC LIMIT 1");
$recentItems['expense'] = $result->fetch_assoc();

$result = $conn->query("SELECT income_id, income_source, total_amount, date_received FROM income ORDER BY income_id DESC LIMIT 1");
$recentItems['income'] = $result->fetch_assoc();

$result = $conn->query("SELECT salary_id, employee_id, salary_amount, pay_date FROM salary ORDER BY salary_id DESC LIMIT 1");
$recentItems['salary'] = $result->fetch_assoc();

$result = $conn->query("SELECT probation_id, result, start_date, end_date FROM probation ORDER BY probation_id DESC LIMIT 1");
$recentItems['probation'] = $result->fetch_assoc();

$conn->close();

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Admin Site</title>
</head>
<body>
    <div class="container">
        <h1>Welcome EMS</h1>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_Site.php">Expense Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="employees.php">Employees</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="salaries.php">Salaries</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="leaves.php">Leaves</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="income.php">Income</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="probations.php">Probation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="expenses.php">Expenses</a>
        </li>
      </ul>
      <button type="button" class="btn btn-danger">
        <a class="dropdown-item text-white" href="login.php">Log out</a>
      </button>
    </div>
  </div>
</nav>

<!-- <div class="container mt-5">
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">Recent Employee</div>
        <div class="card-body" id="recent-employee">
       <?php
       
       ?>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">Recent Expense</div>
        <div class="card-body" id="recent-expense">
       
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">Recent Income</div>
        <div class="card-body" id="recent-income">
        </div>
      </div>
    </div>
    <div class="col-md-4 mt-4">
      <div class="card">
        <div class="card-header">Recent Salary</div>
        <div class="card-body" id="recent-salary">
        </div>
      </div>
    </div>
    <div class="col-md-4 mt-4">
      <div class="card">
        <div class="card-header">Recent Probation</div>
        <div class="card-body" id="recent-probation">
        </div>
      </div>
    </div>
  </div>
</div> -->

        <p>This is a protected page. Only logged-in users can access this content.</p>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
  fetchRecentItems();
});

function fetchRecentItems() {
  fetch('fetch_recent_items.php')
    .then(response => response.json())
    .then(data => {
      updateRecentItem('recent-employee', data.employee, [
        'employee_name',
        'employee_email',
        'employee_city',
        'employee_position',
        'employee_adress',
        'employee_salary',
        'employee_gender',
        'employee_number',
        'employee_probation_start',
        'employee_probation_end'
      ]);
      updateRecentItem('recent-expense', data.expense, [
        'expense_title',
        'expense_description',
        'expense_amount'
      ]);
      updateRecentItem('recent-income', data.income, [
        'income_source',
        'total_amount',
        'date_received'
      ]);
      updateRecentItem('recent-salary', data.salary, [
        'employee_id',
        'salary_amount',
        'pay_date'
      ]);
      updateRecentItem('recent-probation', data.probation, [
        'result',
        'start_date',
        'end_date'
      ]);
    });
}

function updateRecentItem(elementId, item, fields) {
  const element = document.getElementById(elementId);
  if (item) {
    let content = fields.map(field => `<p><strong>${field.replace('_', ' ')}:</strong> ${item[field]}</p>`).join('');
    element.innerHTML = content;
  } else {
    element.innerHTML = '<p>No recent item found.</p>';
  }
}
</script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
