@extends('layouts.app')

@section('title', 'إدارة الثوابت')

@push('styles')
     <style>
        .tree-item {
            padding: 12px 15px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: 0.2s;
        }
        .tree-item:hover {
            background: #f7f9fc;
        }

        .tree-toggle {
            cursor: pointer;
            transition: 0.2s;
            margin-right: 6px;
        }
        .tree-toggle.open {
            transform: rotate(90deg);
        }

        .children-wrap {
            margin-right: 22px;
            border-right: 1px dashed #cfcfcf;
            padding-right: 15px;
            display: none;
        }

        .child {
            padding: 10px 10px;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            margin: 6px 0;
            transition: 0.2s;
        }
        .child:hover {
            background: #f8fafc;
        }
            .page-header {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #0C4079;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-add {
        background: #0C4079;
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-add:hover {
        background: #083058;
        color: white;
    }

    </style>

@endpush
@section('content')
<div class="container">

<div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="page-title">
                <i class="fas fa-sliders-h"></i>
                إدارة الثوابت
            </h1>
            @if(user_can('constants.create'))
            <a href="{{ route('constants.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                إضافة ثابت جديد
            </a>
        @endif

        </div>
    </div>
   
    <div class="card shadow-sm">
        <div class="card-body">
@if (Session::has('success'))
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        toast: true,
        position: 'bottom-start',
        icon: 'success',
        title: '{{ Session::get("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        backdrop: false,
        customClass: {
            popup: 'medium-small-toast'
        }
    });
});
</script>
@endif



            @foreach($constants as $parent)
                <div class="tree-item">

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">

                            @if($parent->children->count())
                                <i class="fas fa-chevron-right tree-toggle"
                                   onclick="toggleTree({{ $parent->id }}, this)"></i>
                            @else
                                <i class="fas fa-circle" style="font-size: 6px; margin-right: 7px;"></i>
                            @endif

                            <i class="fas fa-folder-open text-primary mx-2"></i>
                            <strong>{{ $parent->name }}</strong>
                        </div>

                        @if(user_can('constants.edit') || user_can('constants.delete') || user_can('constants.create'))
                        <div>
                            @if(user_can('constants.edit'))
                            <a href="{{ route('constants.edit', $parent->id) }}" class="text-primary me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif

                            @if(user_can('constants.create'))
                            <a href="{{ route('constants.create', ['parent' => $parent->id]) }}" class="text-success">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                            @endif
                            @if(user_can('constants.delete'))
                            <form action="{{ route('constants.destroy', $parent->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                             <button class="btn btn-sm text-danger" style="
                        pointer-events: none;
                        opacity: 0.5;
                        cursor: not-allowed;" style="border: none; background: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                            @endif
                        </div>
                        @endif
                    </div>

                @if($parent->children->count())
                    <div class="children-wrap mt-2" id="children-{{ $parent->id }}">
                        @include('constants.tree', ['items' => $parent->children])
                    </div>
                @endif


                </div>
            @endforeach

        </div>
    </div>
</div>

<script>
function toggleTree(id, icon) {
    let box = document.getElementById('children-' + id);

    if (box.style.display === "block") {
        box.style.display = "none";
        icon.classList.remove('open');
    } else {
        box.style.display = "block";
        icon.classList.add('open');
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteForms = document.querySelectorAll('form[action*="constants"][method="POST"]');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'تأكيد الحذف',
                text: "هل تريد بالتأكيد حذف هذا العنصر؟ لن تتمكن من التراجع لاحقاً.",
                icon: 'warning',
                iconColor: '#d33',
                showCancelButton: true,
                confirmButtonText: 'نعم، حذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'animated fadeInDown',
                    confirmButton: 'btn btn-danger mx-2 px-4',
                    cancelButton: 'btn btn-secondary mx-2 px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    });

});
</script>
<style>

</style>

@endsection
