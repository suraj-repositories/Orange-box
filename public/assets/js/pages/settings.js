document.addEventListener('DOMContentLoaded', () => {
    new AccountSettings().init();
    new SecuritySettings().init();
    new NotificationSettings().init();
    new ThemeSettings().init();
});

class AccountSettings {
    init() {
        this.enableChangeUsername("#changeUsernameForm");
        this.enableChangePrimaryEmail();
        this.enableDeleteAccount();
    }

    enableChangeUsername(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const errorView = form.querySelector('#errorView');
        const suggestions = form.querySelector('#suggestions');
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!errorView || !suggestions) return;

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            submitBtn.textContent = "Loading...";
            submitBtn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'x-csrf-token': csrfToken
                },
                body: new FormData(form)
            })
                .then(async (response) => {
                    const data = await response.json();

                    if (!response.ok) {
                        errorView.innerHTML = data.message;
                        suggestions.textContent = "";
                        return null;
                    }

                    return data;
                })
                .then(data => {
                    if (!data) return;

                    if (data.success) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                        return;
                    }

                    errorView.innerHTML = data.message + ` To submit a trademark claim, please see our <a href=''>Trademark Policy</a>.`;

                    if (data.suggestions) {
                        suggestions.innerHTML = `
                    <span class="suggestion-badge">${data.suggestions[0]}</span>,
                    <span class="suggestion-badge">${data.suggestions[1]}</span> or
                    <span class="suggestion-badge">${data.suggestions[2]}</span>
                    are available
                `;
                    } else {
                        suggestions.innerHTML = "";
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong!",
                        icon: "error"
                    });

                    errorView.innerHTML = "";
                    suggestions.textContent = "";
                })
                .finally(() => {
                    submitBtn.textContent = "Update";
                    submitBtn.disabled = false;
                });
        });
    }


    enableChangePrimaryEmail() {

    }

    enableDeleteAccount() {

    }

}

class SecuritySettings {

    init() {
        this.enablePasswordToggle();
        this.enableChangePassword('#changePasswordForm');
        this.enableLockScreenPasswordUpdate('#updateScreenLockPinForm');
        this.enableMasterKeyUpdate("#masterKeyForm");
        this.enablePemKeySetupAndUpdate('#pemKeyForm');
    }

    enableChangePassword(formSelector) {

        const form = document.querySelector(formSelector);
        if (!form) return;
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) {
            return;
        }
        const modal = form.closest('.modal');
        if (!modal) {
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            submitBtn.textContent = "Updating...";
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'x-csrf-token': csrfToken
                },
                body: new FormData(form)
            })
                .then((response) => response.json())
                .then(data => {
                    if (!data) return;

                    if (data.success) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });

                        $(modal).modal('hide');
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            text: data.message,
                            icon: "error"
                        });
                    }

                })
                .catch(() => {
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong!",
                        icon: "error"
                    });

                })
                .finally(() => {
                    submitBtn.textContent = "Update";
                    submitBtn.disabled = false;
                });


        });
    }

    enablePasswordToggle() {

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.show-password-btn');
            if (!btn) return;
            const passInput = btn.parentElement.querySelector('input[type="password"], input[type="text"]');
            const icon = btn.querySelector('i');

            if (!passInput || !icon) return;

            if (passInput.type === "password") {
                passInput.type = "text";
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passInput.type = "password";
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });

    }

    enableLockScreenPasswordUpdate(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return;
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) {
            return;
        }
        const modal = form.closest('.modal');
        if (!modal) {
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            submitBtn.textContent = "Updating...";
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'x-csrf-token': csrfToken
                },
                body: new FormData(form)
            })
                .then((response) => response.json())
                .then(data => {
                    if (!data) return;

                    if (data.success) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });

                        $(modal).modal('hide');
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            text: data.message,
                            icon: "error"
                        });
                    }

                })
                .catch(() => {
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong!",
                        icon: "error"
                    });

                })
                .finally(() => {
                    submitBtn.textContent = "Update";
                    submitBtn.disabled = false;
                });
        });

    }

    enableMasterKeyUpdate(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return;
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) {
            return;
        }
        const modal = form.closest('.modal');
        if (!modal) {
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            submitBtn.textContent = "Updating...";
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'x-csrf-token': csrfToken
                },
                body: new FormData(form)
            })
                .then((response) => response.json())
                .then(data => {
                    if (!data) return;

                    if (data.success) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });

                        $(modal).modal('hide');
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            text: data.message,
                            icon: "error"
                        });
                    }

                })
                .catch(() => {
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong!",
                        icon: "error"
                    });

                })
                .finally(() => {
                    submitBtn.textContent = "Update";
                    submitBtn.disabled = false;
                });
        });

    }

    enablePemKeySetupAndUpdate(formSelector) {

        const form = document.querySelector(formSelector);
        if (!form) return;
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) {
            return;
        }
        const modal = form.closest('.modal');
        if (!modal) {
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            submitBtn.textContent = "Generating...";
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'x-csrf-token': csrfToken
                },
                body: new FormData(form)
            })
                .then((response) => response.json())
                .then(data => {
                    if (!data) return;

                    if (data.success) {

                        const blob = new Blob([data.private_key], {
                            type: "application/x-pem-file"
                        });

                        const link = document.createElement("a");
                        link.href = URL.createObjectURL(blob);
                        link.download = data.filename;

                        document.body.appendChild(link);
                        link.click();

                        URL.revokeObjectURL(link.href);
                        link.remove();


                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });

                        $(modal).modal('hide');
                    } else {
                        Swal.fire({
                            title: "Oops...",
                            text: data.message,
                            icon: "error"
                        });
                    }

                })
                .catch(() => {
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong!",
                        icon: "error"
                    });

                })
                .finally(() => {
                    submitBtn.textContent = "Generate";
                    submitBtn.disabled = false;
                });
        });


    }

}

