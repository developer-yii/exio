<div class="paginationBox">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <!-- First Page Link -->
            <li class="page-item {{ $propertyPages->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $propertyPages->url(1) }}" aria-label="First">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Previous Page Link -->
            <li class="page-item {{ $propertyPages->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $propertyPages->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&lsaquo;</span>
                </a>
            </li>

            <!-- Page Numbers with Dots -->
            @php
            $currentPage = $propertyPages->currentPage();
            $lastPage = $propertyPages->lastPage();
            $start = max(1, $currentPage - 1);
            $end = min($lastPage, $currentPage + 1);
            @endphp

            @if ($start > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $propertyPages->url(1) }}">1</a>
            </li>
            @if ($start > 2)
            <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item">
                <a class="page-link {{ $page == $currentPage ? 'active' : '' }}" href="{{ $propertyPages->url($page) }}">{{ $page }}</a>
                </li>
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $propertyPages->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                    @endif

                    <!-- Next Page Link -->
                    <li class="page-item {{ $propertyPages->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $propertyPages->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&rsaquo;</span>
                        </a>
                    </li>

                    <!-- Last Page Link -->
                    <li class="page-item {{ $propertyPages->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $propertyPages->url($lastPage) }}" aria-label="Last">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
        </ul>
    </nav>
</div>