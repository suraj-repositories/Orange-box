class DropZone {

    init() {
        this.createSimpleSingleFileDropzone(".singleFileDropzone");
    }

    createSimpleSingleFileDropzone(dropzoneSelector) {
        const previewTemplateElement = document.querySelector("#dropzone-preview-list");

        const dropzoneOptions = {
            url: "#",
            acceptedFiles: "image/*",
            maxFilesize: 2,
            addRemoveLinks: true,
            dictDefaultMessage: "Drop files here or click to upload (single file only).",
            previewTemplate: previewTemplateElement ? previewTemplateElement.outerHTML : '',
        };

        let dropzone = document.querySelector(dropzoneSelector);
        if (dropzone) {
            dropzone.classList.add("dropzone-prime-selector-area");
        }

        return this.singleFileDropzone(dropzone, dropzoneOptions);
    }

    singleFileDropzone(dropzoneElement, options) {
        let myDropzone = new Dropzone(dropzoneElement, options);

        myDropzone.on("addedfile", (file) => {
            const hiddenFileInput = dropzoneElement.parentElement.querySelector("#hiddenFileInput");
            if (hiddenFileInput) {
                if (file instanceof File) {
                    let dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    hiddenFileInput.files = dataTransfer.files;
                }
            }

            if (myDropzone.files.length > 1) {
                myDropzone.removeAllFiles(true);
                myDropzone.addFile(file);
            }
        });

        myDropzone.on("removedfile", (file) => {
            const hiddenFileInput = dropzoneElement.parentElement.querySelector("#hiddenFileInput");
            if (hiddenFileInput) hiddenFileInput.value = "";
        });

        myDropzone.on("error", (file, errorMessage) => {
            if (typeof errorMessage === "object" && errorMessage !== null) {
                errorMessage = errorMessage.message || JSON.stringify(errorMessage);
            }
            const errorMessageElement = file.previewElement.querySelector('.error');
            if (errorMessageElement) errorMessageElement.textContent = errorMessage;
        });

        myDropzone.on("success", (file, response) => {
            console.log("Success:", response);
        });

        this.dropzoneObj = myDropzone;
        this.styleDropzoneElement(dropzoneElement);
        this.handleFormSubmitForFiles(dropzoneElement, 1);
        return myDropzone;
    }

    multipleFileDropzone(dropzoneElement, options) {
        let myDropzone = new Dropzone(dropzoneElement, options);

        myDropzone.on("addedfile", (file) => {
            const hiddenFileInput = dropzoneElement.parentElement.querySelector("#hiddenFileInput");
            if (!hiddenFileInput) return;

            let dataTransfer = new DataTransfer();
            for (let f of hiddenFileInput.files) {
                dataTransfer.items.add(f);
            }

            dataTransfer.items.add(file);

            hiddenFileInput.files = dataTransfer.files;
        });


        myDropzone.on("removedfile", (file) => {
            const hiddenFileInput = dropzoneElement.parentElement.querySelector("#hiddenFileInput");
            if (!hiddenFileInput) return;

            let dataTransfer = new DataTransfer();

            for (let f of hiddenFileInput.files) {
                if (f.name !== file.name || f.size !== file.size) {
                    dataTransfer.items.add(f);
                }
            }

            hiddenFileInput.files = dataTransfer.files;
        });

        myDropzone.on("error", (file, errorMessage) => {
            if (typeof errorMessage === "object" && errorMessage !== null) {
                errorMessage = errorMessage.message || JSON.stringify(errorMessage);
            }
            const errorMessageElement = file.previewElement.querySelector('.error');
            if (errorMessageElement) errorMessageElement.textContent = errorMessage;
        });

        myDropzone.on("success", (file, response) => {
            console.log("Success:", response);
        });

        this.dropzoneObj = myDropzone;
        this.styleDropzoneElement(dropzoneElement);
        this.handleFormSubmitForFiles(dropzoneElement); // Multiple files
        return myDropzone;
    }

    handleFormSubmitForFiles(dropzoneElement, fileCount = -1) {
        let form = dropzoneElement.closest('form');
        if (!form) return;

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const files = this.dropzoneObj.getAcceptedFiles();
            if (files.length > 0) {
                const hiddenFileInput = dropzoneElement.parentElement.querySelector("#hiddenFileInput");
                let dataTransfer = new DataTransfer();
                files.forEach((file, index) => {
                    if (fileCount !== -1 && index === fileCount) return;
                    dataTransfer.items.add(file);
                });
                hiddenFileInput.files = dataTransfer.files;
            }

            form.submit();
        });
    }

    styleDropzoneElement(dropzoneElement) {
        dropzoneElement.style.border = '2px dashed #ccc';
        dropzoneElement.style.padding = '20px';
        dropzoneElement.style.borderRadius = '5px';

        const messageArea = dropzoneElement.querySelector('.dz-message');
        if (messageArea) messageArea.style.textAlign = 'center';

        const form = dropzoneElement.closest('form');
        if (form) form.addEventListener('submit', this.handleFormSubmitForFiles.bind(this));
    }
}
