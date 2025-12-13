class Toastify {
    static #toastBox;

    static #warningIcon = 'bi bi-exclamation-circle-fill';
    static #successIcon = 'bi bi-check-circle-fill';
    static #errorIcon = 'bi bi-x-circle-fill';
    static #infoIcon = 'bi bi-info-circle-fill';

    static #defaultDuration = 5000;

    static #init() {

        if (!this.#toastBox) {
            this.#toastBox = document.createElement('div');
            this.#toastBox.classList.add('toastify', 'toastBox');
            document.body.appendChild(this.#toastBox);
        }
    }

    static #basicToast({ message = "Welcome to Toastify",
                         type = "info",
                         iconClass = this.#infoIcon,
                         duration = this.#defaultDuration }) {

        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.classList.add(type);
        toast.innerHTML = `<i class="${iconClass}"></i> ${message}`;

        const closeBtn = document.createElement('i');
        closeBtn.classList.add('bi', 'bi-x', 'closeBtn');
        const hideToast = () => {
            toast.classList.add('hide');
            setTimeout(() => {
                toast.classList.add('shrink');
                setTimeout(() => {
                    toast.remove();
                }, 200);
            }, 500);
        };

        closeBtn.addEventListener('click', hideToast);
        toast.addEventListener('click', hideToast);

        toast.appendChild(closeBtn);


        this.#toastBox.appendChild(toast);

        /*
            Start Hover stop timer Logic
        */
        let timeoutId;
        let timeLeft = duration;
        let startTime;

        function startTimer() {
            startTime = Date.now();
            timeoutId = setTimeout(hideToast, timeLeft);
        }

        function stopTimer() {
            clearTimeout(timeoutId);
            const elapsed = Date.now() - startTime;
            timeLeft -= elapsed;
        }
        toast.addEventListener('mouseenter', stopTimer);
        toast.addEventListener('mouseleave', startTimer);

        startTimer();
        /*
            End Hover stop timer Logic
        */

    }

    static error(message, duration = this.#defaultDuration) {
        this.#init();
        this.#basicToast({
            message : message,
            type : "error",
            iconClass : this.#errorIcon,
            duration: duration
        });
    }

    static success(message, duration = this.#defaultDuration) {
        this.#init();
        this.#basicToast({
            message : message,
            type : "success",
            iconClass : this.#successIcon,
            duration : duration
        });
    }

    static warning(message, duration = this.#defaultDuration) {
        this.#init();
        this.#basicToast({
            message : message,
            type : "warning",
            iconClass : this.#warningIcon,
            duration : duration
        });
    }

    static info(message, duration = this.#defaultDuration) {
        this.#init();
        this.#basicToast({
            message : message,
            type : "info",
            iconClass : this.#infoIcon,
            duration : duration
        });
    }

}

