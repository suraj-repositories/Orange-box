document.addEventListener('DOMContentLoaded', function () {
    enableFaqCreation('#create-sponsor-button');
    // enableStatusUpdate(".faqStatusToggleSwitch");
    // enableFaqViewer('.show-faq');
    // enableFaqEditing('.edit-faq');
});



function enableFaqCreation(selector) {
    const button = document.querySelector(selector);
    if (!button) return;
    button.addEventListener('click', openFaqCreateModal);
}

function openFaqCreateModal() {

    const modalEl = document.getElementById('sponsor-form-modal');

    document.getElementById('sponsor-id').value = '';
    document.getElementById('sponsor-name-input').value = '';

    const editorEl = document.querySelector('#description-editor');

    editorEl.value = '';

    editorEl.dataset.markdown = '';
    if (editorEl.editorInstance) {
        editorEl.editorInstance.setData('');
    }

    document.getElementById('sponsor-creation-form-title').innerText = "Create Sponsor";

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function enableStatusUpdate(selector) {
    const checkboxes = document.querySelectorAll(selector);
    if (checkboxes.length <= 0) return;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const status = checkbox.checked ? 'active' : 'inactive';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(authRoute('user.documentation.faqs.status.update', { faq: checkbox.dataset.documentationFaqId }), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toastify.success(data.message);
                    } else {
                        Toastify.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify.error("Something went wrong!");
                });

        });
    });
}

function enableFaqEditing(selector) {
    const btns = document.querySelectorAll(selector);
    const modal = document.querySelector("#faq-creation-form-modal");

    if (!btns || !modal) return;

    btns.forEach(btn => {

        btn.addEventListener("click", () => {

            const form = modal.querySelector("#faq-creation-form");
            const title = modal.querySelector("#faq-creation-form-title");

            form.reset();

            title.textContent = "Edit FAQ";

            const questionInput = form.querySelector("[name='question']");
            const answerInput = form.querySelector("[name='answer']");
            const faqIdInput = form.querySelector("[name='faq_id']");

            const question = btn.getAttribute("data-question") || "";
            const answer = btn.getAttribute("data-answer") || "";
            const faqId = btn.getAttribute("data-id") || "";

            questionInput.value = question;

            answerInput.value = answer;
            answerInput.dataset.markdown = answer;

            faqIdInput.value = faqId;

            $(modal).modal("show");

        });

    });

}

function enableFaqViewer(selector) {

    const buttons = document.querySelectorAll(selector);
    const modalEl = document.getElementById('faq-view-modal');

    if (!buttons.length || !modalEl) return;

    const modal = new bootstrap.Modal(modalEl);

    buttons.forEach(btn => {

        btn.addEventListener('click', () => {

            const question = btn.dataset.question || '';
            const answer = btn.dataset.answer || '';

            document.getElementById('faq-view-question').textContent = question;
            document.getElementById('faq-view-answer').innerHTML = answer;

            modal.show();

        });

    });

}
