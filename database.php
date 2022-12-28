<?php

function addProduct()
{

	global $db, $errors;

	$name = e($_POST["name"]);
	$price = e($_POST["price"]);
	$description = e($_POST["description"]);
	$pictureSource = e($_POST["pictureSource"]);
	$stock = e($_POST["stock"]);

	if (empty($name)) {
		array_push($errors, '<span style="color:red"> Please Fill In The Product Name.</span>');
	}
	if (empty($price)) {
		array_push($errors, '<span style="color:red">Please Fill In The Product Price.</span>');
	}
	if (empty($description)) {
		array_push($errors, '<span style="color:red">Please Fill In The Product Description.</span>');
	}
	if (empty($pictureSource)) {
		array_push($errors, '<span style="color:red">Please Upload The Product Image.</span>');
	}
	if (empty($stock)) {
		array_push($errors, '<span style="color:red">Please Fill In Product Stock.</span>');
	}

	if (count($errors) == 0) {
		$query = "INSERT INTO alt_tree_product (name, price, description, pictureSource, stock)
		VALUES ('$name', '$price', '$description', '$pictureSource', '$stock')";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">New product successfully added.</span>');
			header('location: adminHome.php');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	}
}

function addToCart()
{
	global $db, $errors;

	// $user_id = $id;
	$name = e($_POST["name"]);
	$price = e($_POST["price"]);
	$quantity = e($_POST["quantity"]);
	$pictureSource = e($_POST["pictureSource"]);
	$grand_total = ($price * $quantity);

	$query = "INSERT INTO alt_tree_test (name, price, quantity, pictureSource, grand_total)
		VALUES ('$name', '$price', '$quantity', $pictureSource, '$grand_total')";

	if (mysqli_query($db, $query)) {
		array_push($errors, '<span style="color:green">New product successfully added.</span>');
		header('location: shoppingCart.php');
	} else {
		array_push($errors, '<span style="color:red">Please try again.</span>');
	}
}


function register()
{
	global $db, $errors, $username, $email;

	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	$uppercase = preg_match('@[A-Z]@', $password_1);
	$lowercase = preg_match('@[a-z]@', $password_1);
	$number    = preg_match('@[0-9]@', $password_1);
	$specialChars = preg_match('@[^\w]@', $password_1);

	if (empty($username)) {
		array_push($errors, '<span style="color:red"> Please Fill In Your Username.</span>');
	}
	if (empty($email)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Email.</span>');
	}
	if (empty($password_1)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Password.</span>');
	}
	if ($password_1 != $password_2) {
		array_push($errors, '<span style="color:red">Password Entered Does Not Match. Please Try Again.</span>');
	}
	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password_1) < 8) {
		array_push($errors, '<span style = "color:red">Password should be at least 8 characters in length and should include at least one uppercase letter, one number, and one special character.</span>');
	}
	if (!isset($_POST['check'])) {
		array_push($errors, '<span style="color:red">You Must Agree To Terms Before Registering.</span>');
	}

	if (count($errors) == 0) {
		$password = password_hash($password_1, PASSWORD_DEFAULT);

		$query = "INSERT INTO alt_tree_user (username, email, user_type, password, is_approved) 
					  VALUES('$username', '$email', 'user', '$password', 1)";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">New user successfully created. Please log in to enjoy Alt-Tree at its fullest.</span>');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	}
}

