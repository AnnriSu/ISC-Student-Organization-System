<?php
include("connect.php");

// Fetch status options
$statusQuery = "SELECT apStatusID, apStatusDesc FROM tbl_applicationstatus ORDER BY apStatusID";
$statusResult = $conn->query($statusQuery);
$statusOptions = [];
if ($statusResult && $statusResult->num_rows > 0) {
    while ($statusRow = $statusResult->fetch_assoc()) {
        $statusOptions[] = $statusRow;
    }
}

// Fetch applications
$query = "SELECT a.apID, a.apFname, a.apLname, a.apMname, a.apSuffix, 
                 a.apDepartment, a.apSection, a.apInstitution, a.apEmail, 
                 s.apStatusID, s.apStatusDesc, a.interviewSent
          FROM tbl_applications a
          INNER JOIN tbl_applicationstatus s ON a.apStatusID = s.apStatusID
          ORDER BY a.apID DESC";
$result = $conn->query($query);

// Functions
function formatDepartment($deptCode)
{
    $departments = ['cs' => 'CS', 'it' => 'BSIT', 'is' => 'IS', 'bsit' => 'BSIT'];
    return isset($departments[strtolower($deptCode)]) ? $departments[strtolower($deptCode)] : strtoupper($deptCode);
}

function buildFullName($fname, $lname, $mname = '', $suffix = '')
{
    $name = trim($fname);
    if (!empty($mname))
        $name .= " " . trim($mname);
    $name .= " " . trim($lname);
    if (!empty($suffix))
        $name .= " " . trim($suffix);
    return $name;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membership Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/isc_brand_bold.png" alt="Logo" width="250">
            </a>

            <div class="pe-sm-3 d-flex flex-column flex-sm-row gap-2 gap-lg-4 align-items-center justify-content-center justify-content-md-end ms-md-auto">
                <a class="navbar-brand d-flex" href="adminhomepage.php">
                    <img src="assets\img\back.png" alt="Back" width="30" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="bg-white rounded-4 p-4 shadow">
            <h5 class="fw-bold mb-3">Membership Applications</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Institution</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
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
                                        <select class="form-select form-select-sm status-select" data-id="<?= $row['apID'] ?>"
                                            data-status-id="<?= $row['apStatusID'] ?>"
                                            <?= strtolower($row['apStatusDesc']) === 'approved' ? 'disabled' : '' ?>>
                                            <?php foreach ($statusOptions as $status): ?>
                                                <option value="<?= $status['apStatusID'] ?>" <?=

                                                          // Select Pending for applications already updated
                                                      ($row['apStatusID'] == $status['apStatusID'] ? 'selected' : '')

                                                      ?>
                                                    <?= (strtolower($status['apStatusDesc']) === 'for interview' && $row['interviewSent'] == 1) ? 'disabled' : '' ?>>
                                                    <?= htmlspecialchars($status['apStatusDesc']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="action-cell">
                                        <?php if (strtolower($row['apStatusDesc']) === 'approved'): ?>
                                            <button class="btn btn-success btn-sm send-approval" data-id="<?= $row['apID'] ?>">Send
                                                Membership Approval</button>
                                        <?php endif; ?>

                                        <?php if (strtolower($row['apStatusDesc']) === 'for interview' && $row['interviewSent'] == 0): ?>
                                            <button class="btn btn-primary btn-sm send-interview" data-id="<?= $row['apID'] ?>">Send
                                                Interview Email</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No applications found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Status change handler
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function () {
                const apID = this.dataset.id;
                const oldStatus = this.dataset.statusId;
                const newStatus = this.value;
                if (newStatus === oldStatus) return;

                this.disabled = true;

                fetch('update_application_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ apID: apID, apStatusID: newStatus })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.dataset.statusId = newStatus;

                            if (data.statusDesc.toLowerCase() === 'approved') {
                                this.disabled = true;
                                const cell = this.closest('tr').querySelector('.action-cell');
                                if (!cell.querySelector('.send-approval')) {
                                    const btn = document.createElement('button');
                                    btn.classList.add('btn', 'btn-success', 'btn-sm', 'send-approval');
                                    btn.textContent = 'Send Membership Approval';
                                    btn.dataset.id = apID;
                                    cell.appendChild(btn);
                                    btn.addEventListener('click', sendApprovalHandler);
                                }
                            }
                        } else {
                            alert(data.message || 'Error updating status');
                            this.value = oldStatus;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error updating status');
                        this.value = oldStatus;
                    })
                    .finally(() => {
                        if (this.value.toLowerCase() !== 'approved') this.disabled = false;
                    });
            });
        });

        // Send approval handler
        function sendApprovalHandler() {
            const apID = this.dataset.id;
            const btn = this;
            btn.disabled = true;

            fetch('send_approval.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ apID: apID })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Membership approved! Email has been sent.');
                        const row = btn.closest('tr');
                        row.remove();
                        const tbody = document.querySelector('tbody');
                        if (tbody && tbody.querySelectorAll('tr').length === 0) {
                            tbody.innerHTML = '<tr><td colspan="7">No applications found.</td></tr>';
                        }
                    } else {
                        alert('Error: ' + data.message);
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error sending email');
                    btn.disabled = false;
                });
        }

        // Send interview email handler
        function sendInterviewHandler() {
            const apID = this.dataset.id;
            const btn = this;
            btn.disabled = true;

            fetch('send_interview.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ apID: apID })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Interview email has been sent successfully!');
                        btn.textContent = 'Email Sent';

                        // Update dropdown: select Pending
                        const select = btn.closest('tr').querySelector('.status-select');
                        select.value = data.newStatusID;
                        select.dataset.statusId = data.newStatusID;

                        // Disable "For Interview"
                        select.querySelectorAll('option').forEach(option => {
                            if (option.text.toLowerCase() === 'for interview') {
                                option.disabled = true;
                            }
                        });
                    } else {
                        alert('Error: ' + data.message);
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error sending interview email');
                    btn.disabled = false;
                });
        }

        // Attach interview buttons
        document.querySelectorAll('.send-interview').forEach(btn => {
            btn.addEventListener('click', sendInterviewHandler);
        });

        // Attach approval buttons
        document.querySelectorAll('.send-approval').forEach(btn => {
            btn.addEventListener('click', sendApprovalHandler);
        });
    </script>

</body>

</html>