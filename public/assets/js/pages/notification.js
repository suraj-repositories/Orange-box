document.addEventListener('DOMContentLoaded', function () {
    new NotificationControl().init();
});

class NotificationControl {

    init() {
        this.enableMarkAsRead();
        this.enableDeleteNotification();
        this.enableClearAllNotification();
    }

    enableMarkAsRead() {
        const myCollapsible = document.querySelectorAll('#notificationsList .collapse')
        if (myCollapsible) {
            myCollapsible.forEach(element => {
                element.addEventListener('show.bs.collapse', event => {
                    const notificationId = element.getAttribute('data-ob-notification-id');

                    if (!notificationId) {
                        return;
                    }
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(authRoute('ajax.notification.mark-as-read', { notification: notificationId }), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'x-csrf-token': csrfToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == "success") {
                                element.removeAttribute("data-ob-notification-id");

                                const notificationElement = element.closest('.accordion-item');
                                if (notificationElement) {
                                    notificationElement.setAttribute('data-ob-notification-status', 'read');
                                    notificationElement.classList.add('animate-read');
                                }

                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                });
            });
        }

    }

    enableDeleteNotification() {
        const deleteBtns = document.querySelectorAll('.delete-notification-button');

        if (!deleteBtns) {
            return;
        }

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', async () => {
                const notificationId = btn.getAttribute('data-ob-notification-id');
                btn.disabled = true;
                btn.textContent = "Deleting...";
                const collapse = btn.closest('.accordion-item');
                collapse.classList.add('deleting');

                if (await this.deleteNotifications([notificationId])) {
                    collapse.remove();
                } else {
                    btn.disabled = false;
                    btn.textContent = "Delete";
                    collapse.classList.remove('deleting');
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong while deleting!",
                        icon: "error"
                    });
                }

            });
        });
    }

    async deleteNotifications(notificationIds) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(authRoute('ajax.notification.delete.all'), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ notifications: notificationIds })
            });

            const data = await response.json();

            if (data.status === "success") {
                console.log("-" + data.deleted_count + " Notifications");
                return true;
            } else {
                console.error('Error:', data.error || data.message);
                return false;
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            return false;
        }
    }

    enableClearAllNotification() {
        const btn = document.querySelector('#clearAllNotificationsBtn');
        if (btn) {
            const notificationDrawer = btn.closest('.notification-drawer');
            const scrollArea = notificationDrawer.querySelector('.noti-scroll');

            btn.addEventListener('click', async () => {

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const response = await fetch(authRoute('ajax.notification.clear.all'), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    });

                    const data = await response.json();

                    if (data.status === "success") {
                        notificationDrawer.classList.add('clear-all-notifications');
                        scrollArea.innerHTML = data.emptyComponent;
                    } else {

                    }
                } catch (error) {
                    console.error(error);
                }
            });
        }
    }


}
