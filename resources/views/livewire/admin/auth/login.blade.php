@push('title')
    Admin / Login
@endpush
<div>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="javascript:void(0)" class="logo d-flex align-items-center w-auto">
                                <span class="d-none d-lg-block">{{ config('app.name') }}</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                    <p class="text-center small">Enter your email & password to login</p>
                                </div>

                                <form class="row g-3" wire:submit.prevent="authenticate">
                                    <div class="col-12">
                                        <label for="username" class="form-label">Email or Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" wire:model="username">
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password">
                                        @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="remember_me" name="remember" id="remember_me">
                                            <label class="form-check-label" for="remember_me">Remember me</label>
                                            @error('remember_me')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">Login</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Don't have account? 
                                            <a href="{{ route('admin.auth.register') }}">
                                                Create an account
                                            </a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>
</div>
