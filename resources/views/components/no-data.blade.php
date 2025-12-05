@if (!$isDecorated)
    <div class="no-data no-data-{{ $theme }}">
        <div class="icon-box"><i class="{{ $icon }}"></i></div>
        <div class="message" id="messageOfPassenger">{{ $message }}</div>
    </div>
@else
    <div class="card py-4">
        <div class="no-data no-data-{{ $theme }}">
            <div class="icon-box"><i class="{{ $icon }}"></i></div>
            <div class="message" id="messageOfPassenger">{{ $message }}</div>
        </div>
    </div>
@endif
