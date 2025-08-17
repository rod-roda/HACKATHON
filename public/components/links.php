<link rel="stylesheet" href="../style/style.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Toast Notification Styles -->
<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        max-width: 300px;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
        background-color: #333;
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .notification-success { background-color: #28a745 !important; }
    .notification-error { background-color: #dc3545 !important; }
    .notification-warning { background-color: #ffc107 !important; color: #333; }

    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .notification-message { flex-grow: 1; }

    .notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 0;
        opacity: 0.8;
        transition: opacity 0.3s;
    }

    .notification-close:hover { opacity: 1; }
    .notification.hide { animation: slideOut 0.3s ease-out forwards; }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>

<!-- Inclui o functions.js globalmente -->
<script src="../js/functions.js"></script>



    <!-- fontes -->


    <!--fontes do bootstrap-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
