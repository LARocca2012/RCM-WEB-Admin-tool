<?php


error_reporting(NULL);
ini_set("display_errors", 0);

/**
 * Class OneFileLoginApplication
 *
 * An entire php application with user registration, login and logout in one file.
 * Uses very modern password hashing via the PHP 5.5 password hashing functions.
 * This project includes a compatibility file to make these functions available in PHP 5.3.7+ and PHP 5.4+.
 *
 * @author Panique
 * @link https://github.com/panique/php-login-one-file/
 * @license http://opensource.org/licenses/MIT MIT License
 */
 

class OneFileLoginApplication
{
   
 

 /**
     * @var string Type of used database (currently only SQLite, but feel free to expand this with mysql etc)
     */
    private $db_type = "sqlite"; //

    /**
     * @var string Path of the database file (create this with _install.php)
     */
    private $db_sqlite_path = "./users.db";

    /**
     * @var object Database connection
     */
    private $db_connection = null;

    /**
     * @var bool Login status of user
     */
    private $user_is_logged_in = false;

    /**
     * @var string System messages, likes errors, notices, etc.
     */
    public $feedback = "";


    /**
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct()
    {
        if ($this->performMinimumRequirementsCheck()) {
            $this->runApplication();
        }
    }

    /**
     * Performs a check for minimum requirements to run this application.
     * Does not run the further application when PHP version is lower than 5.3.7
     * Does include the PHP password compatibility library when PHP version lower than 5.5.0
     * (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
     * @return bool Success status of minimum requirements check, default is false
     */
    private function performMinimumRequirementsCheck()
    {
        if (version_compare(PHP_VERSION, '5.3.7', '<')) {
            echo "Sorry, Simple PHP Login does not run on a PHP version older than 5.3.7 !";
        } elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
            require_once("libraries/password_compatibility_library.php");
            return true;
        } elseif (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * This is basically the controller that handles the entire flow of the application.
     */
    public function runApplication()
    {
        // check is user wants to see register page (etc.)
        if (isset($_GET["action"]) && $_GET["action"] == "register") {
            $this->doRegistration();
            $this->showPageRegistration();
        } else {
            // start the session, always needed!
            $this->doStartSession();
            // check for possible user interactions (login with session/post data or logout)
            $this->performUserLoginAction();
            // show "page", according to user's login status
            if ($this->getUserLoginStatus()) {
                $this->showPageLoggedIn();
            } else {
                $this->showPageLoginForm();
            }
        }
    }

    /**
     * Creates a PDO database connection (in this case to a SQLite flat-file database)
     * @return bool Database creation success status, false by default
     */
    private function createDatabaseConnection()
    {
        try {
            $this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);
            return true;
        } catch (PDOException $e) {
            $this->feedback = "PDO database connection problem: " . $e->getMessage();
        } catch (Exception $e) {
            $this->feedback = "General problem: " . $e->getMessage();
        }
        return false;
    }

    /**
     * Handles the flow of the login/logout process. According to the circumstances, a logout, a login with session
     * data or a login with post data will be performed
     */
    private function performUserLoginAction()
    {
        if (isset($_GET["action"]) && $_GET["action"] == "logout") {
            $this->doLogout();
        } elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])) {
            $this->doLoginWithSessionData();
        } elseif (isset($_POST["login"])) {
            $this->doLoginWithPostData();
        }
    }

    /**
     * Simply starts the session.
     * It's cleaner to put this into a method than writing it directly into runApplication()
     */
    private function doStartSession()
    {
        if(session_status() == PHP_SESSION_NONE) session_start();
    }

    /**
     * Set a marker (NOTE: is this method necessary ?)
     */
    private function doLoginWithSessionData()
    {
        $this->user_is_logged_in = true; // ?
    }

    /**
     * Process flow of login with POST data
     */
    private function doLoginWithPostData()
    {
        if ($this->checkLoginFormDataNotEmpty()) {
            if ($this->createDatabaseConnection()) {
                $this->checkPasswordCorrectnessAndLogin();
            }
        }
    }

    /**
     * Logs the user out
     */
    private function doLogout()
    {
        $_SESSION = array();
        session_destroy();
        $this->user_is_logged_in = false;
        $this->feedback = "You were just logged out.";
   ?>
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Lynx_Lynx" />

	<title>RCM ADMINTOOL</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" charset="utf-8"></script>
 
<style type="text/css">
h3 {
    margin-top: 50px;
}
.tooltip {
    position: relative;
    background: #eaeaea;
    cursor: help;
    display: inline-block;
    text-decoration: none;
    color: #222;
    outline: none;
    text-indent: 0;
    padding: 0 3px;
}
.tooltip:hover:before {
    content: attr(data-title);
    white-space: pre-line;
    position: absolute;
    bottom: 30px;
    left: 50%;
    z-index: 9999;
    width: 230px;
    margin-left: -127px;
    padding: 10px;
    border: 2px solid #ccc;
    opacity: .9;
    background-color: #ddd;
    background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    -moz-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    text-shadow: 0 1px 0 rgba(255,255,255,.4);
}
.tooltip:hover:after {
    content: "";
    position: absolute;
    z-index: 10000;
    bottom: 24px;
    left: 50%;
    margin-left: -8px;
    border-top: 8px solid #ddd;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 0;
}
/* Yellow */
.yellow-tooltip:hover:before {
    border-color: #e1ca82;
    background-color: #ffeaa6;
}
.yellow-tooltip:hover:after {
    border-top-color: #ffeaa6;
}
</style>



<style>
 /*demo page style*/
    body {background:url(data:image/png;base64,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);
}
    h1{
      font-family:"Myriad Pro","Helvetica Neue",Helvetica,Arial,Sans-Serif;
      text-shadow: rgba(0, 0, 0, 0.498039) 0px 1px 1px;
    color: #cc0033;
      /* The logo text */
      font-size:3.5em;
      padding:0.5em 0 0;
      text-transform:uppercase;
        margin:0; padding:0;
    text-align: left;
    }
     
    p {line-height:1.5em; padding-bottom:1em;}
     
    #wrapper {
        width:810px;
        margin:0 auto;
        padding: 0;
    }
     
    #header {height: 150px;}
     
    #logo {height: 100px;}
     
/*main menu*/
    #menu {
        text-transform: uppercase;
        text-align: center;
        line-height: 50px;
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,0.9) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0.9)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
border: 1px solid #DDD;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
-webkit-box-shadow:1px 2px 8px #9A9A9A;
-moz-box-shadow:1px 2px 8px #9A9A9A;
 
    }
    #menu ul {padding:0; margin:0;}
    #menu li{
        display: inline;
        list-style:none;
        margin: 5px 10px;
    font-size: 1.5em;
    }
     
    #menu li a {
        padding:5px 10px;
        color:#bbb;
        text-decoration: none;
        text-shadow: rgba(0, 0, 0, 0.6) 0px 1px 1px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
 
    }
     
    #menu li a:hover{
        background: #eee;
        color: #C03;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-ms-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-o-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
        -webkit-transition-property: color, background;
        -webkit-transition-duration: 0.5s, 0.5s;
    }
    .default {
        width:810px;
    }
    .fixed {
        position:fixed;
        top:-5px; left:0;
        width:100%;
        padding:10px 0;
         
        -moz-box-shadow: 5px 5px 20px #333;
        -webkit-box-shadow: 5px 5px 20px #333;
        box-shadow: 5px 5px 20px #333;
    }
    .transbg {
       background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        
        var $menu = $("#menu");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });//scroll
        
        $menu.hover(
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).removeClass('transbg');
                }
            },
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).addClass('transbg');
                }
            });//hover
    });//jQuery
