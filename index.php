<!DOCTYPE html>
<html>

<head>
	<title>Chat - Customer Module</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<?php
include 'loginform.php';

if (isset($_GET['logout'])) {

	//Simple exit message
	$fp = fopen("log.html", 'a');
	fwrite($fp, "<div class='msgln'><i>User " . $_SESSION['name'] . " has left the chat session.</i><br></div>");
	fclose($fp);

	session_destroy();
	header("Location: index.php"); //Redirect the user
}
if (!isset($_SESSION['name'])) {
	loginForm();
} else {
?>
	<div id="wrapper">
		<div id="menu">
			<p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
			<p class="logout"><a id="exit" href="#">Exit Chat</a></p>
			<div style="clear:both"></div>
		</div>

		<div id="chatbox">
			<?php
			if (file_exists("log.html") && filesize("log.html") > 0) {
				$handle = fopen("log.html", "r");
				$contents = fread($handle, filesize("log.html"));
				fclose($handle);
				echo $contents;
			}
			?>
		</div>

		<form name="message" action="">
			<input name="usermsg" type="text" id="usermsg" size="63" autofocus/>
			<input name="submitmsg" type="submit" id="submitmsg" value="Send" />
		</form>

		<form action="upload.php" method="post" enctype="multipart/form-data">
  			Select image to upload:
  			<input type="file" name="fileToUpload" id="fileToUpload">
  			<input type="submit" value="Upload Image" name="submit">
		</form>

	</div>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">
		// jQuery Document
		$(document).ready(function() {
			//If user wants to end session
			$("#exit").click(function() {
				var exit = confirm("Are you sure you want to end the session?");
				if (exit == true) {
					window.location = 'index.php?logout=true';
				}
			});
			$("#submitmsg").click(function() {
				var clientmsg = $("#usermsg").val();
				$("#usermsg").val('');
				$.post("post.php", {
					text: clientmsg
				});
				$("#usermsg").attr("value", "");
				return false;
			});
			function loadLog() {
				reload();
			}
			function reload(){
				var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
					$.ajax({
						url: "log.html",
						cache: true,
						success: function(html) {
							$("#chatbox").html(html); //Insert chat log into the #chatbox div	
							//Auto-scroll			
							var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
							if (newscrollHeight > oldscrollHeight) {
								$("#chatbox").animate({
									scrollTop: newscrollHeight
								}, 1000); //Autoscroll to bottom of div
							}
						},
					});
			}
			setInterval(loadLog, 2500);
		});
	</script>
<?php
}
?>
</body>

</html>