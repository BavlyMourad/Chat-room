<?php

	ob_start();
	session_start();

	if(!isset($_SESSION['Username'])) {
		header('Location: index.php');
		exit();
	}

	include("init.php");

?>
	
	<div class="chat-container">
		<div class="chat-box">
			<a href="logout.php">Leave room</a>
			<h1>Messages for <?php echo date('l, F j') ?></h1>

			<div id="chat"></div>

		</div>
		
		<?php		
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
				$userid = $_SESSION['ID'];
				$messageDate = date('Y-m-d');
				$messageTime = date('H:i:s');

				//Check if the message is empty or contains white spaces only
				if(empty($message) || ctype_space($message)) {
					echo '<center><h3 class="error">You must type something...</h3></center>';
				}
				else {
					$messageError = ''; //Error doesn't exist
				}

				if(isset($messageError)) {
					$stmt = $connect->prepare("INSERT INTO chat(Message,User_ID,Message_Date,Message_Time) VALUES(?,?,?,?)");
					$stmt->execute(array($message,$userid,$messageDate,$messageTime));
					$count = $stmt->rowCount();
				}

			}
		?>

		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<textarea name="message" placeholder="Message" required="required"></textarea>
			<input type="submit" value="Send">
		</form>

	</div>	

<?php
	include("includes/footer.php");
	ob_end_flush();