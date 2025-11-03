document.addEventListener('DOMContentLoaded', function () {
    enableModuleLoading("#pick-project", "#select-module");
    enableUserLoading("#select-module", "#assigned-to-input");

    enableOpenLink("#OpenSelectedModuleBtn", "#select-module");
    enableOpenLink("#OpenSelectedProjectBtn", "#pick-project");
});

function enableModuleLoading(sourceSelector, targetSelector) {
    const $source = $(sourceSelector);
    const $target = $(targetSelector);

    $source.on('change', function () {
        const projectId = $(this).val();

        if (!projectId) {
            $target.empty().append('<option value="">Select Module</option>').trigger('change');
            return;
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const selectedValue = $target.attr('data-selected-value');
        $.ajax({
            url: route('ajax.project-board.modules', { projectBoard: projectId }),
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-XSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $target.empty().append('<option>Loading...</option>').trigger('change');
            },
            success: function (data) {
                $target.empty().append('<option value="">Select Module</option>');
                data.data.forEach(module => {
                    const option = new Option(module.name, module.id);
                    $(option).attr('data-public-url', module.public_url);

                    if (selectedValue && module.id == selectedValue) {
                        $(option).attr('selected', true);
                    }
                    $target.append(option);
                });
                $target.trigger('change');
            },
            error: function (err) {
                console.error(err);
                $target.empty().append('<option value="">Failed to load</option>').trigger('change');
            }
        });

    });

    if ($source.val()) $source.trigger('change');
}

function enableUserLoading(sourceSelector, targetSelector) {
    const $source = $(sourceSelector);
    const $target = $(targetSelector);

    $source.on('change', function () {
        const projectModuleId = $(this).val();
        if (!projectModuleId || isNaN(projectModuleId)) {
            $target.empty().append('<option value="">Select Assignee</option>').trigger('change');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const selectedValue = $target.attr('data-selected-value');
        $.ajax({
            url: route('ajax.project-module.getAssignees', { projectModule: projectModuleId }),
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-XSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $target.empty().append('<option>Loading...</option>').trigger('change');
            },
            success: function (data) {
                $target.empty().append('<option value="">Select Assignee</option>');

                data.data.forEach(user => {
                    const option = new Option(user.username, user.id, false, false);
                    $(option).attr('data-image', user.avatar_url);
                    if (selectedValue && user.id == selectedValue) {
                        $(option).attr('selected', true);
                    }
                    $target.append(option);
                });

                enableSelect2WithImageFor($target);

                $("#due-date-input").attr("max", data.module_due_date);

                $target.trigger('change');
            },
            error: function (err) {
                console.error(err);
                $target.empty().append('<option value="">Failed to load</option>').trigger('change');
            }
        });
    });

    if ($source.val()) $source.trigger('change');
}

function enableSelect2WithImageFor($element) {
    const formatOption = (option) => {
        if (!option.id) return option.text;
        const imageUrl = $(option.element).data('image');
        if (!imageUrl) return option.text;
        return $(`
            <span style="display:flex; align-items:center;">
                <img src="${imageUrl}" style="width:28px;height:28px;border-radius:50%;margin-right:8px;">
                <span>${option.text}</span>
            </span>
        `);
    };

    $element.select2({
        templateResult: formatOption,
        templateSelection: formatOption,
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: "Select an option"
    });
}

function enableOpenLink(actionSourceSelector, inputSourceSelector) {
    const actionSource = document.querySelector(actionSourceSelector);
    const select = document.querySelector(inputSourceSelector);
    const redirectLink = select.closest(".select-box").querySelector(".redirect-link");

    actionSource.addEventListener('click', function () {
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption) {
            const url = selectedOption.dataset.publicUrl;

            if (url) {
                redirectLink.href = url;
                redirectLink.click();
            } else {
                console.warn('No url found for selected option');
                alert("No url found for selected option");
            }
        } else {
            alert("No option selected");
            console.warn('No option selected');
        }
    });

}
