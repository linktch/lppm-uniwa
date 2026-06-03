<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width:400px; border-radius: 16px;">

        <div class="text-center mb-4">
            <div class="mx-auto mb-3"
                style="width: 60px; height: 60px; background: linear-gradient(135deg, #1e3a5f, #2c7da0); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-graduation-cap" style="font-size: 28px; color: white;"></i>
            </div>
            <h4 style="color: #1e3a5f; font-weight: 600;">LPPM Uniwa</h4>
            <p style="color: #6c757d; font-size: 13px;">Login ke akun Anda</p>
        </div>

        <form wire:submit.prevent="login">
            <div class="mb-3">
                <input type="text" wire:model="username" class="form-control @error('username') is-invalid @enderror"
                    style="border-radius: 10px; padding: 10px;" placeholder="Email atau Username">
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <input type="password" wire:model="password"
                    class="form-control @error('password') is-invalid @enderror"
                    style="border-radius: 10px; padding: 10px;" placeholder="Password">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            @if (env('ENABLE_CAPTCHA', true))
                <div class="mb-3">
                    <img src="{{ url('/captcha') }}" onclick="this.src='{{ url('/captcha') }}?'+Math.random()"
                        style="cursor:pointer; border:1px solid #ccc;">
                </div>

                <input type="text" wire:model="captcha" placeholder="Masukkan captcha">
            @endif
            @error('captcha')
                <small style="color:red">{{ $message }}</small>
            @enderror


            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" wire:model="remember">
                <label class="form-check-label" for="remember" style="font-size: 13px;">
                    Ingat saya
                </label>
            </div>

            <button class="btn w-100" type="submit"
                style="background: linear-gradient(135deg, #1e3a5f, #2c7da0); border: none; border-radius: 40px; padding: 10px; color: white; font-weight: 600;"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Login</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>

    </div>
</div>
