<?php
	ob_start();
	session_start();

	if(isset($_SESSION['Username'])) {
		header("Location: chat.php");
		exit();
	}

	include("init.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
		$confirmPassword = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_STRING);
		$hashedPassword = sha1($password);

		$formErrors = array();

		if(strlen($username) < 3 || ctype_space($username)) {
			$formErrors[] = '<h3 class="error">Username must be aleast 3 characters.</h3>';
		}

		if(strlen($password) < 4 || ctype_space($password)) {
			$formErrors[] = '<h3 class="error">Password must be aleast 4 characters.</h3>';
		}
		elseif($password != $confirmPassword) {
			$formErrors[] = '<h3 class="error">Password fields does not match.</h3>';
		}

		if(empty($formErrors)) {
			$stmt = $connect->prepare("INSERT INTO users(Username,Password) VALUES(?,?)");
			$stmt->execute(array($username,$hashedPassword));
			$count = $stmt->rowCount();

			if($count > 0) {
				header("Location: index.php");
				exit();
			}
		}
		else {
			$stmt = $connect->prepare("SELECT Username FROM users WHERE Username=?");
			$stmt->execute(array($username));
			$count = $stmt->rowCount();

			if($count > 0) {
				$formErrors[] = '<h3 class="error">Username is taken.</h3>';
			}
		}
	}
?>

	<div class="register">
		<div class="container">
			<center>
				<h1>Register to Enter The Chat Room</h1>
				<form action="" method="POST">
					<input type="text" name="username" placeholder="Username" required="required">
					<input type="password" name="password" placeholder="Password" required="required">
					<input type="password" name="confirmPassword" placeholder="Confirm Password" required="required">
					<input type="submit" value="Register">					
				</form>
				<p><a href="index.php">Already have an account?</a></p>

				<?php
					if(!empty($formErrors)) {
						foreach($formErrors as $error) {
							echo $error;
						}
					}
				?>

			</center>
		</div>
	</div>

<?php
	include("includes/footer.php");
	ob_end_flush();