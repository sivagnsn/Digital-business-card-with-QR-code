/**
 * Digital Business Card Dashboard - JavaScript
 * Handles all CRUD operations and UI interactions
 */

const API_URL = '../api/cards.php';
let cards = [];
let editingId = null;

// DOM Elements
const cardsContainer = document.getElementById('cardsContainer');
const cardModal = document.getElementById('cardModal');
const qrModal = document.getElementById('qrModal');
const cardForm = document.getElementById('cardForm');
const modalTitle = document.getElementById('modalTitle');
const totalCardsEl = document.getElementById('totalCards');
const activeCardsEl = document.getElementById('activeCards');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadCards();
    setupEventListeners();
});

function setupEventListeners() {
    // Add new card button
    document.getElementById('addCardBtn').addEventListener('click', () => openCardModal());

    // Search filter
    document.getElementById('searchInput').addEventListener('input', (e) => {
        filterCards(e.target.value);
    });

    // Modal close buttons
    document.querySelectorAll('.modal-close, .cancel-btn').forEach(btn => {
        btn.addEventListener('click', closeModals);
    });

    // Form submit
    cardForm.addEventListener('submit', handleFormSubmit);

    // Close modal on backdrop click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModals();
        });
    });

    // Escape key closes modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModals();
    });
}

// Load all cards
async function loadCards() {
    try {
        showLoading();
        const response = await fetch(API_URL);
        if (!response.ok) throw new Error('Failed to load cards');

        cards = await response.json();
        renderCards();
        updateStats();
    } catch (error) {
        showToast('Failed to load cards: ' + error.message, 'error');
        cardsContainer.innerHTML = `
            <div class="empty-state">
                <i>⚠️</i>
                <h3>Connection Error</h3>
                <p>Could not connect to the database. Please check your MySQL configuration.</p>
            </div>
        `;
    }
}

