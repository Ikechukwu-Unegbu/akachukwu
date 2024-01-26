<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="index.html" class="logo d-flex align-items-center w-auto">
                            <span class="d-none d-lg-block">{{ config('app.name') }}</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                <p class="text-center small">Enter your personal details to create account</p>
                            </div>

                            <form class="row g-3" wire:submit.prevent="register">
                                <div class="col-12">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Your Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" wire:model="password_confirmation">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">Create Account</button>
                                </div>
                                <div class="col-12">
                                    <p class="small mb-0">Already have an account? <a href="{{ route('admin.auth.login') }}">Log in</a>
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
