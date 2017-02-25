<?php

/**
 * Example of old php script style
 * Don't do that in 2017, pretty please
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once __DIR__ . '/user.inc';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = $_REQUEST['email'];
	if (empty($email)) {
		$errors['email'][] = 'Email field must not be empty';

	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors['email'][] = 'Email field must be a valid email';

	} elseif (account_already_exists_with_email($email)) {
		$errors['email'][] = 'Email is already registered';
	}

    $name = $_REQUEST['name'];
	if (empty($name)) {
		$errors['name'][] = 'Name must not be empty';
	}

	$password = $_REQUEST['password'];
	if (empty($password)) {
		$errors['password'][] = 'Password cannot be empty';

	} elseif (!password_is_safe($password)) {
		$errors['password'][] = 'Password is not safe enough';
	}

    $passwordAgain = $_REQUEST['password-again'];
	if ($password !== $passwordAgain) {
		$errors['password-again'][] = 'Both passwords must match';
	}

	if (empty($errors)) {
		$id = register_account($email, $password, $name);
		header('Location: /welcome.php/?account-id=' . $id);
		exit();
	}
}

?>
<!DOCTYPE>
<body>
<h1>User register</h1>
<form action="/register.php" method="post">
	<label for="email">Email</label>
	<input id="email" name="email" value="<?php echo $email ?>">
	<?php show_errors($errors, 'email') ?>
	<br>
	<label for="name">Name</label>
	<input id="name" name="name" value="<?php echo $name ?>">
	<?php show_errors($errors, 'name') ?>
	<br>
	<label for="password">Password</label>
	<input id="password" name="password" type="password">
	<?php show_errors($errors, 'password') ?>
	<br>
	<label for="password-again">Repeat password</label>
	<input id="password-again" name="password-again" type="password">
	<?php show_errors($errors, 'password-again') ?>
	<br>
	<input type="submit">
</form>
<style>
	label { display: inline-block; margin: 0 0 1em; min-width: 8em; text-align: right; }
	.errors { color: red; display: inline-block; list-style: none; margin: 0 0 1em; padding: 0 1em 0; vertical-align: top; }
</style>
</body>
<?php

function show_errors($errors, $name)
{
	if (empty($errors[$name])) {
		return;
	}

	?>
	<ul class="errors">
		<?php foreach ($errors[$name] as $description): ?>
			<li><?php echo $description ?></li>
		<?php endforeach ?>
	</ul>
	<?php
}
