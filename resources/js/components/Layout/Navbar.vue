<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <router-link to="/" class="navbar-brand">
        <i class="bi bi-shop me-2"></i>Mini POS
      </router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link to="/" class="nav-link" active-class="active">
              <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/pos" class="nav-link" active-class="active">
              <i class="bi bi-cart3 me-1"></i>POS
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/products" class="nav-link" active-class="active">
              <i class="bi bi-box-seam me-1"></i>Products
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/sales" class="nav-link" active-class="active">
              <i class="bi bi-receipt me-1"></i>Sales
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar'
}
</script>

<style scoped>
.navbar-brand {
  font-weight: bold;
  font-size: 1.3rem;
}

.nav-link {
  transition: all 0.2s ease;
}

.nav-link:hover {
  transform: translateY(-2px);
}

.nav-link.active {
  font-weight: bold;
}
</style>