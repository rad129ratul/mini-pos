<template>
  <div class="container-fluid" @keydown="handleKeydown">
    <div class="row">
      <div class="col-lg-8 col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="mb-0">Point of Sale</h2>
          <div class="keyboard-hints">
            <small class="text-muted">
              <kbd>F9</kbd> Checkout • <kbd>Esc</kbd> Clear Cart
            </small>
          </div>
        </div>
        <SearchBar @search="handleSearch" class="mb-4" />
        
        <div class="category-tabs mb-4">
          <div class="btn-group w-100" role="group">
            <button 
              type="button" 
              class="btn btn-outline-primary"
              :class="{ active: selectedCategory === null }"
              @click="filterByCategory(null)"
            >
              All Products
            </button>
            <button 
              v-for="category in categories" 
              :key="category.id"
              type="button" 
              class="btn btn-outline-primary"
              :class="{ active: selectedCategory === category.id }"
              @click="filterByCategory(category.id)"
            >
              {{ category.name }}
            </button>
          </div>
        </div>

        <ProductGrid :products="filteredProducts" @add-to-cart="addToCart" />
      </div>
      <div class="col-lg-4 col-md-5">
        <Cart />
      </div>
    </div>
  </div>
</template>

<script>
import SearchBar from '../components/POS/SearchBar.vue'
import ProductGrid from '../components/POS/ProductGrid.vue'
import Cart from '../components/POS/Cart.vue'
import productService from '../services/productService'
import categoryService from '../services/categoryService'
import { useCartStore } from '../store/cart'
import { useToastStore } from '../store/toast'

export default {
  name: 'POS',
  components: {
    SearchBar,
    ProductGrid,
    Cart
  },
  data() {
    return {
      products: [],
      categories: [],
      selectedCategory: null,
      searchQuery: ''
    }
  },
  computed: {
    filteredProducts() {
      let filtered = this.products

      if (this.selectedCategory !== null) {
        filtered = filtered.filter(p => p.category.id === this.selectedCategory)
      }

      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(p => 
          p.name.toLowerCase().includes(query) || 
          (p.barcode && p.barcode.includes(query))
        )
      }

      return filtered
    }
  },
  mounted() {
    this.fetchProducts()
    this.fetchCategories()
    window.addEventListener('keydown', this.handleKeydown)
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.handleKeydown)
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await productService.getAll(1)
        this.products = response.data
      } catch (error) {
        useToastStore().error('Failed to load products')
      }
    },
    async fetchCategories() {
      try {
        this.categories = await categoryService.getAll()
      } catch (error) {
        useToastStore().error('Failed to load categories')
      }
    },
    async handleSearch(query) {
      this.searchQuery = query
      if (!query) {
        this.fetchProducts()
        return
      }
      try {
        const response = await productService.search(query)
        this.products = response
      } catch (error) {
        useToastStore().error('Search failed')
      }
    },
    filterByCategory(categoryId) {
      this.selectedCategory = categoryId
      this.searchQuery = ''
      this.fetchProducts()
    },
    addToCart(product) {
      if (product.stock_quantity <= 0) {
        useToastStore().error('Product out of stock')
        return
      }
      useCartStore().addItem(product)
      useToastStore().success(`${product.name} added to cart`)
    },
    handleKeydown(e) {
      if (e.key === 'F9') {
        e.preventDefault()
        const cartStore = useCartStore()
        if (cartStore.items.length > 0) {
          this.$root.$refs.cart?.openCheckout()
        } else {
          useToastStore().error('Cart is empty')
        }
      }
      if (e.key === 'Escape') {
        e.preventDefault()
        const cartStore = useCartStore()
        if (cartStore.items.length > 0) {
          if (confirm('Are you sure you want to clear the cart?')) {
            cartStore.clearCart()
            useToastStore().success('Cart cleared')
          }
        }
      }
    }
  }
}
</script>

<style scoped>
.category-tabs {
  overflow-x: auto;
}

.category-tabs .btn-group {
  flex-wrap: nowrap;
}

.category-tabs .btn {
  white-space: nowrap;
  transition: all 0.3s ease;
}

.category-tabs .btn.active {
  background-color: #0d6efd;
  color: white;
  border-color: #0d6efd;
}

.keyboard-hints kbd {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 3px;
  padding: 2px 6px;
  font-size: 0.75rem;
  font-family: monospace;
}

@media (max-width: 768px) {
  .category-tabs .btn {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
  }
  
  .keyboard-hints {
    display: none;
  }
}
</style>