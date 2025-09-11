
document.addEventListener("DOMContentLoaded", () => {
    enableCommentFeature();
    loadComments("#ob-comment-list");
});

function enableCommentFeature() {
    const commentBox = document.getElementById("comment-message-box");
    const postBtn = document.getElementById("comment-post-btn");

    postBtn.addEventListener("click", function () {
        let message = commentBox.value.trim();

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
                message: message
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    commentBox.value = "";

                    loadComments("#ob-comment-list", args = {page : 1});
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
    });
}

function loadComments(selector, args = {page : 1}) {
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
                if(page == 1){
                    commentBox.innerHTML =  result.data;

                }else {
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


function enableReplyBtns(){
    const commentBox = document.querySelector("#ob-comment-list");

    if(!commentBox) return;

    const replyBtns = commentBox.querySelectorAll('.reply-btn');

    replyBtns.forEach(btn => {
        const isListenerAdded = btn.getAttribute('is-listener-added');
        if(isListenerAdded && isListenerAdded == 'true') return;

        btn.setAttribute("is-listener-added", 'true');
        btn.addEventListener('click', function(){
            const parent = btn.parentElement;
            if(!parent || parent.parentElement.querySelector('.reply-form')){
                return;
            }

             parent.insertAdjacentHTML(
                        "afterend",
                        `<div class="d-flex reply-form">
                            <img class="rounded-circle comment-img" src="https://placehold.co/100/6E92FF/ffffff?text=S"
                                width="128" height="128">
                            <div class="flex-grow-1 ms-3">
                                <div class="mb-2">
                                    <div class="text-body-secondary small">Replying to @Kamisato_Mugi</div>
                                </div>
                                <div class="form-floating comment-compose mb-2">
                                    <textarea class="form-control w-100" placeholder="Leave a comment here" id="my-comment-reply" style="height:2rem;"></textarea>
                                    <label for="my-comment-reply">Leave a comment here</label>
                                </div>
                                <div class="hstack justify-content-end gap-1">
                                    <button class="btn btn-sm btn-secondary rounded-pill">Cancel</button>
                                    <button class="btn btn-sm btn-primary rounded-pill">Comment</button>
                                </div>
                            </div>
                        </div>`
                    );
        });
    });

}
