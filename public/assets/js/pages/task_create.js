document.addEventListener('DOMContentLoaded', function () {
    enableModuleLoading("#pick-project", "#select-module");
    enableUserLoading("#select-module", "#assigned-to-input");
});

function enableModuleLoading(sourceSelector, targetSelector, token = null) {
    const $source = $(sourceSelector);
    const $target = $(targetSelector);

    // Initialize Select2
    $source.select2();
    $target.select2();

    // Listen for change on source select
    $source.on('change', function () {
        const projectId = $(this).val();
        console.log(projectId);
        if (!projectId) {
            // Clear target select if no project selected
            $target.empty().append('<option value="">Select Module</option>').trigger('change');
            return;
        }

        // Build headers for AJAX (include token if provided)
        const headers = { 'Accept': 'application/json' };
        if (token) headers['Authorization'] = `Bearer ${token}`;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // Fetch modules
        $.ajax({
            url: `/api/project-board/${projectId}/modules`,
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-XSRF-TOKEN': csrfToken
            },
            xhrFields: { withCredentials: true },
            beforeSend: function () {
                $target.empty().append('<option>Loading...</option>').trigger('change');
            },
            success: function (data) {
                $target.empty().append('<option value="">Select Module</option>');
                data.forEach(module => $target.append(new Option(module.name, module.id)));
                $target.trigger('change');
            },
            error: function (err) {
                console.error(err);
                $target.empty().append('<option value="">Failed to load</option>').trigger('change');
            }
        });

    });

    // Optional: trigger fetch if source already has a value on page load
    if ($source.val()) $source.trigger('change');
}


function enableUserLoading(sourceSelector, targetSelector) {

}
