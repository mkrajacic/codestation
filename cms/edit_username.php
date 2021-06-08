<?php
include_once("functions.php");
include_once("class/user.php");
$db = connect();
if (isset($_POST['user-name-id'])) {
    $user_id = (int)$_POST['user-name-id'];
    $user = new User($db);
    if (!$stmt = $user->getUserById($user_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška!'));
    } else {
        if (isset($_POST['submitted'])) {
            $form_fields = array('usr-username');
            $form_names = array('Korisničko ime');
            $errors = validate($form_fields, $form_names, $db, null,"User");
        }
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Greška pri promjeni korisničkog imena!'));
}
if (isset($errors)) {
    if (sizeof($errors) > 0) {
        echo json_encode(array('status' => 0, 'message' => $errors));
    } else {
        $username = $_POST['usr-username'];
        $user->set_id($user_id);
        $user->set_username($username);
        if (isset($user)) {
            if (!$user->editUsername()) {
                echo json_encode(array('status' => 0, 'message' => 'Greška pri promjeni korisničkog imena!'));
            } else {
                echo json_encode(array('status' => 1, 'message' => 'Korisničko ime uspješno promijenjeno!'));
            }
        }
    }
}