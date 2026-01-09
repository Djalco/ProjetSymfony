import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['count'];
    static values = {
        refreshInterval: { type: Number, default: 30000 } // 30 secondes par défaut
    };

    connect() {
        console.log('Deal counter controller connected');
        this.loadCount();
        
        // Rafraîchissement automatique
        this.startAutoRefresh();
    }

    disconnect() {
        // Nettoyer l'intervalle quand le controller est déconnecté
        this.stopAutoRefresh();
    }

    startAutoRefresh() {
        this.refreshTimer = setInterval(() => {
            this.loadCount();
        }, this.refreshIntervalValue);
    }

    stopAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
        }
    }

    // Action appelée lors du clic pour rafraîchir manuellement
    refresh(event) {
        event.preventDefault();
        console.log('Manual refresh triggered');
        this.loadCount();
        
        // Animation visuelle pour indiquer le rafraîchissement
        this.countTarget.style.opacity = '0.5';
        setTimeout(() => {
            this.countTarget.style.opacity = '1';
        }, 300);
    }

    async loadCount() {
        try {
            const response = await fetch('/api/deals/count');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            this.countTarget.textContent = data.count;
        } catch (error) {
            console.error('Error fetching deal count:', error);
            this.countTarget.textContent = 'Error';
        }
    }
}