</script>
<style>
	.modalDialog {
		position: fixed;
		font-family: Arial, Helvetica, sans-serif;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0,0,0,0.8);
		z-index: 99999;
		-webkit-transition: opacity 400ms ease-in;
		-moz-transition: opacity 400ms ease-in;
		transition: opacity 400ms ease-in;
		display: none;
		pointer-events: none;
	}

	.modalDialog:target {
		display: block;
		pointer-events: auto;
	}

	.modalDialog > div {
		width: 400px;
		position: relative;
		margin: 10% auto;
		padding: 5px 20px 13px 20px;
		border-radius: 10px;
		background: #fff;
		background: -moz-linear-gradient(#fff, #999);
		background: -webkit-linear-gradient(#fff, #999);
		background: -o-linear-gradient(#fff, #999);
	}

	.close {
		background: #606061;
		color: #FFFFFF;
		line-height: 25px;
		position: absolute;
		right: -12px;
		text-align: center;
		top: -10px;
		width: 24px;
		text-decoration: none;
		font-weight: bold;
		-webkit-border-radius: 12px;
		-moz-border-radius: 12px;
		border-radius: 12px;
		-moz-box-shadow: 1px 1px 3px #000;
		-webkit-box-shadow: 1px 1px 3px #000;
		box-shadow: 1px 1px 3px #000;
	}

	.close:hover { background: #00d9ff; }


/*Всплывающий блок CSS*/
.thumbnail_my{
position: relative;
z-index: 0;
}
.thumbnail_my:hover{
background-color: transparent;
z-index: 70;
}
.thumbnail_my span{ /*CSS for enlarged image*/
position: absolute;
background-color: #3d3d3d;
padding: 15px;
left: 1200px;
border: 1px solid white;
visibility: hidden;
color: Yellow;
text-decoration: none;
border-radius: 4px 4px 4px 4px;
-moz-border-radius: 4px 4px 4px 4px;
-webkit-border-radius: 4px 4px 4px 4px;
}
.thumbnail_my span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 20px;
}
.thumbnail_my:hover span{ /*CSS for enlarged image on hover*/
visibility: visible;
top: 0;
left: -240px; /*position where enlarged image should offset horizontally */
}
/*---------------------*/
</style>		
</head>
<body>



<?php 


}

    /**
     * The registration flow
     * @return bool
     */
    private function doRegistration()
    {
        if ($this->checkRegistrationData()) {
            if ($this->createDatabaseConnection()) {
                $this->createNewUser();
            }
        }
        // default return
        return false;
    }

    /**
     * Validates the login form data, checks if username and password are provided
     * @return bool Login form data check success state
     */
    private function checkLoginFormDataNotEmpty()
    {
        if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
            return true;
        } elseif (empty($_POST['user_name'])) {
            $this->feedback = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->feedback = "Password field was empty.";
        }
        // default return
        return false;
    }

  
  
/**
     * Checks if user exits, if so: check if provided password matches the one in the database
     * @return bool User login success status
     */
    private function checkPasswordCorrectnessAndLogin()
    {
        // remember: the user can log in with username or email address
        $sql = 'SELECT user_name, user_email, user_password_hash
                FROM users
                WHERE user_name = :user_name OR user_email = :user_name
                LIMIT 1';
        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':user_name', $_POST['user_name']);
        $query->execute();

        // Btw that's the weird way to get num_rows in PDO with SQLite:
        // if (count($query->fetchAll(PDO::FETCH_NUM)) == 1) {
        // Holy! But that's how it is. $result->numRows() works with SQLite pure, but not with SQLite PDO.
        // This is so crappy, but that's how PDO works.
        // As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
        // If you meet the inventor of PDO, punch him. Seriously.
include("zzz_add_here_server_configurations.php"); 

        $result_row = $query->fetchObject();
        if ($_POST['user_password'] == $admintool_password) {
            // using PHP 5.5's password_verify() function to check password
            if ($_POST['user_password']== $admintool_password) {
                // write user data into PHP SESSION [a file on your server]
                //$_SESSION['user_name'] = $result_row->user_name;
               // $_SESSION['user_email'] = $result_row->user_email;
                 $_POST['user_name'] = $admintool_user;
                $_POST['user_password'] = $admintool_password;
                $_SESSION['passss'] = $_POST['user_password'];
                $_SESSION['user_name'] = $_POST['user_name'];
                $_SESSION['user_is_logged_in'] = true;
                $this->user_is_logged_in = true;
                return true;
            } else {
                $this->feedback = "Wrong password.";
            }
        } else {
            $this->feedback = "This user does not exist.";
        }
        // default return
        return false;
    }

    /**
     * Validates the user's registration input
     * @return bool Success status of user's registration data validation
     */
    private function checkRegistrationData()
    {
        // if no registration form submitted: exit the method
        if (!isset($_POST["register"])) {
            return false;
        }

        // validating the input
        if (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && strlen($_POST['user_password_new']) >= 6
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            // only this case return true, only this case is valid
            return true;
        } elseif (empty($_POST['user_name'])) {
            $this->feedback = "Empty Username";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->feedback = "Empty Password";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->feedback = "Password and password repeat are not the same";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->feedback = "Password has a minimum length of 6 characters";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->feedback = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (empty($_POST['user_email'])) {
            $this->feedback = "Email cannot be empty";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->feedback = "Email cannot be longer than 64 characters";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->feedback = "Your email address is not in a valid email format";
        } else {
            $this->feedback = "An unknown error occurred.";
        }

        // default return
        return false;
    }

    /**
     * Creates a new user.
     * @return bool Success status of user registration
     */
    private function createNewUser()
    {
        // remove html code etc. from username and email
        $user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
        $user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
        $user_password = $_POST['user_password_new'];
        // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 char hash string.
        // the constant PASSWORD_DEFAULT comes from PHP 5.5 or the password_compatibility_library
        $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

        $sql = 'SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email';
        $query = $this->db_connection->prepare($sql);
        $query->bindValue(':user_name', $user_name);
        $query->bindValue(':user_email', $user_email);
        $query->execute();

        // As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
        // If you meet the inventor of PDO, punch him. Seriously.
        $result_row = $query->fetchObject();
        if ($result_row) {
            $this->feedback = "Sorry, that username / email is already taken. Please choose another one.";
        } else {
            $sql = 'INSERT INTO users (user_name, user_password_hash, user_email)
                    VALUES(:user_name, :user_password_hash, :user_email)';
            $query = $this->db_connection->prepare($sql);
            $query->bindValue(':user_name', $user_name);
            $query->bindValue(':user_password_hash', $user_password_hash);
            $query->bindValue(':user_email', $user_email);
            // PDO's execute() gives back TRUE when successful, FALSE when not
            // @link http://stackoverflow.com/q/1661863/1114320
            $registration_success_state = $query->execute();

            if ($registration_success_state) {
                $this->feedback = "Your account has been created successfully. You can now log in.";
                return true;
            } else {
                $this->feedback = "Sorry, your registration failed. Please go back and try again.";
            }
        }
        // default return
        return false;
    }

    /**
     * Simply returns the current status of the user's login
     * @return bool User's login status
     */
    public function getUserLoginStatus()
    {
        return $this->user_is_logged_in;
    }

    /**
     * Simple demo-"page" that will be shown when the user is logged in.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    private function showPageLoggedIn()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }

if (!empty($_POST['user_name']))
$_SESSION['user_name'] =  $_POST['user_name'];

        echo 'Hello ' . $_SESSION['user_name'] . ', you are logged in.<br/><br/>';
        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=logout">Log out</a>';
    


?>


<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Lynx_Lynx" />

	<title>RCM ADMINTOOL</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" charset="utf-8"></script>
 
<style type="text/css">
h3 {
    margin-top: 50px;
}
.tooltip {
    position: relative;
    background: #eaeaea;
    cursor: help;
    display: inline-block;
    text-decoration: none;
    color: #222;
    outline: none;
    text-indent: 0;
    padding: 0 3px;
}
.tooltip:hover:before {
    content: attr(data-title);
    white-space: pre-line;
    position: absolute;
    bottom: 30px;
    left: 50%;
    z-index: 9999;
    width: 230px;
    margin-left: -127px;
    padding: 10px;
    border: 2px solid #ccc;
    opacity: .9;
    background-color: #ddd;
    background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    -moz-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    text-shadow: 0 1px 0 rgba(255,255,255,.4);
}
.tooltip:hover:after {
    content: "";
    position: absolute;
    z-index: 10000;
    bottom: 24px;
    left: 50%;
    margin-left: -8px;
    border-top: 8px solid #ddd;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 0;
}
/* Yellow */
.yellow-tooltip:hover:before {
    border-color: #e1ca82;
    background-color: #ffeaa6;
}
.yellow-tooltip:hover:after {
    border-top-color: #ffeaa6;
}
</style>



<style>
 /*demo page style*/
    body {background:url(data:image/png;base64,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);
}
    h1{
      font-family:"Myriad Pro","Helvetica Neue",Helvetica,Arial,Sans-Serif;
      text-shadow: rgba(0, 0, 0, 0.498039) 0px 1px 1px;
    color: #cc0033;
      /* The logo text */
      font-size:3.5em;
      padding:0.5em 0 0;
      text-transform:uppercase;
        margin:0; padding:0;
    text-align: left;
    }
     
    p {line-height:1.5em; padding-bottom:1em;}
     
    #wrapper {
        width:810px;
        margin:0 auto;
        padding: 0;
    }
     
    #header {height: 150px;}
     
    #logo {height: 100px;}
     
