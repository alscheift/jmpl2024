<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <input type="hidden" id="recaptcha_token" name="recaptcha_token" value="placeholder">
            <div id="recaptcha" class="flex mt-5 justify-evenly"></div> 

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
    @push('scripts') 
        <script>
            const callback = function(token) {
                document.getElementById('recaptcha_token').value = token;
            };

            const expiredCallback = function() {
                grecaptcha.reset();
                document.getElementById('recaptcha_token').value = '';
            };

            const errorCallback = function() {
                document.getElementById('recaptcha_token').value = '';
            };

            var onloadCallback = function() {
                @if (session('loginAttempts', 0) >= config('session.login_attempts'))
                    grecaptcha.render('recaptcha',{
                        'sitekey': '{{config('services.recaptcha.site_key')}}', // so weird syntax
                        'theme': 'light',
                        'callback': callback,
                        'expired-callback': expiredCallback,
                        'error-callback': errorCallback
                    });
                @else
                    // No captcha horray :3 (max fail attempt is {{config('session.login_attempts')}})
                @endif
            };
        </script>
    @endpush 
</x-guest-layout>