function registerAdmin()
{
	global $db, $errors;

	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password  =  e($_POST['password']);
	$firstName  =  e($_POST['first_name']);
	$lastName  =  e($_POST['last_name']);
	$phoneNumber  =  e($_POST['number']);
	$address  =  e($_POST['address']);


	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if (empty($username)) {
		array_push($errors, '<span style="color:red"> Please Fill In Your Username.</span>');
	}
	if (empty($email)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Email.</span>');
	}
	if (empty($firstName)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Email.</span>');
	}
	if (empty($lastName)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Email.</span>');
	}
	if (empty($phoneNumber)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Email.</span>');
	}
	if (empty($address)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Email.</span>');
	}
	if (empty($password)) {
		array_push($errors, '<span style="color:red">Please Fill In Your Password.</span>');
	}
	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
		array_push($errors, '<span style = "color:red">Password should be at least 8 characters in length and should include at least one uppercase letter, one number, and one special character.</span>');
	}

	if (count($errors) == 0) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = "INSERT INTO alt_tree_user (username, email, user_type, password, is_approved, first_name, last_name, phone_number, address) 
					  VALUES('$username', '$email', 'admin', '$password', 1, '$firstName', '$lastName', '$phoneNumber', '$address')";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">New admin successfully created.</span>');
			header('location: adminHome.php');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	}
}

