<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Update</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff8e6;
        }

        .main-header {
            background: #FFBE00;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo {
            height: 45px;
        }

        .header-text {
            color: #fff;
            font-size: 14px;
            line-height: 1.2;
        }

        .header-right {
            color: #fff;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logout-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        .logout-btn:hover {
            opacity: 0.8;
        }

        .container {
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

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        form label {
            font-size: 14px;
            font-weight: bold;
        }

        form input,
        form select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .full-width {
            grid-column: span 2;
        }

        /* FOOTER */
        footer {
            background: #8b1d2c;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>

    <header class="main-header">
        <div class="header-left">
            <img src="header.png" alt="Logo" class="header-logo">

        </div>


    </header>

    <div class="container">
        <h2>User Profile</h2>
        <p>Admin Profile Form</p>

        <div class="profile-card">
            <button class="btn btn-back mb-3" onclick="history.back()">Go Back</button>

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

    <footer>
        © 2025 Iskonnovators Student Community PUPSTC. All Rights Reserved
    </footer>

    <script>
        document.getElementById("profileForm").addEventListener("submit", function (e) {
            e.preventDefault();
            alert("Profile successfully updated!");
        });
    </script>

</body>

</html>