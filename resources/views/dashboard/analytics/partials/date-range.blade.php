<form action="" class="hstack gap-4 justify-content-end flex-wrap">
    <div class="hstack gap-3">
        <label for="date" class="text-secondary">Ημερομηνία</label>
        <input type="date" id="date" name="date" value="{{ $date->format('Y-m-d') }}" class="form-control shadow-sm">
    </div>

    <div class="hstack gap-3">
        <label for="date-comparison" class="text-secondary">Σύγκριση</label>
        <input type="date" id="date-comparison" name="date_comparison" value="{{ old('date_comparison', ($dateComparison ?? null)?->format('Y-m-d')) }}" class="form-control shadow-sm">
    </div>
    
    <x-bs::button.primary type="submit" class="shadow-sm">Εφαρμογή</x-bs::button.primary>
</form>