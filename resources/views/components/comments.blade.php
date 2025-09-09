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
                    <div class="fs-5">8 Comments</div>
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
                            <img class="rounded-circle comment-img" src="https://placehold.co/100/6E92FF/ffffff?text=S"
                                width="128" height="128">
                            <div class="flex-grow-1 ms-3">
                                <div class="form-floating comment-compose mb-2">
                                    <textarea class="form-control w-100" resizeable='true' rows="1" placeholder="Leave a comment here"
                                        id="comment-message-box"></textarea>
                                    <label for="comment-message-box">Leave a comment here</label>
                                </div>
                                <div class="hstack justify-content-end gap-1">
                                    <button class="btn btn-sm btn-secondary rounded-pill">Cancel</button>
                                    <button class="btn btn-sm btn-primary rounded-pill"
                                        data-ob-commentable-type="{{ $commentable::class }}"
                                        data-ob-commentable-id="{{ $commentable->id }}"
                                        data-ob-parent-id=""

                                        id="comment-post-btn">Comment</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="vstack gap-4" style="--sk-icon-btn-size:1.25em;--sk-icon-btn-padding:.25rem;"
                    id="my-comment-list">

                    @forelse ($comments as $comment)
                        <div class="comment-box">
                            <div class="d-flex comment">
                                <img class="rounded-circle comment-img"
                                    src="{{ $comment->user->profilePicture() }}" width="128" height="128">
                                <div class="flex-grow-1 ms-3">
                                    <div class="mb-1"><a href="#" class="fw-bold link-body-emphasis me-1"> {{ $comment->user->name() }} </a> <i class="zmdi zmdi-check me-1 fw-bold" title="verified"></i>
                                        <span class="text-body-secondary text-nowrap">{{  $comment->created_at->diffForHumans() }}</span></div>
                                    <div class="mb-1">{{ $comment->message }}</div>
                                    <div class="hstack align-items-center mb-0" style="margin-left:-.25rem;">
                                        <button class="icon-btn me-1" href="#"><svg
                                                class="svg-icon material-symbols-filled material-symbols-thumb_up"
                                                width="48" height="48">
                                                <use xlink:href="#google-thumb_up-fill"></use>
                                            </svg></button>
                                        <span class="me-3 small">55</span>
                                        <button class="icon-btn me-4"><svg
                                                class="svg-icon material-symbols-outlined material-symbols-thumb_down"
                                                width="48" height="48">
                                                <use xlink:href="#google-thumb_down"></use>
                                            </svg></button>
                                        <button class="btn btn-sm btn-secondary rounded-pill small">Reply</button>
                                        <button class="btn btn-sm btn-danger rounded-pill small">Delete</button>
                                    </div>
                                    <div style="margin-left:-.769rem;">
                                        <button
                                            class="btn btn-primary rounded-pill d-inline-flex align-items-center collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapse-comment001"
                                            aria-expanded="true" aria-controls="collapse-comment001">
                                            <i class="chevron-down zmdi zmdi-chevron-down fs-4 me-2"></i>
                                            <i class="chevron-up zmdi zmdi-chevron-up fs-4 me-2"></i>
                                            <span>3 replies</span>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="collapse show" id="collapse-comment001" style="">
                                <div class="comment-replies vstack gap-3 mt-1 bg-body-tertiary p-3 rounded-3">


                                    <div class="d-flex">
                                        <img class="rounded-circle comment-img"
                                            src="https://placehold.co/100/cc99ff/ffffff?text=S" width="128"
                                            height="128">
                                        <div class="flex-grow-1 ms-3">
                                            <div class="mb-1"><a href="#"
                                                    class="fw-bold link-body-emphasis pe-1">Shinobu KonKon</a> <span
                                                    class="text-body-secondary text-nowrap">1 day ago</span></div>
                                            <div class="mb-2">Disputando voluptatibus ei sit. Et veri deserunt
                                                theophrastus pri, at mutat choro eum.</div>
                                            <div class="hstack align-items-center" style="margin-left:-.25rem;">
                                                <button class="icon-btn me-1" href="#"><svg
                                                        class="svg-icon material-symbols-outlined material-symbols-thumb_up"
                                                        width="48" height="48">
                                                        <use xlink:href="#google-thumb_up"></use>
                                                    </svg></button>
                                                <span class="me-3 small">1</span>
                                                <button class="icon-btn me-4" href="#"><svg
                                                        class="svg-icon material-symbols-outlined material-symbols-thumb_down"
                                                        width="48" height="48">
                                                        <use xlink:href="#google-thumb_down"></use>
                                                    </svg></button>
                                                <button
                                                    class="btn btn-sm btn-secondary rounded-pill small">Reply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comment #1 Replies #1.2 //-->
                                    <div class="d-flex">
                                        <img class="rounded-circle comment-img"
                                            src="https://placehold.co/100/ffcc99/ffffff?text=O" width="128"
                                            height="128">
                                        <div class="flex-grow-1 ms-3">
                                            <div class="mb-1"><a href="#"
                                                    class="fw-bold link-body-emphasis pe-1">Yuki Uki</a> <span
                                                    class="text-body-secondary text-nowrap">1 minute ago</span></div>
                                            <div class="mb-1">Oremlo ipsumay olay orsumday itamay oremay oxfay imdray
                                                onsecteturcay adipiscingay elitay, eday osay eiusmoday emporecay
                                                incididuntay utay aborecay etay oloredcay agnay aliquaay.</div>
                                            <div class="hstack align-items-center" style="margin-left:-.25rem;">
                                                <button class="icon-btn me-1" href="#"><svg
                                                        class="svg-icon material-symbols-outlined material-symbols-thumb_up"
                                                        width="48" height="48">
                                                        <use xlink:href="#google-thumb_up"></use>
                                                    </svg></button>
                                                <span class="me-3 small" hidden="">0</span>
                                                <button class="icon-btn me-4" href="#"><svg
                                                        class="svg-icon material-symbols-filled material-symbols-thumb_down-fill"
                                                        width="48" height="48">
                                                        <use xlink:href="#google-thumb_down-fill"></use>
                                                    </svg></button>
                                                <button
                                                    class="btn btn-sm btn-secondary rounded-pill small">Reply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comment #1 Replies #1.3 //-->
                                    <div class="d-flex">
                                        <img class="rounded-circle comment-img"
                                            src="https://placehold.co/100/ff99cc/ffffff?text=K" width="128"
                                            height="128">
                                        <div class="flex-grow-1 ms-3">
                                            <div class="mb-1"><a href="#"
                                                    class="fw-bold text-bg-secondary py-1 px-2 rounded-pill me-1">Kamisato
                                                    Mugi</a> <span class="text-body-secondary text-nowrap">just
                                                    now</span>
                                            </div>
                                            <div class="mb-1"><a href="#">@Shinobu_KonKon</a> Vivamus ac
                                                varius
                                                augue. Curabitur luctus convallis lorem, vitae convallis dui volutpat
                                                nec.
                                            </div>
                                            <div class="hstack align-items-center" style="margin-left:-.25rem;">
                                                <button class="icon-btn me-1" href="#"><svg
                                                        class="svg-icon material-symbols-filled material-symbols-thumb_up-fill"
                                                        width="48" height="48">
                                                        <use xlink:href="#google-thumb_up-fill"></use>
                                                    </svg></button>
                                                <span class="me-3 small">2</span>
                                                <button class="icon-btn me-4" href="#"><svg
                                                        class="svg-icon material-symbols-outlined material-symbols-thumb_down"
                                                        width="48" height="48">
                                                        <use xlink:href="#google-thumb_down"></use>
                                                    </svg></button>
                                                <button
                                                    class="btn btn-sm btn-secondary rounded-pill small">Reply</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <img class="rounded-circle comment-img"
                                            src="https://placehold.co/100/6E92FF/ffffff?text=S" width="128"
                                            height="128">
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
                                    </div>

                                </div>
                            </div>

                        </div>
                    @empty
                        <x-no-data />
                    @endforelse


                </div>

            </div>
            <div class="border-top pt-2">
                <p class="mb-4 small"><b>Disclaimer:</b> <i>This theme belongs to oranbyte.</i></p>
            </div>
        </div>
    </div>
</div>
