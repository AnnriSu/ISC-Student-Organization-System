<!doctype html>
<html lang="en">

<?php
include("shared/head.php");
include("connect.php");

// Handle sponsor request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleSponsorRequest();
}

// Fetch all sponsors from database
$sponsors = [];
$query = "SELECT spName FROM tbl_sponsors ORDER BY spID DESC";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = trim($row['spName']);
        if ($name === '' || strcasecmp($name, 'anonymous') === 0) {
            continue; // skip anonymous/blank entries
        }
        $sponsors[] = $name;
    }
}

// Process sponsor submission and save to database

function handleSponsorRequest(): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        $sponsorName = sanitizeInput($_POST['sponsorName'] ?? '');

        // Use "anonymous" if name is empty
        if (empty($sponsorName)) {
            $sponsorName = 'anonymous';
        }

        $sponsorId = saveSponsorToDatabase($sponsorName);

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Sponsor saved successfully',
            'data' => [
                'sponsorId' => $sponsorId,
                'sponsorName' => $sponsorName
            ]
        ]);
        exit;
    } catch (Exception $error) {
        error_log("Sponsor request error: " . $error->getMessage());
        http_response_code($error->getCode() ?: 500);
        echo json_encode([
            'success' => false,
            'message' => $error->getMessage()
        ]);
        exit;
    }
}

// Sanitize user input

function sanitizeInput(string $input): string
{
    return trim(strip_tags($input));
}

// Validate sponsor name

function validateSponsorName(string $name): bool
{
    return !empty($name) && strlen($name) > 0 && strlen($name) <= 200;
}

// Save sponsor to database using prepared statement

function saveSponsorToDatabase(string $sponsorName): int
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO tbl_sponsors (spName) VALUES (?)");

    if (!$stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }

    $stmt->bind_param('s', $sponsorName);

    if (!$stmt->execute()) {
        throw new Exception("Database execute error: " . $stmt->error);
    }

    $sponsorId = $stmt->insert_id;
    $stmt->close();

    return $sponsorId;
}
?>

<head>
</head>

<script src="https://www.paypal.com/sdk/js?client-id=AWXiLk5YzaHQXWeE7asEGI2j1gCP3gbWw4Kq89QXRl5Lfst4S32h7K46LZuV0bi1r-M38LP_Mkod9K14"></script>

</script>

