<?php

namespace App\Livewire\Admin\Profile;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Index extends Component
{

    use WithFileUploads;

    public $user;
    public $profile_image;
    public $name;
    public $username;
    public $email;
    public $mobile;
    public $gender;
    public $address;
    public $current_password;
    public $password;
    public $password_confirmation;
    public $overview_tab = true;
    public $profile_tab = false;
    public $password_tab = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->mobile = $this->user->mobile;
        $this->gender = $this->user->gender;
        $this->address = $this->user->address;
    }

    public function profile()
    {
        $validated = $this->validate([
            'name'          =>  'required|string|min:3|max:50',
            'username'      =>  "required|string|min:3|max:50|unique:users,username,{$this->user->id}",
            'email'         =>  "required|string|min:3|max:50|unique:users,email,{$this->user->id}",
            'mobile'        =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
            'gender'        =>  'required|in:male,female',
            'address'       =>  'nullable|string|max:200',
        ]);

        if ($this->profile_image) {

            $this->validate([
                'profile_image' =>  'nullable|image|mimes:jpeg,jpg,png'
            ]);
            
            $image_name = $this->profile_image->storeAs('', Str::slug($this->username) . '_' . time() . '.png', 'avatars');

            if (!is_null($this->user->image) && Storage::disk('avatars')->exists($this->user->image))
                Storage::disk('avatars')->delete($this->user->image);

            $this->user->update(['image' => $image_name]);
        }

        $this->user->update($validated);
        $this->dispatch('success-toastr', ['message' => 'Profile Updated Successfully']);
        session()->flash('success', 'Profile Updated Successfully');
        $this->redirect(url()->previous());
    }

    public function credential()
    {
        $this->validate([
            'current_password'      =>  'required',
            'password'              =>  'required|min:6|max:15',
            'password_confirmation' =>  'required|same:password'
        ]);

        if (!Hash::check($this->current_password, $this->user->password))
            throw ValidationException::withMessages([
                'current_password'   =>  __('The password provided does not match your current password.')
            ]);

        $this->user->update(['password' => Hash::make($this->password)]);
        $this->dispatch('success-toastr', ['message' => 'Password Updated Successfully']);
        session()->flash('success', 'Password Updated Successfully');
        $this->redirect(url()->previous());
    }

    public function deleteProfileImage()
    {
        if (!is_null($this->user->image) && Storage::disk('avatars')->exists($this->user->image)) Storage::disk('avatars')->delete($this->user->image);

        $this->user->update(['image' => null]);
        $this->dispatch('success-toastr', ['message' => 'Profile Image Deleted Successfully']);
        session()->flash('success', 'Profile Image Deleted Successfully');
        $this->redirect(url()->previous());
    }

    public function dynamicTab($tab)
    {
        $this->overview_tab = false;
        $this->profile_tab = false;
        $this->password_tab = false;
        $this->$tab = true;
    }

    public function render()
    {
        return view('livewire.admin.profile.index');
    }
}
