<template>
  <div class="list-group-item d-flex justify-content-between align-items-center">
    <div class="flex-grow-1">
      <h6 class="mb-1">{{ item.product.name }}</h6>
      <small class="text-muted">৳{{ formatPrice(item.price) }} each</small>
    </div>
    
    <div class="d-flex align-items-center gap-2">
      <div class="btn-group btn-group-sm" role="group">
        <button 
          type="button" 
          class="btn btn-outline-secondary" 
          @click="decreaseQuantity"
          :disabled="item.quantity <= 1"
        >
          <i class="bi bi-dash"></i>
        </button>
        <input 
          type="number" 
          class="form-control text-center" 
          style="width: 60px;"
          :value="item.quantity"
          @input="updateQuantity"
          min="1"
          :max="item.product.stock_quantity"
        >
        <button 
          type="button" 
          class="btn btn-outline-secondary" 
          @click="increaseQuantity"
          :disabled="item.quantity >= item.product.stock_quantity"
        >
          <i class="bi bi-plus"></i>
        </button>
      </div>
      
      <div class="text-end" style="min-width: 80px;">
        <strong>৳{{ formatPrice(item.price * item.quantity) }}</strong>
      </div>
      
      <button 
        type="button" 
        class="btn btn-sm btn-danger" 
        @click="removeItem"
      >
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  </div>
</template>

<script>
import { useCartStore } from '../../store/cart'

export default {
  name: 'CartItem',
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  setup() {
    const cartStore = useCartStore()
    return { cartStore }
  },
  methods: {
    formatPrice(price) {
      return parseFloat(price).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })
    },
    increaseQuantity() {
      if (this.item.quantity < this.item.product.stock_quantity) {
        this.cartStore.updateQuantity(this.item.product.id, this.item.quantity + 1)
      }
    },
    decreaseQuantity() {
      if (this.item.quantity > 1) {
        this.cartStore.updateQuantity(this.item.product.id, this.item.quantity - 1)
      }
    },
    updateQuantity(e) {
      const quantity = parseInt(e.target.value)
      if (quantity > 0 && quantity <= this.item.product.stock_quantity) {
        this.cartStore.updateQuantity(this.item.product.id, quantity)
      }
    },
    removeItem() {
      this.cartStore.removeItem(this.item.product.id)
    }
  }
}
</script>

<style scoped>
.list-group-item {
  border-left: 3px solid #0d6efd;
}

.btn-group-sm .form-control {
  border-radius: 0;
  border-left: 0;
  border-right: 0;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>