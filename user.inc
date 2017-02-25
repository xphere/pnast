<?php

function database_connection()
{
	return new PDO('mysql:host=127.0.0.1;dbname=profile', 'root', 'root');
}

function find_account($accountId)
{
	$connection = database_connection();

	$statement = $connection->query(sprintf(
		'SELECT * FROM accounts WHERE id = %d LIMIT 1',
		$accountId
	));

	return $statement->fetch();
}

function account_already_exists_with_email($email)
{
	$connection = database_connection();

	$statement = $connection->query(sprintf(
		'SELECT id FROM accounts WHERE email = "%s" LIMIT 1',
		$email
	));

	return $statement->rowCount() > 0;
}

function register_account($email, $password, $name)
{
		$connection = database_connection();

	$connection->exec(sprintf(
		'INSERT INTO accounts SET email="%s", name="%s", password="%s"',
		$email,
		$name,
		$password
	));

	$id = $connection->lastInsertId();

	send_confirmation_email($id, $email, $name);

	return $id;
}

function password_is_safe($password)
{
	return strlen($password) >= 4;
}

function send_confirmation_email($id, $email, $name)
{
	$subject = sprintf(
		'Thanks for registering, %s',
		$name
	);

	$welcomeUrl = sprintf(
		'http://%s/welcome.php/?account-id=%d',
		$_SERVER['HTTP_HOST'],
		$id
	);

	$message = sprintf(
		'Dear %s, we are glad you register in our website, please do visit %s to follow.',
		$name,
		$welcomeUrl
	);	

	mail($email, $subject, $message);
}
