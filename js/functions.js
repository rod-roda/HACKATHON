function fetchGet(uri, token = null) {
    const headers = {
        'Content-Type': 'application/json'
    };

    if (token) headers['Authorization'] = `Bearer ${token}`;
    
    return fetch(uri, {
        method: 'GET',
        headers
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .catch(error => {
        console.error('Erro no fetchGet:', error);
        return null;
    });
}

function fetchPost(uri, data, token = null) {
    const headers = {
        'Content-Type': 'application/json'
    };

    if (token) headers['Authorization'] = `Bearer ${token}`;
    console.log(token);
    
    return fetch(uri, {
        method: 'POST',
        headers,
        body: JSON.stringify(data) // transforma o objeto em JSON
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .catch(error => {
        console.error('Erro no fetchPost:', error);
        return null;
    });
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => closeNotification(notification), 3000);
}

function closeNotification(el) {
    const notification = el.classList.contains('notification')
        ? el
        : el.closest('.notification');

    if (notification) {
        notification.classList.add('hide');
        notification.addEventListener('animationend', () => notification.remove(), { once: true });
    }
}