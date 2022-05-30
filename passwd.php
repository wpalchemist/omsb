<?php include 'header.php';
include 'connect.php';
require_once 'paginator.class.php';
require_once 'auth.php'; ?>


<?php if (!sses_running())
	sses_start();

function appLogin($uid, $username, $ulogin){
	$_SESSION['uid'] = $uid;
	$_SESSION['username'] = $username;
	$_SESSION['loggedIn'] = true;

	if (isset($_SESSION['appRememberMeRequested']) && ($_SESSION['appRememberMeRequested'] === true))
	{
		// Enable remember-me
		if ( !$ulogin->SetAutologin($username, true))
			echo "cannot enable autologin<br>";

		unset($_SESSION['appRememberMeRequested']);
	}
	else
	{
		// Disable remember-me
		if ( !$ulogin->SetAutologin($username, false))
			echo 'cannot disable autologin<br>';
	}
}

function appLoginFail($uid, $username, $ulogin){
	// Note, in case of a failed login, $uid, $username or both
	// might not be set (might be NULL).
	echo 'login failed<br>';
}

function appLogout(){
  // When a user explicitly logs out you'll definetely want to disable
  // autologin for the same user. For demonstration purposes,
  // we don't do that here so that the autologin function remains
  // easy to test.
  //$ulogin->SetAutologin($_SESSION['username'], false);

	unset($_SESSION['uid']);
	unset($_SESSION['username']);
	unset($_SESSION['loggedIn']);
}

// Store the messages in a variable to prevent interfering with headers manipulation.
$msg = '';

// This is the action requested by the user
$action = @$_POST['action'];


// must protect against 31336 hax0rz, zomg! (the 6 is on purpose--this won't stop sophisticated hacks)
foreach ($_POST as $post_field) {
	if(strlen($post_field) > 120 )
		die("You submitted a search term that was too long--please alert the web master and include the URL from your browser's location bar.");
}


// This is the first uLogin-specific line in this file.
// We construct an instance and pass a function handle to our
// callback functions (we have just defined 'appLogin' and
// 'appLoginFail' a few lines above).
$ulogin = new uLogin('appLogin', 'appLoginFail');


// First we handle application logic. We make two cases,
// one for logged in users and one for anonymous users.
// We will handle presentation after our logic because what we present is
// also based on the logon state, but the application logic might change whether
// we are logged in or not.

if (isAppLoggedIn()){
	if ($action=='changepasswd') {
#		echo "you can change passwords";
		$uid=$ulogin->Uid($_POST['user'] );
		if ( $ulogin->SetPassword($uid, $_POST['pwd']) ){
			echo "password changed!";
		} else {
			echo "sorry--we couldn't change your password.";
		}


	} else 
	if ($action=='delete')	{	// We've been requested to delete the account

		// Delete account
		if ( !$ulogin->DeleteUser( $_SESSION['uid']) )
			$msg = 'account deletion failure';
		else
			$msg = 'account deleted ok';

		// Logout
		appLogout();
	} else if ($action == 'logout'){ // We've been requested to log out
		// Logout
		appLogout();
		$msg = 'logged out';
	}
} else {
	// We've been requested to log in
	if ($action=='login') {
		// Here we verify the nonce, so that only users can try to log in
		// to whom we've actually shown a login page. The first parameter
		// of Nonce::Verify needs to correspond to the parameter that we
		// used to create the nonce, but otherwise it can be anything
		// as long as they match.
		if (isset($_POST['nonce']) && ulNonce::Verify('login', $_POST['nonce'])){
			// We store it in the session if the user wants to be remembered. This is because
			// some auth backends redirect the user and we will need it after the user
			// arrives back.
      if (isset($_POST['autologin']))
        $_SESSION['appRememberMeRequested'] = true;
      else
        unset($_SESSION['appRememberMeRequested']);

			// This is the line where we actually try to authenticate against some kind
			// of user database. Note that depending on the auth backend, this function might
			// redirect the user to a different page, in which case it does not return.
			$ulogin->Authenticate($_POST['user'],  $_POST['pwd']);
			if ($ulogin->IsAuthSuccess()){
				// Since we have specified callback functions to uLogin,
				// we don't have to do anything here.
			}
		} else
			$msg = 'invalid nonce';

	} else if ($action=='autologin'){	// We were requested to use the remember-me function for logging in.
		// Note, there is no username or password for autologin ('remember me')
		$ulogin->Autologin();
		if (!$ulogin->IsAuthSuccess())
			$msg = 'autologin failure';
		else
			$msg = 'autologin ok';

	} else if ($action=='create'){	// We were requested to try to create a new acount.
		// New account
		if ( !$ulogin->CreateUser( $_POST['user'],  $_POST['pwd']) )
			$msg = 'account creation failure';
		else
			$msg = 'account created';
	}
}

// Now we handle the presentation, based on whether we are logged in or not.
// Nothing fancy, except where we create the 'login'-nonce towards the end
// while generating the login form.

if (isAppLoggedIn() && $_SESSION['username'] == 'morgan'){
	?>
		<?php echo ($msg);?>
		<h2>Change Password</h2>
		<p>Welcome, <?php echo($_SESSION['username']);?>!</p>

	<form action="passwd.php" method="POST" id="login">
		<fieldset>
			<legend>Change Password</legend>
				<li class="whole"><label for="username">Username:</label>
					<input type="text" name="user">
				</li>
				<li class="whole"><label for="password">Password:</label>
					<input type="password" name="pwd">
				</li>
				<input type="hidden" name="action" value="changepasswd">
				<input type="hidden" id="nonce" name="nonce" value="<?php echo ulNonce::Create('login');?>">
				<input type="submit" value="Change Password" class="button">
		</fieldset>
	</form>

	<?php
} else if (isAppLoggedIn() ){
	?>
		<?php echo ($msg);?>
		<h2>Change Password</h2>
		<p>Welcome, <?php echo($_SESSION['username']);?>!</p>

	<form action="passwd.php" method="POST" id="login">
		<fieldset>
			<legend>Change Password</legend>
				<li class="whole"><label for="password">Password:</label>
					<input type="password" name="pwd">
				</li>
				<input type="hidden" name="user" value="<?php echo($_SESSION['username']);?>">
				<input type="hidden" name="action" value="changepasswd">
				<input type="hidden" id="nonce" name="nonce" value="<?php echo ulNonce::Create('login');?>">
				<input type="submit" value="Change Password" class="button">
		</fieldset>
	</form>

	<?php

} else {
?>
	<?php echo ($msg);?>
	<h2>Change Password</h2>
	Sorry--you are not authorized to changed passwords.
<?php
}
?>

<?php include 'footer.php'; ?>
