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

    <div class="container form-container mt-3 mb-3 p-4 shadow-sm rounded-3 " style="max-width: 700px;">
        <div class="row mt-2 ">
            <div class="col-12 ">
                <h2 class="text-center mb-4">Membership Application</h2>
                <form action="submit_application.php" method="POST">
                    <div class="mb-3">

                        <div class="row mt-2">
                            <div class="col-6 px-1 px-1">
                                <label for="firstName" class="form-label ms-1">First Name<span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="col-6 px-1 px-1">
                                <label for="lastName" class="form-label ms-1">Last Name<span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6 px-1">
                                <label for="middleName" class="form-label ms-1">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" name="middleName">
                            </div>
                            <div class="col-6 px-1">
                                <label for="suffix" class="form-label ms-1">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col  px-1">
                                <div class="row mt-2">
                                    <div class="col pe-1 ">
                                        <label for="salutation" class="form-label ms-1">Salutation<span style="color: red;">*</span></label>
                                        <select class="form-select" id="salutation" name="salutation" required>
                                            <option selected disabled>Select</option>
                                            <option value="mr">Mr.</option>
                                            <option value="ms">Ms.</option>
                                            <option value="mrs">Mrs.</option>
                                        </select>
                                    </div>
                                    <div class="col ps-1">
                                        <label for="genderPronoun" class="form-label ms-1">Pronoun<span style="color: red;">*</span></label>
                                        <select class="form-select" id="genderPronoun" name="genderPronoun" required>
                                            <option selected disabled>Select</option>
                                            <option value="he">He/Him</option>
                                            <option value="she">She/Her</option>
                                            <option value="they">They/Them</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col  px-1">
                                <div class="row mt-2">
                                    <div class="col">
                                        <label for="birthDate" class="form-label ms-1">Birth Date<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col  px-1">
                                <label for="department" class="form-label ms-1">Department<span style="color: red;">*</span></label>
                                <select class="form-select" id="department" name="department" required>
                                    <option selected disabled>Select</option>
                                    <option value="cs">Computer Science</option>
                                    <option value="it">Information Technology</option>
                                    <option value="is">Information Systems</option>
                                </select>
                            </div>
                            <div class="col  px-1">
                                <label for="section" class="form-label ms-1">Section<span style="color: red;">*</span></label>
                                <select class="form-select" id="section" name="section" required>
                                    <option selected disabled>Select</option>
                                    <option value="a">Section A</option>
                                    <option value="b">Section B</option>
                                    <option value="c">Section C</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2 ">
                            <div class="col-6 px-1">
                                <label for="institution" class="form-label ms-1">Institution<span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="institution" name="institution" required>
                            </div>
                            <div class="col-6 px-1">
                                <label for="mobileNumber" class="form-label ms-1">Mobile Number<span style="color: red;">*</span></label>
                                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" placeholder="+63" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12  px-1">
                                <label for="email" class="form-label ms-1">Email<span style="color: red;">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12  px-1">
                                <label for="confirmEmail" class="form-label ms-1">Confirm Email<span style="color: red;">*</span></label>
                                <input type="email" class="form-control" id="confirmEmail" name="confirmEmail" required>
                            </div>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary rounded-3 px-5 py-2"><h4>Register</h4></button>
                        </div>
                    </div>

                    <script>
                        
                        const form = document.querySelector('form'); //
                        const submitBtn = document.querySelector('button[type="submit"]');
                        const requiredFields = form.querySelectorAll('[required]');
                        const emailInput = document.getElementById('email');
                        const confirmEmailInput = document.getElementById('confirmEmail');

                        function validateForm() {
                            const allFilled = Array.from(requiredFields).every(field => {
                                if (field.tagName === 'SELECT') {
                                    return field.value !== '' && field.value !== 'Select';
                                }
                                return field.value.trim() !== '';
                            });
                            const emailsMatch = emailInput.value === confirmEmailInput.value && emailInput.value !== '';
                            submitBtn.disabled = !(allFilled && emailsMatch);
                        }

                        requiredFields.forEach(field => {
                            field.addEventListener('input', validateForm);
                            field.addEventListener('change', validateForm);
                        });

                        submitBtn.disabled = true;
                    </script>
                </form>
            </div>
        </div>

    </div>



    <!-- footer -->
    <footer class="footer text-center text-lg-start mt-4 ">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>