function login()
{
	global $db, $username, $errors;

	$username = e($_POST['username']);
	$password = e($_POST['password']);

	if (empty($username)) {
		array_push($errors, '<span style="color:red">Please Enter Your Username.</span>');
	}
	if (empty($password)) {
		array_push($errors, '<span style="color:red">Please Enter Your Password.</span>');
	}

	if (count($errors) == 0) {
		$password = ($password);

		$query = "SELECT * FROM alt_tree_user WHERE username='$username' LIMIT 1";
		$results = mysqli_query($db, $query);

		$logged_in_user = mysqli_fetch_assoc($results);

		$verify = password_verify($password, $logged_in_user['password']);

		if (mysqli_num_rows($results) == 1 && $verify == 1) {

			if ($logged_in_user['user_type'] == 'admin') {
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: adminHome.php');
			} else {
				if ($logged_in_user['is_approved']) {
					$_SESSION['user'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in";
					header('location: home.php');
				} else {
					array_push($errors, '<span style="color:red">This account is not approved yet. Please wait.</span>');
				}
			}
		} else {
			array_push($errors, '<span style="color:red">Incorrect Username and/or Password. Try Again.</span>');
		}
	}
}

function logoutnow()
{
	session_destroy();
	unset($_SESSION['user']);
	header("Location: index.php");
}

function isLoggedInNow()
{
	if (isset($_SESSION['user'])) {
		return true;
	} else {
		return false;
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin') {
		return true;
	} else {
		return false;
	}
}

function updateProduct()
{
	global $db, $errors;

	$id = e($_POST["id"]);
	$name = e($_POST["name"]);
	$price = e($_POST["price"]);
	$description = e($_POST["description"]);
	$pictureSource = e($_POST["pictureSource"]);
	$stock = e($_POST["stock"]);

	if (!empty($pictureSource)) {
		$query = "UPDATE alt_tree_product SET name='$name', price='$price', description='$description', pictureSource='$pictureSource', stock='$stock' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">New product successfully added.</span>');
			header('location: editProductSelect.php');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else {
		$query = "UPDATE alt_tree_product SET name='$name', price='$price', description='$description', stock='$stock' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">New product successfully added.</span>');
			header('location: editProductSelect.php');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	}
}

function updateUser()
{
	global $db, $errors;

	$id = e($_POST["id"]);
	$username = e($_POST["username"]);
	$password = e($_POST["password"]);
	$email = e($_POST["email"]);
	$firstName = e($_POST["first_name"]);
	$lastName = e($_POST["last_name"]);
	$userType = e($_POST["user_type"]);
	$phoneNumber = e($_POST["phone_number"]);
	$address = e($_POST["address"]);
	$pictureSource = e($_POST["pictureSource"]);

	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
		array_push($errors, '<span style = "color:red">Password should be at least 8 characters in length and should include at least one uppercase letter, one number, and one special character.</span>');
	}

	if (!empty($pictureSource)) {
		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', phone_number='$phoneNumber', address='$address', pictureSource='$pictureSource' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else if (!empty($password)) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', password='$password', address='$address', phone_number='$phoneNumber' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else if (!empty($password) && !empty($pictureSource)) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', password='$password', address='$address', pictureSource='$pictureSource', phone_number='$phoneNumber' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else {
		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', phone_number='$phoneNumber', address='$address' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	}
}

function updateAdmin()
{
	global $db, $errors;

	$id = e($_POST["id"]);
	$username = e($_POST["username"]);
	$password = e($_POST["password"]);
	$email = e($_POST["email"]);
	$firstName = e($_POST["first_name"]);
	$lastName = e($_POST["last_name"]);
	$userType = e($_POST["user_type"]);
	$phoneNumber = e($_POST["phone_number"]);
	$address = e($_POST["address"]);
	$pictureSource = e($_POST["pictureSource"]);

	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
		array_push($errors, '<span style = "color:red">Password should be at least 8 characters in length and should include at least one uppercase letter, one number, and one special character.</span>');
	}

	if (!empty($pictureSource)) {
		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', phone_number='$phoneNumber', address='$address', pictureSource='$pictureSource' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
			$_SESSION['user'] = getUserById($id);
			header("Location: " . $_SERVER['HTTP_REFERER'] . '');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else if (!empty($password)) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', password='$password', address='$address', phone_number='$phoneNumber' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
			$_SESSION['user'] = getUserById($id);
			header("Location: " . $_SERVER['HTTP_REFERER'] . '');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else if (!empty($password) && !empty($pictureSource)) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', password='$password', address='$address', pictureSource='$pictureSource', phone_number='$phoneNumber' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
			$_SESSION['user'] = getUserById($id);
			header("Location: " . $_SERVER['HTTP_REFERER'] . '');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	} else {
		$query = "UPDATE alt_tree_user SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', user_type='$userType', phone_number='$phoneNumber', address='$address', phone_number='$phoneNumber' WHERE id='$id'";

		if (mysqli_query($db, $query)) {
			array_push($errors, '<span style="color:green">User successfully updated.</span>');
			$_SESSION['user'] = getUserById($id);
			header("Location: " . $_SERVER['HTTP_REFERER'] . '');
		} else {
			array_push($errors, '<span style="color:red">Please try again.</span>');
		}
	}
}

function getUserById($id)
{
	global $db;
	$query = "SELECT * FROM alt_tree_user WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

function e($val)
{
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error()
{
	global $errors;

	if (count($errors) > 0) {
		echo '<div class="error">';
		foreach ($errors as $error) {
			echo $error . '<br>';
		}
		echo '</div>';
	}
}

function searchAllUsers()
{
	global $dbPDO, $errors;

	$result = $dbPDO->query("SELECT * FROM alt_tree_user");
	if ($result->rowCount() > 0) {
		$_SESSION['users_isApproved'] = $result->fetchAll();
	} else {
		array_push($errors, '<span style="color:green">All users are approved</span>');
	}
}

function approveUser($approve, $id)
{
	global $dbPDO, $errors;

	if (isset($id) && !empty($id)) {
		$approveUser = $dbPDO->prepare("UPDATE alt_tree_user SET is_approved=:is_approved WHERE id=:id");
		$approveUser->bindValue(":is_approved", $approve, PDO::PARAM_BOOL);
		$approveUser->bindValue(":id", $id);

		$approveUser->execute();

		$approveUser->closeCursor();

		header("Location:editUserStatus.php");
	}
}

function deleteUser($id)
{

	global $dbPDO, $errors;

	if (isset($id) && !empty($id)) {
		$deleteUser = $dbPDO->prepare("DELETE FROM alt_tree_user WHERE id='$id'");

		$deleteUser->execute();

		$deleteUser->closeCursor();

		header("Location:editUserStatus.php");
	}
}

function deleteProduct($id)
{

	global $dbPDO, $errors;

	if (isset($id) && !empty($id)) {
		$deleteUser = $dbPDO->prepare("DELETE FROM alt_tree_product WHERE id='$id'");

		$deleteUser->execute();

		$deleteUser->closeCursor();

		header("Location:deleteProduct.php");
	}
}


