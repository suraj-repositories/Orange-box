 function enableSelect2WithImage() {
    const formatOption = (option) => {
        if (!option.id) return option.text;
        const imageUrl = $(option.element).data('image');
        if (!imageUrl) return option.text;

        const imgClass = $(option.element).data('image-class') ?? "";

        return $(`
            <span style="display:flex; align-items:center;">
                <img src="${imageUrl}" class="select2-image ${imgClass}">
                <span>${option.text}</span>
            </span>
        `);
    };

    $('.select2-with-image').select2({
        templateResult: formatOption,
        templateSelection: formatOption,
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: "Select an option",
    });
}

$(function() {
    enableSelect2WithImage();
});
