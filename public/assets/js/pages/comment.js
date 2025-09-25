
document.addEventListener("DOMContentLoaded", () => {
    enableCommentFeature();
    loadComments("#ob-comment-list");
    enableCancelComment("#canelComment", '#comment-message-box');
});

function enableCommentFeature() {
    const commentTextarea = document.getElementById("comment-message-box");
    const postBtn = document.getElementById("comment-post-btn");

    postBtn.addEventListener("click", async function () {
        let message = commentTextarea.value.trim();
        const prevPostBtnText = postBtn.textContent;

        if (message === "") {
            Swal.fire({
                title: "Oops...",
                text: 'Please write a comment before posting.',
                icon: "error"
            });
            return;
        }
        postBtn.textContent = "Saving...";
        const commentableType = postBtn.getAttribute('data-ob-commentable-type');
        const commentableId = postBtn.getAttribute('data-ob-commentable-id');
        const parentId = postBtn.getAttribute('data-ob-parent-id') ?? null;


        await saveComment(commentTextarea, commentableType, commentableId, parentId);
        commentTextarea.style.height = "56px";

        postBtn.textContent = "Comment Saved!";
        setTimeout(() => {
            postBtn.textContent = prevPostBtnText;
        }, 2000);

    });


}

function enableCancelComment(btnSelector, textAreaSelector) {
    const btn = document.querySelector(btnSelector);
    const textArea = document.querySelector(textAreaSelector);
    btn.addEventListener('click', () => {
        textArea.style.height = "56px";
        textArea.value = "";
    });
}

async function saveComment(commentInput, commentableType, commentableId, parentId, counterElement = null) {
    await fetch(authRoute('user.comments.store', {}), {
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
                if (parentId == null || parentId == undefined || parentId == "") {
                    loadComments("#ob-comment-list", args = { page: 1 });
                    // Swal.fire({
                    //     title: "Success",
                    //     text: data.message,
                    //     icon: "success"
                    // });
                } else {
                    const replyForm = commentInput.closest(".reply-form");
                    if (replyForm) {
                        const commentBox = replyForm.closest(".comment-box");
                        const replyBox = commentBox.querySelector('.comment-replies');
                        const collapse = commentBox.querySelector('.collapse');
                        if (collapse && !collapse.classList.contains('show')) {
                            collapse.classList.add('show');
                        }

                        if (replyBox) {
                            const loadMoreBtn = replyBox.querySelector("button.load-more-replies-btn");
                            const noMoreCommentMsg = replyBox.querySelector(".no-more-replies");
                            if (loadMoreBtn) {
                                loadMoreBtn.insertAdjacentHTML("beforebegin", data.html);
                            } else if (noMoreCommentMsg) {
                                noMoreCommentMsg.outerHTML = data.html;
                            } else {
                                replyBox.insertAdjacentHTML("beforeend", data.html);
                            }
                        }
                        replyForm.remove();
                        enableReplyBtns();
                    }

                }

                if (counterElement) {
                    let num = parseInt(counterElement.textContent.trim());
                    counterElement.textContent = (num + 1) + "";
                }

                setTotalCommentCount(data.total_comments);


            } else {
                Swal.fire({
                    title: "Oops...",
                    text: data.message,
                    icon: "error"
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: "Oops...",
                text: 'Failed to post comment.',
                icon: "error"
            });

        });
}

function setTotalCommentCount(count) {
    const counters = document.querySelectorAll('.total_comment_count');
    if (counters) {
        counters.forEach(counter => {
            if (!isNaN(count)) {
                counter.textContent = count;
            } else {
                counter.textContent = 0;
            }

        });
    }
}

function loadComments(selector, args = { page: 1, order: 'desc' }) {
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
                    page: page,
                    order: args.order
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

        btn.addEventListener('click', function () {
            addLisenerToReplyBtn(btn);
        });
        btn.setAttribute("is-listener-added", 'true');
    });
}

function addLisenerToReplyBtn(btn) {
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
                    <button class="btn btn-sm btn-primary rounded-pill" data-type="${commentableType}"  onclick="replyComment(this, '${commentableType}', ${commentableId}, ${parentId})">Comment</button>
                </div>
            </div>
        </div>`
    );

    new App().initResizeableTextArea();
}

function deleteThisReplyForm(deleteBtn) {
    const replyForm = deleteBtn.closest(".reply-form");
    if (replyForm) {
        replyForm.remove();
    }
}

async function replyComment(button, commentableType, commentableId, parentId) {


    const replyForm = button.closest(".reply-form");
    if (replyForm) {

        const textarea = replyForm.querySelector('textarea');
        if (!textarea) {
            return;
        }
        if (textarea.value.trim() == '') {
            Swal.fire({
                title: "Oops...",
                text: 'Please write a comment before posting.',
                icon: "error"
            });
            return;
        }

        const btnTxt = button.textContent;
        button.textContent = "Saving...";
        const commentBox = button.closest('.comment-box');
        const counterElement = commentBox.querySelector(".replies_count");

        await saveComment(textarea, commentableType, commentableId, parentId, counterElement);
        button.textContent = btnTxt;
    }
}

function loadReplyBtnClick(loadMoreBtn) {
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


        const commentBox = loadMoreBtn.closest(".comment-box");
        const newElems = commentBox.querySelectorAll(
            '[data-ob-is-newly-created="true"]'
        );

        const keys = Array.from(newElems).map(el =>
            el.getAttribute('data-ob-new-identification-key')
        );
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
                    page: page,
                    new_identification_keys: keys
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

async function deleteCommentBtnClick(deleteBtn, commentId) {
    const comment = deleteBtn.closest('.comment-box');

    if (!comment) {
        return;
    }
    comment.classList.add('deleting');
    const deleted = await deleteComment(commentId);

    if (deleted) {
        comment.remove();
    } else {
        comment.classList.remove('deleting');
    }

    const hasMore = document.querySelector("#ob-comment-list .comment-box .comment");

    if (!hasMore) {
        loadComments("#ob-comment-list");
    }
}

async function deleteReplyBtnClick(deleteBtn, commentId) {
    const comment = deleteBtn.closest('.comment-reply');
    if (!comment) {
        return;
    }

    comment.classList.add('deleting');

    if (await deleteComment(commentId)) {
        comment.remove();
    } else {

        comment.classList.remove('deleting');
    }
}

async function deleteComment(commentId) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(authRoute('user.comments.destroy', { comment: commentId }), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            }
        });

        if (response.status === 404) {
            return true;
        }

        const data = await response.json();

        if (data.status === "success") {
            return true;
        }

        return false;

    } catch (error) {
        return false;
    }
}
