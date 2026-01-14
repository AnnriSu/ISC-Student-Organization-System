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

        <div class="container-fluid d-flex align-items-center flex-wrap ">
            <div class="d-flex gap-4 mx-auto mx-sm-0 me-xs-auto align-content-lg-center">
                <a class="navbar-brand d-flex ms-2 ms-lg-4 justify-content-center" href="adminhomepage.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" height="auto" class="mt-1 mb-1"
                        style="max-width: 250px; width: auto;">
                </a>
            </div>

            <a class="navbar-brand d-flex" href="adminhomepage.php">
                <img src="assets\img\back.png" alt="Back" width="30" height="auto" class="mt-1 mb-1">
            </a>
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

                                        <?php if (strtolower($row['apStatusDesc']) === 'denied'): ?>
                                            <button class="btn btn-danger btn-sm send-denial" data-id="<?= $row['apID'] ?>">
                                                Send Denial Email
                                            </button>
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
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function () {

                    const apID = this.dataset.id;
                    const newStatus = this.value;

                    // Disable to prevent double clicks
                    this.disabled = true;

                    fetch('update_application_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            apID: apID,
                            apStatusID: newStatus
                        })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message || 'Failed to update status');
                                this.disabled = false;
                                return;
                            }

                            // ✅ AUTO REFRESH FIX
                            location.reload();
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Server error');
                            this.disabled = false;
                        });

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
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    apID: apID
                })
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
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    apID: apID
                })
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

    <?php include("shared/footer.php"); ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Attach existing denial buttons
            document.querySelectorAll('.send-denial').forEach(btn => {
                btn.addEventListener('click', sendDenialHandler);
            });

            // STATUS CHANGE HANDLER (ADD THIS INSIDE YOUR EXISTING STATUS CHANGE LOGIC)
            document.querySelectorAll('.status-dropdown').forEach(select => {
                select.addEventListener('change', function () {
                    const apID = this.dataset.id;

                    fetch('update_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            apID: apID,
                            statusID: this.value
                        })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message);
                                return;
                            }

                            if (data.statusDesc.toLowerCase() === 'denied') {
                                const cell = this.closest('tr').querySelector('.action-cell');

                                if (!cell.querySelector('.send-denial')) {
                                    const btn = document.createElement('button');
                                    btn.className = 'btn btn-danger btn-sm send-denial';
                                    btn.textContent = 'Send Denial Email';
                                    btn.dataset.id = apID;
                                    btn.addEventListener('click', sendDenialHandler);
                                    cell.appendChild(btn);
                                }
                            }
                        });
                });
            });

        });

        // SEND DENIAL HANDLER
        function sendDenialHandler() {
            if (!confirm('Send denial email and permanently delete this application?')) {
                return;
            }

            const btn = this;
            const apID = btn.dataset.id;
            btn.disabled = true;

            fetch('send_denial.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ apID })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Denial email sent. Application deleted.');
                        btn.closest('tr').remove();
                    } else {
                        alert(data.message);
                        btn.disabled = false;
                    }
                })
                .catch(() => {
                    alert('Server error');
                    btn.disabled = false;
                });
        }
    </script>


</body>

</html>