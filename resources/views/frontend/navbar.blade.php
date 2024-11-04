<nav class="navbar backdrop-blur-md bg-base-100/50 z-10 top-0 fixed">
    <div class="container mx-auto flex justify-between items-center">

        <!-- Logo Section -->
        <div class="flex-1">
            <a class="btn btn-ghost normal-case text-xl" href="/">{{ config('app.name') }}</a>
        </div>

        <!-- Right Menu -->
        <div class="flex-none hidden md:flex items-center space-x-4">

            <!-- User Status -->
            <div class="ml-4">
                @if (Auth::check())
                <span class="mr-2">Hoş geldin, {{ Auth::user()->name }}</span>
                <form action="{{ route('filament.admin.auth.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Çıkış Yap</button>
                </form>
                @else
                <a href="{{ route('filament.admin.auth.register') }}" class="btn btn-secondary">Kayıt Ol</a>
                <a href="{{ route('filament.admin.auth.login') }}" class="btn btn-primary ml-2">Giriş Yap</a>
                @endif
            </div>

            <!-- Theme Selection -->
            @include('frontend.partials.theme-dropdown')

            <!-- Navigation Links -->
            <div class="ml-4 flex space-x-4">
                <a class="btn btn-ghost" href="/">Home</a>
                <a class="btn btn-ghost" href="/products">Products</a>
                <a class="btn btn-ghost" href="/cart">Cart</a>
                <a class="btn btn-ghost" href="/checkout">Checkout</a>
            </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
            <button onclick="toggleMenu()" class="btn btn-ghost text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden bg-base-200 p-4 space-y-4 hidden">
        <div class="flex flex-col items-center space-y-2">

            <!-- User Status -->
            <div>
                @if (Auth::check())
                <span class="block text-center mb-2">Hoş geldin, {{ Auth::user()->name }}</span>
                <form action="{{ route('filament.admin.auth.logout') }}" method="POST" class="text-center">
                    @csrf
                    <button type="submit" class="btn btn-primary">Çıkış Yap</button>
                </form>
                @else
                <a href="{{ route('filament.admin.auth.register') }}" class="btn btn-secondary w-full mb-2">Kayıt Ol</a>
                <a href="{{ route('filament.admin.auth.login') }}" class="btn btn-primary w-full">Giriş Yap</a>
                @endif
            </div>

            <!-- Theme Selection -->
            @include('frontend.partials.theme-dropdown')

            <!-- Navigation Links -->
            <a class="btn btn-ghost w-full" href="/">Home</a>
            <a class="btn btn-ghost w-full" href="/products">Products</a>
            <a class="btn btn-ghost w-full" href="/cart">Cart</a>
            <a class="btn btn-ghost w-full" href="/checkout">Checkout</a>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
