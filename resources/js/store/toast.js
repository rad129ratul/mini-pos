import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
    state: () => ({
        messages: []
    }),

    actions: {
        success(message) {
            const id = Date.now();
            this.messages.push({ id, message, type: 'success' });
            setTimeout(() => this.remove(id), 3000);
        },

        error(message) {
            const id = Date.now();
            this.messages.push({ id, message, type: 'error' });
            setTimeout(() => this.remove(id), 3000);
        },

        remove(id) {
            this.messages = this.messages.filter(msg => msg.id !== id);
        },

        clear() {
            this.messages = [];
        }
    }
});