/*main menu*/
    #menu {
        text-transform: uppercase;
        text-align: center;
        line-height: 50px;
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,0.9) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0.9)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
border: 1px solid #DDD;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
-webkit-box-shadow:1px 2px 8px #9A9A9A;
-moz-box-shadow:1px 2px 8px #9A9A9A;
 
    }
    #menu ul {padding:0; margin:0;}
    #menu li{
        display: inline;
        list-style:none;
        margin: 5px 10px;
    font-size: 1.5em;
    }
     
    #menu li a {
        padding:5px 10px;
        color:#bbb;
        text-decoration: none;
        text-shadow: rgba(0, 0, 0, 0.6) 0px 1px 1px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
 
    }
     
    #menu li a:hover{
        background: #eee;
        color: #C03;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-ms-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-o-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
        -webkit-transition-property: color, background;
        -webkit-transition-duration: 0.5s, 0.5s;
    }
    .default {
        width:810px;
    }
    .fixed {
        position:fixed;
        top:-5px; left:0;
        width:100%;
        padding:10px 0;
         
        -moz-box-shadow: 5px 5px 20px #333;
        -webkit-box-shadow: 5px 5px 20px #333;
        box-shadow: 5px 5px 20px #333;
    }
    .transbg {
       background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        
        var $menu = $("#menu");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });//scroll
        
        $menu.hover(
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).removeClass('transbg');
                }
            },
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).addClass('transbg');
                }
            });//hover
    });//jQuery
