<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iskonnovators Student Community</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar shadow-sm">

        <div class="container-fluid sticky-top">

            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250"
                        height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container form-container mt-5 mb-5 p-4 shadow-sm rounded-3 " style="max-width: 500px;">
        
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded icon-box">
            <img src="assets\img\ISC brand logo.png" alt="Application Received" width="150" height="auto">
        </div>

        <hr>

        <h3 class="fw-bold mb-3 d-flex align-items-center justify-content-center text-center">Access Your Account</h3>


        <hr>

        <form>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>

        <hr>
        <a href="apply.php" class="btn btn-primary w-100 text-center" style="background: #DEA500;">Register</a>

        <p class="text-center"><br>Not a member yet? Join now and start your journey with our organization today.<br></p>
        <hr>
    </div>

    



    <!-- footer -->
    <footer class="footer text-center text-lg-start mt-4  ">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>