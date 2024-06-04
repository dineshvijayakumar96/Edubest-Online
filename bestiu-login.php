<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <!-- <base href="https://bestiu.edu.in/testing-540/"> -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- FavIcon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/images/favicon/safari-pinned-tab.svg" color="#283b8f">
    <link rel="shortcut icon" href="/assets/images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="/assets/images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <title>Login - BESTIU | EduBEST - ONLINE LEARNING</title>
</head>

<body>

    <!-- Login Section -->
    <section class="bestiu-login-section">
        <div class="row">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="container-margin bestiu-login my-5 my-lg-0">
                    <div class="bestiu-login-title">
                        <h1>BESTIU Students Login</h1>
                        <p class="my-4">Use the email provided by the university (e.g.,
                            studentname@bestiu.edu.in) and
                            the mobile number that was given by you to the university.</p>
                    </div>
                    <div class="bestiu-login-form">
                        <form method="POST" action="login.php" class="row g-4">
                            <div class="col-12">
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="studentname@bestiu.edu.in" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="mobile" class="form-control" id="mobile"
                                    placeholder="Your 10 digit Mobile Number" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="home-enq-submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1">
                <div class="bestiu-login-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Js Files -->
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>