@foreach($items as $item)

    <div class="child d-flex justify-content-between align-items-center mb-1">

        <div class="d-flex align-items-center">

            @if($item->children->count())
                <i class="fas fa-chevron-right tree-toggle"
                   onclick="toggleTree({{ $item->id }}, this)"></i>
            @else
                <i class="fas fa-circle text-muted" style="font-size: 6px; margin-left: 7px;"></i>
            @endif

            <span class="mx-2">{{ $item->name }}</span>
        </div>

        <div>
            <a href="{{ route('constants.create', ['parent' => $item->id]) }}" class="text-success me-2">
                <i class="fas fa-plus-circle"></i>
            </a>

            <a href="{{ route('constants.edit', $item->id) }}" class="text-primary me-2">
                <i class="fas fa-edit"></i>
            </a>

            <form action="{{ route('constants.destroy', $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm text-danger" style="
                        pointer-events: none;
                        opacity: 0.5;
                        cursor: not-allowed;" style="border:none;background:none;">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>

    </div>

    @if($item->children->count())
        <div class="children-wrap ms-4" id="children-{{ $item->id }}">
            @include('constants.tree', ['items' => $item->children])
        </div>
    @endif

@endforeach
