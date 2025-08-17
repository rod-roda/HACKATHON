<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doações | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <script>
        const links = document.querySelectorAll(".navbar li a");

        links.forEach(link => {
            if (link.textContent.trim() === "Doações") {
                link.classList.add("active");
            }
        });
    </script>

    <?php include __DIR__ . '/../public/components/ongs_content.php'; ?>

    <?php include __DIR__ . '/../public/components/donation_container.php'; ?>

    <script>
        // Se o usuário digitar valor personalizado, desmarca os radios
        document.getElementById('customAmount').addEventListener('input', function() {
            if (this.value.length > 0) {
                document.querySelectorAll('input[name="amount"]').forEach(radio => radio.checked = false);
            }
        });
        // Se o usuário clicar em radio, limpa campo personalizado
        document.querySelectorAll('input[name="amount"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('customAmount').value = '';
            });
        });
    </script>
    
</body>
</html>