<div class="mb-2">
    @if (session('success'))
        <div class="alert alert--success"><x-icon icon="check"/><div>{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="alert alert--danger"><x-icon icon="times"/><div>{{ session('error') }}</div></div>
        </div>
    @endif
    @if ($errors->has('message'))
        <div class="alert alert--danger"><x-icon icon="times"/><div>{{ $errors->first('message') }}</div></div>
    @endif
</div>
