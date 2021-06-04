<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="d-flex justify-content-between mb-2">{{ __("Subcategories") }}<a class="text-decoration-none" href="{{ route('categories.create', ['parent_id' => $this->category->id]) }}">{{ __("Add subcategory") }}</a></h6>
        <div class="table-responsive">
            <table class="table table-hover">
                @foreach($subcategories as $child)
                    <tr>
                        <td style="width: 70px">
                            <div class="ratio ratio-4x3">
                                @isset($child->image)
                                    <img class="img-middle" src="{{ $child->image->url('sm') }}" alt="{{ $child->name }}">
                                @endisset
                            </div>
                        </td>
                        <td class="align-middle"><a href="{{ route('categories.edit', $child) }}" class="text-decoration-none">{{ $child->name }}</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
