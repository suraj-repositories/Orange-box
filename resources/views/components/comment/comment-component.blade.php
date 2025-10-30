<div class="comment-section">

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="google-thumb_up" viewBox="0 -960 960 960">
            <path
                d="M716-120H272v-512l278-288 39 31q6 5 9 14t3 22v10l-45 211h299q24 0 42 18t18 42v81.839q0 7.161 1.5 14.661T915-461L789-171q-8.878 21.25-29.595 36.125Q738.689-120 716-120Zm-384-60h397l126-299v-93H482l53-249-203 214v427Zm0-427v427-427Zm-60-25v60H139v392h133v60H79v-512h193Z">
            </path>
        </symbol>
        <symbol id="google-thumb_up-fill" viewBox="0 -960 960 960">
            <path
                d="M721-120H254v-512l278-288 33 26q11 8 14.5 18t3.5 23v10l-45 211h322q23 0 41.5 18.5T920-572v82q0 11-2.5 25.5T910-439L794-171q-9 21-29.5 36T721-120ZM194-632v512H80v-512h114Z">
            </path>
        </symbol>
        <symbol id="google-thumb_down" viewBox="0 -960 960 960">
            <path
                d="M242-840h444v512L408-40l-39-31q-6-5-9-14t-3-22v-10l45-211H103q-24 0-42-18t-18-42v-81.839Q43-477 41.5-484.5T43-499l126-290q8.878-21.25 29.595-36.125Q219.311-840 242-840Zm384 60H229L103-481v93h373l-53 249 203-214v-427Zm0 427v-427 427Zm60 25v-60h133v-392H686v-60h193v512H686Z">
            </path>
        </symbol>
        <symbol id="google-thumb_down-fill" viewBox="0 -960 960 960">
            <path
                d="M239-840h467v512L428-40l-33-26q-11-8-14.5-18t-3.5-23v-10l45-211H100q-23 0-41.5-18.5T40-388v-82q0-11 2.5-25.5T50-521l116-268q9-21 29.5-36t43.5-15Zm527 512v-512h114v512H766Z">
            </path>
        </symbol>
    </svg>

    <div class="app container">
        <div class="col-12 m-auto">

            <div class="mb-4">

                <div class="mb-5 hstack gap-3 align-items-center">
                    <div class="fs-5"><span class="total_comment_count">{{ $totalComments }}</span> Comments</div>
                    <div class="dropdown">
                        <button class="sort-btn btn btn-secondary hstack align-items-center gap-2 py-1 px-2 fw-normal"
                            data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="ski" style="font-size:1.5em;"><svg aria-hidden="true"
                                    class="svg-icon mdi-outlined mdi-sort" xmlns="http://www.w3.org/2000/svg"
                                    width="48" height="48" viewBox="0 -960 960 960">
                                    <path d="M120-240v-60h240v60H120Zm0-210v-60h480v60H120Zm0-210v-60h720v60H120Z">
                                    </path>
                                </svg></span>
                            <span>Sort by</span>
                        </button>
                        <div class="dropdown-menu mt-1">
                            <div><a class="dropdown-item" href="#">Top comments</a></div>
                            <div><a class="dropdown-item" href="#">Newest first</a></div>
                        </div>
                    </div>
                </div>

                <div class="vstack mb-4">

                    <div class="comment-box">
                        <div class="d-flex comment" id="my-comment-edit">
                            <img class="rounded-circle comment-img" src="https://placehold.co/100/FF8600/ffffff?text=S"
                                width="128" height="128">
                            <div class="flex-grow-1 ms-3">
                                <div class="form-floating comment-compose mb-2">
                                    <textarea class="form-control w-100" resizeable='true' rows="1" placeholder="Leave a comment here"
                                        id="comment-message-box" ></textarea>
                                    <label for="comment-message-box">Leave a comment here</label>
                                </div>
                                <div class="hstack justify-content-end gap-1">
                                    <button class="btn btn-sm btn-secondary rounded-pill" id="canelComment">Cancel</button>
                                    <button class="btn btn-sm btn-primary rounded-pill"
                                        data-ob-commentable-type="{{ $commentable::class }}"
                                        data-ob-commentable-id="{{ $commentable->id }}" data-ob-parent-id=""
                                        id="comment-post-btn">Comment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="vstack gap-4" style="--sk-icon-btn-size:1.25em;--sk-icon-btn-padding:.25rem;"
                    id="ob-comment-list" data-ob-commentable-type="{{ $commentable::class }}"
                    data-ob-commentable-id="{{ $commentable->id }}">
                    <div class="d-flex justify-content-center loader">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary loading-btn rounded-pill px-3 mt-3" type="button" id="ob-load-more-comments">
                    <span class="spinner-border spinner-border-sm " aria-hidden="true"></span>
                    <span class="btn-text" role="status">Load More</span>
                </button>

            </div>
            <div class="border-top pt-2">
                <p class="mb-4 small"><b>Disclaimer:</b> <i>This theme belongs to oranbyte.</i></p>
            </div>


        </div>
    </div>
</div>