<style>
    .donate-card {
        background: white;
        border-radius: 30px;
        padding: 35px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        border: 2px solid #ffe4f2;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    h1 {
        color: var(--pink4);
        font-weight: 800;
        letter-spacing: -1px;
    }

    .form-label {
        color: var(--pink4);
        font-weight: 600;
    }

    /* Amount Buttons */
    .preset {
        border-radius: 25px;
        border: 2px solid var(--pink3);
        color: var(--pink3);
        font-weight: 600;
        padding: 8px 20px;
        transition: 0.2s ease;
    }

    .preset:hover {
        background: var(--pink3);
        color: white;
    }

    .preset.active {
        background: var(--pink3);
        color: white;
    }

    #custom-amount {
        border-radius: 12px;
        border: 2px solid #dea500;
    }

    /* Success Modal Styles */
    .success-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .success-modal.show {
        display: flex;
    }

    .success-card {
        background: white;
        border-radius: 15px;
        padding: 48px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        max-width: 520px;
        width: 90%;
        text-align: center;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-card h2 {
        color: var(--pink4);
        font-weight: 800;
        margin-bottom: 20px;
        font-size: 24px;
    }

    .success-card p {
        color: #666;
        margin-bottom: 30px;
        font-size: 16px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .btn-success-ok {
        background-color: #3769b2;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
        min-width: 150px;
    }

    .btn-success-ok:hover {
        background-color: #2a50a0;
    }

    .btn-success-home {
        background-color: #84152c;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
        flex: 1;
        min-width: 180px;
    }

    .btn-success-home:hover {
        background-color: #6c1122;
    }
</style>

<body>
    <?php include("shared/navbar.php") ?>

    <div class="container mt-3 mb-3" style="max-width: 1000px;">
        <div class="row g-4">
            <!-- Donation Card -->
            <div class="col-12 col-lg-9">
                <div class="donate-card">
                    <h1 class="text-center mb-2">Sponsorship</h1>
                    <p class="text-center text-muted mb-4">
                        Your generous sponsorship would provide the essential resources needed to create a lasting, positive
                        impact on our student community through Iskonnovators.
                    </p>

                    <!-- Name Input -->
                    <div class="mb-3">
                        <label for="donorName" class="form-label">Name</label>
                        <input id="donorName" type="text" class="form-control" placeholder="ex: John Doe (Optional)">
                    </div>

                    <!-- Donation Amount -->
                    <div class="my-3">
                        <label class="form-label">Amount<span style="color: red;">*</span></label>

                        <input id="custom-amount" type="number" min="0.01" step="0.01" class="form-control mb-3"
                            placeholder="Enter amount" value="1.00">


                    </div>

                    <div id="paypal-button-container" class="mt-4">

                    </div>
                </div>
            </div>

            <!-- Sponsors Card -->
            <div class="col-12 col-lg-3 d-flex justify-content-center">
                <div class="donate-card" style="height: 100%; max-height: 600px; overflow-y: auto; max-width: 360px; width: auto;">
                    <h2 class="text-center mb-4" style="color: var(--pink4); font-weight: 700;">SPECIAL THANKS</h2>
                    <h4 class="text-center mb-4">to the following sponsors:</h4>
                    <?php if (count($sponsors) > 0): ?>
                        <ul class="list-unstyled">
                            <?php foreach ($sponsors as $sponsor): ?>
                                <li class="mb-3 p-3" style="background: #fff3f8; border-radius: 12px; border-left: 4px solid var(--pink3); display: flex; align-items: center; justify-content: center; gap: 8px; text-align: center;">
                                    <i class="bi bi-heart-fill" style="color: var(--pink3);"></i>
                                    <span style="color: var(--pink4); font-weight: 400;"><?php echo htmlspecialchars($sponsor); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-center text-muted">No sponsors yet. Be the first to support us!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div id="paypal-button-container" class="mt-4"></div>

    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal">
        <div class="success-card">
            <h2>Thank You!</h2>
            <p>Money has been sent successfully.</p>
            <div class="button-group">
                <button id="okayBtn" class="btn-success-ok">Okay</button>
                <button id="homeBtn" class="btn-success-home">Back to Home</button>
            </div>
        </div>
    </div>

    <?php include("shared/footer.php") ?>

    <script>
        let selectedAmount = "1.00";

        function fmt(v) {
            return parseFloat(v || 0).toFixed(2);
        }

        document.querySelectorAll('.preset').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.preset').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                selectedAmount = fmt(btn.getAttribute('data-amount'));
                document.getElementById('custom-amount').value = selectedAmount;
            });
        });

        document.getElementById('custom-amount').addEventListener('input', () => {
            const num = parseFloat(document.getElementById('custom-amount').value);
            if (num > 0) {
                selectedAmount = fmt(num);
                document.querySelectorAll('.preset').forEach(b => b.classList.remove('active'));
            }
        });

        paypal.Buttons({
            onClick: function(data, actions) {
                if (parseFloat(selectedAmount) <= 0) {
                    alert("Please enter a valid amount.");
                    return actions.reject();
                }
                return actions.resolve();
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: selectedAmount
                        }
                    }]
                });
            },
            onApprove: async (data, actions) => {
                try {
                    const paymentDetails = await actions.order.capture();
                    let sponsorName = document.getElementById('donorName').value.trim();

                    // Use "anonymous" if name field is empty
                    if (!sponsorName) {
                        sponsorName = 'anonymous';
                    }

                    await saveSponsor(sponsorName);
                    showSuccessModal();
                } catch (error) {
                    console.error('Error:', error);
                    showSuccessModal();
                }
            },
            onError: (error) => {
                console.error('PayPal error:', error);
                alert("An error occurred while processing the donation.");
            }
        }).render('#paypal-button-container');

        /**
         * Save sponsor to database
         */
        async function saveSponsor(sponsorName) {
            const formData = new FormData();
            formData.append('sponsorName', sponsorName);

            const response = await fetch(window.location.pathname, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Failed to save sponsor');
            }

            return result.data;
        }

        /**
         * Show success modal
         */
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('show');
        }

        /**
         * Handle okay button click - reload sponsor page
         */
        document.getElementById('okayBtn').addEventListener('click', () => {
            window.location.reload();
        });

        /**
         * Handle back to home button click - go to index.php
         */
        document.getElementById('homeBtn').addEventListener('click', () => {
            window.location.href = 'index.php';
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>