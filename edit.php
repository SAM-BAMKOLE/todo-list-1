<?php
// start session
session_start();

require_once("./db.php");
require_once("./config.php");
$name = $_COOKIE['current_user'];
$result = "";

$todoTitle = "";
$todoDescription = "";
$todoDate = "";

if (isset($_GET['id'])) {
    $currentTodo = $_GET['id'];
    $_SESSION['current_todo'] = $currentTodo;

    // Get todo
    $todoQuery = "SELECT * FROM todos WHERE id = '$currentTodo'";
    $response = $conn->query($todoQuery);
    $response = $response->fetch_assoc();
    if (isset($response)) {
        $todoTitle = $response['title'];
        $todoDescription = $response['description'];
        $todoDate = $response['date'];
    } else {
        $result = "No todo with this id!";
        echo "<script>setTimeout(()=>{window.location.href = " . BASE_URL . "}, 2000)</script>";
        // header("Location: " . BASE_URL);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $todoTitle = $_POST['title'];
    $todoDescription = $_POST['description'];
    $todoDate = $_POST['date'];

    $currentTodo = $_SESSION['current_todo'];
    if (empty($currentTodo)) {
        $result = "Please select a todo first!";
        echo "<script>setTimeout(()=>{window.location.href = " . BASE_URL . "}, 2000)</script>";
        
    }

    $newQuery = "UPDATE todos SET title = '$todoTitle', description = '$todoDescription', date = '$todoDate' WHERE id = '$currentTodo'";
    $response = $conn->query($newQuery);

    if ($response === TRUE) {
        $result = "Your todo has been updated successfully!";
        session_unset();
        session_destroy();
        echo "<script>setTimeout(()=>{window.location.href = " . BASE_URL . "}, 2000)</script>";
    } else {
        $result = "Unable to update todo!";
        // echo "<script>setTimeout(()=>{window.location.href = " . BASE_URL . "}, 2000)</script>";
    }
    // var_dump(array($_POST['title'], $_POST['description'], $_POST['date']));
} else {
    $result = "Please select a todo first!";
    echo "<script>setTimeout(()=>{window.location.href = " . BASE_URL . "}, 2000)</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/output.css">
    <title>Home page for todo master !</title>
</head>
<body>
    <!-- include header component -->
    <?php include_once("./components/header.php");?>

<main class="min-h-[100dvh] h-[100dvh] w-full flex justify-center p-5 bg-base">
    <div class="w-full max-w-2xl  md:px-3 py-4">
        <h1 class="font-bold text-center text-4xl text-white capitalize">Welcome <?= $name ?></h1>
        <h2 class="font-semibold text-center text-2xl mt-5 mb-2 text-white">Edit your todo</h2>
        <p class="font-bold text-secondary text-sm mb-5 text-center"><?= $result ?></p>
        <!-- form -->
        <form class="space-y-3 my-4 max-w-[80%] mx-auto" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <input value="<?= $todoTitle ?>" type="text" class="bg-gray-200 w-full text-gray-800 border border-gray-500 px-2 py-1 md:py-2 rounded-md outline-none" name="title" placeholder="Title">
            <input value="<?= $todoDescription ?>" type="text" class="bg-gray-200 w-full text-gray-800 border border-gray-500 px-2 py-1 md:py-2 rounded-md outline-none" name="description" placeholder="Description">
                <input value="<?= $todoDate ?>" type="datetime-local" class="w-full bg-gray-200 text-gray-800 border border-gray-500 px-2 py-1 md:py-2 rounded-md outline-none" name="date">
                <button type="submit" class="w-full bg-primary text-center py-2 px-2 rounded-md font-semibold text-nowrap">
                    Edit todo
                </button>
        </form>
        <!-- form ends -->
    </div>
</main>
</body>
</html>