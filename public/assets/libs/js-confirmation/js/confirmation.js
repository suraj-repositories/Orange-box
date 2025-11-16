
class Confirm {

    static confirmationModal({
        icon = "bx bx-error",
        iconColor = "#ff5f5fbd",
        title = "Are you sure?",
        message = "Won't be able to recover this back!",
        noBtnText = "Cancel",
        yesBtnText = "Confirm",
        noBtnColor = "white",
        noBtnBackgroundColor = "#ff4d4d",
        yesBtnColor = "white",
        yesBtnBackgroundColor = "#6ac475",
        theme = "light",
        animation = "slide",
        backgroundEffect = "blackish"
    } = {}) {
        return new Promise((resolve) => {
            const modal = document.createElement('div');
            modal.classList.add('confirm-modal', backgroundEffect, animation, theme);

            const modalContent = document.createElement('div');
            modalContent.classList.add('modal-content');

            window.onclick = function (event) {
                if (event.target == modal) {
                    modalContent.classList.add('hide');
                    setTimeout(() => {
                        modal.remove();
                        resolve(0);
                    }, 400);
                }
            };

            const modalBody = document.createElement('div');
            modalBody.classList.add('modal-body');

            const iconDiv = document.createElement('div');
            iconDiv.classList.add('icon');
            const iconElement = document.createElement('i');

            icon.split(" ").forEach(c => {
                iconElement.classList.add(c);
            });

            iconElement.style.color = iconColor;
            iconDiv.appendChild(iconElement);

            const titleDiv = document.createElement('div');
            titleDiv.classList.add('title');
            titleDiv.textContent = title;

            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.textContent = message;

            modalBody.appendChild(iconDiv);
            modalBody.appendChild(titleDiv);
            modalBody.appendChild(messageDiv);

            const modalFooter = document.createElement('div');
            modalFooter.classList.add('modal-footer');

            const closeBtn = document.createElement('div');
            closeBtn.classList.add('no');
            closeBtn.textContent = noBtnText;
            closeBtn.style.backgroundColor = noBtnBackgroundColor;
            closeBtn.style.color = noBtnColor;

            closeBtn.addEventListener('click', () => {
                modalContent.classList.add('hide');
                setTimeout(() => {
                    modal.remove();
                    resolve(0);
                }, 400);
            });

            const confirmBtn = document.createElement('div');
            confirmBtn.classList.add('yes');
            confirmBtn.textContent = yesBtnText;
            confirmBtn.style.backgroundColor = yesBtnBackgroundColor;
            confirmBtn.style.color = yesBtnColor;

            confirmBtn.addEventListener('click', () => {
                modalContent.classList.add('hide');
                setTimeout(() => {
                    modal.remove();
                    resolve(1);
                }, 400);
            });

            modalFooter.appendChild(closeBtn);
            modalFooter.appendChild(confirmBtn);

            modalContent.appendChild(modalBody);
            modalContent.appendChild(modalFooter);

            modal.appendChild(modalContent);

            document.body.appendChild(modal);
        });
    }


}

async function deleteConfirmation(event, options = {}) {
    event.preventDefault();

    const defaults = {
        icon: "bx bx-error",
        iconColor: "#ff7a7a",
        title: "Are you sure!",
        message: "Do you really want to delete this!",
        noBtnText: "Cancel",
        yesBtnText: "Delete",
        noBtnColor: "black",
        noBtnBackgroundColor: "#e1dfdfe3",
        yesBtnColor: "#fff",
        yesBtnBackgroundColor: "#e74c3c",
        theme: "light",
        animation: "zoom",
        backgroundEffect: "blackish"
    };

    const config = { ...defaults, ...options };

    const result = await Confirm.confirmationModal(config);

    if (result === 1) {
        const form = event.target.closest('form');
        form?.submit();
    }
}

async function formConfirmation(event, options = {}){
    event.preventDefault();

    const defaults = {
        icon: "bx bx-error",
        iconColor: "#ffca7a",
        title: "Are you sure!",
        message: "This action cannot be undone!",
        noBtnText: "No",
        yesBtnText: "Yes",
        noBtnColor: "#fff",
        noBtnBackgroundColor: "#e74c3c",
        yesBtnColor: "#fff",
        yesBtnBackgroundColor: "#2ecc71",
        theme: "light",
        animation: "slide",
        backgroundEffect: "blackish"
    };

    const config = { ...defaults, ...options };

    const result = await Confirm.confirmationModal(config);

    if (result === 1) {
        const form = event.target.closest('form');
        form?.submit();
    }
}

