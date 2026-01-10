<?php $uniqueId = 'log-container-' . Str::random(8); ?>

@props(['dataUrl', 'filter' => null])

@push('styles')
    <style>
        /* ... (Semua CSS Anda) ... */
        .log-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .log-container .loading {
            padding: 40px;
            text-align: center;
            color: #6c757d;
        }

        .log-container .day-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            font-weight: 500;
        }

        .log-container .transaction-day:first-child .day-header {
            background-color: #fff;
        }

        .log-container .day-header.gray-bg {
            background-color: #f5f5f5;
            border-top: 8px solid #f0f0f0;
        }

        .log-container .day-header .date {
            font-size: 1.1em;
            color: #555;
        }

        .log-container .summary {
            font-size: 1.1em;
            font-weight: 700;
        }

        .log-container .summary.profit {
            color: #28a745;
        }

        .log-container .summary.loss {
            color: #dc3545;
        }

        .log-container .transaction-table-header {
            display: flex;
            padding: 8px 16px;
            background-color: #fff;
            color: #6c757d;
            font-size: 0.9em;
            font-weight: 500;
            border-bottom: 1px solid #f0f0f0;
        }

        .log-container .transaction-row {
            display: flex;
            padding: 16px;
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
        }

        .log-container .cell-header.note,
        .log-container .cell.note {
            flex: 2;
            text-align: left;
        }

        .log-container .cell-header.sales,
        .log-container .cell.sales,
        .log-container .cell-header.expense,
        .log-container .cell.expense {
            flex: 1;
            text-align: right;
            font-weight: 500;
        }

        .log-container .cell.sales {
            color: #17a2b8;
        }

        .log-container .cell.expense {
            color: #dc3545;
        }

        .log-container .cell.note .note-description {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 2px;
        }

        .log-container .cell-header.action,
        .log-container .cell.action {
            flex: 1;
            text-align: center;
            font-weight: 500;
        }

        .log-container .cell.action button {
            padding: 4px 8px;
            font-size: 0.85em;
            /* Sedikit lebih kecil */
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            margin-right: 4px;
            font-weight: 500;
        }

        .log-container .cell.action .btn-edit {
            background-color: #ffc107;
            /* Kuning */
            color: #333;
        }

        .log-container .cell.action .btn-delete {
            background-color: #dc3545;
            /* Merah */
            color: white;
        }

        .log-container .cell.action .btn-edit:hover {
            background-color: #e0a800;
        }

        .log-container .cell.action .btn-delete:hover {
            background-color: #c82333;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background-color: #f9f9f9;
            border-top: 1px solid #f0f0f0;
        }

        .pagination-info {
            font-size: 0.9em;
            color: #6c757d;
        }

        .pagination-links button {
            padding: 8px 12px;
            margin-left: 4px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }

        .pagination-links button:disabled {
            background-color: #f5f5f5;
            color: #ccc;
            cursor: not-allowed;
        }

        .pagination-links button:hover:not(:disabled) {
            background-color: #f0f0f0;
        }
    </style>
@endpush

<div class="mb-4">
    {{ $filter }}
</div>

<div class="log-container" id="{{ $uniqueId }}">
    <div class="loading">Memuat data...</div>
</div>

<div class="pagination-container" id="pagination-{{ $uniqueId }}">
</div>


