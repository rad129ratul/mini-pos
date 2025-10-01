<template>
  <div class="card h-100 product-card" @click="handleClick">
    <img 
      :src="product.image || 'https://via.placeholder.com/300'" 
      class="card-img-top" 
      :alt="product.name"
    >
    <div class="card-body">
      <h6 class="card-title fw-bold">{{ product.name }}</h6>
      <p class="card-text text-primary fw-bold fs-5 mb-2">
        ৳{{ formatPrice(product.price) }}
      </p>
      <p class="card-text small mb-3">
        <span :class="stockClass">
          <i class="bi bi-box-seam me-1"></i>
          Stock: {{ product.stock_quantity }}
        </span>
      </p>
      <button 
        class="btn btn-primary w-100" 
        :disabled="product.stock_quantity <= 0"
        @click.stop="handleClick"
      >
        <i class="bi bi-cart-plus me-1"></i>
        {{ product.stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCard',
  props: {
    product: {
      type: Object,
      required: true
    }
  },
  computed: {
    stockClass() {
      if (this.product.stock_quantity <= 0) return 'text-danger'
      if (this.product.stock_quantity < 10) return 'text-warning'
      return 'text-success'
    }
  },
  methods: {
    formatPrice(price) {
      return parseFloat(price).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })
    },
    handleClick() {
      if (this.product.stock_quantity > 0) {
        this.$emit('click', this.product)
      }
    }
  }
}
</script>

<style scoped>
.product-card {
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card-img-top {
  height: 200px;
  object-fit: cover;
}
</style>