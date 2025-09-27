document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll("form:has(textarea.summernote)").forEach(form => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.querySelectorAll(".summernote").forEach(area => {
            let uploadedImages = [];

            $(area).summernote({
                placeholder: 'Write here...',
                tabsize: 2,
                height: 250,
                disableResizeEditor: false,
                fontNames: [
                    'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather',
                    'Georgia', 'Impact', 'Times New Roman', 'Trebuchet MS', 'Verdana',
                    'Roboto', 'Lora', 'Open Sans', 'Monospace', 'Tahoma'
                ],
                fontNamesIgnoreCheck: [
                    'Roboto', 'Merriweather', 'Lora', 'Open Sans'
                ],
                toolbar: [
                    ['style', ['undo', 'redo', 'style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontstyle', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],

                callbacks: {
                    onImageUpload: function (files) {
                        const $editor = $(this);

                        Array.from(files).forEach(file => {
                            document.body.style.cursor = 'wait';
                            const formData = new FormData();
                            formData.append('file', file);

                            const url = area.getAttribute('data-image-save-url');
                            if (!url) return;

                            fetch(url, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.url) {
                                        $editor.summernote('insertImage', data.url);
                                        uploadedImages.push(data.url);
                                    } else {
                                        alert('Failed to upload image.');
                                    }
                                    document.body.style.cursor = 'default';
                                })
                                .catch(error => {
                                    console.error('Image upload error:', error);
                                    document.body.style.cursor = 'default';
                                    alert('Image upload failed.');
                                });
                        });
                    },

                    onMediaDelete: function (target) {
                        const src = target[0].src;
                        deleteImage(src);
                        uploadedImages = uploadedImages.filter(url => url !== src);
                    }
                }
            });

            form.addEventListener('submit', function () {
                const content = $(area).summernote('code');
                const div = document.createElement('div');
                div.innerHTML = content;

                const currentImages = Array.from(div.querySelectorAll('img')).map(img => img.src);

                const deletedImages = uploadedImages.filter(url => !currentImages.includes(url));
                deletedImages.forEach(src => deleteImage(src));

                uploadedImages = currentImages;
            });


            function deleteImage(src) {
                if (!src) return;
                fetch(area.getAttribute('data-image-delete-url') || '/delete/image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ src })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) console.warn('Failed to delete image on server:', src);
                    })
                    .catch(err => console.error('Error deleting image:', err));
            }

        });
    });
});
