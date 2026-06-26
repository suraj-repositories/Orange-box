document.addEventListener('DOMContentLoaded', function () {
    const dt = new DataTransfer();

    enableImagePreview(dt);
    enableFileDelete();

    document.querySelector('#templateForm').addEventListener('submit', function (e) {
        const input = document.querySelector('#multiImagePicker');

        if (input) {
            input.files = dt.files;
        }

    });
});

function enableFileDelete() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-btn');

        if (!btn) return;

        const url = btn.dataset.deletionUrl;
        const imageBox = btn.closest('.image-box');

        if (!url || btn.disabled) return;

        btn.disabled = true;
        imageBox?.classList.add('deleting');

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success || data.status == 200) {
                    imageBox?.remove();
                } else {
                    imageBox?.classList.remove('deleting');
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error(error);
                imageBox?.classList.remove('deleting');
                btn.disabled = false;
            });
    });
}

function enableImagePreview(dt) {
    const input = document.querySelector('#multiImagePicker');
    const container = document.querySelector('#multiImageContainer');

    if (!input || !container) return;

    input.addEventListener('change', function () {
        Array.from(this.files).forEach(file => {
            dt.items.add(file);
        });

        renderPreviews();

        this.value = '';
    });

    function renderPreviews() {
        container.querySelectorAll('.image-box.preview').forEach(el => el.remove());

        Array.from(dt.files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                const imageBox = document.createElement('div');
                imageBox.className = 'image-box preview';

                imageBox.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <button type="button" class="remove-btn">&times;</button>
                `;

                imageBox.querySelector('.remove-btn').addEventListener('click', () => {
                    const newDt = new DataTransfer();

                    Array.from(dt.files).forEach((f, i) => {
                        if (i !== index) {
                            newDt.items.add(f);
                        }
                    });

                    while (dt.items.length) {
                        dt.items.remove(0);
                    }

                    Array.from(newDt.files).forEach(file => {
                        dt.items.add(file);
                    });

                    // Refresh previews
                    renderPreviews();
                });

                container.appendChild(imageBox);
            };

            reader.readAsDataURL(file);
        });
    }
}
