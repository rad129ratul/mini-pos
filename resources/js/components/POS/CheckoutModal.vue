<template>
  <teleport to="body">
    <div class="modal fade" ref="modal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="bi bi-credit-card me-2"></i>Checkout
            </h5>
            <button type="button" class="btn-close btn-close-white" @click="close"></button>
          </div>
          
          <div class="modal-body">
            <!-- Cart Summary table same as before -->
            <div class="row mb-4">
              <div class="col-12">
                <h6 class="border-bottom pb-2">Cart Summary</h6>
                <div class="table-responsive">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in cartStore.items" :key="item.product.id">
                        <td>{{ item.product.name }}</td>
                        <td class="text-center">{{ item.quantity }}</td>
                        <td class="text-end">৳{{ formatPrice(item.price) }}</td>
                        <td class="text-end">৳{{ formatPrice(item.price * item.quantity) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end">৳{{ formatPrice(cartStore.subtotal) }}</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="text-end"><strong>Tax (10%):</strong></td>
                        <td class="text-end">৳{{ formatPrice(cartStore.tax) }}</td>
                      </tr>
                      <tr class="table-primary">
                        <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                        <td class="text-end"><strong class="fs-5">৳{{ formatPrice(cartStore.grandTotal) }}</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <!-- Form fields same as before -->
            <form @submit.prevent="handleSubmit">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Customer Name (Optional)</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    v-model="form.customer_name"
                    placeholder="Enter customer name"
                  >
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Payment Method *</label>
                  <div class="d-flex gap-3">
                    <div class="form-check">
                      <input 
                        class="form-check-input" 
                        type="radio" 
                        value="cash" 
                        v-model="form.payment_method"
                        id="cash"
                      >
                      <label class="form-check-label" for="cash">
                        <i class="bi bi-cash-stack me-1"></i> Cash
                      </label>
                    </div>
                    <div class="form-check">
                      <input 
                        class="form-check-input" 
                        type="radio" 
                        value="card" 
                        v-model="form.payment_method"
                        id="card"
                      >
                      <label class="form-check-label" for="card">
                        <i class="bi bi-credit-card me-1"></i> Card
                      </label>
                    </div>
                  </div>
                </div>

                <div v-if="form.payment_method === 'cash'" class="col-md-6 mb-3">
                  <label class="form-label">Amount Received *</label>
                  <input 
                    type="number" 
                    class="form-control" 
                    v-model.number="form.amount_received"
                    placeholder="Enter amount"
                    step="0.01"
                    min="0"
                    :class="{ 'is-invalid': amountError }"
                  >
                  <div v-if="amountError" class="invalid-feedback">
                    Amount must be at least ৳{{ formatPrice(cartStore.grandTotal) }}
                  </div>
                </div>

                <div v-if="form.payment_method === 'cash' && form.amount_received >= cartStore.grandTotal" class="col-md-6 mb-3">
                  <label class="form-label">Change</label>
                  <div class="alert alert-success mb-0 py-2">
                    <strong class="fs-5">৳{{ formatPrice(change) }}</strong>
                  </div>
                </div>
              </div>

              <div v-if="error" class="alert alert-danger">
                {{ error }}
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="close" :disabled="loading">
              Cancel
            </button>
            <button 
              type="button" 
              class="btn btn-success btn-lg" 
              @click="handleSubmit"
              :disabled="loading || !isValid"
            >
              <span v-if="loading">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Processing...
              </span>
              <span v-else>
                <i class="bi bi-check-circle me-2"></i>
                Complete Sale
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>
import { Modal } from 'bootstrap'
import { useCartStore } from '../../store/cart'
import { useToastStore } from '../../store/toast'
import saleService from '../../services/saleService'

export default {
  name: 'CheckoutModal',
  data() {
    return {
      modalInstance: null,
      form: {
        customer_name: '',
        payment_method: 'cash',
        amount_received: 0
      },
      loading: false,
      error: null
    }
  },
  setup() {
    const cartStore = useCartStore()
    const toastStore = useToastStore()
    return { cartStore, toastStore }
  },
  computed: {
    change() {
      if (this.form.payment_method === 'cash' && this.form.amount_received) {
        return Math.max(0, this.form.amount_received - this.cartStore.grandTotal)
      }
      return 0
    },
    amountError() {
      return this.form.payment_method === 'cash' && 
             this.form.amount_received > 0 && 
             this.form.amount_received < this.cartStore.grandTotal
    },
    isValid() {
      if (!this.form.payment_method) return false
      if (this.form.payment_method === 'cash') {
        return this.form.amount_received >= this.cartStore.grandTotal
      }
      return true
    }
  },
  mounted() {
    this.modalInstance = new Modal(this.$refs.modal)
  },
  methods: {
    formatPrice(price) {
      return parseFloat(price).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })
    },
    show() {
      this.resetForm()
      this.modalInstance.show()
    },
    close() {
      this.modalInstance.hide()
    },
    resetForm() {
      this.form = {
        customer_name: '',
        payment_method: 'cash',
        amount_received: 0
      }
      this.error = null
    },
    async handleSubmit() {
      if (!this.isValid) return

      this.loading = true
      this.error = null

      try {
        const saleData = {
          items: this.cartStore.items.map(item => ({
            product_id: item.product.id,
            quantity: item.quantity
          })),
          payment_method: this.form.payment_method,
          customer_name: this.form.customer_name || null
        }

        await saleService.create(saleData)
        
        this.toastStore.success('Sale completed successfully!')
        this.cartStore.clearCart()
        this.close()
        
        if (this.form.payment_method === 'cash' && this.change > 0) {
          setTimeout(() => {
            this.toastStore.success(`Change: ৳${this.formatPrice(this.change)}`)
          }, 500)
        }
      } catch (err) {
        this.error = err.response?.data?.error || 'Failed to complete sale'
        this.toastStore.error(this.error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.modal-body {
  max-height: 70vh;
  overflow-y: auto;
}
</style>