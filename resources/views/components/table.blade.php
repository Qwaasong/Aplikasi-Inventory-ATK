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
                class="w-full pl-10 pr-4 py-3 sm:py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm">
        <div class="hidden sm:table w-full">
            <table class="w-full text-sm text-left text-gray-60">
                <thead class="bg-gray-50 border-b border-t border-gray-200">
                    <tr>
                        {{-- LOOPING KOLOM HEADER --}}
                        @foreach (array_keys($dataTable) as $header)
                            <th scope="col" class="px-6 py-3 font-bold text-gray-900">{{ $header }}</th>
                        @endforeach
                        {{-- Kolom Aksi --}}
                        @if($showAction)
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                        @endif
                    </tr>
                </thead>
                {{-- ISI TABEL AKAN DI-INJECT OLEH AJAX/JQUERY --}}
                <tbody id="table-body">
                    <tr>
                        <td colspan="{{ count($dataTable) + ($showAction ? 1 : 0) }}" class="text-center py-4 text-gray-500">
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="sm:hidden space-y-4" id="card-view-container">
            <div class="text-center py-4 text-gray-500">Memuat data...</div>
        </div>
    </div>

    {{-- Pagination akan di-inject di sini --}}
    <div id="pagination-links" class="mt-4 flex justify-center"></div>

</div>

{{-- SCRIPT JAVASCRIPT/JQUERY UNTUK AJAX --}}
<script>
    $(document).ready(function() {
        // Mendapatkan URL data dari properti component PHP
        const dataUrl = "{{ $dataUrl }}";
        console.log(dataUrl);
        // Base entity URL (remove /api prefix). Didefinisikan di scope yang lebih luas
        const entityBaseUrl = dataUrl.replace('/api', '');
        console.log(entityBaseUrl);
        const tableBody = $('#table-body');
        const cardContainer = $('#card-view-container');

        // Mendapatkan field/key JSON dan header secara dinamis
        const fields = {!! json_encode($dataTable) !!};
        const fieldKeys = Object.values(fields); // Header Tabel
        const fieldHeaders = Object.keys(fields); // Isi Tabel
        const showAction = {!! $showAction ? 'true' : 'false' !!}; // Menentukan apakah kolom aksi ditampilkan
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
                            if(showAction) {
                                actionsHtml = `
                                    <div class="inline-block text-left relative">
                                        <button class="menu-button p-2 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none" data-id="${item.id}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                        </button>
                                        <div id="dropdown-${item.id}" class="menu-dropdown hidden absolute right-0 mt-2 w-32 bg-white border border-gray-20 rounded-lg shadow-xl z-30">
                                            <a href="${entityBaseUrl}/${item.id}/edit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                            <a href="" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-10 delete-btn" data-id="${item.id}" data-url="${entityBaseUrl}">Delete</a>
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
                                `<tr class="bg-white border-b" id="row-${item.id}">`;
                            tableRows += dataCells;
                            // Tambahkan kolom aksi hanya jika showAction adalah true
                            if(showAction) {
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
                            if(showAction) {
                                actionsHtml = `
                                    <div class="relative">
                                        <button class="menu-button p-1 text-gray-500 rounded-full hover:bg-gray-100" data-id="${item.id}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                        </button>
                                        <div id="dropdown-mobile-${item.id}" class="menu-dropdown hidden absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded-lg shadow-xl z-10">
                                            <a href="${entityBaseUrl}/${item.id}/edit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 delete-btn" data-id="${item.id}" data-url="${entityBaseUrl}">Delete</a>
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
                                        <dt class="font-semibold text-gray-800">${header}</dt>
                                        <dd class="text-gray-600 text-right">${value || '-'}</dd>
                                    </div>
                                `;
                            });

                            cardHtml += `
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5" id="card-${item.id}">
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
                    if (paginationMeta && paginationMeta.last_page > 1) {
                        // Calculate pagination range with limits
                        let startPage = Math.max(1, paginationMeta.current_page - 2); // 2 pages before current
                        let endPage = Math.min(paginationMeta.last_page, startPage + 4); // 4 pages after start (total 5)
                        
                        // Adjust start if we're near the end
                        if (endPage - startPage < 4) {
                            startPage = Math.max(1, endPage - 4);
                        }
                        
                        // For mobile, show only 3 pages max
                        let mobileStartPage = Math.max(1, paginationMeta.current_page - 1);
                        let mobileEndPage = Math.min(paginationMeta.last_page, mobileStartPage + 2);
                        
                        if (mobileEndPage - mobileStartPage < 2) {
                            mobileStartPage = Math.max(1, mobileEndPage - 2);
                        }
                        
                        let paginationHtml = `
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px justify-center" aria-label="Pagination">
                                <button data-page="${paginationMeta.current_page - 1}" ${paginationMeta.current_page === 1 ? 'disabled' : ''} class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 rounded-l-md hover:text-gray-700 ${paginationMeta.current_page === 1 ? 'cursor-not-allowed opacity-50' : ''}">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /> </svg> Previous
                                </button>
                                
                                ${window.innerWidth >= 768 ?
                                    // Desktop: Show up to 5 pages
                                    Array.from({length: endPage - startPage + 1}, (_, i) => {
                                        let pageNum = startPage + i;
                                        return `<button data-page="${pageNum}" class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium ${pageNum === paginationMeta.current_page ? 'text-white bg-blue-600' : 'text-gray-500 hover:text-gray-700'} ${pageNum === paginationMeta.current_page ? '' : 'hover:bg-gray-50'}">
                                            ${pageNum}
                                        </button>`;
                                    }).join('')
                                    :
                                    // Mobile: Show up to 3 pages
                                    Array.from({length: mobileEndPage - mobileStartPage + 1}, (_, i) => {
                                        let pageNum = mobileStartPage + i;
                                        return `<button data-page="${pageNum}" class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium ${pageNum === paginationMeta.current_page ? 'text-white bg-blue-600' : 'text-gray-500 hover:text-gray-700'} ${pageNum === paginationMeta.current_page ? '' : 'hover:bg-gray-50'}">
                                            ${pageNum}
                                        </button>`;
                                    }).join('')
                                }
                                
                                <button data-page="${paginationMeta.current_page + 1}" ${paginationMeta.current_page === paginationMeta.last_page ? 'disabled' : ''} class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 rounded-r-md hover:text-gray-700 ${paginationMeta.current_page === paginationMeta.last_page ? 'cursor-not-allowed opacity-50' : ''}">
                                    Next <svg class="w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /> </svg>
                                </button>
                            </nav>
                         `;
                        paginationLinks.html(paginationHtml);
                    }

                    // Re-attach event listeners untuk dropdown menu
                    attachMenuDropdownListeners();
                },
                error: function(xhr, status, error) {
                    console.error('API Error:', { xhr, status, error }); // Debugging line
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
                        console.error('Delete error:', { xhr, status, error });
                        console.error('Response JSON:', xhr.responseJSON);
                        alert('Gagal menghapus item! ' + (xhr.responseJSON?.message || error || ''));
                    }
                });
            }
        });

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

        // Event listener untuk menutup dropdown saat halaman di-scroll
        window.addEventListener('scroll', handleScrollWithThrottle);

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
