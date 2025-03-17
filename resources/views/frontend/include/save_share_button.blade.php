<ul>
    <li>
        <a href="javascript:void(0)" class="save-property" data-id="{{ $project->id }}">
            @if (Auth::user())
                <a href="javascript:void(0)" class="save-property"
                    data-id="{{ $project->id }}">
                    <i class="{{ $project->wishlistedByUsers->contains(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                    Save
                </a>
            @endif
        </a>
    </li>

    <li>
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#share_property" class="saveProperty"><i class="fa-solid fa-arrow-up-from-bracket"></i>Share
        </a>
    </li>
</ul>