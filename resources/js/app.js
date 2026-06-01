import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});

window.Alpine = Alpine;

Alpine.data('notificationPolling', (pollUrl) => ({
    unreadCount: 0,
    notifications: [],
    intervalId: null,

    init() {
        this.fetchNotifications();
        this.intervalId = setInterval(() => this.fetchNotifications(), 10000);
    },

    async fetchNotifications() {
        try {
            const res = await fetch(pollUrl);
            if (!res.ok) return;
            const data = await res.json();
            this.unreadCount = data.count;
            this.notifications = data.notifications;
        } catch (e) {}
    },

    async markRead(id) {
        try {
            await fetch(`/poll/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content } });
            this.unreadCount = Math.max(0, this.unreadCount - 1);
            this.notifications = this.notifications.filter(n => n.id !== id);
        } catch (e) {}
    },

    async markAllRead() {
        try {
            await fetch('/poll/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content } });
            this.unreadCount = 0;
            this.notifications = [];
        } catch (e) {}
    },

    destroy() {
        if (this.intervalId) clearInterval(this.intervalId);
    }
}));

Alpine.data('emergencyAlert', (url) => ({
    alerts: [],
    intervalId: null,
    init() {
        this.fetchAlerts();
        this.intervalId = setInterval(() => this.fetchAlerts(), 30000);
    },
    async fetchAlerts() {
        try {
            const res = await fetch(url);
            if (!res.ok) return;
            const data = await res.json();
            this.alerts = Array.isArray(data) ? data.map(a => ({ id: a.id, title: a.title, url: `/news/${a.slug || a.id}` })) : [];
        } catch (e) {}
    },
    destroy() { if (this.intervalId) clearInterval(this.intervalId); }
}));

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
