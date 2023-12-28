<form class="space-y-3 my-4 max-w-[80%] mx-auto" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
    <input type="text" class="bg-gray-200 w-full text-gray-800 border border-gray-500 px-2 py-1 md:py-2 rounded-md outline-none" name="title" placeholder="Title">
    <input type="text" class="bg-gray-200 w-full text-gray-800 border border-gray-500 px-2 py-1 md:py-2 rounded-md outline-none" name="description" placeholder="Description">
        <input type="datetime-local" class="w-full bg-gray-200 text-gray-800 border border-gray-500 px-2 py-1 md:py-2 rounded-md outline-none" name="date">
        <button type="submit" class="w-full bg-primary text-center py-2 px-2 rounded-md font-semibold text-nowrap">
            Add todo
        </button>
</form>