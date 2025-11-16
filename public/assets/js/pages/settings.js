document.addEventListener('DOMContentLoaded', () => {
    new AccountSettings().init();
    new SecuritySettings().init();
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

}