function showLoading() {
    cardsContainer.innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
        </div>
    `;
}

function renderCards() {
    if (cards.length === 0) {
        cardsContainer.innerHTML = `
            <div class="empty-state">
                <i>📇</i>
                <h3>No Business Cards Yet</h3>
                <p>Create your first digital business card to get started.</p>
                <button class="btn btn-primary" onclick="openCardModal()">
                    ➕ Add First Card
                </button>
            </div>
        `;
        return;
    }

    cardsContainer.innerHTML = cards.map(card => createCardHTML(card)).join('');
}

function createCardHTML(card) {
    const initials = (card.first_name[0] + card.last_name[0]).toUpperCase();
    const statusClass = card.is_active == 1 ? 'status-active' : 'status-inactive';
    const statusText = card.is_active == 1 ? 'Active' : 'Inactive';

    return `
        <div class="business-card" data-id="${card.id}">
            <span class="status-badge ${statusClass}">${statusText}</span>
            <div class="card-header">
                <div class="card-avatar">${initials}</div>
                <div class="card-info">
                    <h3>${card.first_name} ${card.last_name}</h3>
                    <p class="title">${card.title}</p>
                </div>
            </div>
            <div class="card-details">
                <div class="card-detail">
                    <i>📞</i>
                    <span>${card.phone}</span>
                </div>
                <div class="card-detail">
                    <i>✉️</i>
                    <span>${card.email}</span>
                </div>
                <div class="card-detail">
                    <i>🏢</i>
                    <span>${card.company}</span>
                </div>
                <div class="card-detail">
                    <i>📍</i>
                    <span>${card.address || 'Not specified'}</span>
                </div>
            </div>
            <div class="card-actions">
                <button class="btn btn-ghost btn-sm" onclick="showQRCode(${card.id}, '${card.first_name} ${card.last_name}')">
                    📱 QR
                </button>
                <a href="../api/vcard.php?id=${card.id}" class="btn btn-ghost btn-sm" download>
                    📥 vCard
                </a>
                <button class="btn btn-ghost btn-sm" onclick="editCard(${card.id})">
                    ✏️ Edit
                </button>
                <button class="btn btn-ghost btn-sm" onclick="deleteCard(${card.id})" style="color: #ef4444;">
                    🗑️
                </button>
            </div>
        </div>
    `;
}

function updateStats() {
    totalCardsEl.textContent = cards.length;
    activeCardsEl.textContent = cards.filter(c => c.is_active == 1).length;
}

// Filter cards based on search query
function filterCards(query) {
    const searchTerm = query.toLowerCase().trim();

    if (!searchTerm) {
        renderCards();
        return;
    }

    const filtered = cards.filter(card => {
        return (
            card.first_name.toLowerCase().includes(searchTerm) ||
            card.last_name.toLowerCase().includes(searchTerm) ||
            card.title.toLowerCase().includes(searchTerm) ||
            card.email.toLowerCase().includes(searchTerm) ||
            card.phone.includes(searchTerm) ||
            (card.phone2 && card.phone2.includes(searchTerm)) ||
            (card.email2 && card.email2.toLowerCase().includes(searchTerm)) ||
            card.company.toLowerCase().includes(searchTerm)
        );
    });

    if (filtered.length === 0) {
        cardsContainer.innerHTML = `
            <div class="empty-state">
                <i>🔍</i>
                <h3>No Results Found</h3>
                <p>No cards match your search: "${query}"</p>
            </div>
        `;
    } else {
        cardsContainer.innerHTML = filtered.map(card => createCardHTML(card)).join('');
    }
}

// Modal functions
function openCardModal(card = null) {
    editingId = card ? card.id : null;
    modalTitle.textContent = card ? 'Edit Business Card' : 'Add New Business Card';

    if (card) {
        document.getElementById('firstName').value = card.first_name;
        document.getElementById('lastName').value = card.last_name;
        document.getElementById('phone').value = card.phone;
        document.getElementById('phone2').value = card.phone2 || '';
        document.getElementById('email').value = card.email;
        document.getElementById('email2').value = card.email2 || '';
        document.getElementById('company').value = card.company;
        document.getElementById('title').value = card.title;
        document.getElementById('address').value = card.address || '';
        document.getElementById('website').value = card.website || '';
        document.getElementById('isActive').checked = card.is_active == 1;
    } else {
        cardForm.reset();
        document.getElementById('company').value = 'Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC';
        document.getElementById('website').value = 'www.ajgroupuae.com';
        document.getElementById('address').value = 'Dubai Industrial City, Dubai, UAE';
        document.getElementById('isActive').checked = true;
    }

    cardModal.classList.add('active');
}

function closeModals() {
    cardModal.classList.remove('active');
    qrModal.classList.remove('active');
    editingId = null;
}

function editCard(id) {
    const card = cards.find(c => c.id == id);
    if (card) openCardModal(card);
}

// Form submission
async function handleFormSubmit(e) {
    e.preventDefault();

    const formData = {
        first_name: document.getElementById('firstName').value.trim(),
        last_name: document.getElementById('lastName').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        phone2: document.getElementById('phone2').value.trim(),
        email: document.getElementById('email').value.trim(),
        email2: document.getElementById('email2').value.trim(),
        company: document.getElementById('company').value.trim(),
        title: document.getElementById('title').value.trim(),
        address: document.getElementById('address').value.trim(),
        website: document.getElementById('website').value.trim(),
        is_active: document.getElementById('isActive').checked
    };

    try {
        let response;
        if (editingId) {
            response = await fetch(`${API_URL}?id=${editingId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        } else {
            response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        }

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Unknown error');
        }

        showToast(editingId ? 'Card updated successfully!' : 'Card created successfully!', 'success');
        closeModals();
        loadCards();
    } catch (error) {
        showToast('Error: ' + error.message, 'error');
    }
}

// Delete card
async function deleteCard(id) {
    if (!confirm('Are you sure you want to delete this business card?')) return;

    try {
        const response = await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
        if (!response.ok) throw new Error('Failed to delete');

        showToast('Card deleted successfully!', 'success');
        loadCards();
    } catch (error) {
        showToast('Error deleting card: ' + error.message, 'error');
    }
}

// QR Code
function showQRCode(id, name) {
    // Add timestamp to prevent caching
    const timestamp = new Date().getTime();
    document.getElementById('qrImage').src = `../api/qrcode.php?id=${id}&size=250&t=${timestamp}`;
    document.getElementById('qrName').textContent = name;
    qrModal.classList.add('active');
}

// Toast notifications
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <span>${type === 'success' ? '✅' : '❌'}</span>
        <span>${message}</span>
    `;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'toastSlide 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