class NotificationSettings {
    init() {
        this.enableSettingToggle('#setting_task_notification');
        this.enableSettingToggle('#setting_module_notification');
        this.enableSettingToggle('#setting_comment_notification');
        this.enableSettingToggle('#setting_comment_reply_notification');
        this.enableSettingToggle('#setting_unlisted_visit_notification');
    }

    enableSettingToggle(switchSelector) {
        const notificationSwitch = document.querySelector(switchSelector);
        if (!switchSelector) return;

        notificationSwitch.addEventListener('change', function () {
            const settingKey = notificationSwitch.getAttribute('data-setting-key');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(route('ajax.settings.security.notification.toggle'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({
                    'setting_key': settingKey,
                    'setting_value': (notificationSwitch.checked ? 1 : 0)
                })
            })
                .then((response) => response.json())
                .then(data => {
                    if (!data) return;

                    if (data.success) {

                    } else {
                        Swal.fire({
                            title: "Oops...",
                            text: data.message,
                            icon: "error"
                        });
                    }

                })
                .catch(() => {
                    Swal.fire({
                        title: "Oops...",
                        text: "Something went wrong!",
                        icon: "error"
                    });

                });
        });
    }

}

class ThemeSettings {
    init() {
        this.enableThemeChange();
    }

    enableThemeChange() {
        const radios = document.querySelectorAll('[name="app_theme"]');

        if (!radios) {
            return;
        }
        const obj = this;
        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (!radio.checked) {
                    return;
                }
                const body = document.querySelector('body');
                body.setAttribute('data-app-theme', radio.value);

                obj.updateUserTheme(route('ajax.settings.theme.update', { theme: radio.value }))

            });
        });

    }

    updateUserTheme(url) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(url, {
            method: 'POST',
            headers: {
                'x-csrf-token': csrfToken
            }
        })
            .then((response) => response.json())
            .then(data => {
                if (!data) return;

                if (data.success) {
                    const body = document.querySelector('body');
                    body.setAttribute('data-app-theme', data.theme);
                } else {
                    Swal.fire({
                        title: "Oops...",
                        text: data.message,
                        icon: "error"
                    });
                }

            })
            .catch(() => {
                Swal.fire({
                    title: "Oops...",
                    text: "Something went wrong!",
                    icon: "error"
                });

            });
    }
}
