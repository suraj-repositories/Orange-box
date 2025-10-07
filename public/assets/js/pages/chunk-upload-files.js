
const UPLOADING_FILES_KEY = '0x-uploading-file';
const UPLOAD_CHUNK_SIZE = 1 * 1024 * 1024;

let uploadChunkTimes = {};
let uploadProcesses = {};
let uploadDropzone = null;

window.addEventListener("beforeunload", (e) => {
    const inProgress = localStorage.getItem(UPLOADING_FILES_KEY) ?? null;
    if (inProgress) {
        e.preventDefault();
        if (!confirm("Upload in progress. Leave page?")) e.preventDefault();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const dropzone = document.querySelector(".upload_file_input_dropzone");
    dropzone.classList.add("dropzone-prime-selector-area");

    Dropzone.autoDiscover = false;
    const dropzoneOptions = {
        url: "#!",
        acceptedFiles: "*/*",
        maxFilesize: 300,
        addRemoveLinks: true,
        dictDefaultMessage: "Drop files here or click to upload (multiple files allowed).",
        previewTemplate: document.querySelector("#dropzone-preview-list").outerHTML,
    };

    uploadDropzone = new DropZone().multipleFileDropzone(
        document.querySelector(".multipleFileDropzone"),
        dropzoneOptions
    );

    emptyUploadHistoryFormLocal();
    init();
});

function emptyUploadHistoryFormLocal() {
    let uploadkeys = JSON.parse(localStorage.getItem(UPLOADING_FILES_KEY)) ?? [];
    uploadkeys.forEach(uploadId => localStorage.removeItem(uploadId));
    localStorage.removeItem(UPLOADING_FILES_KEY);
}

function init() {
    const fileUploadBtn = document.querySelector('#fileUploadBtn');

    fileUploadBtn.addEventListener('click', async () => {
        const fileInput = document.querySelector('#uploadFileArea input[type="file"]');
        const files = fileInput.files;
        const folderId = $('#folder-picker').val();

        if (!files.length) {
            alert('Please select a file to upload.');
            return;
        }

        emptyFileSelection();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        for (let file of files) {
            const uploadId = crypto.randomUUID();
            await uploadFile(folderId, file, uploadId, csrfToken);
        }
    });
}

async function uploadFile(folder, file, uploadId, csrfToken) {
    uploadChunkTimes[uploadId] = [];
    const totalChunks = Math.ceil(file.size / UPLOAD_CHUNK_SIZE);
    const maxParallelUploads = 3;

    let uploadedChunks = await getUploadedChunks(uploadId, csrfToken);
    const savedProgress = JSON.parse(localStorage.getItem(uploadId)) || [];
    uploadedChunks = Array.from(new Set([...uploadedChunks, ...savedProgress]));

    addToUploadingFiles(uploadId);
    if (!uploadProcesses[uploadId]) uploadProcesses[uploadId] = [];

    let progressBar = createProgressBar(uploadId, file, uploadedChunks, totalChunks);

    const doUploadChunk = async (index) => {
        const start = index * UPLOAD_CHUNK_SIZE;
        const end = Math.min(start + UPLOAD_CHUNK_SIZE, file.size);
        const chunk = file.slice(start, end);

        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('chunkIndex', String(index));
        formData.append('totalChunks', String(totalChunks));
        formData.append('fileName', String(file.name));
        formData.append('folderId', String(folder));
        formData.append('uploadId', String(uploadId));

        const controller = new AbortController();
        uploadProcesses[uploadId].push(controller);

        try {
            const startTime = performance.now();
            const response = await fetch(authRoute('user.folder-factory.files.upload.chunk'), {
                method: 'POST',
                headers: { 'x-csrf-token': csrfToken },
                body: formData,
                signal: controller.signal,
            });

            uploadProcesses[uploadId] = uploadProcesses[uploadId].filter(c => c !== controller);

            if (!response.ok) {
                let text;
                try { text = await response.json(); } catch (e) { text = await response.text(); }
                throw new Error(`Chunk ${index} failed: ${response.status} - ${JSON.stringify(text)}`);
            }

            const endTime = performance.now();
            uploadChunkTimes[uploadId].push((endTime - startTime) / 1000);

            uploadedChunks.push(index);
            localStorage.setItem(uploadId, JSON.stringify(uploadedChunks));
            updateProgressBar(uploadId, progressBar, file, uploadedChunks, totalChunks);

            if (uploadedChunks.length === totalChunks) {
                onCompleteUpload(uploadId, progressBar);
            }

            return true;
        } catch (err) {
            if (err.name === 'AbortError') throw err;
            throw err;
        }
    };

    if (!uploadedChunks.includes(0)) {
        let retry = 0;
        const maxRetries = 3;
        while (retry < maxRetries) {
            try {
                await doUploadChunk(0);
                break;
            } catch (err) {
                retry++;
                console.warn(`Failed to upload chunk 0 (attempt ${retry}):`, err);
                if (retry >= maxRetries) {
                    alert('Failed to upload initial chunk (0). Aborting upload.');
                    if (uploadProcesses[uploadId]) uploadProcesses[uploadId].forEach(c => c.abort());
                    removeFromUploadingFiles(uploadId);
                    return;
                }
                await new Promise(r => setTimeout(r, 500 * retry));
            }
        }
    }

    const uploadChunk = async (index) => {
        if (uploadedChunks.includes(index) || index >= totalChunks) return;
        let attempts = 0, maxRetries = 3;
        while (attempts < maxRetries) {
            try {
                await doUploadChunk(index);
                return;
            } catch (err) {
                if (err.name === 'AbortError') return;
                attempts++;
                console.warn(`Retry chunk ${index} (${attempts}/${maxRetries}):`, err);
                await new Promise(r => setTimeout(r, 300 * attempts));
            }
        }
        alert(`Failed to upload chunk ${index} after ${maxRetries} attempts.`);
    };

    const uploadChunksInParallel = async () => {
        const promises = [];
        for (let i = 1; i < maxParallelUploads + 1; i++) {
            promises.push(processNextChunk(i - 1));
        }
        await Promise.all(promises);
    };

    async function processNextChunk(workerIndex) {
        for (let i = workerIndex + 1; i < totalChunks; i += maxParallelUploads) {
            if (uploadedChunks.includes(i)) continue;
            await uploadChunk(i);
        }
    }

    await uploadChunksInParallel();
}


async function getUploadedChunks(uploadId, csrfToken) {
    try {
        const response = await fetch(authRoute('user.folder-factory.file.upload.status') + `?uploadId=${encodeURIComponent(uploadId)}`, {
            headers: { 'Content-Type': 'application/json', 'x-csrf-token': csrfToken },
        });
        const data = await response.json();
        return data.uploadedChunks || [];
    } catch (error) {
        console.error('Failed to fetch uploaded chunks:', error);
        return [];
    }
}

async function cancelUploading(uploadId, csrfToken) {
    try {
        const response = await fetch(authRoute('user.folder-factory.files.upload.cancel'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'x-csrf-token': csrfToken },
            body: JSON.stringify({ uploadId }),
        });
        const data = await response.json();
        return data.success || false;
    } catch (error) {
        console.error('Failed to cancel uploading:', error);
        return false;
    }
}

