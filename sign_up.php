<?php 
require_once("./db.php");
require_once("./config.php");
global $result;
$result = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // $name = filter_input(INPUT_POST, $_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    // $password = filter_input(INPUT_POST, $_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $name = $_POST['name'];
    $password = $_POST['password'];
    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // write query to insert into database
    $query = "INSERT INTO users (name, password) VALUES ('$name', '$hashedPassword')";
    /*
    if ($conn->query($query) === TRUE) {
        $result = "User created successfully!";
        header("Location: sign_in.php");
    } else {
        $result = "Failed to register user";
    }
    */
    try {
        if ($conn->query($query) === TRUE) {
        $result = "User created successfully!";
        header("Location: sign_in.php");
    } else {
        $result = "Failed to register user";
    }
    } catch (\Throwable $th) {
        $result = "Failed to register user, username already exists!";
        // throw $th;
    }
    $conn->close();
    
    // var_dump(array("name"=> $name, "password"=> $hashedPassword));
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
                <h1 class="font-bold text-4xl mb-5 text-white">Sign up</h1>
                <p class="font-bold text-secondary text-sm mb-3"><?= $result ?></p>
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
                        <input id="passwordInput"
                            type="password"
                            required
                            placeholder="Your Password"
                            name="password"
                            minLength={4}
                            maxLength={12}
                            class="input bg-gray-200 text-gray-800 w-[90%] h-full px-3 py-3 rounded-md outline-none"
                        />
                        <button id="showInputBtn"
                            class="w-[15%]"
                            type="button">
                            show
                        </button>
                    </span>

                    <button type="submit" class="bg-primary text-center py-3 w-full rounded-md font-semibold">
                        Submit
                    </button>
                    <p class="text-sm text-gray-100">
                        Have an account already?
                        <a href="sign_in.php"  class="text-blue-400 underline">
                            Sign in
                        </a>
                    </p>
                </form>
            </div>
        </div>
</body>
</html>