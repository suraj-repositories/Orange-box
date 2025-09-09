document.addEventListener("DOMContentLoaded", ()=>{
    enableCommentFeature();
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