</script>
<style>
	.modalDialog {
		position: fixed;
		font-family: Arial, Helvetica, sans-serif;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0,0,0,0.8);
		z-index: 99999;
		-webkit-transition: opacity 400ms ease-in;
		-moz-transition: opacity 400ms ease-in;
		transition: opacity 400ms ease-in;
		display: none;
		pointer-events: none;
	}

	.modalDialog:target {
		display: block;
		pointer-events: auto;
	}

	.modalDialog > div {
		width: 400px;
		position: relative;
		margin: 10% auto;
		padding: 5px 20px 13px 20px;
		border-radius: 10px;
		background: #fff;
		background: -moz-linear-gradient(#fff, #999);
		background: -webkit-linear-gradient(#fff, #999);
		background: -o-linear-gradient(#fff, #999);
	}

	.close {
		background: #606061;
		color: #FFFFFF;
		line-height: 25px;
		position: absolute;
		right: -12px;
		text-align: center;
		top: -10px;
		width: 24px;
		text-decoration: none;
		font-weight: bold;
		-webkit-border-radius: 12px;
		-moz-border-radius: 12px;
		border-radius: 12px;
		-moz-box-shadow: 1px 1px 3px #000;
		-webkit-box-shadow: 1px 1px 3px #000;
		box-shadow: 1px 1px 3px #000;
	}

	.close:hover { background: #00d9ff; }


/*Всплывающий блок CSS*/
.thumbnail_my{
position: relative;
z-index: 0;
}
.thumbnail_my:hover{
background-color: transparent;
z-index: 70;
}
.thumbnail_my span{ /*CSS for enlarged image*/
position: absolute;
background-color: #3d3d3d;
padding: 15px;
left: 1200px;
border: 1px solid white;
visibility: hidden;
color: Yellow;
text-decoration: none;
border-radius: 4px 4px 4px 4px;
-moz-border-radius: 4px 4px 4px 4px;
-webkit-border-radius: 4px 4px 4px 4px;
}
.thumbnail_my span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 20px;
}
.thumbnail_my:hover span{ /*CSS for enlarged image on hover*/
visibility: visible;
top: 0;
left: -240px; /*position where enlarged image should offset horizontally */
}
/*---------------------*/
</style>		
</head>
<body>



<?php












include("zzz_add_here_server_configurations.php"); 


function hx($sc)
{
    $sc = str_replace(array(
        "index.php"
    ), '', $sc);
    return $sc . "";
}
$x_ff = 0;
$cpath = hx(__FILE__);

error_reporting(E_ALL);
ini_set('display_errors','On');


$fxx = 0;

function colorx($string) {
$string = substr($string, 0, 777);
$string = str_replace("^^00", "</font><font color=\"gray\">", $string);
$string = str_replace("^^11", "</font><font color=\"red\">", $string);
$string = str_replace("^^22", "</font><font color=\"lime\">", $string);
$string = str_replace("^^33", "</font><font color=\"yellow\">", $string);
$string = str_replace("^^44", "</font><font color=\"blue\">", $string);
$string = str_replace("^^55", "</font><font color=\"aqua\">", $string);
$string = str_replace("^^66", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^^77", "</font><font color=\"white\">", $string);
$string = str_replace("^^88", "</font><font color=\"pink\">", $string);
$string = str_replace("^^99", "</font><font color=\"#ddd\">", $string);
$string = str_replace("^^00", "</font><font color=\"gray\">", $string);
$string = str_replace("^^1", "</font><font color=\"red\">", $string);
$string = str_replace("^^2", "</font><font color=\"lime\">", $string);
$string = str_replace("^^3", "</font><font color=\"yellow\">", $string);
$string = str_replace("^^4", "</font><font color=\"blue\">", $string);
$string = str_replace("^^5", "</font><font color=\"aqua\">", $string);
$string = str_replace("^^6", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^^7", "</font><font color=\"white\">", $string);
$string = str_replace("^^8", "</font><font color=\"pink\">", $string);
$string = str_replace("^^9", "</font><font color=\"#ddd\">", $string);
$string = str_replace("^11", "</font><font color=\"red\">", $string);
$string = str_replace("^22", "</font><font color=\"lime\">", $string);
$string = str_replace("^33", "</font><font color=\"yellow\">", $string);
$string = str_replace("^44", "</font><font color=\"blue\">", $string);
$string = str_replace("^55", "</font><font color=\"aqua\">", $string);
$string = str_replace("^66", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^77", "</font><font color=\"white\">", $string);
$string = str_replace("^88", "</font><font color=\"pink\">", $string);
$string = str_replace("^99", "</font><font color=\"#ddd\">", $string);
$string = str_replace("^0", "</font><font color=\"gray\">", $string);
$string = str_replace("^1", "</font><font color=\"red\">", $string);
$string = str_replace("^2", "</font><font color=\"lime\">", $string);
$string = str_replace("^3", "</font><font color=\"yellow\">", $string);
$string = str_replace("^4", "</font><font color=\"blue\">", $string);
$string = str_replace("^5", "</font><font color=\"aqua\">", $string);
$string = str_replace("^6", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^7", "</font><font color=\"white\">", $string);
$string = str_replace("^8", "</font><font color=\"pink\">", $string);
$string = str_replace("^9", "</font><font color=\"#ddd\">", $string);
//$string = str_replace("^", "", $string);
return $string . "</font>";
}
////////////////////////////////////////////////////////////////
// do not modify below here

usleep(998000);


/* - GEO - */
/* - SX GEO - */
include("SxGeo.php"); 
?>


<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h1>RCM Web Admintool</h1>
        </div>
         
<?php
include('menux.php');
?>
           
    </div>
    <div id="content">
<?php









$connect = fsockopen("udp://" .$server_ip, $server_port, $errno, $errstr, 30); 

// Set the timeout: 

//socket_set_timeout ($connect, 1, 000000); 
stream_set_timeout ($connect, 0, 10000); //1e5


// Get the information from the server, and put it into the $output array: 

$send = "\xFF\xFF\xFF\xFFgetstatus\x00";  

fputs($connect, $send); 
fwrite ($connect, $send); 

$output = fread ($connect, 1); 
if (! empty ($output)) { 
   do { 

     $status_pre = socket_get_status ($connect); 
     $output = $output . fread ($connect, 1); 
     $status_post = socket_get_status ($connect); 

   } 
   while ($status_pre["unread_bytes"] != $status_post["unread_bytes"]); 
}; 


// Close the connection: 
fclose($connect); 


// Select the variables from the $output array: 
$output = explode ("\\", $output); 

$max_index = array_search ("sv_maxclients", $output); 
$max_clients = $output[$max_index+1];


$max_index = array_search ("sv_privateClients", $output);

$priv_clients = $output[$max_index+1]; 

$max_index = array_search ("sv_hostname", $output); 

$hostname = $output[$max_index+1]; 

$max_index = array_search ("mapname", $output); 

$mapname = $output[$max_index+1]; 

$max_index = array_search ("al_ver", $output); 

$alba = $output[$max_index+1];   

$max_index = array_search ("g_gametype", $output); 

$gametype = $output[$max_index+1]; 
$ggg = $gametype; 


$last_value = count($output) - 1; 
$players_string = $output[$last_value]; 
$players_string = explode("\"", $players_string); 


$get_first_ping = explode("\n", $players_string[0]); 

$players_string[0] = $get_first_ping[1]; 

$i = 1; 

$players = 0; 
while (count($players_string) != $i) { 
$i++; 

$i++; 
$players++; 
} 


$play3rs = $max_clients - $priv_clients;


$picture_src = "images/" . $mapname . ".jpg"; 

if ($alba == "7.61"){$alba = "+AC alba&emsp;";}

else{$alba = "No AC&emsp;&emsp;";} 

if(!file_exists($picture_src)){$picture_src = "images/non.jpg"; $alba = "Offline";}


$mapname = substr($mapname, 3); 

$mapname = ucwords($mapname);    

 if ($gametype == "sd"){$gametype = "Search&Destroy";} 	

elseif ($gametype == "dm"){$gametype = "Deathmatch";}
elseif ($gametype == "tdm"){$gametype = "Team DeathMatch";}
elseif ($gametype == "bash"){$gametype = "DeathMatch Bash";}

elseif ($gametype == "actf"){$gametype = "Awe Capture The Flag";}
elseif ($gametype == "gun"){$gametype = "GunGame";}      
elseif ($gametype == "htf"){$gametype = "Hol The Flag";}    
elseif ($gametype == "ft_v"){$gametype = "Search&Destroy*";}
elseif ($gametype == "^5sd"){$gametype = "Search&Destroy*";} 
elseif ($gametype == "^9old^5sd"){$gametype = "Search&Destroy";}   

elseif ($gametype == "^3actf"){$gametype = "Capture The Flag";} 
elseif ($gametype == "^6gun"){$gametype = "GunGame";}     
elseif ($gametype == "^2tdm"){$gametype = "Team DeathMatch";}   

elseif ($gametype == "csd"){$gametype = "Pam Search&Destroy";} 
elseif ($gametype == "^3_actf"){$gametype = "Capture The Flag";} 

elseif ($gametype == "^2_tdm"){$gametype = "Team DeathMatch";} 
elseif ($gametype == "^1htf"){$gametype = "Hold The Flag";} 

function colors($string) {
$string = str_replace("^0", "<font color=\"gray\">", $string);
$string = str_replace("^1", "<font color=\"red\">", $string);

$string = str_replace("^2", "<font color=\"lime\">", $string);
$string = str_replace("^3", "<font color=\"yellow\">", $string);

$string = str_replace("^4", "<font color=\"blue\">", $string);

$string = str_replace("^5", "<font color=\"aqua\">", $string);
$string = str_replace("^6", "<font color=\"fuchsia\">", $string);
$string = str_replace("^7", "<font color=\"white\">", $string);
$string = str_replace("^8", "<font color=\"pink\">", $string);
$string = str_replace("^9", "<font color=\"silver\">", $string);
return $string . "</font>";
}

echo '<table border="0" align="center" cellpadding="0" cellspacing="0" width="812">';
echo "<tr style=\"background:#727272; border:4px solid #CCC;\">
<td class='ccccc'>";
echo '<center><font size="4" background-color="#333"><b>'.colors(str_replace("\"", "",substr($hostname, 0, 70))).'</b>   &emsp;|||&emsp; <b>                       

<!--Ссылка с текстом HTML:-->
<a class="thumbnail_my" href="#">'.$mapname.'<span>'.$gametype.'<img alt="'.$picture_src.'" src="'.$picture_src.'" width="200" /></span></a>

 </b>&emsp;|||&emsp; Players: <b>'.$players.'</b><b>/'.$play3rs.'</b>  </font></center>';
echo "</td></tr></table>";


echo "
<table align='center'><td> 
<table><tr style=\"background:#727272; border:4px solid #CCC;\"></font></td>


<td class='ccccc'><font color='yellow'><center><b>Kick</b></center></font></td>
 
<td class='ccccc'><font color='#FFE203'><center><b>Ban</b></center></font></td>
<td class='ccccc'><font color='#FFD903'><center><b>Range</b></center></font></td>

<td><font color='white'>N</font></td>
<td><font color='white'>City</font></td>
<td><font color='black'><center><b>
";
echo '<left><a href="#openModal9fmmd"><img class="flag" src="icons/balloons-white.png" height="20px" width="20px"></a></left>';
echo "
Playername</b></center></font></td>
<td><font color='green'><center><b>IP</b></center></font></td> 
<td class='ccccc'><font color='#FFAA0D'><center><b>Ping</b></center></font></td>

</tr>



";







 
echo '
<div id="openModal9fmmd" class="modalDialog">
	<div>
<a href="#close" title="Закрыть" class="close">X</a>
<center>
<h2>Say meassage to all!</h2>
<form action="1_messageall.php" method="get">
  <input type="hidden" name="whoo" value="'.$_SESSION['user_name'].'"/>
  <input type="text" name="msgg"/>
 <p><input type="submit" /></p>
</form>
</center>
</div>
</div>';












$server_addr = "udp://" . $server_ip;
@$connect = fsockopen($server_addr, $server_port, $re, $errstr, 30);
if (!$connect) { die('Can\'t connect to COD gameserver.'); }
//socket_set_timeout ($connect, 1, 000000);  // bylo2
stream_set_timeout ($connect, 0, 37000); //1e5
$send = "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" status';
fwrite($connect, $send);
$output = fread ($connect, 1);
if (!empty($output)) {
do {
$status_pre = socket_get_status ($connect);
$output = $output . fread ($connect, 1024);  //1024
$status_post = socket_get_status ($connect);
} while ($status_pre['unread_bytes'] != $status_post['unread_bytes']);
};

$output = explode ("\xff\xff\xff\xffprint\n", $output);
$output = implode ('!', $output);
$output = explode ("\n",$output);


function rcon($s, $replace='')
	{
	global $connect, $server_rconpass;
	fwrite($connect, "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" '.strtr($s,array('%s'=>$replace)));
	$output = fread ($connect, 512);
    usleep(500*1000);
    return $output;
	}

// var_dump(rcon('pb_sv_ver'));
	
function AddToLog($s)
	{
	global $rules_log_file;
	$fp = fopen($rules_log_file, 'a');
    fwrite($fp, $s."\n");
    fclose($fp);
	}

function stringrpos($haystack,$needle,$offset=NULL)
	{
	   if (strpos($haystack,$needle,$offset) === FALSE)
	     return FALSE;

	   return strlen($haystack)
	           - strpos( strrev($haystack) , strrev($needle) , $offset)
	           - strlen($needle);
	}

function GetPlainName($name) // copied from original - IPluginCOD.php
	{

		$repname = $name;

		for($y=0; $y < 2; $y++) // Loop around a few time in case we have embedded colors!
		{
			for($x=0; $x < 10; $x++)
			{
				$repname = str_replace("^$x","",$repname);
			}
		}
		return $repname;
	}
$datetime = date('Y-m-d H:i:s');
 
$player_cnt = count($output);
$rec_ok = ($player_cnt>3);

// smazeme z vystupu nepotrebne radky
unset($output[0],$output[1],$output[2],$output[$player_cnt-2],$output[$player_cnt-1]);
$output = array_values($output);
$player_cnt = count($output);




for ($i=0; $i<$player_cnt; $i++)
	{
if ($game_patch == 'cod1_1.1')
			$i_name1c = 22;
else if ($game_patch == 'cod4')
                        $i_name1c = 47;
else
		        $i_name1c = 22;

if((empty($output[$i])) || ( $server_rconpass == 'my_rcon_password'))
{

$output[$i] = 0;
$player_cnt = 2;
$i_id = '';
$i_ping = '';
$i_ip = '';
$i_name = '';

$erroor = 'ERROR001';
//echo $erroor;
}else{

	$i_rcon_string = $output[$i];
	if ($i_rcon_string != '')
		{
		$pat[0] = "/^\s+/";
		$pat[1] = "/\s{2,}/";
		$pat[2] = "/\s+\$/";
		$rep[0] = "";
		$rep[1] = " ";
		$rep[2] = "";
		  $i_rcon_string = preg_replace($pat,$rep,$i_rcon_string);

		unset($tmp2);
        if (preg_match("/[[:space:]][0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/",  $i_rcon_string, $tmp2))
            {
            $i_ip = substr($tmp2[0],1);
			} else $i_ip = '';

if ($game_patch == 'cod1_1.1')
		$i_rcon_string = explode(' ', $i_rcon_string, 15);
else
                $i_rcon_string = explode(' ', $i_rcon_string, 5); 
        
$i_id = $i_rcon_string[0];
if (!empty($i_rcon_string[2]))
{
$i_ping = $i_rcon_string[2];
$piing = $i_ping;
}
else{ 
$i_ping = '001';}
		
if ($game_patch == 'cod1_1.1')
{

if(empty($i_rcon_string[4]))
$i_rcon_string[4] = '';
 if(empty($i_rcon_string[5]))
$i_rcon_string[5] = '';
 if(empty($i_rcon_string[6]))
$i_rcon_string[6] = '';
 if(empty($i_rcon_string[7]))
$i_rcon_string[7] = '';
 if(empty($i_rcon_string[8]))
$i_rcon_string[8] = '';
 if(empty($i_rcon_string[9]))
$i_rcon_string[9] = '';
 if(empty($i_rcon_string[10]))
$i_rcon_string[10] = '';
 if(empty($i_rcon_string[11]))
$i_rcon_string[11] = '';


$i_ping = $i_rcon_string[3].' '.$i_rcon_string[4].' '.$i_rcon_string[5].' '.$i_rcon_string[6].' '.$i_rcon_string[7].' '.$i_rcon_string[8].' '.$i_rcon_string[9].' '.$i_rcon_string[10].' '.$i_rcon_string[11];
 
$fff = preg_replace("/[[:space:]][0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\:/",  '#;#;#', $i_ping);

$i_nam = explode("#;#;#", $fff);
 $i_namea = $i_nam[0];        

}

		$i_name = substr($output[$i], $i_name1c);
		$i_name = preg_replace($pat,$rep,$i_name);
		$vyk = ':';
		$tmp = stringrpos($i_name, $vyk);
		if ($tmp === false)
		    {
			$vyk = '!';
			$tmp = stringrpos($i_name, $vyk);
			  
			  
			  if ($tmp === false)
			 	{
					
				}
				else
				{
					
				if(!empty($output[$i]))
					return;	
					
				}
			
			}



	    $i_name = substr($i_name, 0, $tmp);
	    $i_name = substr($i_name, 0, stringrpos($i_name, ' '));
	    $tmp2 = stringrpos($i_name, ' ');
	    if ($tmp2 !== false)
	    	{$i_name = substr($i_name, 0, $tmp2);}
		$chistx = $i_name;
        $i_name = trim(GetPlainName($i_name));

		}
	
if ($game_patch == 'cod1_1.1')
{
$chistx = $i_namea;
$i_name = trim(GetPlainName($i_namea));
}

$valid_id = is_numeric($i_id);
	
if ($game_patch == 'cod1_1.1')
$valid_ping = !is_numeric($i_ping);
else
$valid_ping = is_numeric($i_ping); 


	
}
++$fxx;
$colorb=$i%2>0? '#a3a3a3':'#c7c7c7';
echo "<tr style=\"background:$colorb;\">";
echo "<td class='ccccc'>";

























echo '<center><a href="#openModal'.$i_id.'"><img class="flag" src="icons/stickman-run.png" height="20px" width="20px"></a></center>';

?>

<div id="openModal<?php echo $i_id ?>" class="modalDialog">
	<div>
		<a href="#close" title="Закрыть" class="close">X</a>
<center>
<h2>Kick:  <?php echo colorx($chistx) ?> </h2>
<form action="1_kick.php" method="get">
 <input type="hidden" name="plrname" value="<?php echo $i_name; ?>"/>
  <input type="hidden" name="plrip" value="<?php echo $i_ip; ?>"/>
  <input type="hidden" name="plrid" value="<?php echo $i_id; ?>"/>
  <input type="hidden" name="whoo" value="<?php echo $_SESSION['user_name']; ?>"/>
 <p><input type="submit" /></p>
</form>
</center>
</div>
</div>



<?php

echo "</td>";
















 


echo "<td class='ccccc'>";
echo '<center><a href="#openModal'.($i_id*9+8729-1+732-33).'"><img class="flag" src="icons/minus-octagon-frame.png" height="20px" width="20px"></a></center>';
echo "</td>";

?>

<div id="openModal<?php echo ($i_id*9+8729-1+732-33) ?>" class="modalDialog">
	<div>
		<a href="#close" title="Закрыть" class="close">X</a>
<center>
<h2>Ban:  <?php echo colorx($chistx) ?> </h2>

<form action="1_ban.php" method="get">
 <input type="hidden" name="plrname" value="<?php echo $i_name; ?>"/>
  <input type="hidden" name="plrip" value="<?php echo $i_ip; ?>"/>
  <input type="hidden" name="plrid" value="<?php echo $i_id; ?>"/>
  <input type="hidden" name="whoo" value="<?php echo $_SESSION['user_name']; ?>"/>
  <p>Reason: <input type="text" name="plreason" /></p>
 <p><input type="submit" /></p>
</form>
</center>
		
	</div>
</div>




<?php














echo "<td class='ccccc'>";

echo '<center><a href="#openModal'.($i_id*7+729-1).'"><img class="flag" src="icons/servers-network.png" height="20px" width="20px"></a></center>';

echo "</td>";



?>

<div id="openModal<?php echo ($i_id*7+729-1) ?>" class="modalDialog">
	<div>
		<a href="#close" title="Закрыть" class="close">X</a>
		


<center>
<h2>Ip Range Ban:  <?php echo colorx($chistx) ?> </h2>

<form action="1_range.php" method="get">
 <input type="hidden" name="plrname" value="<?php echo $i_name; ?>"/>
  <input type="hidden" name="plrip" value="<?php echo $i_ip; ?>"/>
  <input type="hidden" name="plrid" value="<?php echo $i_id; ?>"/>
  <input type="hidden" name="whoo" value="<?php echo $_SESSION['user_name']; ?>"/>
 <p><input type="submit" /></p>
</form>
</center>





	</div>
</div>







<?php
echo "  <td><font color='#555' size='3'><b>".$i_id.".</b></font></td>";
////////////////////////////////////////////////////////////////////////////////////////////////////
$SxGeo = new SxGeo('SxGeoCity.dat');
$country_name = $SxGeo->get($i_ip);
//$city = $SxGeo->get($i_ip);
////////////////////////////////////////////////////////////////////////////////////////////////////
echo "  <td><font color='#555' size='3'><b>"; 
echo '<a href="#" class="tooltip yellow-tooltip"
    data-title="'.ciity($country_name['country']['iso']).'"><img class="flag" src="flags/'.mb_strtolower($country_name['country']['iso']).'.gif" height="30px" width="38px"></a>';

echo "</b></font></td>
";
echo "<td width='350px'>";



echo '
<left><a href="#openModal'.($i_id*7*3+115-1).'"><img class="flag" src="icons/balloons-white.png" height="20px" width="20px"></a></left>';
echo '
<div id="openModal'; echo ($i_id*7*3+115-1);
echo '" class="modalDialog">
	<div>
		<a href="#close" title="Закрыть" class="close">X</a>
<center>
<h2>Say message: '.colorx($chistx).' </h2>
<form action="1_message.php" method="get">
  <input type="hidden" name="plrname" value="'.$i_name.'"/>
  <input type="hidden" name="plrip" value="'.$i_ip.'"/>
  <input type="hidden" name="plrid" value="'.$i_id.'"/>
  <input type="hidden" name="whoo" value="'.$_SESSION['user_name'].'"/>
  <input type="text" name="msgg"/>
 <p><input type="submit" /></p>
</form>
</center>
</div>
</div>';

if ($game_patch == 'cod1_1.1')
$i_ping = $piing;

echo"
 &emsp;
<font color='white' size='3'>".colorx($chistx)."
</font></td>";
echo "<td class='ccccc'><font color='green' size='3'>&emsp;<b>".$i_ip."</b>&emsp;</font></td>
<td class='ccccc'><font color='yellow' size='3'>&emsp;<b>".$i_ping."</b>&emsp;</font></td>
</tr>";
 
	}

echo '</table> 
 </td></table>'; 
 
fclose($connect);
?>
    </div>
</div>
</body>
</html>


<?php
}

    


/**
     * Simple demo-"page" with the login form.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    private function showPageLoginForm()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }


        echo '<div id="wrapper">
    <div id="header"><div id="logo">
            <h1>RCM Web Admintool</h1>
        </div>';

        echo '<h2>Login</h2>';

        echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="loginform">';
        echo '<label for="login_input_username">Username (or email)</label> ';
        echo '<input id="login_input_username" type="text" name="user_name" required /> ';
        echo '<label for="login_input_password">Password</label> ';
        echo '<input id="login_input_password" type="password" name="user_password" required /> ';
        echo '<input type="submit"  name="login" value="Log in" />';
        echo '</form>';
 echo '</div></div>';
?>
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Lynx_Lynx" />

	<title>RCM ADMINTOOL</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" charset="utf-8"></script>
 
<style type="text/css">
h3 {
    margin-top: 50px;
}
.tooltip {
    position: relative;
    background: #eaeaea;
    cursor: help;
    display: inline-block;
    text-decoration: none;
    color: #222;
    outline: none;
    text-indent: 0;
    padding: 0 3px;
}
.tooltip:hover:before {
    content: attr(data-title);
    white-space: pre-line;
    position: absolute;
    bottom: 30px;
    left: 50%;
    z-index: 9999;
    width: 230px;
    margin-left: -127px;
    padding: 10px;
    border: 2px solid #ccc;
    opacity: .9;
    background-color: #ddd;
    background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    -moz-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    text-shadow: 0 1px 0 rgba(255,255,255,.4);
}
.tooltip:hover:after {
    content: "";
    position: absolute;
    z-index: 10000;
    bottom: 24px;
    left: 50%;
    margin-left: -8px;
    border-top: 8px solid #ddd;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 0;
}
/* Yellow */
.yellow-tooltip:hover:before {
    border-color: #e1ca82;
    background-color: #ffeaa6;
}
.yellow-tooltip:hover:after {
    border-top-color: #ffeaa6;
}
</style>



<style>
 /*demo page style*/
    body {background:url(data:image/png;base64,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);
}
    h1{
      font-family:"Myriad Pro","Helvetica Neue",Helvetica,Arial,Sans-Serif;
      text-shadow: rgba(0, 0, 0, 0.498039) 0px 1px 1px;
    color: #cc0033;
      /* The logo text */
      font-size:3.5em;
      padding:0.5em 0 0;
      text-transform:uppercase;
        margin:0; padding:0;
    text-align: left;
    }
     
    p {line-height:1.5em; padding-bottom:1em;}
     
    #wrapper {
        width:810px;
        margin:0 auto;
        padding: 0;
    }
     
    #header {height: 150px;}
     
    #logo {height: 100px;}
     
/*main menu*/
    #menu {
        text-transform: uppercase;
        text-align: center;
        line-height: 50px;
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,0.9) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0.9)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
border: 1px solid #DDD;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
-webkit-box-shadow:1px 2px 8px #9A9A9A;
-moz-box-shadow:1px 2px 8px #9A9A9A;
 
    }
    #menu ul {padding:0; margin:0;}
    #menu li{
        display: inline;
        list-style:none;
        margin: 5px 10px;
    font-size: 1.5em;
    }
     
    #menu li a {
        padding:5px 10px;
        color:#bbb;
        text-decoration: none;
        text-shadow: rgba(0, 0, 0, 0.6) 0px 1px 1px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
 
    }
     
    #menu li a:hover{
        background: #eee;
        color: #C03;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-ms-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-o-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
        -webkit-transition-property: color, background;
        -webkit-transition-duration: 0.5s, 0.5s;
    }
    .default {
        width:810px;
    }
    .fixed {
        position:fixed;
        top:-5px; left:0;
        width:100%;
        padding:10px 0;
         
        -moz-box-shadow: 5px 5px 20px #333;
        -webkit-box-shadow: 5px 5px 20px #333;
        box-shadow: 5px 5px 20px #333;
    }
    .transbg {
       background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        
        var $menu = $("#menu");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });//scroll
        
        $menu.hover(
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).removeClass('transbg');
                }
            },
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).addClass('transbg');
                }
            });//hover
    });//jQuery
