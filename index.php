<?php 
require_once("./db.php");
require_once("./config.php");

$name = "";
$result="";
$length = 0;
if (empty($_COOKIE['current_user'])) {
    header("Location: sign_up.php");
}
$name = $_COOKIE['current_user'];

// add new todo
if ($_SERVER['REQUEST_METHOD'] === "POST" && empty($_POST['deleteCookie'])  && empty($_POST['deleteTodo']) && empty($_POST['toggleCompleted'])) {
   $title = $_POST['title'];
   $description = $_POST['description'];
   $date = $_POST['date'];

   try {
        $ownerName = $_COOKIE['current_user'];
        //    get curent user's id from database
        $ownerQuery = "SELECT id FROM users WHERE name = '$ownerName'";
        $owner = $conn->query($ownerQuery);

        $ownerResponse = $owner->fetch_assoc();
        $ownerId = $ownerResponse['id'];

        $query = "INSERT INTO todos (title, description, date, owner) VALUES ('$title', '$description', '$date', '$ownerId')";
        $response = $conn->query($query);
        if ($response === TRUE) {
            $result = "New todo added successfully!";
        } else {
            $result = "Unable to add new todo!";
        }
    
   } catch (\Throwable $th) {
    throw $th;
   }
   header("Location: " . BASE_URL);
}

// Get id of current user;
$idQuery = "SELECT id FROM users WHERE name = '$name'";
$userid = $conn->query($idQuery);
$userid = $userid->fetch_assoc();
$userid = $userid['id'];

// fetch 5 latest todos and display
$todosQuery = "SELECT * FROM todos WHERE owner = '$userid' ORDER BY id DESC LIMIT 10";
$todosResponse = $conn->query($todosQuery);

function toggleTodoCompleted($todoId) {
    require("./db.php");
    $todoQuery = "UPDATE todos SET completed = CASE WHEN completed = 0 THEN 1 ELSE 0 END WHERE id = '$todoId'";
    $conn->query($todoQuery);
    $conn->close();
}
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
    "}, 1500)</script>";
}
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['toggleCompleted'])) {
    $todoId = $_POST['toggleCompleted'];
    toggleTodoCompleted($todoId);

    header("Location: ". BASE_URL);
}

$conn->close();

// $currentTime = time();
// $currentTime = new DateTime();
// var_dump($currentTime);
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
        <h2 class="font-semibold text-center text-2xl mt-5 mb-2 text-white">Add new todo</h2>
        <p class="font-bold text-secondary text-sm mb-5 text-center" id="result"><?= $result ?></p>
        <?php include "./components/form_add_todo.php" ?>
        <section class="mt-10">
            <h2 class="font-semibold text-center text-2xl mt-5 mb-2 text-white">Latest Todos</h2>
            <ul class="space-y-2 w-full gap-3">
            <?php 
            while($todosData = $todosResponse->fetch_assoc()) {
                $length += 1;
                $todoTitle = $todosData['title'];
                $todoId = $todosData['id'];
                $todoDate = new DateTime($todosData['date']);
                $todoDate = $todoDate->format("H:m, d/m/Y");
                // $completedColor = "$todosData['completed'] ? "green" : "red"; 
                $completedColor = "red";
                if($todosData['completed'] == 0) {
                    $completedColor = "red";
                } else {
                    $completedColor = "green";
                }
                ?>
                <li class='text-black font-medium px-3 py-3 bg-gray-200 rounded-md border-l-4 flex justify-between items-center'
                        style="border-left: 4px solid <?= $completedColor ?>;">
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
            <?php 
            if ($length > 10) {
            echo '<div class="mt-10">
                <a href="all_todos.php" class="px-5 py-3 rounded bg-secondary text-white font-semibold text-center">View all</a>
            </div>';
            }
            ?>
        </section>
    </div>
</main>
<!-- include footer component -->
<?php require_once("./components/footer.php");?>

