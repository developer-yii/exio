<ul>
    <li>
        {{-- <a href="javascript:void(0)" class="save-property" data-id="{{ $project->id }}">
            <i class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                Save
        </a> --}}
        @if(auth()->check())
            <a href="javascript:void(0)" class="save-property" data-id="{{ $project->id }}">
                <i class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                Save
            </a>
        @else
            <a href="javascript:void(0)" class="show-login-toastr">
                <i class="fa-regular fa-heart"></i> Save
            </a>
        @endif
    </li>

    <li>
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#share_property" class="saveProperty"><i class="fa-solid fa-arrow-up-from-bracket"></i>Share
        </a>
    </li>
</ul>