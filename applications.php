<?php
include("connect.php");

// Fetch all applications with their status
$query = "SELECT a.apID, a.apFname, a.apLname, a.apMname, a.apSuffix, 
                 a.apDepartment, a.apSection, a.apInstitution, a.apEmail, 
                 s.apStatusID, s.apStatusDesc
          FROM tbl_applications a
          INNER JOIN tbl_applicationstatus s ON a.apStatusID = s.apStatusID
          ORDER BY a.apID DESC";
$result = $conn->query($query);

// Function to format department code to display name
function formatDepartment($deptCode) {
    $departments = [
        'cs' => 'CS',
        'it' => 'BSIT',
        'is' => 'IS',
        'bsit' => 'BSIT'
    ];
    return isset($departments[strtolower($deptCode)]) ? $departments[strtolower($deptCode)] : strtoupper($deptCode);
}

// Function to build full name
function buildFullName($fname, $lname, $mname = '', $suffix = '') {
    $name = trim($fname);
    if (!empty($mname)) {
        $name .= " " . trim($mname);
    }
    $name .= " " . trim($lname);
    if (!empty($suffix)) {
        $name .= " " . trim($suffix);
    }
    return $name;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iskonnovators Student Community</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar shadow-sm">

        <div class="container-fluid sticky-top">

            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="bg-white rounded-4 p-4 shadow" style="border:3px solid #2f6fed; min-height:450px;">

            <h5 class="fw-bold mb-3">Membership Application</h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-white">
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Institution</th>
                            <th>Email</th>
                            <th>Application Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): 
                                $fullName = buildFullName($row['apFname'], $row['apLname'], $row['apMname'], $row['apSuffix']);
                                $department = formatDepartment($row['apDepartment']);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($fullName) ?></td>
                                <td><?= htmlspecialchars($department) ?></td>
                                <td><?= htmlspecialchars($row['apSection']) ?></td>
                                <td><?= htmlspecialchars($row['apInstitution']) ?></td>
                                <td><?= htmlspecialchars($row['apEmail']) ?></td>
                                <td>
                                    <select class="form-select form-select-sm status-select" 
                                            data-id="<?= $row['apID'] ?>" 
                                            data-status-id="<?= $row['apStatusID'] ?>">
                                        <option value="1" <?= $row['apStatusID'] == 1 ? 'selected' : '' ?>>Pending</option>
                                        <option value="2" <?= $row['apStatusID'] == 2 ? 'selected' : '' ?>>For Interview</option>
                                        <option value="3" <?= $row['apStatusID'] == 3 ? 'selected' : '' ?>>Approved</option>
                                        <option value="4" <?= $row['apStatusID'] == 4 ? 'selected' : '' ?>>Denied</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No applications found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- footer -->
    <footer class="footer text-center text-lg-start mt-4 fixed-bottom ">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    
    <script>
        // Handle status change updates
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const applicationId = this.getAttribute('data-id');
                const newStatusId = this.value;
                
                // You can add AJAX call here to update the status in the database
                // For now, we'll just log it
                console.log('Application ID:', applicationId, 'New Status ID:', newStatusId);
                
                // Example AJAX call (uncomment and configure as needed):
                /*
                fetch('update_application_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        apID: applicationId,
                        apStatusID: newStatusId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated successfully');
                    } else {
                        alert('Error updating status');
                        this.value = this.getAttribute('data-status-id'); // Revert
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.value = this.getAttribute('data-status-id'); // Revert
                });
                */
            });
        });
    </script>
</body>

</html>