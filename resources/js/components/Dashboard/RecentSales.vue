<template>
  <div class="card shadow-sm">
    <div class="card-header bg-white">
      <h5 class="mb-0">
        <i class="bi bi-clock-history me-2"></i>
        Recent Sales
      </h5>
    </div>
    <div class="card-body">
      <div v-if="sales && sales.length > 0" class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Date/Time</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Payment</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="sale in sales" 
              :key="sale.id"
              @click="$emit('view-details', sale)"
              style="cursor: pointer;"
            >
              <td>
                <small>{{ formatDate(sale.date || sale.created_at) }}</small>
              </td>
              <td>{{ sale.customer_name || 'Walk-in Customer' }}</td>
              <td class="fw-bold text-success">৳{{ formatNumber(sale.amount || sale.total_amount) }}</td>
              <td>
                <span 
                  class="badge"
                  :class="sale.payment_method === 'cash' ? 'bg-success' : 'bg-primary'"
                >
                  {{ sale.payment_method.toUpperCase() }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="text-center py-4 text-muted">
        <i class="bi bi-inbox fs-1"></i>
        <p class="mt-2">No sales yet</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RecentSales',
  props: {
    sales: {
      type: Array,
      default: () => []
    }
  },
  emits: ['view-details'],
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    formatNumber(num) {
      return parseFloat(num).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
  }
}
</script>

<style scoped>
tbody tr:hover {
  background-color: rgba(0, 0, 0, 0.02);
}
</style>