</script>
<style>
	.modalDialog {
		position: fixed;
		font-family: Arial, Helvetica, sans-serif;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0,0,0,0.8);
		z-index: 99999;
		-webkit-transition: opacity 400ms ease-in;
		-moz-transition: opacity 400ms ease-in;
		transition: opacity 400ms ease-in;
		display: none;
		pointer-events: none;
	}

	.modalDialog:target {
		display: block;
		pointer-events: auto;
	}

	.modalDialog > div {
		width: 400px;
		position: relative;
		margin: 10% auto;
		padding: 5px 20px 13px 20px;
		border-radius: 10px;
		background: #fff;
		background: -moz-linear-gradient(#fff, #999);
		background: -webkit-linear-gradient(#fff, #999);
		background: -o-linear-gradient(#fff, #999);
	}

	.close {
		background: #606061;
		color: #FFFFFF;
		line-height: 25px;
		position: absolute;
		right: -12px;
		text-align: center;
		top: -10px;
		width: 24px;
		text-decoration: none;
		font-weight: bold;
		-webkit-border-radius: 12px;
		-moz-border-radius: 12px;
		border-radius: 12px;
		-moz-box-shadow: 1px 1px 3px #000;
		-webkit-box-shadow: 1px 1px 3px #000;
		box-shadow: 1px 1px 3px #000;
	}

	.close:hover { background: #00d9ff; }


/*Всплывающий блок CSS*/
.thumbnail_my{
position: relative;
z-index: 0;
}
.thumbnail_my:hover{
background-color: transparent;
z-index: 70;
}
.thumbnail_my span{ /*CSS for enlarged image*/
position: absolute;
background-color: #3d3d3d;
padding: 15px;
left: 1200px;
border: 1px solid white;
visibility: hidden;
color: Yellow;
text-decoration: none;
border-radius: 4px 4px 4px 4px;
-moz-border-radius: 4px 4px 4px 4px;
-webkit-border-radius: 4px 4px 4px 4px;
}
.thumbnail_my span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 20px;
}
.thumbnail_my:hover span{ /*CSS for enlarged image on hover*/
visibility: visible;
top: 0;
left: -240px; /*position where enlarged image should offset horizontally */
}
/*---------------------*/
</style>		
</head>
<body>
<?php
    
}

    /**
     * Simple demo-"page" with the registration form.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    private function showPageRegistration()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }

        echo '<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h1>RCM Web Admintool</h1>
        </div>';
        echo '<h2>Registration</h2>';

        echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?action=register" name="registerform">';
        echo '</br><label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>';
        echo '<input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />';
        echo '</br><label for="login_input_email">User\'s email</label>';
        echo '<input id="login_input_email" type="email" name="user_email" required />';
        echo '</br><label for="login_input_password_new">Password (min. 6 characters)</label>';
        echo '<input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />';
        echo '</br><label for="login_input_password_repeat">Repeat password</label>';
        echo '<input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />';
        echo '</br><input type="submit" name="register" value="Register" />';
        echo '</form>';

        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '">Homepage</a></div></div>';
    
?>

<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Lynx_Lynx" />

	<title>RCM ADMINTOOL</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" charset="utf-8"></script>
 
<style type="text/css">
h3 {
    margin-top: 50px;
}
.tooltip {
    position: relative;
    background: #eaeaea;
    cursor: help;
    display: inline-block;
    text-decoration: none;
    color: #222;
    outline: none;
    text-indent: 0;
    padding: 0 3px;
}
.tooltip:hover:before {
    content: attr(data-title);
    white-space: pre-line;
    position: absolute;
    bottom: 30px;
    left: 50%;
    z-index: 9999;
    width: 230px;
    margin-left: -127px;
    padding: 10px;
    border: 2px solid #ccc;
    opacity: .9;
    background-color: #ddd;
    background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    -moz-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    text-shadow: 0 1px 0 rgba(255,255,255,.4);
}
.tooltip:hover:after {
    content: "";
    position: absolute;
    z-index: 10000;
    bottom: 24px;
    left: 50%;
    margin-left: -8px;
    border-top: 8px solid #ddd;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 0;
}
/* Yellow */
.yellow-tooltip:hover:before {
    border-color: #e1ca82;
    background-color: #ffeaa6;
}
.yellow-tooltip:hover:after {
    border-top-color: #ffeaa6;
}
</style>



<style>
 /*demo page style*/
    body {background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAADlJREFUeNpivHHjxn8JCQkGGHjx4gUDMp9JQkKC4cWLF3ABdD4TNkFkPhMunTA+Ez7jJSQk6OEGwAC5GzDfgQk5bwAAAABJRU5ErkJggg==);
}
    h1{
      font-family:"Myriad Pro","Helvetica Neue",Helvetica,Arial,Sans-Serif;
      text-shadow: rgba(0, 0, 0, 0.498039) 0px 1px 1px;
    color: #cc0033;
      /* The logo text */
      font-size:3.5em;
      padding:0.5em 0 0;
      text-transform:uppercase;
        margin:0; padding:0;
    text-align: left;
    }
     
    p {line-height:1.5em; padding-bottom:1em;}
     
    #wrapper {
        width:810px;
        margin:0 auto;
        padding: 0;
    }
     
    #header {height: 150px;}
     
    #logo {height: 100px;}
     
