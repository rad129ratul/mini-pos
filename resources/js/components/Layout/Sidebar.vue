<template>
  <div class="sidebar bg-dark text-white vh-100 p-3">
    <nav class="nav flex-column">
      <router-link to="/" class="nav-link text-white mb-2" active-class="active">
        <i class="bi bi-speedometer2 me-2"></i>
        Dashboard
      </router-link>
      <router-link to="/pos" class="nav-link text-white mb-2" active-class="active">
        <i class="bi bi-cart3 me-2"></i>
        POS
      </router-link>
      <router-link to="/products" class="nav-link text-white mb-2" active-class="active">
        <i class="bi bi-box-seam me-2"></i>
        Products
      </router-link>
      <router-link to="/sales" class="nav-link text-white mb-2" active-class="active">
        <i class="bi bi-receipt me-1"></i>
        Sales
      </router-link>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'Sidebar'
}
</script>

<style scoped>
.sidebar {
  width: 250px;
  position: fixed;
  top: 56px;
  left: 0;
  overflow-y: auto;
}

.nav-link {
  transition: all 0.3s;
  border-radius: 5px;
  padding: 10px 15px;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
  background-color: #0d6efd;
  font-weight: bold;
}
</style>