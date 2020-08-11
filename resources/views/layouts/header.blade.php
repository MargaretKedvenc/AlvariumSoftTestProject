<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">AlvariumSoft Test Project</h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="{{route('home')}}">Home</a>
        <a class="p-2 text-dark" href="{{route('employee-index')}}">Employees</a>
        <a class="p-2 text-dark" href="#">My contacts</a>

{{--                                <!-- Authentication Links -->--}}
{{--                                @guest--}}

{{--                                        <a class="btn btn-outline-primary" href="{{ route('login') }}">{{ __('Login') }}</a>--}}

{{--                                    @if (Route::has('register'))--}}

{{--                                            <a class="btn btn-outline-primary" href="{{ route('register') }}">{{ __('Register') }}</a>--}}

{{--                                    @endif--}}
{{--                                @else--}}
{{--                                        <a id="navbarDropdown" class="p-2 text-blue dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>--}}
{{--                                            {{ Auth::user()->name }} <span class="caret"></span>--}}
{{--                                        </a>--}}

{{--                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">--}}
{{--                                            <a class="dropdown-item" href="#"--}}
{{--                                               onclick="event.preventDefault();--}}
{{--                                                             document.getElementById('logout-form').submit();">--}}
{{--                                                {{ __('Logout') }}--}}
{{--                                            </a>--}}

{{--                                            <form id="logout-form" action="#" method="POST" style="display: none;">--}}
{{--                                                @csrf--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                @endguest--}}
    </nav>
</div>
