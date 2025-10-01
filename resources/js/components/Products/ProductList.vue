<template>
  <div>
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="products.length === 0" class="text-center py-5">
      <i class="bi bi-box-seam fs-1 text-muted"></i>
      <p class="mt-3 text-muted">No products found</p>
    </div>

    <div v-else>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 80px">Image</th>
              <th>Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th style="width: 150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td>
                <img 
                  :src="product.image || 'https://via.placeholder.com/60'" 
                  :alt="product.name"
                  class="rounded"
                  style="width: 60px; height: 60px; object-fit: cover;"
                >
              </td>
              <td>
                <strong>{{ product.name }}</strong>
                <br>
                <small class="text-muted">{{ product.barcode || 'No barcode' }}</small>
              </td>
              <td>
                <span class="badge bg-secondary">{{ product.category.name }}</span>
              </td>
              <td>
                <strong class="text-primary">{{ product.formatted_price }}</strong>
              </td>
              <td>
                <span 
                  class="badge"
                  :class="{
                    'bg-danger': product.stock_quantity === 0,
                    'bg-warning': product.stock_quantity > 0 && product.stock_quantity < 10,
                    'bg-success': product.stock_quantity >= 10
                  }"
                >
                  {{ product.stock_quantity }}
                </span>
              </td>
              <td>
                <button 
                  class="btn btn-sm btn-outline-primary me-2" 
                  @click="$emit('edit', product)"
                >
                  <i class="bi bi-pencil"></i>
                </button>
                <button 
                  class="btn btn-sm btn-outline-danger" 
                  @click="$emit('delete', product)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav v-if="pagination.last_page > 1" class="mt-3">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
            <button class="page-link" @click="changePage(pagination.current_page - 1)">
              Previous
            </button>
          </li>
          
          <li 
            v-for="page in visiblePages" 
            :key="page"
            class="page-item"
            :class="{ active: page === pagination.current_page }"
          >
            <button class="page-link" @click="changePage(page)">{{ page }}</button>
          </li>
          
          <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
            <button class="page-link" @click="changePage(pagination.current_page + 1)">
              Next
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import productService from '../../services/productService';

export default {
  name: 'ProductList',
  data() {
    return {
      products: [],
      loading: false,
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
      }
    };
  },
  computed: {
    visiblePages() {
      const pages = [];
      const current = this.pagination.current_page;
      const last = this.pagination.last_page;
      
      for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
        pages.push(i);
      }
      return pages;
    }
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts(page = 1) {
      this.loading = true;
      try {
        const response = await productService.getAll(page);
        this.products = response.data;
        this.pagination = {
          current_page: response.current_page,
          last_page: response.last_page,
          per_page: response.per_page,
          total: response.total
        };
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        this.loading = false;
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.fetchProducts(page);
      }
    }
  },
  emits: ['edit', 'delete']
};
</script>