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

    <!-- Navigation Bar -->
    <nav class="navbar shadow-sm">

        <div class="container-fluid sticky-top">

            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>


    <div class="container form-container mt-3 mb-2 p-2 shadow-sm rounded-3" style="max-width: 500px;">
        <div class="row mt-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Announcements</h2>
                <hr class="mx-auto" style="width: 100px; height: 3px; background-color: #000;">
            </div>
        </div>
    </div>

    <div class="container form-container mt-5 mb-5 p-2 shadow-sm rounded-3" style="max-width: 1000px;">
        <div class="row mt-2">
            <div class="col-12 px-1">
                <label for="subject" class="form-label ms-5">Subject<span style="color: red;">*</span></label>
                <input type="subject" placeholder="Enter email subject here (e.g., event name, event reminder)..." class="form-control mx-auto" style="max-width: 900px;" id="subject" name="subject"
                    required>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 px-1">
                <label for="message" class="form-label ms-5">Message<span style="color: red;">*</span></label><br>
                <textarea placeholder="Enter message to be sent via email here (e.g., schedule updates, reminders)..." name="message" class="form-control mx-auto" style="max-width: 900px;" id="message" rows="6"
                    cols="120"></textarea>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary rounded-3 px-3 py-2">
                    <h6>Send Announcement</h6>
                </button>
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