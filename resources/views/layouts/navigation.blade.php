<nav class="bg-yellow-400 shadow-md py-3 px-6 flex items-center justify-between">
    <div class="flex items-center space-x-4">
        <a href="/home" class="text-lg font-bold text-yellow-900 hover:text-white">Home</a>
        <a href="{{ route('profile.show') }}" class="text-lg font-bold text-yellow-900 hover:text-white">Profile</a>
        @if(Auth::user() && Auth::user()->is_admin)
            <div class="flex items-center space-x-4 ml-4 pl-4 border-l-2 border-yellow-700">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-yellow-900 hover:text-white">Admin</a>
                <a href="{{ route('admin.users') }}" class="text-lg font-bold text-yellow-900 hover:text-white">Users</a>
                <a href="{{ route('admin.reports') }}" class="text-lg font-bold text-yellow-900 hover:text-white">Reports</a>
                <a href="{{ route('admin.daily-prompts.index') }}" class="text-lg font-bold text-yellow-900 hover:text-white">Prompts</a>
                <a href="{{ route('admin.statistics') }}" class="text-lg font-bold text-yellow-900 hover:text-white">Stats</a>
            </div>
        @endif
    </div>
    <div class="flex items-center space-x-4">
        @if(Auth::user())
            <span class="text-yellow-900 font-semibold">{{ Auth::user()->name }}</span>
            @if(Auth::user()->is_admin)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Admin</span>
            @endif
            @if(Auth::user()->profile_picture)
                <img src="/{{ Auth::user()->profile_picture }}" alt="Profile Picture" class="w-8 h-8 rounded-full object-cover border-2 border-yellow-700">
            @endif
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="ml-2 text-yellow-900 hover:text-white font-bold">Logout</button>
            </form>
        @endif
    </div>
</nav>
