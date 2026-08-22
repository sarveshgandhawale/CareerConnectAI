<?php
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Login | CareerConnect AI</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet"
          href="assets/css/style.css">

</head>


<body>


<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card shadow-lg">

                <div class="row">


                    <!-- =================================
                         LEFT PANEL
                    ================================= -->

                    <div class="col-lg-5 left-panel">

                        <h2>

                            <i class="fa-solid fa-user-graduate"></i>

                            CareerConnect AI

                        </h2>


                        <p>

                            Login to access your
                            AI Student Portal.

                        </p>


                        <div class="left-features">


                            <!-- CAREER -->

                            <div class="left-feature">

                                <i class="fa-solid fa-compass"></i>

                                <span>
                                    Career Guidance
                                </span>

                            </div>


                            <!-- INTERVIEW -->

                            <div class="left-feature">

                                <i class="fa-solid fa-microphone"></i>

                                <span>
                                    Interview Practice
                                </span>

                            </div>


                            <!-- RESUME -->

                            <div class="left-feature">

                                <i class="fa-solid fa-file-lines"></i>

                                <span>
                                    Resume Builder
                                </span>

                            </div>


                            <!-- SKILL EXCHANGE -->

                            <div class="left-feature">

                                <i class="fa-solid fa-users"></i>

                                <span>
                                    Student Skill Exchange
                                </span>

                            </div>


                        </div>

                    </div>



                    <!-- =================================
                         LOGIN FORM
                    ================================= -->

                    <div class="col-lg-7">

                        <div class="p-4">


                            <h3 class="text-center mb-4">

                                <i class="fa-solid fa-right-to-bracket"></i>

                                Login

                            </h3>


                            <form
                                action="login_process.php"
                                method="POST">


                                <!-- EMAIL -->

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control mb-3"
                                    placeholder="Enter Email"
                                    required>


                                <!-- PASSWORD -->

                                <div class="input-group mb-3">

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="Enter Password"
                                        required>

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="showPassword()">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>


                                <!-- FORGOT PASSWORD -->

                                <div class="text-end mb-3">

                                    <a href="forgot_password.php">

                                        Forgot Password?

                                    </a>

                                </div>


                                <!-- LOGIN -->

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100">

                                    <i class="fa-solid fa-right-to-bracket"></i>

                                    Login

                                </button>


                            </form>


                            <!-- REGISTER -->

                            <div class="text-center mt-3">

                                Don't have an account?

                                <a href="register.php">
                                    Register
                                </a>

                            </div>


                            <!-- HOME -->

                            <div class="text-center mt-2">

                                <a href="index.php">

                                    <i class="fa-solid fa-arrow-left"></i>

                                    Back to Home

                                </a>

                            </div>


                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


<script src="assets/js/script.js"></script>

</body>

</html>