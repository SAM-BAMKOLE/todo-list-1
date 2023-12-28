<?php 

if(isset($_POST['deleteCookie'])) {
    if (isset($_COOKIE['current_user'])) {
        try {
            setcookie("current_user", "", time() - 60 * 60);
        } catch (\Throwable $th) {
            throw $th;
        }
        header("Location: sign_up.php");
    } else {
        echo "No user logged in";
    }
}

?>

<nav class="bg-base px-3 py-3 flex items-center justify-between shadow-2xl w-full">
    <a href="<?= BASE_URL ?>" class="font-bold text-secondary px-4 py-3 rounded bg-transparent hover:bg-white hover:bg-opacity-20">TodoMaster</a>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <input type="submit" value="Logout" name="deleteCookie" class="px-4 py-3 rounded bg-transparent hover:bg-white hover:bg-opacity-20 text-white font-bold cursor-pointer"  />
    </form>
</nav>