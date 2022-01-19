<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <router-link to="/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt blue"></i>
          <p>
            Dashboard
          </p>
        </router-link>
      </li>

      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/users" class="nav-link">
            <i class="fa fa-users nav-icon blue"></i>
            <p>Usuários</p>
          </router-link>
        </li>
      @endcan

      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/comments" class="nav-link">
            <i class="fas fa-comment nav-icon blue"></i>
            <p>Comentários</p>
          </router-link>
        </li>
      @endcan

      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/facebook-accounts" class="nav-link">
            <i class="fab fa-facebook-square nav-icon blue"></i>
            <p>Contas do Facebook</p>
          </router-link>
        </li>
      @endcan

      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/niches" class="nav-link">
            <i class="fas fa-bezier-curve nav-icon blue"></i>
            <p>Nichos</p>
          </router-link>
        </li>
      @endcan

      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Logout') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>
