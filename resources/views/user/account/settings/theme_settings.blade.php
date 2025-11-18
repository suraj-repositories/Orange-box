<div class="row row-cols-1 row-cols-md-3 row-col-lg-4 row-col-xl-5 g-3" id="app-themes">
    @foreach ($appThemes as $theme)
        <div class="col">
            <label class="theme-option">
                <input type="radio" name="app_theme" value="{{ $theme->theme_key }}" class="theme-checkbox" {{ $userSettings['app_theme'] == $theme->id ? 'checked' : '' }} hidden>
                <img src="{{ asset($theme->theme_image) }}" class="theme-image" alt="" title="{{ $theme->title }}">
            </label>
        </div>
    @endforeach

</div>
