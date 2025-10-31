
document.addEventListener('DOMContentLoaded', function () {
    new PasswordLockerControl().init();
});

class PasswordLockerControl {
    init() {
        this.enablePasswordToggle();
        this.enablePasswordLockerCreation("#create-password-locker", authRoute('user.password_locker.save'));
        this.enableLockerInfoView();
        this.enablePasswordLockerEdit(".edit-password-locker-btn");
        this.enablePasswordLockerDelete(".delete-password-locker-button");
        this.enablePasswordShow(".reveal-password-btn");
        this.enableVarificationMethodToggle();
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

    enableLockerInfoView() {
        $(document).on('click', '.show-locker-info', function () {
            $('#created_at')
                .text($(this).data('ob-created-at') || '-')
                .attr('title', $(this).data('ob-created-at-title') || '-');

            $('#updated_at')
                .text($(this).data('ob-updated-at') || '-')
                .attr('title', $(this).data('ob-updated-at-title') || '-');

            $('#notes-content').html($(this).data('ob-notes-content') || '<p class="fs-5 text-muted p-4 text-center fst-italic"> NO DATA </p>');

            $('#password-locker-info-modal').modal('show');
        });
    }

    enablePasswordLockerDelete(selector) {
        const buttons = document.querySelectorAll(selector);
        if (!buttons) return;

        buttons.forEach(button => {
            button.addEventListener('click', function () {

                const deleteUrl = button.getAttribute('data-ob-password-locker-delete-url');

                if (!deleteUrl) {
                    return;
                }

                const deletableRow = button.closest("tr");
                if (deletableRow) {
                    deletableRow.classList.add('deleting');
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'x-csrf-token': csrfToken
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        deletableRow.classList.remove('deleting');
                        const isCol = deletableRow.closest(".col");
                        if (isCol) {
                            isCol.remove();
                        } else {
                            deletableRow.remove();
                        }

                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        deletableRow.classList.remove('deleting');
                        deletableRow.classList.add('delete-error');
                        setTimeout(() => {
                            deletableRow.classList.remove('delete-error');
                        }, 3000);
                    });

            });
        });

    }

