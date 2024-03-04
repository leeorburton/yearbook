# Yearbook System with Login & Registration

## Project Roadmap:

### Visible Pages: 
1. index.php
    - homepage
    - user is able to browse all profiles
    - can be accessed with or without an account
    - when logged out, user is able to access login.php and when logged in, able to access profile.php and logout.php
    - images are displayed via displayImage.php
    - connect to db via db.php
2. login.php
    - accessible from the homepage when logged out
    - user is able to log into their account
    - user can only navigate to registration page via this page
    - error message delivered via query
    ```php
    <?php 
        if (isset($_GET['empty'])){
            echo "<p class='error-cont'>Please fill out required field(s)</p>";
        } 
        if (isset($_GET['incorrect'])){
            echo "<p class='error-cont'>Username and/or password is incorrect</p>";
        }
    ?>
    ```
    - validated via authenticate.php
3. register.php
    - via the login page, user is able to make an account and profile
    - once account is created, user is navigated to the login page and has to log in before entering the system
    - validates data inputs and delivers error messages before calling create_account.php
    - db populated via create_account.php
    - connect to db via db.php
4. profile.php
    - user is able to view and edit their profile
    - user can edit their profile from this page, but navigates to editImg.php to change their profile picture
    - validates data and returns error messages
    - image is displayed via displayImage.php
    - db populated via update.php
    - connect to db via db.php
5. editImg.php
    - accessed via profile.php
    - user is able to change their profile picture
    - validates file, returns error messages, and uploads image to db
    - returns user to profile.php with success message
    - image is displayed via displayImage.php
    - connect to db via db.php

### Backend Pages:
1. authenticate.php 
    - logs user in
2. create_account.php
    - creates account/user record in db
3. db.php
    - connects to db
4. logout.php
    - logs user out and navigates to login page
5. update.php
    - updates edited version of user profile
6. displayImg.php
    - retrieves and displays image via src attribute


### Important Elements:
- profile accessed via ```php $_SESSION['id'] ``` global variable