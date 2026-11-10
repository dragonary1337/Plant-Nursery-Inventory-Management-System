<form method="POST" action="{{ $plant ? route('plants.update', $plant) : route('plants.store') }}">
    @csrf
    @if ($plant)
        @method('PUT')
    @endif

    <div class="field">
        <label for="plant_name">Plant name</label>
        <input id="plant_name" name="plant_name" type="text" maxlength="255" value="{{ old('plant_name', $plant?->plant_name) }}" required>
        @error('plant_name') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div class="grid">
        <div class="field">
            <label for="category">Category</label>
            <input id="category" name="category" type="text" list="plant-categories" maxlength="255" value="{{ old('category', $plant?->category) }}" placeholder="e.g. flowering" required>
            <datalist id="plant-categories">
                <option value="ornamental">
                <option value="flowering">
                <option value="succulent">
                <option value="herb">
            </datalist>
            @error('category') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="unit_price">Unit price</label>
            <input id="unit_price" name="unit_price" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('unit_price', $plant?->unit_price) }}" required>
            @error('unit_price') <div class="error-text">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="status">Stock status</label>
        <select id="status" name="status" required>
            <option value="in-stock" @selected(old('status', $plant?->status ?? 'in-stock') === 'in-stock')>In stock</option>
            <option value="out of stock" @selected(old('status', $plant?->status) === 'out of stock')>Out of stock</option>
        </select>
        @error('status') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="description">Description</label>
        <textarea id="description" name="description" required>{{ old('description', $plant?->description) }}</textarea>
        @error('description') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div class="actions">
        <button class="btn" type="submit">{{ $plant ? 'Save changes' : 'Add plant' }}</button>
        <a class="btn secondary" href="{{ $plant ? route('plants.show', $plant) : route('plants.index') }}">Cancel</a>
    </div>
</form>