@push('scripts')
    <script>
        // =======================================================
        //          TAMBAHKAN PEMBUNGKUS INI
        // =======================================================
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const logContainer = $(@json('#' . $uniqueId));
            const paginationContainer = $(@json('#pagination-' . $uniqueId));
            const editUrlTemplate = '{{ route('pembukuan_transaksi.edit', ['id' => 'PLACEHOLDER']) }}';
            const deleteUrlTemplate =
                '{{ route('api.pembukuan_transaksi.destroy', ['id' => 'PLACEHOLDER']) }}';

            const dataUrl = @json($dataUrl);
            let currentFilters = {};
            let currentPage = 1;

            // SEKARANG filterForm dijamin ada
            const filterForm = $('#filter-form');

            fetchData(currentPage, currentFilters);

            if (filterForm.length) {
                filterForm.on('submit', function(e) {
                    e.preventDefault();
                    const formData = $(this).serializeArray();
                    let filters = {};
                    formData.forEach(item => {
                        if (item.value) {
                            const key = item.name.match(/\[(.*?)\]/)[1];
                            filters[key] = item.value;
                        }
                    });
                    currentFilters = {
                        filter: filters
                    };
                    currentPage = 1;
                    fetchData(currentPage, currentFilters);
                    $('#filter-dropdown').addClass('hidden');
                });

                $('#reset-filter-btn').on('click', function() {
                    filterForm[0].reset();
                    $('#filter_start_date, #filter_end_date, #filter_date_range_display').val('');
                    currentFilters = {};
                    currentPage = 1;
                    fetchData(currentPage, currentFilters);
                    $('#filter-dropdown').addClass('hidden');
                });

                // Logika event listener ini SEKARANG aman
                const filterButton = $('#filter-button');
                const filterDropdown = $('#filter-dropdown');
                if (filterButton.length && filterDropdown.length) {
                    filterButton.on('click', function(e) {
                        e.stopPropagation();
                        filterDropdown.toggleClass('hidden');
                    });
                }
            }

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                if (!$(this).closest(logContainer).length) return;

                const transactionId = $(this).data('id');
                if (!confirm('Anda yakin ingin menghapus data ini secara permanen?')) {
                    return;
                }

                // --- GANTI LOGIKA URL YANG LAMA ---
                // const baseUrl = dataUrl.split('?')[0]; 
                // const deleteUrl = `${baseUrl}/${transactionId}`;

                // --- DENGAN LOGIKA URL TEMPLATE YANG BARU (AMAN) ---
                const deleteUrl = deleteUrlTemplate.replace('PLACEHOLDER', transactionId);

                $.ajax({
                    url: deleteUrl, // URL sekarang dijamin benar
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            alert('Transaksi berhasil dihapus.');
                            fetchData(currentPage, currentFilters); // Muat ulang data
                        } else {
                            alert('Gagal menghapus: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi error.';
                        alert('Gagal menghapus data. ' + errorMsg);
                    }
                });
            });

            $(window).on('click', function(e) {
                if ($('#filter-dropdown').length && !$('#filter-dropdown').hasClass('hidden')) {
                    if (!$(e.target).closest('#filter-button').length && !$(e.target).closest(
                            '#filter-dropdown').length) {
                        $('#filter-dropdown').addClass('hidden');
                    }
                }
            });

            function fetchData(page, filters = {}) {
                logContainer.html('<div class="loading">Memuat data...</div>');
                paginationContainer.html('');
                const requestData = {
                    page: page,
                    ...filters
                };

                $.ajax({
                    url: dataUrl,
                    type: 'GET',
                    data: requestData,
                    dataType: 'json',
                    success: function(response) {
                        try {
                            const data = response.data || [];
                            if (data && Array.isArray(data) && data.length > 0) {
                                renderFinancialData(data);
                                renderPagination(response);
                            } else {
                                logContainer.html(
                                    '<div class="loading">Tidak ada data ditemukan.</div>');
                            }
                        } catch (e) {
                            console.error("!!! ERROR SAAT MERENDER DATA:", e);
                            logContainer.html(
                                '<div class="loading" style="color: red;">Terjadi error JavaScript saat render.</div>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        logContainer.html(
                            '<div class="loading" style="color: red;">Gagal memuat data API.</div>');
                    }
                });
            }

            function renderFinancialData(data) {
                let html = '';
                data.forEach((day, index) => {
                    const summaryClass = day.summaryType === 'profit' ? 'profit' : 'loss';
                    const summaryText = day.summaryType === 'profit' ? 'Untung' : 'Rugi';
                    const headerClass = index > 0 ? 'gray-bg' : '';
                    html += `
                    <div class="transaction-day">
                        <div class="day-header ${headerClass}">
                            <span class="date">${formatDisplayDate(day.date)}</span>
                            <span class="summary ${summaryClass}">
                                ${summaryText} ${formatCurrency(day.summaryAmount)}
                            </span>
                        </div>
                        <div class="transaction-table-header">
                            <div class="cell-header note">Catatan</div>
                            <div class="cell-header sales">Pemasukan</div>
                            <div class="cell-header expense">Pengeluaran</div>
                            <div class="cell-header expense">    </div>
                        </div>
                `;
                    if (day.transactions && Array.isArray(day.transactions)) {
                        day.transactions.forEach(tx => {
                            // Buat URL Edit. Asumsi rute web Anda adalah /pembukuan-transaksi/{id}/edit
                            const editUrl = editUrlTemplate.replace('PLACEHOLDER', tx.id);

                            html += `
                            <div class="transaction-row">
                                <div class="cell note">
                                    <span>-</span>
                                    <span class="note-description">${tx.note || 'Tidak ada catatan'}</span>
                                </div>
                                <div class="cell sales">${formatValue(tx.sales)}</div>
                                <div class="cell expense">${formatValue(tx.expense)}</div>
                                <div class="cell action">
                                    <!-- <a href="${editUrl}" class="btn-edit">Edit</a> -->
                                     <!-- <button class="btn-delete" data-id="${tx.id}">Hapus</button> -->
                                </div>
                            </div>
                        `;
                        });
                    }
                    html += `</div>`;
                });
                logContainer.html(html);
            }

            function renderPagination(meta) {
                if (!meta || meta.last_page <= 1) {
                    paginationContainer.html('');
                    return;
                }
                let html = `
                <div class="pagination-info">
                    Menampilkan ${meta.from} - ${meta.to} dari ${meta.total} data
                </div>
                <div class="pagination-links">
                    <button class="pagination-link" data-page="${meta.current_page - 1}" ${meta.current_page === 1 ? 'disabled' : ''}>
                        &laquo; Previous
                    </button>
                    <button class="pagination-link" data-page="${meta.current_page + 1}" ${meta.current_page === meta.last_page ? 'disabled' : ''}>
                        Next &raquo;
                    </button>
                </div>
            `;
                paginationContainer.html(html);
            }

            $(document).on('click', '.pagination-link', function(e) {
                e.preventDefault();
                if (!$(this).closest(paginationContainer).length) return;
                if (!$(this).prop('disabled')) {
                    currentPage = $(this).data('page');
                    fetchData(currentPage, currentFilters);
                }
            });

            function formatDisplayDate(dateString) {
                try {
                    const date = new Date(dateString);
                    const options = {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    };
                    return new Intl.DateTimeFormat('id-ID', options).format(date);
                } catch (e) {
                    return dateString;
                }
            }

            function formatCurrency(amount) {
                if (typeof amount !== 'number') return amount;
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount).replace(/\s/g, '');
            }

            function formatValue(value) {
                if (value === 0 || !value) return '-';
                return formatCurrency(value);
            }

            // =======================================================
            //          TAMBAHKAN PENUTUP INI
            // =======================================================
        }); // Akhir dari $(document).ready()
    </script>
@endpush
