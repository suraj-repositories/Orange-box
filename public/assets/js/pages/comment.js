
document.addEventListener("DOMContentLoaded", () => {
    enableCommentFeature();
    loadComments("#ob-comment-list");
});

function enableCommentFeature() {
    const commentTextarea = document.getElementById("comment-message-box");
    const postBtn = document.getElementById("comment-post-btn");

    postBtn.addEventListener("click", function () {
        let message = commentTextarea.value.trim();

        if (message === "") {
            Swal.fire({
                title: "Oops...",
                text: 'Please write a comment before posting.',
                icon: "error"
            });
            return;
        }
        const commentableType = postBtn.getAttribute('data-ob-commentable-type');
        const commentableId = postBtn.getAttribute('data-ob-commentable-id');
        const parentId = postBtn.getAttribute('data-ob-parent-id') ?? null;


        saveComment(commentTextarea, commentableType, commentableId, parentId);
    });


}

function saveComment(commentInput, commentableType, commentableId, parentId) {
    fetch(authRoute('user.comments.store', {}), {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({
            commentable_type: commentableType,
            commentable_id: commentableId,
            parent_id: parentId,
            message: commentInput.value.trim()
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {

                commentInput.value = "";
                if(parentId == null || parentId == undefined || parentId == ""){
                    loadComments("#ob-comment-list", args = { page: 1 });
                }

                Swal.fire({
                    title: "Success",
                    text: data.message,
                    icon: "success"
                });
            } else {
                Swal.fire({
                    title: "Oops...",
                    text: data.message,
                    icon: "error"
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: "Oops...",
                text: 'Failed to post comment.',
                icon: "error"
            });

        });
}

function loadComments(selector, args = { page: 1 }) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const commentBox = document.querySelector(selector);
    const loader = commentBox.querySelector('.loader');
    const loadMoreBtn = document.getElementById("ob-load-more-comments");

    if (!commentBox) return;

    const commentableType = commentBox.getAttribute("data-ob-commentable-type");
    const commentableId = commentBox.getAttribute("data-ob-commentable-id");

    if (!commentableType || !commentableId) return;

    let page = args.page;
    let loading = false;
    let hasMore = true;

    async function fetchComments() {
        if (loading || !hasMore) return;
        loading = true;

        try {
            const response = await fetch(authRoute('user.comments.load.comments'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({
                    commentable_type: commentableType,
                    commentable_id: commentableId,
                    page: page
                })
            });

            const result = await response.json();
            if (loader) {
                loader.remove();
            }
            loadMoreBtn.classList.remove('loading');
            loadMoreBtn.querySelector('.btn-text').textContent = 'Load More';
            if (result.status === 200) {
                if (page == 1) {
                    commentBox.innerHTML = result.data;

                } else {
                    commentBox.insertAdjacentHTML("beforeend", result.data);
                }
                page++;
                enableReplyBtns();
            } else if (result.status === 204) {
                hasMore = false;
                const btn = document.getElementById("ob-load-more-comments");
                if (btn) btn.style.display = "none";

                if (!commentBox.querySelector(".no-more-comments")) {

                    commentBox.insertAdjacentHTML(
                        "beforeend",
                        `<div class="text-center text-muted p-2 no-more-comments">No ${page == 1 ? '' : 'More '}comments</div>`
                    );
                }
            } else if (result.status === 404) {
                hasMore = false;
                commentBox.innerHTML = `<div class="text-center text-danger p-2">Commentable not found</div>`;
            }
        } catch (error) {
            console.error("Error loading comments:", error);
        } finally {
            loading = false;
        }
    }

    fetchComments();


    if (loadMoreBtn) {
        loadMoreBtn.addEventListener("click", () => {
            loadMoreBtn.classList.add('loading');
            loadMoreBtn.querySelector('.btn-text').textContent = 'Loading...';
            fetchComments();
        });
    }
}

function enableReplyBtns() {
    const commentBox = document.querySelector("#ob-comment-list");

    if (!commentBox) return;

    const replyBtns = commentBox.querySelectorAll('.reply-btn');

    replyBtns.forEach(btn => {
        const isListenerAdded = btn.getAttribute('is-listener-added');
        if (isListenerAdded && isListenerAdded == 'true') return;

        btn.setAttribute("is-listener-added", 'true');
        btn.addEventListener('click', function () {
            const parent = btn.parentElement;
            const replyUser = btn.getAttribute("data-ob-replyto");
            const replierPhotoElement = document.querySelector('img.user-image');
            if (!parent || parent.parentElement.querySelector('.reply-form')) {
                return;
            }
            let replierPhotoSrc = "/assets/images/defaults/user.png";
            if (replierPhotoElement) {
                replierPhotoSrc = replierPhotoElement.src;
            }

            const commentableType = btn.getAttribute('data-ob-commentable-type');
            const commentableId = btn.getAttribute('data-ob-commentable-id');
            const parentId = btn.getAttribute('data-ob-parent-id');

            parent.insertAdjacentHTML(
                "afterend",
                `<div class="d-flex reply-form mt-2">
                            <img class="rounded-circle comment-img" src="${replierPhotoSrc}">
                            <div class="flex-grow-1 ms-3">
                                <div class="mb-2">
                                    <div class="text-body-secondary small">Replying to <i class="text-primary">@${replyUser}</i></div>
                                </div>
                                <div class="form-floating comment-compose mb-2">
                                    <textarea class="form-control w-100" id="my-comment-reply"  placeholder="Leave a comment here" resizeable="true"></textarea>
                                </div>
                                <div class="hstack justify-content-end gap-1">
                                    <button class="btn btn-sm btn-secondary rounded-pill" onclick="deleteThisReplyForm(this)">Cancel</button>
                                    <button class="btn btn-sm btn-primary rounded-pill" onclick="replyComment(this, '${commentableType}', ${commentableId}, ${parentId})">Comment</button>
                                </div>
                            </div>
                        </div>`
            );

            new App().initResizeableTextArea();
        });
    });



}

function deleteThisReplyForm(deleteBtn) {
    const replyForm = deleteBtn.closest(".reply-form");
    if (replyForm) {
        replyForm.remove();
    }
}

function replyComment(button, commentableType, commentableId, parentId) {

    const replyForm = button.closest(".reply-form");
    if (replyForm) {

        const textarea = replyForm.querySelector('textarea');
        if (!textarea) {
            return;
        }
        saveComment(textarea, commentableType, commentableId, parentId);
    }
}

function loadReplyBtnClick(loadMoreBtn){
    const commentableType = loadMoreBtn.getAttribute("data-ob-commentable-type");
    const commentableId = loadMoreBtn.getAttribute("data-ob-commentable-id");
    const commentId = loadMoreBtn.getAttribute("data-ob-comment-id");
    let page = parseInt(loadMoreBtn.getAttribute("data-ob-page"));

    loadReplies(loadMoreBtn, commentableType, commentableId, commentId, page);

}

function loadReplies(loadMoreBtn, commentableType, commentableId, commentId, page) {

    const replyBox = loadMoreBtn.parentElement;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const loader = replyBox.querySelector('.loader');
    let loading = false;
    let hasMore = true;

    async function fetchReplies() {
        if (loading || !hasMore) return;
        loading = true;
        loadMoreBtn.classList.add('loading');
        loadMoreBtn.querySelector('.btn-text').textContent = 'Loading...';

        try {

            const response = await fetch(authRoute('user.comments.load.replies'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({
                    commentable_type: commentableType,
                    commentable_id: commentableId,
                    comment_id: commentId,
                    page: page
                })
            });

            const result = await response.json();
            if (loader) {
                loader.remove();
            }
            loadMoreBtn.classList.remove('loading');
            loadMoreBtn.querySelector('.btn-text').textContent = 'Load More';
            if (result.status === 200) {
                loadMoreBtn.insertAdjacentHTML("beforebegin", result.data);
                page++;
                enableReplyBtns();
            } else if (result.status === 204) {
                hasMore = false;
                loadMoreBtn.outerHTML = `<div class="text-center text-muted p-2 no-more-replies">No More Replies</div>`;

            } else if (result.status === 404) {
                hasMore = false;
                loadMoreBtn.outerHTML = `<div class="text-center text-muted p-2 no-more-replies">Something went wrong!</div>`;

            }
            loadMoreBtn.setAttribute('data-ob-page', page);
        } catch (error) {
            console.error("Error loading comments:", error);
        } finally {
            loading = false;
        }
    }

    fetchReplies();

}