/*main menu*/
    #menu {
        text-transform: uppercase;
        text-align: center;
        line-height: 50px;
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,0.9) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0.9)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
border: 1px solid #DDD;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
-webkit-box-shadow:1px 2px 8px #9A9A9A;
-moz-box-shadow:1px 2px 8px #9A9A9A;
 
    }
    #menu ul {padding:0; margin:0;}
    #menu li{
        display: inline;
        list-style:none;
        margin: 5px 10px;
    font-size: 1.5em;
    }
     
    #menu li a {
        padding:5px 10px;
        color:#bbb;
        text-decoration: none;
        text-shadow: rgba(0, 0, 0, 0.6) 0px 1px 1px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
 
    }
     
    #menu li a:hover{
        background: #eee;
        color: #C03;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-ms-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-o-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
        -webkit-transition-property: color, background;
        -webkit-transition-duration: 0.5s, 0.5s;
    }
    .default {
        width:810px;
    }
    .fixed {
        position:fixed;
        top:-5px; left:0;
        width:100%;
        padding:10px 0;
         
        -moz-box-shadow: 5px 5px 20px #333;
        -webkit-box-shadow: 5px 5px 20px #333;
        box-shadow: 5px 5px 20px #333;
    }
    .transbg {
       background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        
        var $menu = $("#menu");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });//scroll
        
        $menu.hover(
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).removeClass('transbg');
                }
            },
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).addClass('transbg');
                }
            });//hover
    });//jQuery
