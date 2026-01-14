<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iskonnovators Student Community</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="assets/style.css" rel="stylesheet">

    <style>
        /* Prevent horizontal scrolling and ensure consistent box-sizing */
        html,
        body {
            overflow-x: hidden;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* Floating chat button */
        #chat-button {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: rgb(232, 174, 0);
            color: white;
            font-size: 26px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Chat window sizing (responsive) */
        #chat-window {
            position: fixed;
            bottom: 100px;
            right: 25px;
            width: 400px;
            max-width: 90vw;
            height: 520px;
            max-height: 80vh;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            display: none;
            z-index: 9999;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        #chat-window iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Minor responsive tweaks */
        @media (max-width: 768px) {
            #chat-window {
                width: 85vw;
                height: 400px;
                bottom: 80px;
                right: 10px;
            }

            #chat-button {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .navbar {
                padding: 0.5rem 0 !important;
            }

            .navbar-brand img {
                max-width: 180px;
                height: auto !important;
            }
        }

        @media (max-width: 576px) {
            #chat-button {
                bottom: 15px;
                right: 15px;
                width: 50px;
                height: 50px;
                font-size: 18px;
            }

            #chat-window {
                width: 90vw;
                height: 350px;
                bottom: 70px;
                right: 5px;
            }

            .navbar-brand img {
                max-width: 150px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar shadow-sm navbar-expand-lg">
        <div class="container-fluid d-flex align-items-center flex-wrap ">
            <div class="d-flex gap-4 mx-auto mx-sm-0 me-xs-auto align-content-lg-center">
                <a class="navbar-brand d-flex ms-2 ms-lg-4 justify-content-center" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" height="auto" class="mt-1 mb-1"
                        style="max-width: 250px; width: auto;">
                </a>
            </div>

            <div
                class="pe-sm-3 d-flex flex-column flex-sm-row gap-2 gap-lg-4 align-items-center justify-content-center justify-content-md-end ms-md-auto">
                <button class="btn rounded rounded-5 pb-2 px-3 px-lg-4 w-100 w-sm-auto  mb-sm-0">
                    <a class="nav-link text-light fw-bold text-center" href="login.php">
                        <h6 class="mb-0 d-lg-none">Login</h6>
                        <h5 class="mb-0 d-none py-1 d-lg-block">Login</h5>
                    </a>
                </button>

                <button class="btn rounded rounded-5 pb-2 px-3 px-lg-4 w-100 w-sm-auto">
                    <a class="nav-link text-light fw-bold text-center" href="apply.php">
                        <h6 class="mb-0 d-lg-none">Apply</h6>
                        <h5 class="mb-0 d-none py-1 d-lg-block">Apply</h5>
                    </a>
                </button>
            </div>
        </div>
    </nav>


    <!-- Apply Now Container -->
    <div class="container-fluid introduction-container p-0 shadow-sm pb-0">
        <div class="row g-0">
            <div
                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-dark d-flex align-items-center justify-content-center">
                <div class="p-4 p-lg-5 text-lg-start text-center w-100">
                    <h1 class="fw-bold">Join the Iskonnovators Student Community!</h1>
                    <p>Become a part of our vibrant community and unlock a world of opportunities. By joining the ISC,
                        you'll connect with others, grow your skills, and take part in meaningful experiences. Apply now
                        and embark on an exciting journey with the ISC.</p>

                    <button class="btn btn-lg rounded rounded-5 px-4  px-lg-5 pb-2 pt-2"
                        style="background-color: #84152c; color: white;">
                        <a class="nav-link text-light" href="apply.php">
                            <h4 class="fw-bold my-2  text-center d-flex justify-content-center">Apply Now!</h4>
                        </a>
                    </button>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex align-items-center justify-content-center">
                <img src="assets/img/mascot.png" alt="Banner" class="img-fluid d-none d-lg-block"
                    style="object-fit:contain; max-height: 500px;">
                <img src="assets/img/mascot full.png" alt="Banner" class="img-fluid d-lg-none"
                    style="object-fit:contain; max-height: 400px;">
            </div>
        </div>
    </div>

    <!-- Photo Cards Container -->
    <div class="container-fluid py-5">
        <div class="row g-5 px-4 justify-content-center">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm rounded-4">
                    <img src="assets\img\1.jpg" class="card-img-top" alt="Photo 1"
                        style="object-fit:cover; height:300px;">
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title">Community Event</h5>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12  col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm rounded-4">
                    <img src="assets\img\2.png" class="card-img-top" alt="Photo 2"
                        style="object-fit:cover; height:300px;">
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title">Workshop Highlights</h5>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm rounded-4">
                    <img src="assets\img\3.png" class="card-img-top " alt="Photo 3"
                        style="object-fit:cover; height:300px;">
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title">Project Showcase</h5>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsorship Container -->
    <div class="container-fluid introduction-container p-0 shadow-sm pb-3 ">
        <div class="row g-0">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex align-items-center justify-content-center">
                <img src="assets/img/Hanging_Right.png" alt="Mascot" class="img-fluid d-none d-lg-block"
                    style="object-fit:contain; max-height: 500px;">
                <img src="assets\img\mascot full 2.png" alt="Mascot" class="img-fluid d-lg-none"
                    style="object-fit:contain; max-height: 400px;">
            </div>

            <div
                class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-dark d-flex align-items-center justify-content-center">
                <div class="p-4 p-lg-5 text-lg-end text-center w-100">
                    <h1 class="fw-bold">Support. Inspire. Innovate.</h1>
                    <div class="d-flex justify-content-end">
                        <p class="text-justify" style="max-width: 600px; text-align: justify;">
                            Your sponsorship plays a vital role in shaping the future of student leaders. By supporting
                            the Iskonnovators Student Community PUPSTC, you help us continue creating meaningful events,
                            impactful programs, and enriching experiences that inspire personal growth and academic
                            excellence among students. Together, we can empower young minds, build community, and turn
                            ideas into lasting impact.
                        </p>
                    </div>
                    <div class="text-lg-end text-center">
                        <button class="btn btn-lg rounded rounded-5 px-4 px-lg-5 pb-2 pt-2"
                            style="background-color: #84152c; color: white;">
                            <a class="nav-link text-light" href="sponsorship.php">
                                <h4 class="fw-bold my-2  text-center d-flex justify-content-center">Become a Sponsor
                                </h4>
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="chat-button">
        💬
    </div>

    <div id="chat-window">
        <iframe src="chat.php" frameborder="0"></iframe>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = "flex";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        function submitApplication() {
            alert("Application submitted!");
            closeModal('applyModal');
        }

        document.getElementById("chat-button").onclick = function () {
            const windowChat = document.getElementById("chat-window");
            windowChat.style.display = (windowChat.style.display === "none" || windowChat.style.display === "") ?
                "block" :
                "none";
        };
    </script>


    <!-- footer -->
    <footer class="footer text-center text-lg-start mt-auto ">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>