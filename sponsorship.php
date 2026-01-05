<!doctype html>
<html lang="en">

<head>
    <?php include("shared/head.php") ?>
</head>

<script src="https://www.paypal.com/sdk/js?client-id=AWXiLk5YzaHQXWeE7asEGI2j1gCP3gbWw4Kq89QXRl5Lfst4S32h7K46LZuV0bi1r-M38LP_Mkod9K14" ></script>
   
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
</style>

<body>
    <?php include("shared/navbar.php") ?>

    <div class="container form-container mt-3 mb-3 p-4 shadow-sm rounded-3 " style="max-width: 700px;">
        <div class="row mt-2 ">
            <div class="col-12 ">
                <h1 class="text-center mb-2">Sponsorship</h1>
                <p class="text-center text-muted mb-4">
                    Your generous sponsorship would provide the essential resources needed to create a lasting, positive
                    impact on our student community through Iskonnovators.
                </p>

                <!-- Name Input -->
                <div class="mb-3">
                    <label for="donorName" class="form-label">Name</label>
                    <input id="donorName" type="text" class="form-control" placeholder="ex: John Doe">
                </div>

                <!-- Donation Amount -->
                <div class="my-3">
                    <label class="form-label">Amount<span style="color: red;">*</span></label>

                    <input id="custom-amount" type="number" min="0.01" step="0.01" class="form-control mb-3"
                        placeholder="Enter amount" value="1.00">

                    <div class="d-flex justify-content-center gap-2 flex-wrap text-light">
                        <button data-amount="5.00" class="preset btn btn-sm">₱5</button>
                        <button data-amount="10.00" class="preset btn btn-sm">₱10</button>
                        <button data-amount="50.00" class="preset btn btn-sm">₱50</button>
                        <button data-amount="100.00" class="preset btn btn-sm">₱100</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="paypal-button-container" class="mt-4"></div>

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
            onClick: function (data, actions) {
                if (parseFloat(selectedAmount) <= 0) {
                    alert("Please enter a valid amount.");
                    return actions.reject();
                }
                return actions.resolve();
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: selectedAmount }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert("Thank you! Donation completed.");
                });
            },
            onError: function (err) {
                alert("An error occurred while processing the donation.");
            }
        }).render('#paypal-button-container');
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>