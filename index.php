<?php
	ob_start();
	session_start();

	if(isset($_SESSION['Username'])) {
		header("Location: chat.php");
		exit();
	}

	include("init.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$hashedPassword = sha1($password);

		$stmt = $connect->prepare("SELECT * FROM users WHERE Username=? AND Password=? LIMIT 1");
		$stmt->execute(array($username,$hashedPassword));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if($count > 0) {
			$_SESSION['Username'] = $row['Username'];
			$_SESSION['ID'] = $row['UserID'];
			header("Location: chat.php");
			exit();
		}
		else {
			$loginError = '<h3 class="error">Username or Password is wrong.</h3>';
		}
	}
?>

	<div class="login">
		<div class="container">
			<center>
				<h1>Login to Enter The Chat Room</h1>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
					<input type="text" name="username" required="required" placeholder="Username">
					<input type="password" name="password" required="required" placeholder="Password">
					<input type="submit" value="Login">					
				</form>
				<p>Not registered? <a href="register.php">Create an account</a></p>

				<?php
					if(isset($loginError)) {
						echo $loginError;
					}
				?>

			</center>
		</div>
	</div>

<?php
	include("includes/footer.php");
	ob_end_flush();