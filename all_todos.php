<?php 
require_once("./db.php");
require_once("./config.php");

$name = $_COOKIE['current_user'];
// get current users id
$idQuery = "SELECT id FROM users WHERE name = '$name'";
$userid = $conn->query($idQuery);
$userid = $userid->fetch_assoc();
$userid = $userid['id'];

$query = "SELECT * FROM todos WHERE owner = '$userid' ORDER BY id DESC";
$request = $conn->query($query);

function deleteTodo($todoId) {
    include("./db.php");
    $todoQuery = "DELETE FROM todos WHERE id = '$todoId'";
    $response = $conn->query($todoQuery);
    
    $conn->close();
    if ($response === TRUE) {
        return true;
    }
    return false;
}
function toggleTodoCompleted($todoId) {
    require("./db.php");
    $todoQuery = "UPDATE todos SET completed = CASE WHEN completed = 0 THEN 1 ELSE 0 END WHERE id = '$todoId'";
    $conn->query($todoQuery);
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleteTodo'])) {
    $todoId = $_POST['deleteTodo'];
    if (deleteTodo($todoId) === true) {
        $result = "Todo deleted successfully!";
    } else {
        $result = "Unable to delete todo!";
    }
    echo "<script>setTimeout(()=>{
        document.querySelector('#result').textContent = '';
        window.location.href = " . BASE_URL . 
    "}, 3000)</script>";
}
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['toggleCompleted'])) {
    $todoId = $_POST['toggleCompleted'];
    toggleTodoCompleted($todoId);

    header("Location: ". BASE_URL);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/output.css">
    <title>View all your todos</title>
</head>
<body>
    <!-- include header component -->
<?php include_once("./components/header.php"); ?>

<main class="min-h-[100dvh] h-[100dvh] w-full flex justify-center p-5 bg-base">
    <div class="w-full max-w-2xl  md:px-3 py-4">
        <h1 class="font-bold text-center text-4xl text-white capitalize">Welcome <?= $name ?></h1>
        <p class="font-bold text-secondary text-sm mb-5 text-center" id="result"><?= $result ?></p>
        <section class="mt-10">
            <ul class="space-y-2 w-full gap-3">
                <?php
                    while($todosData = $request->fetch_assoc()) {
                        
                        $todoTitle = $todosData['title'];
                        $todoId = $todosData['id'];
                        $todoDate = new DateTime($todosData['date']);
                        $todoDate = $todoDate->format("H:m, d/m/Y");
                        $completedColor = $todosData['completed'] ? "green" : "red"; ?>
                        <li class='text-black font-medium px-3 py-3 bg-gray-200 rounded-md border-l-4 border-<?= $completedColor ?>-600 flex justify-between items-center'>
                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="mb-0 flex items-center">
                                <input type="checkbox" class="hidden focus:outline-none focus:border-0" id="<?= $todoId ?>" value="<?= $todoId ?>" name="toggleCompleted">
                                <button type='submit'>
                                    <label for='<?= $todoId ?>' class='cursor-pointer flex gap-3 items-center'><?= $todoTitle ?><span class='text-sm text-gray-700'><?= $todoDate ?></span> </label>
                                </button>
                            </form>
                            <div class='flex gap-3 md:gap-4 items-center'>
                                <a href="edit.php?id=<?= $todoId ?>"><img src="./assets/edit.png" class="w-5" alt="edit icon" /></a>
                                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                    <input type="hidden" name="deleteTodo" value="<?= $todoId ?>"/>
                                    <button type="submit"><img src="./assets/trash.png" class="w-5" alt="trash icon" /></button>
                                </form>
                            </div>
                        </li>
                    <?php 
                    }
                    ?>
            </ul>
    </div>
</main>
</body>
</html>