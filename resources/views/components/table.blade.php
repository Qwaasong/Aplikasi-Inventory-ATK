@props(['dataTable' => [], 'showAction' => true])
<div class="max-w-7xl mx-auto">
    <div class="flex sm:flex-row items-center sm:justify-between gap-4 mb-6">
        <div class="sm:w-auto relative">
            {{-- Slot untuk Tombol Filter Custom (Opsional) --}}
            {{ $filter ?? '' }}
        </div>
        <div class="relative sm:w-64 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="table-search-input" placeholder="Search"
                class="w-full pl-10 pr-4 py-3 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 transition-colors">
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <div class="hidden sm:table w-full">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            {{-- LOOPING KOLOM HEADER --}}
                            @foreach (array_keys($dataTable) as $header)
                            <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider text-xs">{{ $header }}</th>
                            @endforeach
                            {{-- Kolom Aksi --}}
                            @if($showAction)
                            <th scope="col" class="px-6 py-4">
                                <span class="sr-only">Actions</span>
                            </th>
                            @endif
                        </tr>
                    </thead>
                    {{-- ISI TABEL AKAN DI-INJECT OLEH AJAX/JQUERY --}}
                    <tbody id="table-body" class="divide-y divide-gray-100">
                        <tr>
                            <td colspan="{{ count($dataTable) + ($showAction ? 1 : 0) }}" class="text-center py-10 text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Memuat data...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="sm:hidden p-4 space-y-4" id="card-view-container">
                <div class="text-center py-10 text-gray-400 font-medium">Memuat data...</div>
            </div>
        </div>

        {{-- Pagination terintegrasi (Modern & Minimalist) --}}
        <div id="pagination-links" class="border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 transition-colors"></div>
    </div>

</div>

