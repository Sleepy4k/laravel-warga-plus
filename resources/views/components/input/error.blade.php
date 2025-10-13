@if ($errors->has($for))
    <div class="invalid-feedback d-block">
        {{ $errors->first($for) }}
    </div>
@endif
