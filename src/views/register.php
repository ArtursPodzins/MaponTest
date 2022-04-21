<?php
    include_once 'src/helpers/session_helper.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Register</title>
</head>
<body>
<section class="register">
    <div class="register-wrapper">
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-login-btn" data-mdb-toggle="pill" onclick="window.location.href='login'" role="tab" aria-controls="pills-login" aria-selected="true">Login</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-register" data-mdb-toggle="pill" href="#pills-register" role="tab" aria-controls="pills-register" aria-selected="false">Register</a>
            </li>
        </ul>
        <!-- Button navs -->
        <div class="error-message">
            <?php flash("register")?>
        </div>
        <!-- Main content -->
        <div class="tab-pane fade show active" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
            <form method="post" action="submitted">
                <input type="hidden" name="type" value="register">
                <!-- Username input -->
                <div class="form-outline mb-4">
                    <input type="text" name="uid" class="form-control" placeholder="Username..." required/>
                    <label class="form-label" for="registerUsername">Username</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" name="pwd" class="form-control" placeholder="********" required/>
                    <label class="form-label" for="registerPassword">Password</label>
                </div>

                <!-- Repeat Password input -->
                <div class="form-outline mb-4">
                    <input type="password" name="pwdRepeat" class="form-control" placeholder="********" required/>
                    <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                </div>

                <!-- Submit button -->
                <button type="submit" name="submit" class="btn btn-primary btn-block mb-3">Sign up</button>

                <!-- Back to login button -->
                <button onclick="window.location.href='login'" id="account-btn" class="btn btn-block mb-3">Already have an account?</button>
            </form>
        </div>
    </div>
</section>
</body>
</html>