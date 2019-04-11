<template>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #065C7B">

    <a class="navbar-brand" href="#">
      <img src="../assets/logo.png" height="40" width="200" alt="">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">

        <li class="nav-item active">
          <a class="nav-link"><i class="fas fa-home"></i> Inicio</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-building"></i> Empresa
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#">Todas</a>
            <a class="dropdown-item" href="#">Actual</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-gavel"></i> Bases Legales
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#">Todas</a>
            <a class="dropdown-item" href="#">Actual</a>
          </div>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link"><i class="fas fa-user-tie"></i> Trabajadores</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-clipboard-list"></i> Nomina
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#">Todas</a>
            <a class="dropdown-item" href="#">Actual</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fas fa-users"></i> Usuarios</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fas fa-umbrella-beach"></i> Vacaciones</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user"></i> SuperUser
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" @click="logout">Cerrar Sesion</a>
          </div>
        </li>

      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Header',
  methods: {
    logout () {
      this.$store.commit('logout');
      this.$router.push({path: '/login'});
    }
  }
}
</script>
