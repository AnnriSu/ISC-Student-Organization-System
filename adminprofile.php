<?php
session_start();
include("connect.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    // User is not authenticated or not an admin, redirect to login
    header("Location: login.php");
    exit();
}

// Get admin's current email and ID from session
$currentEmail = $_SESSION['email'] ?? null;
$adminID = null;

// Get admin's ID first
if ($currentEmail) {
    $stmt = $conn->prepare("SELECT adID FROM tbl_admin WHERE adEmail = ?");
    $stmt->bind_param("s", $currentEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $adminID = $row['adID'];
    }
    $stmt->close();
}

// Handle form submission
$updateMessage = "";
$updateType = ""; // 'success' or 'danger'

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['firstName'])) {
    // Get form data
    $fname = trim($_POST['firstName']);
    $lname = trim($_POST['lastName']);
    $mname = trim($_POST['middleName'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $salutation = $_POST['salutation'] ?? '';
    $pronoun = $_POST['genderPronoun'] ?? '';
    $birthDate = $_POST['birthDate'] ?? '';
    $department = $_POST['department'] ?? '';
    $section = $_POST['section'] ?? '';
    $institution = trim($_POST['institution'] ?? '');
    $mobile = trim($_POST['mobileNumber'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $confirmEmail = trim($_POST['confirmEmail'] ?? '');

    // Validate required fields
    if (
        empty($fname) || empty($lname) || empty($salutation) || empty($pronoun) ||
        empty($birthDate) || empty($department) || empty($section) ||
        empty($institution) || empty($mobile) || empty($email)
    ) {
        $updateMessage = "Please fill in all required fields.";
        $updateType = "danger";
    } elseif ($email !== $confirmEmail) {
        $updateMessage = "Email addresses do not match.";
        $updateType = "danger";
    } elseif ($adminID === null) {
        $updateMessage = "Error: Admin ID not found. Please try logging in again.";
        $updateType = "danger";
    } else {
        // Update admin information in database
        $updateStmt = $conn->prepare("UPDATE tbl_admin SET adFname = ?, adLname = ?, adMname = ?, adSuffix = ?, adSalutations = ?, adPronouns = ?, adBirthDate = ?, adDepartment = ?, adSection = ?, adInstitution = ?, adMobileNo = ?, adEmail = ? WHERE adID = ?");
        $updateStmt->bind_param("ssssssssssssi", $fname, $lname, $mname, $suffix, $salutation, $pronoun, $birthDate, $department, $section, $institution, $mobile, $email, $adminID);

        if ($updateStmt->execute()) {
            $updateMessage = "Profile updated successfully!";
            $updateType = "success";

            // Update session email if email was changed
            if ($email !== $currentEmail) {
                $_SESSION['email'] = $email;
            }

            // Refresh admin data
            $currentEmail = $email;
        } else {
            $updateMessage = "Error updating profile: " . $conn->error . ". Please try again.";
            $updateType = "danger";
        }
        $updateStmt->close();
    }
}

// Get admin's data from database to pre-fill the form
$adminData = null;
if ($currentEmail) {
    $stmt = $conn->prepare("SELECT adFname, adLname, adMname, adSuffix, adSalutations, adPronouns, adBirthDate, adDepartment, adSection, adInstitution, adMobileNo, adEmail FROM tbl_admin WHERE adEmail = ?");
    $stmt->bind_param("s", $currentEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $adminData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
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
            display: flex;
            align-items: center;
        }

        .btn-update {
            background: #8b1d2c;
            color: #fff;
            display: flex;
            align-items: center;
        }

        .full-width {
            margin-top: 20px;
            align-items: center;
        }
    </style>
</head>


<body style="padding-bottom: 100px;">

    <!-- Navigation Bar -->
    <nav class="navbar shadow-sm">

        <div class="container-fluid sticky-top">

            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="adminhomepage.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250"
                        height="auto" class="mt-1 mb-1">
                </a>
            </div>

            <div class="pe-sm-3 d-flex flex-column flex-sm-row gap-2 gap-lg-4 align-items-center justify-content-center justify-content-md-end ms-md-auto">
                <a class="navbar-brand d-flex" href="adminhomepage.php">
                    <img src="assets\img\back.png" alt="Back" width="30" height="auto" class="mt-1 mb-1">
                </a>
            </div>
            
        </div>
    </nav>

    <div class="container profile-container">
        <h2>User Profile</h2>
        <p>Admin Profile Form</p>

        <?php if (!empty($updateMessage)): ?>
            <div class="alert alert-<?= $updateType ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($updateMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="profile-card">

            <form method="POST" id="profileForm">
                <div class="row mt-2">
                    <div class="col-6 px-1 px-1">
                        <label for="firstName" class="form-label ms-1">First Name<span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="firstName" name="firstName"
                            value="<?= $adminData ? htmlspecialchars($adminData['adFname']) : '' ?>" required>
                    </div>
                    <div class="col-6 px-1 px-1">
                        <label for="lastName" class="form-label ms-1">Last Name<span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="lastName" name="lastName"
                            value="<?= $adminData ? htmlspecialchars($adminData['adLname']) : '' ?>" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6 px-1">
                        <label for="middleName" class="form-label ms-1">Middle Name</label>
                        <input type="text" class="form-control" id="middleName" name="middleName"
                            value="<?= $adminData ? htmlspecialchars($adminData['adMname'] ?? '') : '' ?>">
                    </div>
                    <div class="col-6 px-1">
                        <label for="suffix" class="form-label ms-1">Suffix</label>
                        <input type="text" class="form-control" id="suffix" name="suffix"
                            value="<?= $adminData ? htmlspecialchars($adminData['adSuffix'] ?? '') : '' ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col  px-1">
                        <div class="row mt-2">
                            <div class="col pe-1 ">
                                <label for="salutation" class="form-label ms-1">Salutation<span
                                        style="color: red;">*</span></label>
                                <select class="form-select" id="salutation" name="salutation" required>
                                    <option value="" disabled>Select</option>
                                    <option value="mr" <?= $adminData && $adminData['adSalutations'] == 'mr' ? 'selected' : '' ?>>Mr.</option>
                                    <option value="ms" <?= $adminData && $adminData['adSalutations'] == 'ms' ? 'selected' : '' ?>>Ms.</option>
                                    <option value="mrs" <?= $adminData && $adminData['adSalutations'] == 'mrs' ? 'selected' : '' ?>>Mrs.</option>
                                </select>
                            </div>
                            <div class="col ps-1">
                                <label for="genderPronoun" class="form-label ms-1">Pronoun<span
                                        style="color: red;">*</span></label>
                                <select class="form-select" id="genderPronoun" name="genderPronoun" required>
                                    <option value="" disabled>Select</option>
                                    <option value="he" <?= $adminData && $adminData['adPronouns'] == 'he' ? 'selected' : '' ?>>He/Him</option>
                                    <option value="she" <?= $adminData && $adminData['adPronouns'] == 'she' ? 'selected' : '' ?>>She/Her</option>
                                    <option value="they" <?= $adminData && $adminData['adPronouns'] == 'they' ? 'selected' : '' ?>>They/Them</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col  px-1">
                        <div class="row mt-2">
                            <div class="col">
                                <label for="birthDate" class="form-label ms-1">Birth Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="birthDate" name="birthDate"
                                    value="<?= $adminData ? htmlspecialchars($adminData['adBirthDate']) : '' ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col  px-1">
                        <label for="department" class="form-label ms-1">Department<span
                                style="color: red;">*</span></label>
                        <select class="form-select" id="department" name="department" required>
                            <option value="" disabled>Select</option>
                            <option value="cs" <?= $adminData && $adminData['adDepartment'] == 'cs' ? 'selected' : '' ?>>
                                Computer Science</option>
                            <option value="it" <?= $adminData && $adminData['adDepartment'] == 'it' ? 'selected' : '' ?>>
                                Information Technology</option>
                            <option value="is" <?= $adminData && $adminData['adDepartment'] == 'is' ? 'selected' : '' ?>>
                                Information Systems</option>
                            <option value="bsit" <?= $adminData && $adminData['adDepartment'] == 'bsit' ? 'selected' : '' ?>>BSIT</option>
                        </select>
                    </div>
                    <div class="col  px-1">
                        <label for="section" class="form-label ms-1">Section<span style="color: red;">*</span></label>
                        <select class="form-select" id="section" name="section" required>
                            <option value="" disabled>Select</option>
                            <option value="a" <?= $adminData && $adminData['adSection'] == 'a' ? 'selected' : '' ?>>Section
                                A</option>
                            <option value="b" <?= $adminData && $adminData['adSection'] == 'b' ? 'selected' : '' ?>>Section
                                B</option>
                            <option value="c" <?= $adminData && $adminData['adSection'] == 'c' ? 'selected' : '' ?>>Section
                                C</option>
                            <option value="3-1" <?= $adminData && $adminData['adSection'] == '3-1' ? 'selected' : '' ?>>3-1
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2 ">
                    <div class="col-6 px-1">
                        <label for="institution" class="form-label ms-1">Institution<span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="institution" name="institution"
                            value="<?= $adminData ? htmlspecialchars($adminData['adInstitution']) : '' ?>" required>
                    </div>
                    <div class="col-6 px-1">
                        <label for="mobileNumber" class="form-label ms-1">Mobile Number<span
                                style="color: red;">*</span></label>
                        <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber"
                            value="<?= $adminData ? htmlspecialchars($adminData['adMobileNo']) : '' ?>"
                            placeholder="+63" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12  px-1">
                        <label for="email" class="form-label ms-1">Email<span style="color: red;">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= $adminData ? htmlspecialchars($adminData['adEmail']) : '' ?>" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12  px-1">
                        <label for="confirmEmail" class="form-label ms-1">Confirm Email<span
                                style="color: red;">*</span></label>
                        <input type="email" class="form-control" id="confirmEmail" name="confirmEmail"
                            value="<?= $adminData ? htmlspecialchars($adminData['adEmail']) : '' ?>" required>
                    </div>
                </div>

                <div class="w-100 d-flex justify-content-end gap-2"> <a href="adminhomepage.php"
                        class="btn btn-back mt-3">Back</a> <button type="submit"
                        class="btn btn-update mt-3">Update</button> </div>

            </form>
        </div>
    </div>

    <?php include("shared/footer.php"); ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }, 5000);
        }

        // Optional: Add form validation before submit
        document.getElementById("profileForm").addEventListener("submit", function (e) {
            const email = document.getElementById("email").value;
            const confirmEmail = document.getElementById("confirmEmail").value;

            if (email !== confirmEmail) {
                e.preventDefault();
                alert("Email addresses do not match!");
                return false;
            }
        });
    </script>

</body>

</html>