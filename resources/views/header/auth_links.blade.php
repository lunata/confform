                        <li class="dropdown">
                    @if ($user=Sentinel::check())
                            <?php $user = Confform\User::find($user->id);?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $user->name }} ({{ $user->rolesNames() }})<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>{{ trans('navigation.profile') }}</a></li>
                                @if (Confform\User::checkAccess('event.*'))
                                <li><a href="{{ url('/event') }}"><i class="fa fa-btn fa-handshake-o"></i>{{ trans('navigation.event_list') }}</a></li>
                                @endif
                                @if (Confform\User::checkAccess('user.view'))
                                <li><a href="{{ url('/user') }}"><i class="fa fa-btn fa-users"></i>{{ trans('navigation.users') }}</a></li>
                                @endif
                                @if (Confform\User::checkAccess('role'))
                                <li><a href="{{ url('/role') }}"><i class="fa fa-btn fa-briefcase"></i>{{ trans('navigation.roles') }}</a></li>
                                @endif
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>{{ trans('navigation.logout') }}</a></li>
                            </ul>
                    @else
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ trans('auth.login') }}<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/login') }}">{{ trans('auth.login') }}</a></li>
                                <li><a href="{{ url('/register') }}">{{ trans('auth.register') }}</a></li>
                                <li><a href="{{ url('/reset') }}">{{ trans('auth.reset') }}</a></li>
                            </ul>
                    @endif
                        </li>
