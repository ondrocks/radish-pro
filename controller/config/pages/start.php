<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$_SESSION['EMAIL_ADDRESS'] = $_POST['emailUser'];
	$_SESSION['EMAIL_PASS'] = $_POST['emailPass'];
	$_POST['doTranslate'] == 'on' ? $_SESSION['doTranslate'] = true : $_SESSION['doTranslate'] = false;
}
?>

<h1 class='tacenter'>Settings:</h1>

<table class='settings'>
<form action='/config/' method='POST'>
<tr><td colspan='2'><p><?php echo PText::getString('Import_connections', 
					array(
						"<a href='/config/?page=linkedin'>LinkedIn</a>", 
						"<a href='/config/?page=gmail'>Gmail</a>"));?></p><?php echo PText::getString('Explain_email', array('<a href="https://__url__' . controllerFront::getController() . '/">', '</a>'));?></p></td></tr>
<tr><td><?php echo PText::getString('Email');?></td><td><input name='emailUser' value='<?php if(isset($_SESSION['EMAIL_ADDRESS'])) echo $_SESSION['EMAIL_ADDRESS'];?>'/></td></tr>
<tr><td><?php echo PText::getString('Pass');?></td><td><input name='emailPass' type='password' value='<?php if(isset($_SESSION['EMAIL_PASS'])) echo $_SESSION['EMAIL_PASS'];?>'/></td></tr>
<tr><td><?php echo PText::getString('Email_type');?></td><td><select name='emailType'>
		<option value='gmail'>Gmail</option>
		</select></td></tr>
<tr><td><?php echo PText::getString('doTranslate');?></td><td><input type='checkbox' name='doTranslate' <?php if(isset($_SESSION['doTranslate']) && $_SESSION['doTranslate']) echo 'checked';?>/></td></tr>
<tr><td colspan='2' class='deleteText'><?php echo PText::getString('Delete', array("<a href=''>", "</a>"));?></td></tr>
<tr><td></td><td><input type='submit' value='Send'/></td></tr>
<form>
</table>


