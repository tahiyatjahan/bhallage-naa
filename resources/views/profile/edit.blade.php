@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Profile
    </h2>
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-2xl font-bold mb-4">Edit Profile</h3>
        @if(session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2" required>
                @error('name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
                @error('email')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Profile Picture</label>
                @if($user->profile_picture)
                    <img src="/{{ $user->profile_picture }}" alt="Profile Picture" class="w-24 h-24 rounded-full mb-2 object-cover">
                @endif
                <input type="file" name="profile_picture" class="block w-full text-sm text-gray-500">
                @error('profile_picture')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
            <a href="{{ route('profile.show') }}" class="ml-4 text-gray-600 underline">Cancel</a>
        </form>
    </div>
</div>
@endsection