</script>
<style>
	.modalDialog {
		position: fixed;
		font-family: Arial, Helvetica, sans-serif;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0,0,0,0.8);
		z-index: 99999;
		-webkit-transition: opacity 400ms ease-in;
		-moz-transition: opacity 400ms ease-in;
		transition: opacity 400ms ease-in;
		display: none;
		pointer-events: none;
	}

	.modalDialog:target {
		display: block;
		pointer-events: auto;
	}

	.modalDialog > div {
		width: 400px;
		position: relative;
		margin: 10% auto;
		padding: 5px 20px 13px 20px;
		border-radius: 10px;
		background: #fff;
		background: -moz-linear-gradient(#fff, #999);
		background: -webkit-linear-gradient(#fff, #999);
		background: -o-linear-gradient(#fff, #999);
	}

	.close {
		background: #606061;
		color: #FFFFFF;
		line-height: 25px;
		position: absolute;
		right: -12px;
		text-align: center;
		top: -10px;
		width: 24px;
		text-decoration: none;
		font-weight: bold;
		-webkit-border-radius: 12px;
		-moz-border-radius: 12px;
		border-radius: 12px;
		-moz-box-shadow: 1px 1px 3px #000;
		-webkit-box-shadow: 1px 1px 3px #000;
		box-shadow: 1px 1px 3px #000;
	}

	.close:hover { background: #00d9ff; }


/*Всплывающий блок CSS*/
.thumbnail_my{
position: relative;
z-index: 0;
}
.thumbnail_my:hover{
background-color: transparent;
z-index: 70;
}
.thumbnail_my span{ /*CSS for enlarged image*/
position: absolute;
background-color: #3d3d3d;
padding: 15px;
left: 1200px;
border: 1px solid white;
visibility: hidden;
color: Yellow;
text-decoration: none;
border-radius: 4px 4px 4px 4px;
-moz-border-radius: 4px 4px 4px 4px;
-webkit-border-radius: 4px 4px 4px 4px;
}
.thumbnail_my span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 20px;
}
.thumbnail_my:hover span{ /*CSS for enlarged image on hover*/
visibility: visible;
top: 0;
left: -240px; /*position where enlarged image should offset horizontally */
}
/*---------------------*/
</style>		
</head>
<body>

<?php



}
}



// run the application
$application = new OneFileLoginApplication();
?>
