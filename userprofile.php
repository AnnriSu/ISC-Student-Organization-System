<?php
session_start();
include("connect.php");

// Check if user is logged in and is a member
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'member') {
    // User is not authenticated or not a member, redirect to login
    header("Location: login.php");
    exit();
}

// Get member's current email and ID from session
$currentEmail = $_SESSION['email'] ?? null;
$memberID = null;

// Get member's ID first
if ($currentEmail) {
    $stmt = $conn->prepare("SELECT mbID FROM tbl_members WHERE mbEmail = ?");
    $stmt->bind_param("s", $currentEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $memberID = $row['mbID'];
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
    if (empty($fname) || empty($lname) || empty($salutation) || empty($pronoun) || 
        empty($birthDate) || empty($department) || empty($section) || 
        empty($institution) || empty($mobile) || empty($email)) {
        $updateMessage = "Please fill in all required fields.";
        $updateType = "danger";
    } elseif ($email !== $confirmEmail) {
        $updateMessage = "Email addresses do not match.";
        $updateType = "danger";
    } elseif ($memberID === null) {
        $updateMessage = "Error: Member ID not found. Please try logging in again.";
        $updateType = "danger";
    } else {
        // Update member information in database
        $updateStmt = $conn->prepare("UPDATE tbl_members SET mbFname = ?, mbLname = ?, mbMname = ?, mbSuffix = ?, mbSalutations = ?, mbPronouns = ?, mbBirthDate = ?, mbDepartment = ?, mbSection = ?, mbInstitution = ?, mbMobileNo = ?, mbEmail = ? WHERE mbID = ?");
        $updateStmt->bind_param("ssssssssssssi", $fname, $lname, $mname, $suffix, $salutation, $pronoun, $birthDate, $department, $section, $institution, $mobile, $email, $memberID);
        
        if ($updateStmt->execute()) {
            $updateMessage = "Profile updated successfully!";
            $updateType = "success";
            
            // Update session email if email was changed
            if ($email !== $currentEmail) {
                $_SESSION['email'] = $email;
            }
            
            // Refresh member data
            $currentEmail = $email;
        } else {
            $updateMessage = "Error updating profile: " . $conn->error . ". Please try again.";
            $updateType = "danger";
        }
        $updateStmt->close();
    }
}

// Get member's data from database to pre-fill the form
$memberData = null;
if ($currentEmail) {
    $stmt = $conn->prepare("SELECT mbFname, mbLname, mbMname, mbSuffix, mbSalutations, mbPronouns, mbBirthDate, mbDepartment, mbSection, mbInstitution, mbMobileNo, mbEmail FROM tbl_members WHERE mbEmail = ?");
    $stmt->bind_param("s", $currentEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $memberData = $result->fetch_assoc();
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

        <?php if (!empty($updateMessage)): ?>
            <div class="alert alert-<?= $updateType ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($updateMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <a href="homepage-member.php" class="btn btn-back mb-3">Go Back</a>

            <form method="POST" id="profileForm">
            <div class="row mt-2">
                <div class="col-6 px-1 px-1">
                    <label for="firstName" class="form-label ms-1">First Name<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $memberData ? htmlspecialchars($memberData['mbFname']) : '' ?>" required>
                </div>
                <div class="col-6 px-1 px-1">
                    <label for="lastName" class="form-label ms-1">Last Name<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $memberData ? htmlspecialchars($memberData['mbLname']) : '' ?>" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6 px-1">
                    <label for="middleName" class="form-label ms-1">Middle Name</label>
                    <input type="text" class="form-control" id="middleName" name="middleName" value="<?= $memberData ? htmlspecialchars($memberData['mbMname'] ?? '') : '' ?>">
                </div>
                <div class="col-6 px-1">
                    <label for="suffix" class="form-label ms-1">Suffix</label>
                    <input type="text" class="form-control" id="suffix" name="suffix" value="<?= $memberData ? htmlspecialchars($memberData['mbSuffix'] ?? '') : '' ?>">
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
                                <option value="mr" <?= $memberData && $memberData['mbSalutations'] == 'mr' ? 'selected' : '' ?>>Mr.</option>
                                <option value="ms" <?= $memberData && $memberData['mbSalutations'] == 'ms' ? 'selected' : '' ?>>Ms.</option>
                                <option value="mrs" <?= $memberData && $memberData['mbSalutations'] == 'mrs' ? 'selected' : '' ?>>Mrs.</option>
                            </select>
                        </div>
                        <div class="col ps-1">
                            <label for="genderPronoun" class="form-label ms-1">Pronoun<span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="genderPronoun" name="genderPronoun" required>
                                <option value="" disabled>Select</option>
                                <option value="he" <?= $memberData && $memberData['mbPronouns'] == 'he' ? 'selected' : '' ?>>He/Him</option>
                                <option value="she" <?= $memberData && $memberData['mbPronouns'] == 'she' ? 'selected' : '' ?>>She/Her</option>
                                <option value="they" <?= $memberData && $memberData['mbPronouns'] == 'they' ? 'selected' : '' ?>>They/Them</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col  px-1">
                    <div class="row mt-2">
                        <div class="col">
                            <label for="birthDate" class="form-label ms-1">Birth Date<span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control" id="birthDate" name="birthDate" value="<?= $memberData ? htmlspecialchars($memberData['mbBirthDate']) : '' ?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col  px-1">
                    <label for="department" class="form-label ms-1">Department<span style="color: red;">*</span></label>
                    <select class="form-select" id="department" name="department" required>
                        <option value="" disabled>Select</option>
                        <option value="cs" <?= $memberData && $memberData['mbDepartment'] == 'cs' ? 'selected' : '' ?>>Computer Science</option>
                        <option value="it" <?= $memberData && $memberData['mbDepartment'] == 'it' ? 'selected' : '' ?>>Information Technology</option>
                        <option value="is" <?= $memberData && $memberData['mbDepartment'] == 'is' ? 'selected' : '' ?>>Information Systems</option>
                        <option value="bsit" <?= $memberData && $memberData['mbDepartment'] == 'bsit' ? 'selected' : '' ?>>BSIT</option>
                    </select>
                </div>
                <div class="col  px-1">
                    <label for="section" class="form-label ms-1">Section<span style="color: red;">*</span></label>
                    <select class="form-select" id="section" name="section" required>
                        <option value="" disabled>Select</option>
                        <option value="a" <?= $memberData && $memberData['mbSection'] == 'a' ? 'selected' : '' ?>>Section A</option>
                        <option value="b" <?= $memberData && $memberData['mbSection'] == 'b' ? 'selected' : '' ?>>Section B</option>
                        <option value="c" <?= $memberData && $memberData['mbSection'] == 'c' ? 'selected' : '' ?>>Section C</option>
                        <option value="3-1" <?= $memberData && $memberData['mbSection'] == '3-1' ? 'selected' : '' ?>>3-1</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2 ">
                <div class="col-6 px-1">
                    <label for="institution" class="form-label ms-1">Institution<span
                            style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="institution" name="institution" value="<?= $memberData ? htmlspecialchars($memberData['mbInstitution']) : '' ?>" required>
                </div>
                <div class="col-6 px-1">
                    <label for="mobileNumber" class="form-label ms-1">Mobile Number<span
                            style="color: red;">*</span></label>
                    <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" value="<?= $memberData ? htmlspecialchars($memberData['mbMobileNo']) : '' ?>" placeholder="+63" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12  px-1">
                    <label for="email" class="form-label ms-1">Email<span style="color: red;">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $memberData ? htmlspecialchars($memberData['mbEmail']) : '' ?>" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12  px-1">
                    <label for="confirmEmail" class="form-label ms-1">Confirm Email<span
                            style="color: red;">*</span></label>
                    <input type="email" class="form-control" id="confirmEmail" name="confirmEmail" value="<?= $memberData ? htmlspecialchars($memberData['mbEmail']) : '' ?>" required>
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
        document.getElementById("profileForm").addEventListener("submit", function(e) {
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