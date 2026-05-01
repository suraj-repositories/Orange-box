 <ul class="list-group user-view-list">
     @foreach ($users as $user)
         <li class="list-group-item  py-3">
             <div class="d-flex align-items-center gap-3">

                 <div class="flex-shrink-0">
                    <a href="{{  route('users.profile.index', ['user' => $user]) }}">
                        <img src="{{ $user->avatar_url }}" class="rounded-3 border avatar-md " alt="">
                    </a>
                 </div>

                 <div class="flex-grow-1">
                     <div class="d-flex justify-content-between align-items-start">

                         <div>
                             <h6 class="mb-1 fw-semibold"><a href="{{ route('users.profile.index', ['user' => $user]) }}">{{ '@' . $user->username }}</a></h6>
                             <p class="text-muted mb-0 small" style="line-height:1.4;">
                                 {{ \Illuminate\Support\Str::limit($user->details?->tag_line ?? '', 200, '...') }}
                             </p>
                         </div>

                         <div class="ms-3">
                             @php $isFollowing = Auth::user()->isFollowing($user) @endphp
                             <button class="btn btn-sm btn-light border follow-btn"
                                 data-follow-url="{{ authRoute('user.follow', ['id' => $user->id]) }}"
                                 data-unfollow-url="{{ authRoute('user.unfollow', ['id' => $user->id]) }}"
                                 data-following="{{ $isFollowing ? 'true' : 'false' }}">{{ $isFollowing ? 'Unfollow' : 'Follow' }}</button>
                         </div>

                     </div>
                 </div>

             </div>
         </li>
     @endforeach
 </ul>
