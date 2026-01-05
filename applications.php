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
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>BSIT</td>
                            <td>2-1</td>
                            <td>PUP Sto. Tomas</td>
                            <td>juandelacruz@gmail.com</td>
                            <td>
                                <select class="form-select form-select-sm">
                                    <option selected>Pending</option>
                                    <option>For Interview</option>
                                    <option>Approved</option>
                                    <option>Denied</option>
                                </select>
                            </td>
                        </tr>
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
</body>

</html>


<!-- KAPAG SA DATABASE - SAMPLE LANG TO HAA 
<?php
$conn = new mysqli("localhost", "root", "", "your_database");

$result = $conn->query("SELECT * FROM membership_applications");
?>

<table class="table table-bordered align-middle text-center">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Department</th>
            <th>Section</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['fullname']; ?></td>
            <td><?= $row['department']; ?></td>
            <td><?= $row['section']; ?></td>
            <td><?= $row['email']; ?></td>
            <td>
                <select class="form-select form-select-sm status-select 
                    <?= strtolower(str_replace(' ', '-', $row['status'])) ?>"
                    data-id="<?= $row['id']; ?>">

                    <option <?= $row['status'] == "Pending" ? "selected" : "" ?>>Pending</option>
                    <option <?= $row['status'] == "For Interview" ? "selected" : "" ?>>For Interview</option>
                    <option <?= $row['status'] == "Approved" ? "selected" : "" ?>>Approved</option>
                    <option <?= $row['status'] == "Denied" ? "selected" : "" ?>>Denied</option>
                </select>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
-->