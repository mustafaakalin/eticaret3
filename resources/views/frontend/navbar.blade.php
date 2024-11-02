<nav class="navbar backdrop-blur-md bg-base-100/50  z-10 top-0 fixed">
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
            
            <div class="dropdown  dropdown-end">
                <div tabindex="0" role="button" class="btn m-1">
                    Theme
                    <svg width="12px" height="12px" class="inline-block h-2 w-2 fill-current opacity-60"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                        <path d="M1799 349l242 241-1017 1017L7 590l242-241 775 775 775-775z"></path>
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content bg-base-300 rounded-box z-[1] w-52 p-2 shadow-2xl max-h-60 overflow-y-auto">
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Light" value="light" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dark" value="dark" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Cupcake" value="cupcake" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Bumblebee" value="bumblebee" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Emerald" value="emerald" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Corporate" value="corporate" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Synthwave" value="synthwave" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Retro" value="retro" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Cyberpunk" value="cyberpunk" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Valentine" value="valentine" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Halloween" value="halloween" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Garden" value="garden" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Forest" value="forest" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Aqua" value="aqua" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Lofi" value="lofi" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Pastel" value="pastel" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Fantasy" value="fantasy" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Wireframe" value="wireframe" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Black" value="black" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Luxury" value="luxury" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dracula" value="dracula" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="CMYK" value="cmyk" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Autumn" value="autumn" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Business" value="business" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Acid" value="acid" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Lemonade" value="lemonade" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Night" value="night" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Coffee" value="coffee" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Winter" value="winter" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dim" value="dim" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Nord" value="nord" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Sunset" value="sunset" />
                    </li>
                </ul>

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
            <div class="dropdown dropdown-left">
                <div tabindex="0" role="button" class="btn m-1">
                    Theme
                    <svg width="12px" height="12px" class="inline-block h-2 w-2 fill-current opacity-60"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                        <path d="M1799 349l242 241-1017 1017L7 590l242-241 775 775 775-775z"></path>
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content bg-base-300 rounded-box z-[1] w-52 p-2 shadow-2xl max-h-60 overflow-y-auto">
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Light" value="light" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dark" value="dark" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Cupcake" value="cupcake" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Bumblebee" value="bumblebee" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Emerald" value="emerald" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Corporate" value="corporate" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Synthwave" value="synthwave" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Retro" value="retro" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Cyberpunk" value="cyberpunk" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Valentine" value="valentine" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Halloween" value="halloween" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Garden" value="garden" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Forest" value="forest" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Aqua" value="aqua" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Lofi" value="lofi" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Pastel" value="pastel" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Fantasy" value="fantasy" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Wireframe" value="wireframe" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Black" value="black" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Luxury" value="luxury" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dracula" value="dracula" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="CMYK" value="cmyk" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Autumn" value="autumn" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Business" value="business" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Acid" value="acid" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Lemonade" value="lemonade" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Night" value="night" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Coffee" value="coffee" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Winter" value="winter" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dim" value="dim" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Nord" value="nord" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Sunset" value="sunset" />
                    </li>
                </ul>

            </div>

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

