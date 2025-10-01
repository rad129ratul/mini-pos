<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 col-md-7">
        <h2 class="mb-4">Point of Sale</h2>
        <SearchBar @search="handleSearch" class="mb-4" />
        <ProductGrid :products="products" @add-to-cart="addToCart" />
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
      products: []
    }
  },
  mounted() {
    this.fetchProducts()
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
    async handleSearch(query) {
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
    addToCart(product) {
      if (product.stock_quantity <= 0) {
        useToastStore().error('Product out of stock')
        return
      }
      useCartStore().addItem(product)
      useToastStore().success(`${product.name} added to cart`)
    }
  }
}
</script>