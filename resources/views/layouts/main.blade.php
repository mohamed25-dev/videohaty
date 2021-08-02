<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Bootstrap --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous">
    </script>

    <title>فيديوهاتي</title>

</head>

<body dir="rtl" style="text-align: right">


    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-white">
            <a class="navbar-brand" href="#">فيديوهاتي</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-home">
                                الصفحة الرئيسية
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-history">
                                سجل المشاهدة
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('videos.create')}}">
                            <i class="fas fa-upload">
                                رفع فيديو
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('videos.index')}}">
                            <i class="fas fa-play-circle">
                                قناتي
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-film">
                                القنوات
                            </i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav mr-auto">
                    @guest
                        <li class="nav-item mt-2">
                            <a class="nav-link" href="{{ route('login') }}">
                                {{ __('تسجيل الدخول') }}
                            </a>
                        </li>

                        @if(Route::has('register'))
                            <li class="nav-item mt-2">
                                <a class="nav-link" href="{{ route('register') }}">
                                    {{ __('إنشاء حساب') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown justify-content-left mt-2">
                            <a id="navbarDropdown" class="nav-link" href="#" data-toggle="dropdown">
                                <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                                    alt="{{ Auth::user()->name }}">
                            </a>

                        <div class="dropdown-menu dropdown-menu-left px-2 text-right mt-2">
                            <div class="pt-4 pb-1 border-t border-gray-200">
                                <div class="flex items-center px-4">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <div class="flex-shrink-0 ml-3">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ Auth::user()->profile_photo_url }}"
                                                alt="{{ Auth::user()->name }}" />
                                        </div>
                                    @endif

                                    <div class="ml-3">
                                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>

                                <div class="mt-3 space-y-1">
                                    <!-- Account Management -->
                                    <x-jet-responsive-nav-link href="{{ route('profile.show') }}"
                                        :active="request()->routeIs('profile.show')">
                                        {{ __('site.profile') }}
                                    </x-jet-responsive-nav-link>

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}"
                                            :active="request()->routeIs('api-tokens.index')">
                                            {{ __('site.api_token') }}
                                        </x-jet-responsive-nav-link>
                                    @endif

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-jet-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                            {{ __('site.logout') }}
                                        </x-jet-responsive-nav-link>
                                    </form>

                                    <!-- Team Management -->
                                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('site.manage_team') }}
                                        </div>

                                        <!-- Team Settings -->
                                        <x-jet-responsive-nav-link
                                            href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                            :active="request()->routeIs('teams.show')">
                                            {{ __('site.team_settings') }}
                                        </x-jet-responsive-nav-link>

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-jet-responsive-nav-link href="{{ route('teams.create') }}"
                                                :active="request()->routeIs('teams.create')">
                                                {{ __('site.new_team') }}
                                            </x-jet-responsive-nav-link>
                                        @endcan

                                        <div class="border-t border-gray-200"></div>

                                        <!-- Team Switcher -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('site.team_switch') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                          </div> 
                        </div>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">
          @if (Session::has('success'))
            <div class="p-3 mb-2 bg-success text-white rounded mx-auto col-8">
              <span class="text-center">
                {{session('success')}}
              </span>
            </div>
          @endif

          @yield('content')
        </main>
    </div>

    @yield('script')
</body>

</html>
