<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Profile" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ $user->profile_picture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ Str::title($user->role) }}</h3>
                        <div class="social-links mt-2">
                            <a href="javascript:void(0)" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="javascript:void(0)" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="javascript:void(0)" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="javascript:void(0)" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul wire:ignore class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" wire:click="dynamicTab('overview_tab')">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" wire:click="dynamicTab('profile_tab')">Edit
                                    Profile</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" wire:click="dynamicTab('password_tab')">Change Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade {{ ($overview_tab) ? 'active show' : '' }} profile-overview" >
                                
                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Username</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->username }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{ ($user->address) ? $user->address : 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ ($user->mobile) ? $user->mobile : 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Gender</div>
                                    <div class="col-lg-9 col-md-8">{{ ($user->gender) ? $user->gender : 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{ ($user->address) ? $user->address : 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="tab-pane fade {{ ($profile_tab) ? 'active show' : '' }} profile-edit pt-3" >
                                <!-- Profile Edit Form -->
                                <form wire:submit.prevent="profile">
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                            Image</label>
                                        <div class="col-md-8 col-lg-9" x-data>
                                            <img src="{{ ($profile_image) ? $profile_image->temporaryUrl() : $user->profile_picture }}" class="thumbnail img-fluid" alt="Profile">
                                            <div class="pt-2" >
                                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" title="Upload new profile image" x-on:click="$refs.profile_image.click()">
                                                    <i class="bi bi-upload"></i>
                                                </a>
                                                <input type="file" x-ref="profile_image" style="display: none" accept="image/*" wire:model.defer="profile_image" />                                                
                                                <a href="javascript:void(0)" onclick="confirm('You are about to delete your profile image. Do you want to continue?') || event.stopImmediatePropagation()" wire:click="deleteProfileImage()" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name" />
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="username" type="username" class="form-control @error('username') is-invalid @enderror" id="username" wire:model="username" />
                                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email" />
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="mobile" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="mobile" type="number" class="form-control @error('mobile') is-invalid @enderror" id="mobile" wire:model="mobile" />
                                            @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="gender" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select class="form-select @error('gender') is-invalid @enderror"  id="gender" wire:model="gender">
                                                <option value="">-- Select Gender --</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            @error('gender') <div class="text-danger" style="font-size: .875em">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="address" type="text" class="form-control @error('address') is-invalid @enderror" id="address" wire:model="address" />
                                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                   
                                    <div class="text-center">
                                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                                            <div wire:loading.attr="disabled" wire:loading.remove wire:target="profile">
                                                Update
                                            </div>
               
                                            <div wire:loading wire:target="profile">  
                                                <i class="bx bx-loader-circle bx-spin"></i>  Updating...
                                            </div>
                                        </button>
                                    </div>
                                </form><!-- End Profile Edit Form -->
                            </div>                       

                            <div class="tab-pane {{ ($password_tab) ? 'active show' : '' }} fade pt-3" >
                                <!-- Change Password Form -->
                                <form wire:submit.prevent="credential">

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" wire:model="current_password" id="current_password">
                                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" id="password">
                                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" wire:model="password_confirmation" id="password_confirmation">
                                            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                                        <div wire:loading.attr="disabled" wire:loading.remove wire:target="credential">
                                            Change Password
                                        </div>
           
                                        <div wire:loading wire:target="credential">  
                                            <i class="bx bx-loader-circle bx-spin"></i>  Updating...
                                        </div>
                                    </button>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@push('title')
Settings / Profile
@endpush