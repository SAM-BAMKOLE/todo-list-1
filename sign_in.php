<?php 
require_once("./db.php");
require_once("./config.php");

$result = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // $inputName = filter_input(INPUT_POST, $_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    // $inputPassword = filter_input(INPUT_POST, $_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $inputName = $_POST['name'];
    $inputPassword = $_POST['password'];

    // find user in the database
    $query = "SELECT * FROM users WHERE name = '$inputName'";
    $response = $conn->query($query);
    $response = $response->fetch_assoc();
    if ($response) {
        // verify the password
        if (password_verify($inputPassword, $response['password']) === TRUE) {
            $result = "Password validated, signing you in!";
            setcookie("current_user", $inputName, time() + 1000 * 60 * 60 * 6, "", "", true);
            $delay = 2;
            // header("Location: " . BASE_URL);
            echo "<script>
                setTimeout(()=> {
                    window.location.href = " . BASE_URL . "
                }, $delay * 1000);
            </script>";
        } else {
        $result = "Incorrect Password!";
        }
    } else {
        $result = "This user does not exist, please sign up!";
    }


    
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/output.css">
    <title>Sign up for todo master</title>
    <script defer src="./js/script.js"></script>
</head>
<body>
    <div class="min-h-[100dvh] h-[100dvh] w-full flex items-center justify-center p-5 bg-base">
            <div class="w-full max-w-2xl  px-3 py-4">
                <h1 class="font-bold text-4xl mb-5 text-white">Sign in</h1>
                <?php if($result) { 
                    echo "<div class='flex items-center gap-3 mb-3'>" . '<p class="font-bold text-secondary text-sm">' . $result . '</p>' . "<img src='./assets/spinner.gif' class='h-10' alt='spinner image' />" .  "</div>";
                }
                ?>
                <h3
                    class="font-semibold mb-5 text-center text-red-400 text-sm hidden">
                    This username already exists, please choose another username!
                </h3>
                <form class="rounded-lg drop-shadow-xl space-y-3" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <input
                        type="text"
                        required
                        name="name"
                        placeholder="Username"
                        name="username"
                        class="bg-gray-200 w-full text-gray-800 border border-gray-500 px-3 py-3 rounded-md outline-none"
                    />
                    <span class="w-full flex bg-gray-200 border border-gray-500 rounded-md">
                        <input
                            id="passwordInput"
                            type="password"
                            required
                            placeholder="Your Password"
                            name="password"
                            minLength={4}
                            maxLength={12}
                            class="input bg-gray-200 text-gray-800 w-[90%] h-full px-3 py-3 rounded-md outline-none"
                        />
                        <button
                            id="showInputBtn"
                            class="w-[15%]"
                            type="button">
                            show
                        </button>
                    </span>

                    <button type="submit" class="bg-primary text-center py-3 w-full rounded-md font-semibold">
                        Submit
                    </button>
                    <p class="text-sm text-gray-100">
                        Do not have an account yet?
                        <a href="sign_up.php" class="text-blue-400 underline">
                            Sign up
                        </a> or <a href="<?= BASE_URL ?>" class="text-blue-400 underline">Forgotten Password?</a>
                    </p>
                </form>
            </div>
        </div>
</body>
</html>