<template>
  <div>
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2">Loading products...</p>
    </div>

    <div v-else-if="products.length === 0" class="text-center py-5">
      <i class="bi bi-box-seam fs-1 text-muted"></i>
      <p class="mt-3 text-muted">No products found</p>
    </div>

    <div v-else class="row g-3">
      <div 
        v-for="product in products" 
        :key="product.id" 
        class="col-12 col-md-6 col-lg-4"
      >
        <ProductCard :product="product" @click="$emit('add-to-cart', product)" />
      </div>
    </div>
  </div>
</template>

<script>
import ProductCard from './ProductCard.vue'

export default {
  name: 'ProductGrid',
  components: {
    ProductCard
  },
  props: {
    products: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      loading: false
    }
  },
  emits: ['add-to-cart']
}
</script>