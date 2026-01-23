<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Card Dashboard | AJ Group</title>
    <meta name="description" content="Manage digital business cards for AJ Group employees">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>📇 Business Card Manager</h1>
            <div class="header-stats">
                <div class="stat">
                    <div class="stat-value" id="totalCards">0</div>
                    <div class="stat-label">Total Cards</div>
                </div>
                <div class="stat">
                    <div class="stat-value" id="activeCards">0</div>
                    <div class="stat-label">Active</div>
                </div>
            </div>
            <button class="btn btn-primary" id="addCardBtn">
                ➕ Add New Card
            </button>
        </header>

        <!-- Search Filter -->
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="🔍 Search by name, title, email, or phone...">
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid" id="cardsContainer">
            <div class="loading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Card Modal -->
    <div class="modal-overlay" id="cardModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Business Card</h2>
                <button class="modal-close">&times;</button>
            </div>
            <form id="cardForm">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" id="firstName" name="first_name" required placeholder="John">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" id="lastName" name="last_name" required placeholder="Doe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Job Title *</label>
                        <input type="text" id="title" name="title" required placeholder="e.g. IT Manager">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone *</label>
                            <input type="tel" id="phone" name="phone" required placeholder="+971 5X XXX XXXX">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required placeholder="name@ajgroupuae.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone2">Phone 2 (optional)</label>
                            <input type="tel" id="phone2" name="phone2" placeholder="+971 5X XXX XXXX">
                        </div>
                        <div class="form-group">
                            <label for="email2">Email 2 (optional)</label>
                            <input type="email" id="email2" name="email2" placeholder="alt@ajgroupuae.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" 
                               value="Group of Factories Abduljalil Mahdi Mohd Alasmawi LLC">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" 
                               placeholder="Dubai Industrial City, Dubai, UAE">
                    </div>
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" 
                               value="www.ajgroupuae.com">
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                            <input type="checkbox" id="isActive" name="is_active" checked 
                                   style="width: 18px; height: 18px;">
                            <span>Active (visible and downloadable)</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-success">💾 Save Card</button>
                </div>
            </form>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal-overlay" id="qrModal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <h2>QR Code</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="qr-container">
                    <div class="qr-code">
                        <img id="qrImage" src="" alt="QR Code">
                    </div>
                    <p class="qr-info">
                        <strong id="qrName"></strong><br>
                        Scan to save contact
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="assets/js/app.js"></script>
</body>
</html>
