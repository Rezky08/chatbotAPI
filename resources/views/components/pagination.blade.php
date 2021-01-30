<div>
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous">Previous</a>
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next">Next page</a>
        <ul class="pagination-list">
            @foreach ($paginator->links()->elements[0] as $label => $value)
                <li>
                    <a href="{{ $value }}" class="pagination-link">{{ $label }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let current_url = "{{ Request::get('page') }}"
            if (current_url) {
                document.querySelectorAll('.pagination-link').forEach((pagination) => {
                    if (pagination.innerHTML == current_url) {
                        pagination.classList.add("is-current");
                    }
                })
            } else {
                document.querySelector('.pagination-link').classList.add("is-current");
            }
        })

    </script>

</div>