<script>
    $(document).ready(function() {
        // Mendapatkan URL data dari properti component PHP
        const dataUrl = "{{ $dataUrl }}";
        const primaryKey = "{{ $primaryKey }}";
        console.log(dataUrl);
        // Base entity URL (remove /api prefix). Didefinisikan di scope yang lebih luas
        const entityBaseUrl = dataUrl.replace('/api', '');
        console.log(entityBaseUrl);
        const tableBody = $('#table-body');
        const cardContainer = $('#card-view-container');

        // Mendapatkan field/key JSON dan header secara dinamis
        const fields = @json($dataTable);
        const fieldKeys = Object.values(fields); // Header Tabel
        const fieldHeaders = Object.keys(fields); // Isi Tabel
        const showAction = @json($showAction); // Menentukan apakah kolom aksi ditampilkan
        const totalColumns = fieldKeys.length + (showAction ? 1 : 0); // Jumlah kolom data + kolom Aksi jika ditampilkan

        const paginationLinks = $('#pagination-links');
        const searchInput = $('#table-search-input');

        let currentPage = 1;
        let currentSearch = '';
        let currentFilters = {}; // Menyimpan state filter saat ini
        let paginationMeta = {}; // Initialize pagination meta to avoid reference errors

        // Fungsi untuk mengakses nilai bersarang
        function getNestedValue(obj, path) {
            return path.split('.').reduce((current, key) => {
                return current && current[key] !== undefined ? current[key] : null;
            }, obj);
        }

        // Fungsi untuk memformat tanggal
        function formatDate(dateString) {
            if (!dateString) return '-';
            // Jika format sudah dalam format Y-m-d H:i:s, tampilkan langsung
            if (dateString.length === 19 && dateString.includes(' ')) {
                return dateString;
            }
            // Jika format adalah ISO 8601, ubah ke format yang diinginkan
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString; // Jika bukan tanggal valid, kembalikan aslinya

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        // Helper: buat tombol pagination modern minimalis
        function renderPageButton(pageNum, currentPage) {
            const isActive = pageNum === currentPage;
            return `
                <button
                    data-page="${pageNum}"
                    class="pagination-link flex items-center justify-center min-w-[32px] h-8 mx-0.5 text-sm font-medium rounded-lg transition-all 
                        ${isActive 
                            ? 'bg-blue-600 text-white shadow-sm' 
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'}"
                >
                    ${pageNum}
                </button>
            `;
        }

        // Helper: tombol navigasi (Prev/Next)
        function renderNavButton({
            label,
            page,
            disabled,
            icon,
            iconPosition = 'left'
        }) {
            return `
        <button
            data-page="${page}"
            ${disabled ? 'disabled' : ''}
            class="pagination-link flex items-center px-3 h-8 text-sm font-medium rounded-lg transition-all
                ${disabled 
                    ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed' 
                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600'}"
        >
            ${icon && iconPosition === 'left' ? icon : ''}
            <span class="${icon ? (iconPosition === 'left' ? 'ml-1.5' : 'mr-1.5') : ''}">
                ${label}
            </span>
            ${icon && iconPosition === 'right' ? icon : ''}
        </button>
    `;
        }


        // Helper: renderer titik-titik (ellipsis)
        function renderEllipsis() {
            return `
                <span class="flex items-center justify-center w-10 h-10 mx-0.5 text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </span>
            `;
        }

        // Fungsi utama render pagination dengan tampilan integrated
        function renderPagination(paginationMeta) {
            if (!paginationMeta || paginationMeta.last_page <= 1) {
                return '';
            }

            const current = paginationMeta.current_page;
            const last = paginationMeta.last_page;
            const isMobile = window.innerWidth < 640;
            const delta = isMobile ? 1 : 2;

            let html = `
                <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 w-full gap-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 order-2 sm:order-1">
                        Menampilkan <span class="text-gray-900 dark:text-gray-200">${paginationMeta.from || 0}</span> - 
                        <span class="text-gray-900 dark:text-gray-200">${paginationMeta.to || 0}</span> dari 
                        <span class="text-gray-900 dark:text-gray-200">${paginationMeta.total || 0}</span> data
                    </div>
                    <nav class="flex items-center gap-1 order-1 sm:order-2" aria-label="Pagination">
            `;

            // Button: Previous
            html += renderNavButton({
                label: 'Prev',
                page: current - 1,
                disabled: current === 1,
                iconPosition: 'left',
                icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>'
            });

            // Logic Page Numbers...
            const pages = [];
            const leftLimit = current - delta;
            const rightLimit = current + delta;
            for (let i = 1; i <= last; i++) {
                if (i === 1 || i === last || (i >= leftLimit && i <= rightLimit)) {
                    pages.push(i);
                } else if (i === leftLimit - 1 || i === rightLimit + 1) {
                    pages.push('...');
                }
            }

            let lastPageAdded = null;
            pages.forEach(page => {
                if (page === '...') {
                    if (lastPageAdded !== '...') {
                        html += renderEllipsis();
                        lastPageAdded = '...';
                    }
                } else {
                    html += renderPageButton(page, current);
                    lastPageAdded = page;
                }
            });

            // Button: Next
            html += renderNavButton({
                label: 'Next',
                page: current + 1,
                disabled: current === last,
                iconPosition: 'right',
                icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>'
            });

            html += `
                    </nav>
                </div>
            `;

            return html;
        }

        function fetchData(page = 1, search = '', filters = {}) {
            tableBody.html(
                `<tr><td colspan="${totalColumns}" class="text-center py-4 text-gray-500">Memuat data...</td></tr>`
            );
            cardContainer.html(`<div class="text-center py-4 text-gray-500">Memuat data...</div>`);
            paginationLinks.empty();

            // Gabungkan semua parameter untuk request AJAX
            const requestData = {
                page: page,
                search: search,
                ...filters // Sertakan parameter filter
            };

            $.ajax({
                url: dataUrl,
                type: 'GET',
                data: requestData,
                dataType: 'json',
                success: function(response) {
                    console.log('API Response:', response); // Debugging line

                    // Safely extract data with multiple fallbacks
                    let data = [];
                    let paginationMeta = {};
                    try {
                        if (response && typeof response === 'object') {
                            // Handle new FinancialTransaction format: response.data directly
                            if (response.data && Array.isArray(response.data)) {
                                data = response.data;
                                paginationMeta = {
                                    current_page: response.current_page,
                                    last_page: response.last_page,
                                    per_page: response.per_page,
                                    from: response.from,
                                    to: response.to,
                                    total: response.total
                                };
                            }
                            // Handle Laravel pagination structure - for backward compatibility
                            else if (response.data && response.data.data && Array.isArray(response.data.data)) {
                                data = response.data.data;
                                paginationMeta = {
                                    current_page: response.data.current_page,
                                    last_page: response.data.last_page,
                                    per_page: response.data.per_page,
                                    from: response.data.from,
                                    to: response.data.to,
                                    total: response.data.total
                                };
                            }
                            // Handle direct data structure
                            else if (response.data && Array.isArray(response.data)) {
                                data = response.data;
                            }
                            // Handle case where response is already the data array
                            else if (Array.isArray(response)) {
                                data = response;
                            }
                        }
                    } catch (e) {
                        console.error('Error extracting data:', e);
                        data = [];
                    }

                    console.log('Extracted Data:', data); // Debugging line
                    console.log('Data length:', data.length); // Debugging line
                    console.log('Pagination Meta:', paginationMeta); // Debugging line

                    // 1. Render Tabel Desktop
                    let tableRows = '';
                    console.log('Rendering table with data length:', data.length);
                    console.log('Data type:', typeof data);
                    console.log('Is Array:', Array.isArray(data));
                    if (data && Array.isArray(data) && data.length > 0) {
                        console.log('Processing data items:', data);
                        $.each(data, function(i, item) {
                            // Membuat tombol aksi yang reusable (gunakan slot untuk isi)
                            let actionsHtml = '';
                            if (showAction) {
                                actionsHtml = `
                                    <div class="inline-block text-left relative">
                                        <button class="menu-button p-2 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none" data-id="${item[primaryKey]}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                        </button>
                                        <div id="dropdown-${item[primaryKey]}" class="menu-dropdown hidden absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-30">
                                            <button type="button" class="edit-btn block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" data-id="${item[primaryKey]}">Edit</button>
                                            <a href="" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 delete-btn" data-id="${item[primaryKey]}" data-url="${entityBaseUrl}">Delete</a>
                                        </div>
                                    </div>
                                `;
                            }

                            // MEMBUAT BARIS SECARA DINAMIS
                            let dataCells = '';
                            // Di dalam fungsi fetchData, ubah bagian ini:
                            $.each(fieldKeys, function(j, key) {
                                let value = getNestedValue(item, key);
                                // Format tanggal jika field adalah created_at atau updated_at
                                if (key === 'created_at' || key === 'updated_at' || key === 'email_verified_at' || key.endsWith('.created_at') || key.endsWith('.updated_at') || key.endsWith('.email_verified_at')) {
                                    value = formatDate(value);
                                }
                                dataCells += `<td class="px-6 py-4">${value || '-'}</td>`;
                            });

                            tableRows +=
                                `<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" id="row-${item[primaryKey]}">`;
                            tableRows += dataCells;
                            // Tambahkan kolom aksi hanya jika showAction adalah true
                            if (showAction) {
                                tableRows +=
                                    `<td class="px-6 py-4 text-right relative">${actionsHtml}</td>`;
                            }
                            tableRows += `</tr>`;

                        });
                    } else {
                        console.log('No data condition triggered');
                        tableRows =
                            `<tr><td colspan="${totalColumns}" class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</td></tr>`;
                    }
                    tableBody.html(tableRows);


                    // 2. Render Card Mobile
                    let cardHtml = '';
                    console.log('Rendering cards with data length:', data.length);
                    if (data && Array.isArray(data) && data.length > 0) {
                        $.each(data, function(i, item) {
                            // Sama seperti di desktop, gunakan item.id untuk dropdown
                            let actionsHtml = '';
                            if (showAction) {
                                actionsHtml = `
                                    <div class="relative">
                                        <button class="menu-button p-1 text-gray-500 rounded-full hover:bg-gray-100" data-id="${item[primaryKey]}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                        </button>
                                        <div id="dropdown-mobile-${item[primaryKey]}" class="menu-dropdown hidden absolute right-0 mt-1 w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-10">
                                            <button type="button" class="edit-btn block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" data-id="${item[primaryKey]}">Edit</button>
                                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 delete-btn" data-id="${item[primaryKey]}" data-url="${entityBaseUrl}">Delete</a>
                                        </div>
                                    </div>
                                `;
                            }

                            // MEMBUAT BARIS DETAIL CARD SECARA DINAMIS
                            let cardDetails = '';
                            // Dan di bagian card view:
                            $.each(fields, function(header, key) {
                                let value = getNestedValue(item, key);
                                // Format tanggal jika field adalah created_at atau updated_at
                                if (key === 'created_at' || key === 'updated_at' || key === 'email_verified_at' || key.endsWith('.created_at') || key.endsWith('.updated_at') || key.endsWith('.email_verified_at')) {
                                    value = formatDate(value);
                                }
                                cardDetails += `
                                    <div class="flex justify-between items-center">
                                        <dt class="font-semibold text-gray-800 dark:text-gray-200">${header}</dt>
                                        <dd class="text-gray-600 dark:text-gray-400 text-right">${value || '-'}</dd>
                                    </div>
                                `;
                            });

                            cardHtml += `
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5 transition-colors" id="card-${item[primaryKey]}">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-8"></div>
                                        ${showAction ? actionsHtml : ''}
                                    </div>
                                    <dl class="space-y-3 text-sm">
                                        ${cardDetails}
                                    </dl>
                                </div>
                            `;
                        });
                    } else {
                        cardHtml =
                            `<div class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</div>`;
                    }
                    cardContainer.html(cardHtml);

                    // 3. Render Pagination (Jika menggunakan Laravel Pagination)
                    paginationLinks.html(renderPagination(paginationMeta));

                    // Re-attach event listeners untuk dropdown menu
                    attachMenuDropdownListeners();
                },
                error: function(xhr, status, error) {
                    console.error('API Error:', {
                        xhr,
                        status,
                        error
                    }); // Debugging line
                    // Gunakan totalColumns yang sudah didefinisikan di scope atas
                    tableBody.html(
                        `<tr><td colspan="${totalColumns}" class="text-center py-4 text-red-500">Gagal memuat data: ${xhr.statusText}</td></tr>`
                    );
                    cardContainer.html(
                        `<div class="text-center py-4 text-red-500">Gagal memuat data.</div>`);
                }
            });
        }

        // 4. Implementasi Search dengan Debounce
        let searchTimeout = null;
        searchInput.on('keyup', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val();
            searchTimeout = setTimeout(function() {
                currentSearch = query;
                currentPage = 1;
                fetchData(currentPage, currentSearch, currentFilters);
            }, 500); // Tunggu 300ms setelah user berhenti mengetik
        });

        // 5. Implementasi Pindah Halaman
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (!$(this).prop('disabled')) {
                currentPage = page;
                fetchData(currentPage, currentSearch, currentFilters);
            }
        });

        // 6. Implementasi Tombol Aksi (Delete Example)
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const itemId = $(this).data('id');
            console.log('Delete clicked for item ID:', itemId);

            // Ambil URL dari atribut tombol jika ada, kalau tidak gunakan entityBaseUrl sebagai fallback
            const entityUrl = $(this).data('url') || entityBaseUrl; // Mengambil URL entitas dari tombol
            const apiUrl = entityUrl.replace(
                /^(https?:\/\/[^/]+)(\/.*)?$/,
                (_, host, path = '') => `${host}/api${path}`
            );

            console.log('Entity URL:', entityUrl);
            console.log('API URL:', apiUrl);

            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                $.ajax({
                    url: `${apiUrl}/${itemId}`, // Menggunakan URL dinamis
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Delete success:', response);
                        $(`#row-${itemId}`).remove();
                        $(`#card-${itemId}`).remove();
                        alert('Item berhasil dihapus!');
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', {
                            xhr,
                            status,
                            error
                        });
                        console.error('Response JSON:', xhr.responseJSON);
                        alert('Gagal menghapus item! ' + (xhr.responseJSON?.message || error || ''));
                    }
                });
            }
        });

        // Tambahkan Implementasi Tombol Edit untuk Modal
        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            const itemId = $(this).data('id');
            showEditModal(itemId);
        });

        // Fungsi untuk menampilkan modal edit - memancarkan custom event ke parent
        function showEditModal(id) {
            // Fetch data item via AJAX, lalu emit custom event 'edit-data'
            $.ajax({
                url: `${dataUrl}/${id}`,
                type: 'GET',
                success: function(data) {
                    // Emit custom event dengan data item untuk ditangkap oleh halaman parent
                    window.dispatchEvent(new CustomEvent('edit-data', {
                        detail: data
                    }));
                },
                error: function() {
                    alert('Gagal memuat data untuk edit.');
                }
            });
        }

        // 7. Logika untuk Dropdown Menu dan Filter
        const filterButton = document.getElementById('filter-button');
        const filterDropdown = document.getElementById('filter-dropdown');
        let currentlyOpenMenu = null;
        let originalParent = null; // Untuk menyimpan parent asli dari dropdown yang di-teleport

        // Fungsi untuk menutup semua dropdown yang terbuka

        function closeAllDropdowns() {
            // console.log('Closing all dropdowns');
            if (currentlyOpenMenu && originalParent) {
                currentlyOpenMenu.classList.add('hidden');
                currentlyOpenMenu.style = '';
                originalParent.appendChild(currentlyOpenMenu);
            }

            // Selalu pastikan filter dropdown ditutup
            if (filterDropdown) {
                filterDropdown.classList.add('hidden');
            }
            // Reset state
            currentlyOpenMenu = null;
            originalParent = null;
        }

        // Fungsi untuk menangani klik pada tombol filter

        function handleFilterButtonClick(event) {
            event.stopPropagation();
            // console.log('Filter button clicked');
            if (!filterButton || !filterDropdown) return;
            const isHidden = filterDropdown.classList.contains('hidden');
            closeAllDropdowns();
            if (isHidden) {
                filterDropdown.classList.remove('hidden');
                currentlyOpenMenu = filterDropdown;
            } else {
                filterDropdown.classList.add('hidden');
                // currentlyOpenMenu = filterDropdown;
                closeAllDropdowns();
            }
        }

        // Fungsi untuk menangani klik pada tombol menu
        function handleMenuButtonClick(event) {
            event.stopPropagation();
            const button = event.currentTarget;
            const dropdown = button.nextElementSibling;

            if (!dropdown) return;

            // Jika menu yang sama diklik lagi, tutup saja
            if (currentlyOpenMenu === dropdown) {
                closeAllDropdowns();
                return;
            }

            // Tutup semua menu lain sebelum membuka yang baru
            closeAllDropdowns();

            // --- LOGIKA TELEPORTASI ---
            // Simpan parent asli dan pindahkan dropdown ke body
            originalParent = dropdown.parentNode;
            document.body.appendChild(dropdown);

            // Hitung posisi tombol dan posisikan dropdown relatif terhadapnya
            const rect = button.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.top = `${rect.bottom + 4}px`; // 4px di bawah tombol
            dropdown.style.right = `${window.innerWidth - rect.right}px`;
            dropdown.style.left = 'auto'; // Biarkan browser menentukan posisi kiri
            dropdown.style.marginTop = '0';

            // Tampilkan dropdown dan tandai sebagai menu yang sedang terbuka
            dropdown.classList.remove('hidden');
            currentlyOpenMenu = dropdown;
        }


        // Fungsi untuk memasang event listener ke semua tombol menu
        function attachMenuDropdownListeners() {
            const menuButtons = document.querySelectorAll('.menu-button');
            menuButtons.forEach(button => {
                // Hapus listener lama untuk menghindari duplikasi
                button.removeEventListener('click', handleMenuButtonClick);
                // Tambahkan listener baru
                button.addEventListener('click', handleMenuButtonClick);
            });

            // Tambahkan listener untuk tombol filter
            if (filterButton) {
                filterButton.addEventListener('click', handleFilterButtonClick);
            }
        }

        function throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            }
        }

        // Buat fungsi handler yang sudah di-throttle
        const handleScrollWithThrottle = throttle(() => {
            // HANYA jalankan logika penutupan jika ada menu atau filter yang terbuka
            if (currentlyOpenMenu || (filterDropdown && !filterDropdown.classList.contains('hidden'))) {
                // console.log('Scroll detected (throttled), closing open dropdowns...');
                closeAllDropdowns();
            }
        }, 150); // Jalankan maksimal sekali setiap 150ms

        // Event listener untuk menutup dropdown saat halaman di-scroll atau di-resize
        // Menggunakan capture: true agar mendeteksi scroll pada elemen internal (seperti container tabel)
        window.addEventListener('scroll', handleScrollWithThrottle, true);
        window.addEventListener('resize', handleScrollWithThrottle);

        // Event listener untuk menutup dropdown jika diklik di luar area
        window.addEventListener('click', (event) => {
            const isClickInsideMenu = currentlyOpenMenu && currentlyOpenMenu.contains(event.target);
            const isClickInsideFilterDropdown = filterDropdown && filterDropdown.contains(event.target);

            if (!event.target.closest('.menu-button') &&
                !event.target.closest('#filter-button') &&
                !isClickInsideMenu &&
                !isClickInsideFilterDropdown) {
                closeAllDropdowns();
            }
        });

        // 8. Implementasi Form Filter
        const filterForm = $('#filter-form');

        filterForm.on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serializeArray();
            let filters = {};

            // Mengubah format dari .serializeArray() ke objek yang sesuai
            formData.forEach(item => {
                // Pastikan hanya input dengan nilai yang diikutkan
                if (item.value) {
                    const key = item.name.match(/\[(.*?)\]/)[1]; // Ekstrak 'nama_vendor'
                    filters[key] = item.value;
                }
            });

            currentFilters = {
                filter: filters
            }; // Simpan state filter
            currentPage = 1;
            fetchData(currentPage, currentSearch, currentFilters);
            closeAllDropdowns(); // Tutup dropdown setelah apply
        });

        // Tombol Reset Filter
        $('#reset-filter-btn').on('click', function() {
            filterForm[0].reset(); // Reset form
            currentFilters = {}; // Hapus state filter
            currentPage = 1;
            fetchData(currentPage, currentSearch, currentFilters);
            closeAllDropdowns(); // Tutup dropdown
        });


        // Muat data awal saat halaman pertama dimuat
        fetchData(currentPage, currentSearch, currentFilters);
    });
</script>