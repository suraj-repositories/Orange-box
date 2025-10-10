document.addEventListener('DOMContentLoaded', () => {
    enableUserSelection(".pick-user-btn");
    setupSearchInput();
});

function enableUserSelection(selector) {
    const btns = document.querySelectorAll(selector);
    if (!btns) return;

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            const userPickResult = document.querySelector("#pick-user-modal #user-pick-results");
            if (userPickResult) {
                userPickResult.innerHTML = "";
            }
            const searchInput = document.querySelector("#pick-user-modal input[type='search']");
            if (searchInput) {
                searchInput.value = "";
            }
            $("#pick-user-modal").modal('show');
        });
    });
}

function setupSearchInput() {
    const searchInput = document.querySelector("#pick-user-modal input[type='search']");
    if (!searchInput) return;

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            fetchUsers(searchInput.value);
        }
    });

    searchInput.addEventListener('keyup', function (e) {
        fetchUsers(searchInput.value);
    });
}

function fetchUsers(username) {
    const userPickResult = document.querySelector("#pick-user-modal #user-pick-results");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    userPickResult.innerHTML = `<div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                </div>`;

    fetch(route('search.username'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'x-csrf-token': csrfToken
        },
        body: JSON.stringify({ username })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
                userPickResult.innerHTML = "";

                data.data.forEach(user => {
                    userPickResult.appendChild(createSearchResultUser(user, () => {
                        const userChip = createUserChip(user);
                        const pickUserBtn = document.querySelector('.pick-user-btn');
                        if (pickUserBtn) {
                            const alreadyHere = pickUserBtn.parentElement.querySelector("[data-ob-uid='" + user.id + "']");
                            if (!alreadyHere) {
                                pickUserBtn.insertAdjacentElement("beforebegin", userChip);

                            }
                            $("#pick-user-modal").modal('hide');
                        }
                    }));
                });

            }

        })
        .catch(error => {
            console.error('Error:', error);
            userPickResult.innerHTML = "<p class='text-center text-danger'>ERROR</p>";
        });
}


function createSearchResultUser(user, onuserpick) {
    const userItem = document.createElement('a');
    userItem.href = 'javascript::void()';
    userItem.className = 'user-item';
    userItem.addEventListener('click', onuserpick);

    const img = document.createElement('img');
    img.src = user.avatar;
    img.alt = user.username;

    const span = document.createElement('span');
    span.textContent = user.username;

    const icon = document.createElement('i');
    icon.className = 'bx bxs-circle ms-auto p-2';

    userItem.appendChild(img);
    userItem.appendChild(span);
    userItem.appendChild(icon);

    return userItem;

}
function createUserChip(user) {
    const chip = document.createElement('div');
    chip.className = 'chip';
    chip.setAttribute('data-ob-uid', user.id);

    const img = document.createElement('img');
    img.src = user.avatar;
    img.alt = user.username;
    img.width = 96;
    img.height = 96;

    const text = document.createTextNode(user.username);

    const input = document.createElement('input');
    input.type = "hidden";
    input.name = "user[]";
    input.value = user.id;

    const closeBtn = document.createElement('span');
    closeBtn.className = 'closebtn';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function () {
        chip.remove();
    }

    chip.appendChild(img);
    chip.appendChild(text);
    chip.appendChild(input);
    chip.appendChild(closeBtn);

    return chip;
}
