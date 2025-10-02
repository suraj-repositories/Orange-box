document.addEventListener('DOMContentLoaded', function(){
    enableLikeDislike();
})

function enableLikeDislike() {

    const likeBtn = document.querySelector(".like-btn");
    const dislikeBtn = document.querySelector(".dislike-btn");
    const likeCount = document.querySelector("#like-count");
    const dislikeCount = document.querySelector("#dislike-count");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!likeBtn || !dislikeBtn) {
        return;
    }
    const likeIcon = likeBtn.querySelector('i');
    const dislikeIcon = dislikeBtn.querySelector('i');
    function toggleLike(isLiked) {
        if (isLiked) {
            likeIcon.classList.remove('mdi-thumb-up-outline');
            likeIcon.classList.add('mdi-thumb-up');
        } else {
            likeIcon.classList.remove('mdi-thumb-up');
            likeIcon.classList.add('mdi-thumb-up-outline');
        }
    }
    function toggleDislike(isDisliked) {
        if (isDisliked) {
            dislikeIcon.classList.remove('mdi-thumb-down-outline');
            dislikeIcon.classList.add('mdi-thumb-down');
        } else {
            dislikeIcon.classList.remove('mdi-thumb-down');
            dislikeIcon.classList.add('mdi-thumb-down-outline');
        }
    }

    function apiCall(url) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    toggleLike(data.is_liked);
                    toggleDislike(data.is_disliked);
                    likeCount.textContent = data.likes == 0 ? 'Like' : data.likes;
                    dislikeCount.textContent = data.dislikes == 0 ? 'Dislike' : data.dislikes;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    likeBtn.addEventListener('click', () => {
        const syntaxStore = likeBtn.getAttribute('data-syntax-store-id');
        const url = authRoute('user.syntax-store.like', { syntaxStore: syntaxStore });
        apiCall(url)
    });

    dislikeBtn.addEventListener('click', () => {
        const syntaxStore = likeBtn.getAttribute('data-syntax-store-id');
        const url = authRoute('user.syntax-store.dislike', { syntaxStore: syntaxStore });
        apiCall(url);
    });
}

