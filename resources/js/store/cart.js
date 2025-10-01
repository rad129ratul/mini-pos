import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: []
    }),

    getters: {
        cartCount: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
        
        subtotal: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
        
        tax: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0) * 0.10,
        
        grandTotal() {
            return this.subtotal + this.tax;
        }
    },

    actions: {
        addItem(product) {
            const existingItem = this.items.find(item => item.product.id === product.id);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.items.push({
                    product: product,
                    quantity: 1,
                    price: product.price
                });
            }
        },

        removeItem(productId) {
            this.items = this.items.filter(item => item.product.id !== productId);
        },

        updateQuantity(productId, quantity) {
            const item = this.items.find(item => item.product.id === productId);
            if (item) {
                item.quantity = quantity;
            }
        },

        clearCart() {
            this.items = [];
        }
    }
});