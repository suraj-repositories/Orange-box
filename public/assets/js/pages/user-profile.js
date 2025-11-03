
document.addEventListener('DOMContentLoaded', function () {
    new UserSkillControl().init();

    new ProfileImageEditor("#profile_image_edit_btn", ".user_profile_picture").init();
})


class UserSkillControl {

    init() {
        this.populateUserSkills("#user_skill_viewer");
        this.enableUserSkillCreation("#add_skill_form", "#user_skill_viewer");

    }
    populateUserSkills(selector) {
        const view = document.querySelector(selector);
        if (!view) {
            return;
        }
        const skills = JSON.parse(view.getAttribute('data-user-skills'));

        skills.forEach(skill => {
            this.addSkillBadge('#user_skill_viewer', skill.id, skill.skill, skill.level);
        });

    }

    enableUserSkillCreation(selector, viewerSelector) {
        const form = document.querySelector(selector);
        const view = document.querySelector(viewerSelector);


        if (!form || !view) return;
        const saveBtn = form.querySelector('button[type="submit"]');


        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const formSubmitUrl = authRoute('user.profile.user_skill.save');

            saveBtn.disabled = true;
            saveBtn.textContent = "Adding...";

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(formSubmitUrl, {
                method: 'POST',
                headers: { 'x-csrf-token': csrfToken },
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === "success") {
                        this.addSkillBadge(viewerSelector, data.skill.id, data.skill.skill, data.skill.level);

                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        });

                        form.reset();
                    } else {
                        Swal.fire("Oops!", data.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Oops!", err.message, "error");
                })
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.textContent = "Add";
                });
        });
    }

    addSkillBadge(containerSelector, skillId, skillName, percentage) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const skillDiv = document.createElement('div');
        skillDiv.className = 'skill-badge';
        skillDiv.innerHTML = `
        <div class="skill_name">${skillName}</div>
        <div class="percentage">${percentage}%</div>
        <div class="close-btn">&times;</div>
    `;

        skillDiv.querySelector('.close-btn').addEventListener('click', () => {

            skillDiv.classList.add('deleting');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(authRoute('user.profile.user_skill.delete', { userSkill: skillId }), {
                method: 'DELETE',
                headers: { 'x-csrf-token': csrfToken },

            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === "success") {

                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: "success"
                        });
                        skillDiv.remove();
                    } else {
                        Swal.fire("Oops!", data.message, "error");
                        skillDiv.classList.remove('deleting');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Oops!", err.message, "error");
                    skillDiv.classList.remove('deleting');
                });
        });

        container.appendChild(skillDiv);
    }


}

class ProfileImageEditor {

    constructor(inputElementSelector, reflectorImagesSelector) {
        this.inputElementSelector = inputElementSelector;
        this.reflectorImagesSelector = reflectorImagesSelector;
    }

    init() {

        const input = document.querySelector(this.inputElementSelector);
        const submitUrl = input.getAttribute('data-image-submit-url');

        if (!submitUrl || !input) {
            return;
        }
        input.addEventListener('change', async () => {
            const uploadFile = input.files[0];
            if (!uploadFile) {
                return;
            }
            this.setImageToReflectors(uploadFile);
            this.uploadImage(uploadFile, submitUrl);

        });

    }

    uploadImage(file, submitUrl) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let formData = new FormData();
        formData.append('image', file);

        fetch(submitUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.success) {

                } else {
                    Swal.fire("Oops!", data.message, "error");
                }
            })
            .catch(error => {
                Swal.fire("Oops!", error.message, "error");
            });
    }

    setImageToReflectors(file) {
        const reader = new FileReader();
        const selector = this.reflectorImagesSelector
        reader.onload = function (e) {
            const images = document.querySelectorAll(selector);
            console.log(images);
            if(!images){
                return;
            }
            images.forEach(img => {
                img.src = e.target.result;
            });
        };
        reader.readAsDataURL(file);
    }
}

