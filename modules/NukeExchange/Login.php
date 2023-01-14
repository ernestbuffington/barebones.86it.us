<?php
$title = "Login";
include("header.php"); ?>

<body>

<link rel="stylesheet" href="css/login.css" />

    <div class="loginbox">
        <div class="alert alert-success hide" role="alert" id="alert-success">
          This is a success alert—check it out!
        </div>
        <div class="alert alert-danger hide" role="alert" id="alert-danger">
          This is a danger alert—check it out!
        </div>
      <img src="defaultpic.png" class="avatar" />
      <h1></h1>
      <form action="user/signin" id="signin">

        <p>Email</p>
        <input type="text" name="user_email" />
        <p>Password</p>
        <input type="user_password" name="user_password" />
        <!-- <input type="submit" name="" value="Login" /> -->

      </form>
      <button onclick='submitForm("signin", "onDataReceived")'>Login</button>
      <br />
     
        <a href="SignUp.php">Don't have an account?</a></a>
    </div>


    <script src="shared/js/shared.js"></script>
    <script>

      function  onDataReceived(data) {
        if(data.RESULT == 1) {
          showAlert("alert-success",data.MESSAGE + '. ' + 'Redirecting in 3 seconds');
          redirectUser(`profile.php?user_id=${data.user_id}`,3000);
        } else {
          showAlert("alert-danger", data.MESSAGE);
        }
      }
    </script>
</body>
</html>
