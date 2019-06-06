<?php
	ob_start();
	session_start();

	include("init.php");

	$stmt = $connect->prepare("SELECT * FROM chat INNER JOIN users ON chat.User_ID=users.UserID WHERE Message_Date=? ORDER BY Message_Time DESC");
	$stmt->execute(array(date('Y-m-d')));
	$rows = $stmt->fetchAll();

	if($rows > 0) {
		foreach($rows as $row) {
?>

<div class="chat-data">
	<span class="name" style="<?php echo $_SESSION['ID'] == $row['UserID'] ? 'color:#0500a5':'color:#af0000'; ?>"><?php echo $row['Username'] ?>: </span>
	<span class="messages"><?php echo $row['Message'] ?></span>
	<span class="time"><?php echo date("g:i A",strtotime($row['Message_Time'])) ?></span>
</div>

<?php
		}
	}

	include("includes/footer.php");
	ob_end_flush();