function createProgressBar(uploadId, file, uploadedChunks, totalChunks) {
    const template = document.getElementById("file-progress-template");
    let uploadProgress = template.content.cloneNode(true);

    const fileService = new FileService(file);
    const progressBarId = `progress-` + crypto.randomUUID();

    uploadProgress.querySelector('.upload-progress-card').id = progressBarId;
    uploadProgress.querySelector('#file_name').innerHTML = fileService.getName();
    uploadProgress.querySelector('#time_remaining').innerHTML = "Calculating...";
    uploadProgress.querySelector('#completed_percentage').innerHTML = "0% Completed";
    uploadProgress.querySelector('#progress_bar').style.width = "0%";
    uploadProgress.querySelector('#file_size').innerHTML = fileService.getSize(file);
    uploadProgress.querySelector('#file_icon').classList.add(fileService.getIconFromExtension(fileService.getExtension()));

    const container = document.querySelector("#processing_files");
    container.appendChild(uploadProgress.cloneNode(true));

    let cancelBtn = container.querySelector(`#${progressBarId} #stop-upload`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    cancelBtn.addEventListener('click', async () => {
        if (uploadProcesses[uploadId]) uploadProcesses[uploadId].forEach(controller => controller.abort());
        delete uploadProcesses[uploadId];

        removeFromUploadingFiles(uploadId);
        await cancelUploading(uploadId, csrfToken);

        const removeElement = document.querySelector(`#${progressBarId}`);
        if (removeElement) removeElement.remove();
    });

    return container.querySelector(`#${progressBarId}`);
}

function updateProgressBar(uploadId, progressBar, file, uploadedChunks, totalChunks) {
    const progress = Math.round((uploadedChunks.length / totalChunks) * 100);
    progressBar.querySelector('#progress_bar').style.width = `${progress}%`;
    progressBar.querySelector('#progress_bar').textContent = `${progress}%`;
    progressBar.querySelector('#completed_percentage').innerHTML = `${progress}% Completed`;

    let secondsToComplete = calculateAverageUploadTimeForFile(uploadId, uploadedChunks, totalChunks);
    const readableTime = Utility.formatTimeFromSeconds(secondsToComplete);
    progressBar.querySelector('#time_remaining').innerHTML = readableTime;
}

function calculateAverageUploadTimeForFile(fileKey, uploadedChunks, totalChunks) {
    if (!uploadChunkTimes.hasOwnProperty(fileKey)) return null;

    const fileUploadTimes = uploadChunkTimes[fileKey];
    const averageUploadTime = fileUploadTimes.reduce((sum, time) => sum + time, 0) / fileUploadTimes.length;
    const timeToComplete = (totalChunks * averageUploadTime) - (uploadedChunks.length * averageUploadTime);
    return timeToComplete.toFixed();
}

function onCompleteUpload(uploadId, progressBar) {
    removeFromUploadingFiles(uploadId);
    setTimeout(() => progressBar.remove(), 1000);
}

function addToUploadingFiles(uploadId) {
    const savedUploadings = JSON.parse(localStorage.getItem(UPLOADING_FILES_KEY)) || [];
    const newUploadings = Array.from(new Set([...savedUploadings, uploadId]));
    localStorage.setItem(UPLOADING_FILES_KEY, JSON.stringify(newUploadings));
}

function removeFromUploadingFiles(uploadId) {
    localStorage.removeItem(uploadId);
    const savedUploadings = JSON.parse(localStorage.getItem(UPLOADING_FILES_KEY)) || [];
    const updatedUploadings = savedUploadings.filter(item => item !== uploadId);
    if (updatedUploadings.length) {
        localStorage.setItem(UPLOADING_FILES_KEY, JSON.stringify(updatedUploadings));
    } else {
        localStorage.removeItem(UPLOADING_FILES_KEY);
    }
}

function emptyFileSelection() {
    const uploadArea = document.querySelector("#uploadFileArea");
    const hiddenFileInput = uploadArea.querySelector("input[type='file']");
    uploadDropzone.removeAllFiles(true);
    hiddenFileInput.files = new DataTransfer().files;
}
