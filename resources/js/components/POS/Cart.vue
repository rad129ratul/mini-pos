<template>
  <div class="card shadow-sm cart-sidebar">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">
        <i class="bi bi-cart3 me-2"></i>
        Shopping Cart
        <span v-if="cartStore.cartCount > 0" class="badge bg-light text-dark ms-2">
          {{ cartStore.cartCount }}
        </span>
      </h5>
    </div>
    <div class="card-body">
      <div v-if="cartStore.items.length === 0" class="text-center py-5">
        <i class="bi bi-cart-x fs-1 text-muted"></i>
        <p class="mt-3 text-muted">Cart is empty</p>
      </div>

      <div v-else>
        <div class="cart-items mb-3">
          <CartItem 
            v-for="item in cartStore.items" 
            :key="item.product.id" 
            :item="item"
          />
        </div>

        <hr>

        <div class="cart-summary">
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <span>৳{{ formatPrice(cartStore.subtotal) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Tax (10%):</span>
            <span>৳{{ formatPrice(cartStore.tax) }}</span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-3">
            <strong>Grand Total:</strong>
            <strong class="fs-4 text-primary">৳{{ formatPrice(cartStore.grandTotal) }}</strong>
          </div>

          <button 
            class="btn btn-success btn-lg w-100 mb-2" 
            @click="openCheckout"
          >
            <i class="bi bi-credit-card me-2"></i>
            Checkout
          </button>
          <button 
            class="btn btn-outline-danger w-100" 
            @click="clearCart"
          >
            <i class="bi bi-trash me-2"></i>
            Clear Cart
          </button>
        </div>
      </div>
    </div>

    <CheckoutModal ref="checkoutModal" />
  </div>
</template>

<script>
import { useCartStore } from '../../store/cart'
import { useToastStore } from '../../store/toast'
import CartItem from './CartItem.vue'
import CheckoutModal from './CheckoutModal.vue'

export default {
  name: 'Cart',
  components: {
    CartItem,
    CheckoutModal
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
    clearCart() {
      if (confirm('Are you sure you want to clear the cart?')) {
        this.cartStore.clearCart()
        useToastStore().success('Cart cleared')
      }
    },
    openCheckout() {
      this.$refs.checkoutModal.show()
    }
  }
}
</script>

<style scoped>
.cart-sidebar {
  position: sticky;
  top: 20px;
  max-height: calc(100vh - 100px);
  overflow-y: auto;
}

.cart-items {
  max-height: 400px;
  overflow-y: auto;
}

.cart-items::-webkit-scrollbar {
  width: 6px;
}

.cart-items::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 3px;
}
</style>