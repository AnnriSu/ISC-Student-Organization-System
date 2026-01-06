<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Update</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">

    <style>
        .profile-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        .profile-card {
            background: #ffefb5;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-back {
            background: #3b82f6;
            color: #fff;
        }

        .btn-update {
            background: #8b1d2c;
            color: #fff;
            margin-top: 20px;
        }        
    </style>
</head>


<body>

    <nav class="navbar shadow-sm ">

        <div class="container-fluid d-flex align-items-center">

            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container profile-container">
        <h2>User Profile</h2>
        <p>Member Profile Form</p>

        <div class="profile-card">
            <a href="homepage-member.php" class="btn btn-back mb-3">Go Back</a>

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
                            <label for="salutation" class="form-label ms-1">Salutation<span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="salutation" name="salutation" required>
                                <option selected disabled>Select</option>
                                <option value="mr">Mr.</option>
                                <option value="ms">Ms.</option>
                                <option value="mrs">Mrs.</option>
                            </select>
                        </div>
                        <div class="col ps-1">
                            <label for="genderPronoun" class="form-label ms-1">Pronoun<span
                                    style="color: red;">*</span></label>
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
                            <label for="birthDate" class="form-label ms-1">Birth Date<span
                                    style="color: red;">*</span></label>
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
                    <label for="institution" class="form-label ms-1">Institution<span
                            style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="institution" name="institution" required>
                </div>
                <div class="col-6 px-1">
                    <label for="mobileNumber" class="form-label ms-1">Mobile Number<span
                            style="color: red;">*</span></label>
                    <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" placeholder="+63"
                        required>
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
                    <label for="confirmEmail" class="form-label ms-1">Confirm Email<span
                            style="color: red;">*</span></label>
                    <input type="email" class="form-control" id="confirmEmail" name="confirmEmail" required>
                </div>
            </div>

            <div class="full-width">
                <button type="submit" class="btn btn-update">Update</button>
            </div>
            </form>
        </div>
    </div>

    <footer class="footer text-center text-lg-start mt-auto ">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>

    <script>
        document.getElementById("profileForm").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Profile successfully updated!");
        });
    </script>

</body>

</html>