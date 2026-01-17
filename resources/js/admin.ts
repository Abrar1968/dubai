/**
 * Dubai Tourism & Travel - Admin Panel JavaScript
 * Alpine.js powered interactions
 */

import Alpine from 'alpinejs';

// Make Alpine available globally
declare global {
    interface Window {
        Alpine: typeof Alpine;
    }
}

window.Alpine = Alpine;

// Global Alpine data stores
Alpine.store('sidebar', {
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
});

// Toast notification system
Alpine.store('toast', {
    items: [] as Array<{
        id: number;
        type: 'success' | 'error' | 'warning' | 'info';
        message: string;
    }>,
    counter: 0,
    show(message: string, type: 'success' | 'error' | 'warning' | 'info' = 'info') {
        const id = ++this.counter;
        this.items.push({ id, type, message });
        setTimeout(() => this.remove(id), 5000);
    },
    remove(id: number) {
        this.items = this.items.filter((item) => item.id !== id);
    },
    success(message: string) {
        this.show(message, 'success');
    },
    error(message: string) {
        this.show(message, 'error');
    },
    warning(message: string) {
        this.show(message, 'warning');
    },
    info(message: string) {
        this.show(message, 'info');
    },
});

// Confirm dialog system
Alpine.data('confirmDialog', () => ({
    open: false,
    title: '',
    message: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    onConfirm: null as (() => void) | null,

    show(options: {
        title: string;
        message: string;
        confirmText?: string;
        cancelText?: string;
        onConfirm: () => void;
    }) {
        this.title = options.title;
        this.message = options.message;
        this.confirmText = options.confirmText || 'Confirm';
        this.cancelText = options.cancelText || 'Cancel';
        this.onConfirm = options.onConfirm;
        this.open = true;
    },

    confirm() {
        if (this.onConfirm) {
            this.onConfirm();
        }
        this.close();
    },

    close() {
        this.open = false;
        this.onConfirm = null;
    },
}));

// Dropdown component
Alpine.data('dropdown', () => ({
    open: false,

    toggle() {
        this.open = !this.open;
    },

    close() {
        this.open = false;
    },
}));

// Image upload component
Alpine.data('imageUpload', (options: { maxSize?: number; accept?: string } = {}) => ({
    file: null as File | null,
    preview: '',
    error: '',
    dragging: false,
    maxSize: options.maxSize || 2 * 1024 * 1024, // 2MB default
    accept: options.accept || 'image/*',

    handleDrop(event: DragEvent) {
        this.dragging = false;
        const files = event.dataTransfer?.files;
        if (files && files.length > 0) {
            this.handleFile(files[0]);
        }
    },

    handleFileSelect(event: Event) {
        const input = event.target as HTMLInputElement;
        if (input.files && input.files.length > 0) {
            this.handleFile(input.files[0]);
        }
    },

    handleFile(file: File) {
        this.error = '';

        if (!file.type.startsWith('image/')) {
            this.error = 'Please select an image file';
            return;
        }

        if (file.size > this.maxSize) {
            this.error = `File size must be less than ${Math.round(this.maxSize / 1024 / 1024)}MB`;
            return;
        }

        this.file = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            this.preview = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    },

    remove() {
        this.file = null;
        this.preview = '';
        this.error = '';
    },
}));

// Table selection component
Alpine.data('tableSelect', () => ({
    selected: [] as (string | number)[],
    selectAll: false,

    toggleAll(ids: (string | number)[]) {
        if (this.selectAll) {
            this.selected = [...ids];
        } else {
            this.selected = [];
        }
    },

    toggle(id: string | number) {
        const index = this.selected.indexOf(id);
        if (index === -1) {
            this.selected.push(id);
        } else {
            this.selected.splice(index, 1);
        }
    },

    isSelected(id: string | number) {
        return this.selected.includes(id);
    },

    get count() {
        return this.selected.length;
    },

    clear() {
        this.selected = [];
        this.selectAll = false;
    },
}));

// Rich text editor toolbar (for integration with editors)
Alpine.data('richTextToolbar', () => ({
    bold: false,
    italic: false,
    underline: false,

    toggleBold() {
        document.execCommand('bold');
        this.bold = !this.bold;
    },

    toggleItalic() {
        document.execCommand('italic');
        this.italic = !this.italic;
    },

    toggleUnderline() {
        document.execCommand('underline');
        this.underline = !this.underline;
    },
}));

// Date range picker helper
Alpine.data('dateRange', () => ({
    startDate: '',
    endDate: '',

    setRange(range: 'today' | 'week' | 'month' | 'year') {
        const today = new Date();
        this.endDate = today.toISOString().split('T')[0];

        switch (range) {
            case 'today':
                this.startDate = this.endDate;
                break;
            case 'week':
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                this.startDate = weekAgo.toISOString().split('T')[0];
                break;
            case 'month':
                const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                this.startDate = monthAgo.toISOString().split('T')[0];
                break;
            case 'year':
                const yearAgo = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
                this.startDate = yearAgo.toISOString().split('T')[0];
                break;
        }
    },
}));

// Form validation helper
Alpine.data('formValidation', (rules: Record<string, string[]> = {}) => ({
    errors: {} as Record<string, string>,
    touched: {} as Record<string, boolean>,

    validate(field: string, value: unknown) {
        this.touched[field] = true;
        const fieldRules = rules[field] || [];

        for (const rule of fieldRules) {
            if (rule === 'required' && !value) {
                this.errors[field] = 'This field is required';
                return false;
            }
            if (rule === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value as string)) {
                this.errors[field] = 'Please enter a valid email address';
                return false;
            }
            if (rule.startsWith('min:')) {
                const min = parseInt(rule.split(':')[1]);
                if (typeof value === 'string' && value.length < min) {
                    this.errors[field] = `Must be at least ${min} characters`;
                    return false;
                }
            }
            if (rule.startsWith('max:')) {
                const max = parseInt(rule.split(':')[1]);
                if (typeof value === 'string' && value.length > max) {
                    this.errors[field] = `Must be no more than ${max} characters`;
                    return false;
                }
            }
        }

        delete this.errors[field];
        return true;
    },

    hasError(field: string) {
        return this.touched[field] && this.errors[field];
    },

    isValid() {
        return Object.keys(this.errors).length === 0;
    },

    reset() {
        this.errors = {};
        this.touched = {};
    },
}));

// Initialize Alpine
Alpine.start();

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    // Close modal on Escape
    if (e.key === 'Escape') {
        const sidebar = Alpine.store('sidebar') as { open: boolean; close: () => void };
        if (sidebar.open) {
            sidebar.close();
        }
    }

    // Toggle sidebar with Ctrl+B
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        const sidebar = Alpine.store('sidebar') as { toggle: () => void };
        sidebar.toggle();
    }
});
