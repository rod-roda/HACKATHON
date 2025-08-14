<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <script>
        const links = document.querySelectorAll(".navbar li a");

        links.forEach(link => {
            if (link.textContent.trim() === "EcoQuiz") {
                link.classList.add("active");
            }
        });

    </script>

    <?php include __DIR__ . '/../public/components/quiz_container.php'; ?>

    <script src="../js/quiz.js"></script>
    <script src="../js/functions.js"></script>
</body>
</html>