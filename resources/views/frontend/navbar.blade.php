<nav class="navbar bg-base-200 sticky z-10 top-0">
    <div class="container mx-auto flex justify-between items-center">

        <!-- Logo Bölümü -->
        <div class="flex-1">
            <a class="btn btn-ghost normal-case text-xl" href="/">{{ config('app.name') }}</a>
        </div>

        <!-- Sağ Menü -->
        <div class="flex-none hidden md:flex items-center space-x-4">

            <!-- Kullanıcı Durumu -->
            <div class="ml-4">
                @if (Auth::check())
                <span class="mr-2">Hoş geldin, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Çıkış Yap</button>
                </form>
                @else
                <a href="{{ route('register') }}" class="btn btn-secondary">Kayıt Ol</a>
                <a href="{{ route('login') }}" class="btn btn-primary ml-2">Giriş Yap</a>
                @endif
            </div>

            <!-- Tema Seçimi -->
            <div class="ml-4">
                <select onchange="changeTheme(this.value)" class="select select-bordered">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="cupcake">Cupcake</option>
                    <option value="bumblebee">Bumblebee</option>
                    <option value="emerald">Emerald</option>
                    <option value="corporate">Corporate</option>
                    <option value="synthwave">Synthwave</option>
                    <option value="retro">Retro</option>
                    <option value="cyberpunk">Cyberpunk</option>
                    <option value="valentine">Valentine</option>
                    <option value="halloween">Halloween</option>
                    <option value="forest">Forest</option>
                    <option value="aqua">Aqua</option>
                    <option value="lofi">Lofi</option>
                    <option value="pastel">Pastel</option>
                    <option value="fantasy">Fantasy</option>
                    <option value="wireframe">Wireframe</option>
                    <option value="black">Black</option>
                    <option value="luxury">Luxury</option>
                    <option value="dracula">Dracula</option>
                    <option value="cmyk">CMYK</option>
                    <option value="autumn">Autumn</option>
                    <option value="business">Business</option>
                </select>
            </div>

            <!-- Navigasyon Linkleri -->
            <div class="ml-4 flex space-x-4">
                <a class="btn btn-ghost" href="/">Home</a>
                <a class="btn btn-ghost" href="/products">Products</a>
                <a class="btn btn-ghost" href="/cart">Cart</a>
                <a class="btn btn-ghost" href="/checkout">Checkout</a>
            </div>
        </div>

        <!-- Mobil Menü Butonu -->
        <div class="md:hidden flex items-center">
            <button onclick="toggleMenu()" class="btn btn-ghost text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobil Menü -->
    <div id="mobile-menu" class="md:hidden bg-base-200 p-4 space-y-4 hidden">
        <div class="flex flex-col items-center space-y-2">

            <!-- Kullanıcı Durumu -->
            <div>
                @if (Auth::check())
                <span class="block text-center mb-2">Hoş geldin, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="text-center">
                    @csrf
                    <button type="submit" class="btn btn-primary">Çıkış Yap</button>
                </form>
                @else
                <a href="{{ route('register') }}" class="btn btn-secondary w-full mb-2">Kayıt Ol</a>
                <a href="{{ route('login') }}" class="btn btn-primary w-full">Giriş Yap</a>
                @endif
            </div>

            <!-- Tema Seçimi -->
            <select onchange="changeTheme(this.value)" class="select select-bordered">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="cupcake">Cupcake</option>
                <option value="bumblebee">Bumblebee</option>
                <option value="emerald">Emerald</option>
                <option value="corporate">Corporate</option>
                <option value="synthwave">Synthwave</option>
                <option value="retro">Retro</option>
                <option value="cyberpunk">Cyberpunk</option>
                <option value="valentine">Valentine</option>
                <option value="halloween">Halloween</option>
                <option value="forest">Forest</option>
                <option value="aqua">Aqua</option>
                <option value="lofi">Lofi</option>
                <option value="pastel">Pastel</option>
                <option value="fantasy">Fantasy</option>
                <option value="wireframe">Wireframe</option>
                <option value="black">Black</option>
                <option value="luxury">Luxury</option>
                <option value="dracula">Dracula</option>
                <option value="cmyk">CMYK</option>
                <option value="autumn">Autumn</option>
                <option value="business">Business</option>
            </select>

            <!-- Navigasyon Linkleri -->
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