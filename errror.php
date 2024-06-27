// // Check if a new picture is uploaded
    // if ($_FILES['employee_picture']['name'] != '') {
    //     // Move uploaded file to 'uploads/' directory
    //     $picture = $_FILES['employee_picture']['name'];
    //     $target = "uploads/" . basename($picture);
    //     move_uploaded_file($_FILES['employee_picture']['tmp_name'], $target);

    //     // Get the current employee's picture to delete it later
    //     $sql_select_current_picture = "SELECT EMPLOYEE_PICTURE FROM employees WHERE id = $id";
    //     $result_select = $conn->query($sql_select_current_picture);
    //     if ($result_select->num_rows > 0) {
    //         $row = $result_select->fetch_assoc();
    //         $current_picture = $row['EMPLOYEE_PICTURE'];

    //         // Delete the current picture file from 'uploads/' directory
    //         if (file_exists("uploads/" . $current_picture)) {
    //             unlink("uploads/" . $current_picture);
    //         }
    //     }

    //     // Update query with new picture path
    //     $sql = "UPDATE employees SET EMPLOYEE_NAME='$employee_name', EMPLOYEE_EMAIL='$employee_email', EMPLOYEE_PICTURE='$picture', EMPLOYEE_CITY='$employee_city', EMPLOYEE_POSITION='$employee_position', EMPLOYEE_GENDER='$employee_gender', EMPLOYEE_NUMBER='$employee_number', EMPLOYEE_ADRESS='$employee_adress', EMPLOYEE_SALARY='$employee_salary', EMPLOYEE_PROBATION_START='$employee_probation_start', EMPLOYEE_PROBATION_END='$employee_probation_end' WHERE id=$id";
    // } else {