    enablePasswordLockerCreation(selector, submitUrl) {
        const btn = document.querySelector(selector);
        const modal = document.querySelector("#password-locker-form-modal");

        if (!btn) return;

        btn.addEventListener('click', () => {

            const oldForm = modal.querySelector("#password-locker-form");
            oldForm.replaceWith(oldForm.cloneNode(true));

            const title = modal.querySelector("#password-locker-form-title");
            const saveBtn = modal.querySelector("#save-btn");
            if (!title || !saveBtn) {
                return;
            }

            title.textContent = "Create Password"
            saveBtn.textContent = "Create";


            const form = modal.querySelector("#password-locker-form");
            form.reset();
            const defaultIcon = form.querySelector('[name="icon"]');
            if (defaultIcon) {
                defaultIcon.checked = true;
            }


            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const notesField = form.querySelector("[name='notes']");
                if (notesField && notesField.editorInstance) {
                    notesField.value = notesField.editorInstance.getData();
                }

                const formData = new FormData(form);

                saveBtn.disabled = true;
                saveBtn.textContent = "Creating...";

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(submitUrl, {
                    method: 'POST',
                    headers: {
                        'x-csrf-token': csrfToken
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.status == "success") {
                            $(modal).modal('hide');
                            Swal.fire({
                                title: "Success",
                                text: data.message,
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });;

                            form.reset();
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: data.message,
                                icon: "error"
                            });
                        }
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Create";
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Create";
                    });
            });
            $(modal).modal('show');
        });
    }

    enablePasswordLockerEdit(selector) {
        const btns = document.querySelectorAll(selector);
        const modal = document.querySelector("#password-locker-form-modal");
        if (!btns || !modal) return;

        btns.forEach(btn => {
            const submitUrl = btn.getAttribute('data-ob-submit-url');

            btn.addEventListener('click', () => {
                const form = modal.querySelector("#password-locker-form");
                const title = modal.querySelector("#password-locker-form-title");
                const saveBtn = modal.querySelector("#save-btn");

                if (!form || !title || !saveBtn) return;

                form.reset();

                title.textContent = "Edit Password";
                saveBtn.textContent = "Save";

                const usernameInput = form.querySelector("[name='username']");
                const passwordInput = form.querySelector("[name='password']");
                const urlInput = form.querySelector("[name='url']");
                const expiresAtInput = form.querySelector("[name='expires_at']");
                const notesInput = form.querySelector("[name='notes']");

                usernameInput.value = btn.getAttribute('data-ob-username') || "";
                passwordInput.value = btn.getAttribute('data-ob-password') || "";
                urlInput.value = btn.getAttribute('data-ob-url') || "";
                expiresAtInput.value = btn.getAttribute('data-ob-expires-at') || "";

                const notes = btn.getAttribute('data-ob-notes') || "";
                notesInput.value = notes;
                notesInput.dataset.markdown = notes;

                const newForm = form.cloneNode(true);
                form.replaceWith(newForm);

                newForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const saveBtn = modal.querySelector("#save-btn");
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const notesField = newForm.querySelector("[name='notes']");
                    if (notesField && notesField.editorInstance) {
                        notesField.value = notesField.editorInstance.getData();
                    }

                    const formData = new FormData(newForm);

                    saveBtn.disabled = true;
                    saveBtn.textContent = "Saving...";

                    fetch(submitUrl, {
                        method: "POST",
                        headers: { "x-csrf-token": csrfToken },
                        body: formData
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status === "success") {
                                $(modal).modal('hide');
                                Swal.fire({
                                    title: "Success",
                                    text: data.message,
                                    icon: "success"
                                }).then(() => window.location.reload());
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: data.message,
                                    icon: "error"
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire({
                                title: "Oops!",
                                text: "Something went wrong.",
                                icon: "error"
                            });
                        })
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.textContent = "Save";
                        });
                });

                $(modal).modal("show");
            });
        });
    }

    enablePasswordShow(selector) {
        const btns = document.querySelectorAll(selector);
        if (!btns) {
            return;
        }

        const modal = document.querySelector("#password-locker-show-modal");
        const unlockBtn = modal.querySelector('#unlock-btn');

        const emailSendBtn = modal.querySelector("#send-email-otp-btn");
        emailSendBtn.addEventListener('click', function () {
            sendEmailOtp(emailSendBtn);
        })

        btns.forEach(btn => {
            const submitUrl = btn.getAttribute('data-ob-show-password-url');
            const form = modal.querySelector('#no-submit-form');
            const revealArea = modal.querySelector('#reveal-area');
            btn.addEventListener('click', () => {
                revealArea.classList.add('d-none');
                form.classList.remove('d-none');
                form.reset();
                const inputGroups = document.querySelectorAll(".input-group-wrapper");
                inputGroups.forEach(div => div.style.display = "none");
                unlockBtn.classList.remove('d-none');
                unlockBtn.textContent = "Unlock";

                unlockBtn.setAttribute('data-password-locker-id', btn.getAttribute('data-password-locker-id'));
                $(modal).modal('show');

            });
        });


        unlockBtn.addEventListener('click', () => {
            const radioBtns = modal.querySelectorAll('input[name="keytype"]');
            radioBtns.forEach(radio => {
                if (!radio.checked) {
                    return;
                }
                const inputContainer = document.getElementById(radio.id + "Input");
                const input = inputContainer.querySelector("input");
                const inputName = input.name;

                if (!input) return;
                const password_locker_id = unlockBtn.getAttribute('data-password-locker-id');

                switch (inputName) {
                    case "pem": this.handlePemSelection(input, password_locker_id);
                        break;
                    case "key": this.handleMasterPasswordSelection(input, password_locker_id);
                        break;
                    case "otp": this.handleEmailSelection(input, password_locker_id);
                        break;
                    case "code": this.handleAppSelection(input, password_locker_id);
                        break;
                    default: this.handleNoneSelection();
                }
            });
        });

    }

    sendEmailOtp(sendBtn) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        sendBtn.disabled = true;
        sendBtn.textContent = "Sending...";

        const modal = sendBtn.closest(".modal");

        fetch(route('ajax.password_locker.auth.email.send-otp'), {
            method: 'POST',
            headers: {
                'x-csrf-token': csrfToken
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    sendBtn.classList.add('d-none');
                    const resendMessage = sendBtn.parentElement.querySelector('.resend-message');
                    let time = 30;
                    resendMessage.classList.remove("d-none");
                    resendMessage.innerHTML = `Resend in <span class="text-danger">${time}</span> seconds`;

                    const interval = setInterval(() => {
                        time--;
                        if (time > 0) {
                            resendMessage.innerHTML = `Resend in <span class="text-danger">${time}</span> seconds`;
                        } else {
                            clearInterval(interval);
                            resendMessage.classList.add("d-none");
                            sendBtn.classList.remove('d-none');
                            sendBtn.disabled = false;
                            sendBtn.textContent = "Resend";
                        }
                    }, 1000);

                    modal.addEventListener('hidden.bs.modal', () => {
                        clearInterval(interval); // stop interval if modal closes
                    }, { once: true });

                } else {
                    sendBtn.disabled = false;
                    sendBtn.textContent = "Resend";

                    Swal.fire({
                        title: "Oops!",
                        text: data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                sendBtn.disabled = false;
                sendBtn.textContent = "Resend";

                Swal.fire({
                    title: "Oops!",
                    text: error,
                    icon: "error"
                });
            });
    }


    enableVarificationMethodToggle() {
        const inputGroups = document.querySelectorAll(".input-group-wrapper");

        inputGroups.forEach(div => div.style.display = "none");

        document.querySelectorAll('input[name="keytype"]').forEach(radio => {

            const checkAction = () => {
                inputGroups.forEach(div => div.style.display = "none");
                const selectedInput = document.getElementById(radio.id + "Input");
                if (selectedInput) selectedInput.style.display = "block";
            };

            radio.addEventListener('change', checkAction);
            if (radio.checked) {
                checkAction();
            }
        });
    }

    handleNoneSelection() {
        Swal.fire({
            title: "Oops!",
            text: "Please select a valid method to unlock!",
            icon: "error"
        });
    }

    async handlePemSelection(input, password_locker_id) {
        const unlockBtn = document.querySelector('.modal .modal-footer #unlock-btn');
        const unlockBtnTxt = unlockBtn.textContent;

        const resetUnlockBtn = () => {
            unlockBtn.textContent = unlockBtnTxt;
        };

        try {
            unlockBtn.textContent = "Unlocking...";

            const file = input.files[0];
            if (!file) {
                throw new Error('Choose your private PEM file first');
            }

            const pemToArrayBuffer = (pem) => {
                try {
                    const b64 = pem.replace(/-----[^-]+-----/g, '').replace(/\s+/g, '');
                    const raw = atob(b64);
                    const buf = new Uint8Array(raw.length);
                    for (let i = 0; i < raw.length; i++) buf[i] = raw.charCodeAt(i);
                    return buf.buffer;
                } catch (e) {
                    throw new Error('Invalid PEM file format');
                }
            };

            const importPrivateKey = async (pem) => {
                const ab = pemToArrayBuffer(pem);
                try {
                    return await crypto.subtle.importKey(
                        "pkcs8",
                        ab,
                        { name: "RSASSA-PKCS1-v1_5", hash: "SHA-256" },
                        false,
                        ["sign"]
                    );
                } catch (e) {
                    throw new Error('Failed to import private key');
                }
            };

            const signNonce = async (privateKey, nonceBase64) => {
                try {
                    const nonceBytes = Uint8Array.from(atob(nonceBase64), c => c.charCodeAt(0));
                    const signature = await crypto.subtle.sign(
                        { name: "RSASSA-PKCS1-v1_5" },
                        privateKey,
                        nonceBytes
                    );
                    return btoa(String.fromCharCode(...new Uint8Array(signature)));
                } catch (e) {
                    throw new Error('Failed to sign challenge');
                }
            };

            const pem = await file.text();
            const privateKey = await importPrivateKey(pem);

            let resp, data;
            try {
                resp = await fetch(authRoute('ajax.pemkey.getChallenge'));
                if (!resp.ok) throw new Error('Challenge request failed');
                data = await resp.json();
                if (!data?.nonce || !data?.challenge_id) throw new Error('Invalid challenge response');
            } catch (e) {
                throw new Error('Failed to get challenge from server');
            }

            const sigB64 = await signNonce(privateKey, data.nonce);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let verifyResp, result;
            try {
                verifyResp = await fetch(authRoute('ajax.pemkey.verify'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'x-csrf-token': csrfToken
                    },
                    body: JSON.stringify({
                        challenge_id: data.challenge_id,
                        signature: sigB64,
                        password_locker_id: password_locker_id
                    })
                });
                if (!verifyResp.ok) throw new Error('Verification request failed');
                result = await verifyResp.json();
            } catch (e) {
                throw new Error('Failed to verify signature with server');
            }

            if (result.status === "success") {
                this.showPassword(result.key);
            } else {
                Swal.fire({
                    title: "Oops!",
                    text: result.message || "Verification failed.",
                    icon: "error"
                });
            }

        } catch (err) {
            Swal.fire({
                title: "Error",
                text: err.message || "Something went wrong!",
                icon: "error"
            });
        } finally {
            resetUnlockBtn();
        }
    }

    handleMasterPasswordSelection(input, password_locker_id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(route('ajax.password_locker.auth.verify_master_key'), {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                master_key: input.value,
                password_locker_id: password_locker_id
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    this.showPassword(data.key);
                } else {
                    Swal.fire({
                        title: "Oops!",
                        text: data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Oops!",
                    text: error,
                    icon: "error"
                });
            });
    }

    handleEmailSelection(input, password_locker_id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(route('ajax.password_locker.auth.email.verify-otp'), {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                otp: input.value,
                password_locker_id: password_locker_id
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    this.showPassword(data.key);
                } else {
                    Swal.fire({
                        title: "Oops!",
                        text: data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Oops!",
                    text: error,
                    icon: "error"
                });
            });
    }

    handleAppSelection(input, password_locker_id) {

    }

    showPassword(key) {
        const modal = document.querySelector('.modal.show');
        const unlockBtn = modal.querySelector('.modal-footer #unlock-btn');
        unlockBtn.classList.add('d-none');

        const revealArea = document.getElementById("reveal-area");
        const form = document.getElementById("no-submit-form");

        form.classList.add('d-none');
        revealArea.classList.remove("d-none");

        const txt = revealArea.querySelector("#reveal-password-txt");
        const pctText = document.querySelector('.circular-progress .pct');
        const pctCircle = document.querySelector('.circular-progress #pct-ind');

        const duration = 15 * 1000;
        const radius = 40;
        const circumference = 2 * Math.PI * radius;
        let animationFrameId = null;
        let stopped = false;

        pctCircle.style.strokeDasharray = `${circumference}`;
        pctCircle.style.strokeDashoffset = `0`;
        pctText.textContent = `${duration / 1000}s`;
        txt.textContent = key;

        const startTime = performance.now();

        function animate(now) {
            if (stopped) return;

            const elapsed = now - startTime;
            const remaining = Math.max(duration - elapsed, 0);
            const progress = elapsed / duration;
            const offset = progress * circumference;

            pctCircle.style.strokeDashoffset = `${offset}`;
            pctText.textContent = `${Math.ceil(remaining / 1000)}s`;

            if (elapsed < duration) {
                animationFrameId = requestAnimationFrame(animate);
            } else {
                pctText.textContent = "0s";
                txt.textContent = "Reloading...";
                setTimeout(() => window.location.reload(), 500);
            }
        }

        animationFrameId = requestAnimationFrame(animate);

        modal.addEventListener('hidden.bs.modal', () => {
            stopped = true;
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            pctText.textContent = "Stopped";
        }, { once: true });
    }

}
