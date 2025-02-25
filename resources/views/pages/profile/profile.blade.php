@extends('layouts.new-guest')

@section('body')
   


<div class="bg-white p-6 w-full max-w-md">
    <a href="#" class="text-blue-600 mb-4 block">&lt; Back</a>
  

    <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data">
      @csrf 

      <div class="text-center mb-4">
        <div class="relative inline-block">
          <img id="profile-image" class="w-24 h-24 rounded-full mx-auto" src=" {{auth()->user()->profilePicture}}" alt="Profile Picture">
          <label class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-1 cursor-pointer">
            <input id="image-upload" type="file" class="hidden" name="image" accept="image/*">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l-3.75 3.75-3.75-3.75M19.5 4.5v15a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5v-15A2.25 2.25 0 016.75 2.25h10.5A2.25 2.25 0 0119.5 4.5z"/>
            </svg>
          </label>
        </div>
      </div>

      @include('components.error_message')

      <!-- First Name -->
      <div class="border border-gray-300 rounded-lg mb-4 p-2">
        <label class="block text-sm font-medium text-gray-700" for="firstname">First Name</label>
        <input type="text" id="first-name" name="firstname"  value="{{ count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[0] : Auth::user()->name }}" class="w-full px-3 py-2 outline-none focus:ring-0 border-none">
      </div>

      <!-- Last Name -->
      <div class="border border-gray-300 rounded-lg mb-4 p-2">
        <label class="block text-sm font-medium text-gray-700" for="lastname">Last Name</label>
        <input type="text" id="last-name" name="lastname"  value="{{ count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[1] : Auth::user()->name }}" class="w-full px-3 py-2 outline-none focus:ring-0 border-none  ">
      </div>

      <!-- Username -->
      <div class="border border-gray-300 rounded-lg mb-4 p-2">
        <label class="block text-sm font-medium text-gray-700" for="username">Username</label>
        <input type="text" id="username" disabled name="username" value="{{Auth::user()->username}}" class="w-full px-3 py-2 outline-none focus:ring-0 border-none ">
      </div>

      <!-- Email -->
      <div class="border border-gray-300 rounded-lg mb-4 p-2">
        <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
        <input type="email" id="email" name="email" value="{{Auth::user()->email}}" disabled class="w-full px-3 py-2 outline-none border-none focus:ring-0">
      </div>

      <!-- Phone -->
      <div class="border border-gray-300 rounded-lg mb-4 p-2">
        <label class="block text-sm font-medium text-gray-700" for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="{{Auth::user()->phone}}" class="w-full px-3 py-2 outline-none focus:ring-0 border-none ">
      </div>

      <button type="submit" class="w-[8rem] py-2 px-4 bg-vastel_blue hover:bg-blue-600 text-white font-bold rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
        Update
      </button>
    </form>
  </div>

  <script>
  const imageUpload = document.getElementById('image-upload');
  const profileImage = document.getElementById('profile-image');

  imageUpload.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        profileImage.src = e.target.result;
      };
      
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection 