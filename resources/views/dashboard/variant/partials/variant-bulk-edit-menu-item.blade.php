<form action="{{ route('variants.bulk-edit', $product) }}" method="GET" class="w-100"
      x-data="{ ids: [] }"
      x-on:submit.prevent="
        ids = [...document.querySelectorAll('.variant:checked')].map(i => i.value)
        $nextTick(() => $el.submit())
      "
>
    <template x-for="id in ids" :key="id">
        <input type="text" name="ids[]" :value="id" hidden>
    </template>

    <button type="submit" class="px-3 py-2 shadow-none bg-transparent text-start border-0 w-100" style="color: inherit">
        <em class="far fa-edit me-2 text-secondary w-1r"></em>
        {{ __("eshop::variant.bulk-actions.edit") }}
    </button>
</form>