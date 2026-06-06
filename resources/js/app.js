import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* ── Helpers globaux ────────────────────────────────────────────────── */
window.KD = {

    // Formater un montant en FCFA
    formatMontant(montant) {
        if (!montant && montant !== 0) return '—';
        return new Intl.NumberFormat('fr-SN').format(montant) + ' FCFA';
    },

    // Formater une date
    formatDate(date) {
        if (!date) return '—';
        return new Date(date).toLocaleDateString('fr-FR', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
    },

    // Afficher une notification toast
    toast(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error:   'bg-red-500',
            warning: 'bg-yellow-500',
            info:    'bg-blue-500',
        };

        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl text-white text-sm font-semibold shadow-lg transition-all duration-300 ${colors[type] || colors.info}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(8px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    // Confirmer une action
    confirm(message) {
        return window.confirm(message);
    },
};

/* ── Composant Alpine : Notifications ──────────────────────────────── */
document.addEventListener('alpine:init', () => {

    Alpine.data('notifications', () => ({
        count: 0,
        items: [],
        open: false,
        loading: false,

        async init() {
            await this.fetchCount();
            // Rafraîchir toutes les 30 secondes
            setInterval(() => this.fetchCount(), 30000);
        },

        async fetchCount() {
            try {
                const res = await fetch('/web/notifications/compteur');
                const json = await res.json();
                this.count = json.non_lues || 0;
            } catch (e) {
                console.error('Erreur compteur notifications', e);
            }
        },

        async fetchItems() {
            this.loading = true;
            try {
                const res = await fetch('/web/notifications?filtre=non_lues');
                const json = await res.json();
                this.items = json.data || [];
            } catch (e) {
                console.error('Erreur notifications', e);
            } finally {
                this.loading = false;
            }
        },

        async toggle() {
            this.open = !this.open;
            if (this.open && this.items.length === 0) {
                await this.fetchItems();
            }
        },

        async marquerLue(id) {
            try {
                await fetch(`/web/notifications/${id}/lire`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                this.items = this.items.filter(n => n.id !== id);
                this.count = Math.max(0, this.count - 1);
            } catch (e) {
                console.error('Erreur marquer lu', e);
            }
        },

        async marquerTout() {
            try {
                await fetch('/web/notifications/lire-tout', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                this.items = [];
                this.count = 0;
                KD.toast('Toutes les notifications sont lues');
            } catch (e) {
                console.error('Erreur marquer tout', e);
            }
        },
    }));

    /* ── Composant Alpine : Sidebar mobile ── */
    Alpine.data('sidebar', () => ({
        open: false,
        toggle() { this.open = !this.open; },
        close()  { this.open = false; },
    }));

    /* ── Composant Alpine : Filtres ── */
    Alpine.data('filtres', () => ({
        visible: false,
        toggle() { this.visible = !this.visible; },
    }));

    /* ── Composant Alpine : Modal ── */
    Alpine.data('modal', () => ({
        open: false,
        show() { this.open = true; document.body.style.overflow = 'hidden'; },
        hide() { this.open = false; document.body.style.overflow = ''; },
    }));

    /* ── Composant Alpine : Dropdown ── */
    Alpine.data('dropdown', () => ({
        open: false,
        toggle() { this.open = !this.open; },
        close()  { this.open = false; },
    }));
});

Alpine.start();

/* ── Animations au scroll ───────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {

    // Observer pour animer les éléments au scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('kd-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.kd-animate').forEach(el => {
        observer.observe(el);
    });

    // Auto-fermer les alertes après 4 secondes
    document.querySelectorAll('.kd-alert-auto').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });
});