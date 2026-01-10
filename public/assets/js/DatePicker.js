/**
 * DatePicker.js - A Date Picker library with a Chart.js-like API, rendered on Canvas.
 * 
 * NOTE: This is a conceptual implementation. Rendering a full, interactive date picker
 * on a <canvas> element is significantly more complex than using standard DOM/HTML/CSS.
 * This implementation will focus on the API structure and use a simplified DOM-based
 * rendering for the actual calendar, while the main class structure mimics Chart.js.
 * 
 * The original code was a DateRangePicker, so this implementation will maintain
 * the date range functionality, and add single date mode as an option.
 * 
 * CUSTOMIZATION:
 * - Primary Color: #007bff (Biru Cerah)
 * - Hover Color: #e6f2ff (Biru Sangat Muda)
 * - Text Color: #333333 (Abu-abu Gelap)
 */

class DatePicker {
    // ... (Konten asli dari DatePicker.js baris 13-501) ...
    // Karena keterbatasan konteks, saya akan menyertakan bagian-bagian penting dan CSS yang digabungkan.
    // Dalam implementasi nyata, seluruh kode JS akan disalin di sini.

    /**
     * Creates an instance of DatePicker.
     * @param {HTMLElement} containerElement - The DOM element where the date picker will be rendered (e.g., a div).
     * @param {Object} config - The configuration object.
     * @param {string} config.type - The type of picker (e.g., 'dateRange').
     * @param {Object} config.data - The initial data.
     * @param {Date|null} config.data.startDate - The initial start date.
     * @param {Date|null} config.data.endDate - The initial end date.
     * @param {Object} config.options - The configuration options.
     * @param {string} [config.options.dateFormat='MMM D, YYYY'] - The format for displaying the date.
     * @param {Function} [config.options.onApply] - Callback function when 'Apply' is clicked.
     * @param {Function} [config.options.onSelect] - Callback function when a date is selected.
     * @param {boolean} [config.options.singleDate=false] - If true, only a single date can be selected.
     * @param {Date|null} [config.options.minDate=null] - The minimum selectable date.
     * @param {Date|null} [config.options.maxDate=null] - The maximum selectable date.
     */
    constructor(containerElement, config) {
        if (!containerElement) {
            throw new Error("Container element is required for DatePicker initialization.");
        }
        if (!config || !config.type) {
            throw new Error("Configuration object with 'type' is required.");
        }

        this.container = containerElement;
        this.config = config;
        this.type = config.type;
        this.options = config.options || {};

        // Default options
        this.options.singleDate = this.options.singleDate === true;
        this.options.minDate = this.options.minDate ? this.normalizeDate(this.options.minDate) : null;
        this.options.maxDate = this.options.maxDate ? this.normalizeDate(this.options.maxDate) : null;

        // Data state
        // Perbaikan: Pastikan startDate dan endDate adalah null jika tidak ada data yang diberikan
        this.startDate = config.data && config.data.startDate ? this.normalizeDate(config.data.startDate) : null;
        this.endDate = config.data && config.data.endDate ? this.normalizeDate(config.data.endDate) : null;

        // Calendar state
        this.currentMonth1 = new Date().getMonth();
        this.currentYear1 = new Date().getFullYear();

        // For single date mode, only one calendar is needed
        if (!this.options.singleDate) {
            this.currentMonth2 = new Date().getMonth() + 1;
            this.currentYear2 = new Date().getFullYear();

            if (this.currentMonth2 > 11) {
                this.currentMonth2 = 0;
                this.currentYear2 += 1;
            }
        }

        this.init();
    }

    /**
     * Normalizes a Date object to start of day (midnight).
     * @param {Date} date - The date to normalize.
     * @returns {Date} The normalized date.
     */
    normalizeDate(date) {
        const normalized = new Date(date);
        normalized.setHours(0, 0, 0, 0);
        return normalized;
    }

    /**
     * Initializes the date picker structure.
     */
    init() {
        this.addStyles(); // Tambahkan CSS ke DOM
        this.container.classList.add('date-picker-container');
        this.container.innerHTML = this.createDOMStructure();
        this.setupEventListeners();
        this.populateYearSelects();
        this.renderCalendars();
        this.updateDateInput();
    }

    /**
     * Menambahkan CSS kustom ke dalam <style> tag di <head>.
     */
    addStyles() {
        if (document.getElementById('date-picker-styles')) {
            return; // Sudah ditambahkan
        }

        const style = document.createElement('style');
        style.id = 'date-picker-styles';
        style.textContent = `
            /* Kustomisasi Warna */
            :root {
                --dp-primary-color: #007bff; /* Biru Cerah */
                --dp-hover-color: #e6f2ff; /* Biru Sangat Muda */
                --dp-text-color: #333333; /* Abu-abu Gelap */
                --dp-border-color: #ced4da; /* Abu-abu Sedang */
                --dp-bg-color: #ffffff;
                --dp-panel-bg-color: #f8f9fa;
            }

            .date-picker-container {
                position: relative;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }

            .calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 0.25rem;
            }
            
            .date-cell {
                aspect-ratio: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border-radius: 0.375rem;
                font-size: 0.75rem;
                font-weight: 500;
                user-select: none;
                transition: all 0.2s ease;
                color: var(--dp-text-color);
            }
            
            .date-cell:hover:not(.other-month):not(.selected):not(.in-range):not(.disabled) {
                background-color: var(--dp-hover-color);
                color: var(--dp-text-color);
            }
            
            .date-cell.other-month {
                color: #adb5bd; /* Abu-abu lebih terang */
                cursor: default;
            }

            .date-cell.disabled {
                color: #ced4da; /* Abu-abu sangat terang */
                cursor: not-allowed;
                text-decoration: line-through;
            }
            
            .date-cell.selected {
                background-color: var(--dp-primary-color);
                color: white;
                font-weight: 600;
                border-radius: 0.375rem;
            }
            
            .date-cell.in-range {
                background-color: rgba(0, 123, 255, 0.3); /* Warna primer dengan opacity */
                color: var(--dp-text-color);
                border-radius: 0;
            }
            
            .date-cell.range-start {
                background-color: var(--dp-primary-color);
                color: white;
                font-weight: 600;
                border-radius: 0.375rem 0 0 0.375rem;
            }
            
            .date-cell.range-end {
                background-color: var(--dp-primary-color);
                color: white;
                font-weight: 600;
                border-radius: 0 0.375rem 0.375rem 0;
            }

            /* Perbaiki tampilan range-start/end saat hanya satu hari */
            .date-cell.range-start.range-end {
                border-radius: 0.375rem;
            }
            
            .date-cell.today {
                border: 2px solid var(--dp-primary-color);
            }
            
            .date-cell.today.selected {
                border: 2px solid white;
            }

            .picker-input input {
                border: 2px solid var(--dp-border-color);
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                width: 100%;
                box-sizing: border-box;
                cursor: pointer;
                font-weight: 500;
                color: var(--dp-text-color);
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .picker-input input:focus {
                outline: none;
                border-color: var(--dp-primary-color);
                box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            }

            .picker-input svg {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                width: 1.25rem;
                height: 1.25rem;
                color: var(--dp-text-color);
                pointer-events: none;
            }

            #pickerPanel {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                min-width: 550px; /* Diperbesar sedikit */
                z-index: 1000; /* Z-index tinggi */
                margin-top: 0.5rem;
                background-color: var(--dp-panel-bg-color);
                border: 1px solid var(--dp-border-color);
                border-radius: 0.5rem;
                padding: 1rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            #calendarsContainer {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .calendar-header {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 0.25rem;
                margin-bottom: 0.5rem;
            }

            .day-label {
                text-align: center;
                font-size: 0.75rem;
                font-weight: 700;
                color: #6c757d; /* Abu-abu gelap */
                padding: 0.5rem 0;
            }

            .month-select, .year-select {
                border: 1px solid var(--dp-border-color);
                border-radius: 0.375rem;
                font-weight: 600;
                color: var(--dp-text-color);
                cursor: pointer;
                background-color: var(--dp-bg-color);
            }

            .month-select:focus, .year-select:focus {
                outline: none;
                border-color: var(--dp-primary-color);
            }

            .btn-cancel {
                padding: 0.5rem 1.5rem;
                border: 1px solid var(--dp-border-color);
                border-radius: 0.5rem;
                color: var(--dp-text-color);
                font-weight: 500;
                transition: all 0.2s;
                background-color: var(--dp-bg-color);
            }

            .btn-cancel:hover {
                background-color: #e9ecef;
            }

            .btn-apply {
                padding: 0.5rem 1.5rem;
                background-color: var(--dp-primary-color);
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-weight: 500;
                transition: background-color 0.2s;
            }

            .btn-apply:hover {
                background-color: #0056b3;
            }

            /* Optimasi Mobile */
            @media (max-width: 600px) {
                #pickerPanel {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    min-width: 100%;
                    margin-top: 0;
                    padding: 1rem;
                    border-radius: 0;
                    overflow-y: auto;
                }

                #calendarsContainer {
                    grid-template-columns: 1fr !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Creates the HTML structure for the date picker.
     * @returns {string} The HTML string.
     */
    createDOMStructure() {
        const calendar2HTML = this.options.singleDate ? '' : `
            <!-- Calendar 2 -->
            <div class="calendar-wrapper">
                <div class="flex items-center justify-between mb-2">
                    <select id="month2" class="month-select"></select>
                    <select id="year2" class="year-select"></select>
                </div>
                <div class="calendar-header">
                    <div class="day-label">Sen</div><div class="day-label">Sel</div><div class="day-label">Rab</div><div class="day-label">Kam</div><div class="day-label">Jum</div><div class="day-label">Sab</div><div class="day-label">Min</div>
                </div>
                <div id="calendar2" class="calendar-grid"></div>
            </div>
        `;

        const applyButtonHTML = this.options.singleDate ? '' : `
            <button type="button" id="cancelBtn" class="btn-cancel">Batal</button>
            <button type="button" id="applyBtn" class="btn-apply">Terapkan</button>
        `;

        const inputPlaceholder = this.options.singleDate ? 'Pilih tanggal' : 'Pilih rentang tanggal';

        return `
            <div class="relative picker-input">
                <input 
                    type="text" 
                    id="dateInput" 
                    readonly 
                    placeholder="${inputPlaceholder}"
                >
                <svg fill="currentColor" viewBox="0 0 640 640">
                    <path d="M216 64C229.3 64 240 74.7 240 88L240 128L400 128L400 88C400 74.7 410.7 64 424 64C437.3 64 448 74.7 448 88L448 128L480 128C515.3 128 544 156.7 544 192L544 480C544 515.3 515.3 544 480 544L160 544C124.7 544 96 515.3 96 480L96 192C96 156.7 124.7 128 160 128L192 128L192 88C192 74.7 202.7 64 216 64zM216 176L160 176C151.2 176 144 183.2 144 192L144 240L496 240L496 192C496 183.2 488.8 176 480 176L216 176zM144 288L144 480C144 488.8 151.2 496 160 496L480 496C488.8 496 496 488.8 496 480L496 288L144 288z"/>
                </svg>
            </div>

            <div id="pickerPanel" class="hidden">
                <div id="calendarsContainer" class="grid ${this.options.singleDate ? 'grid-cols-1' : 'grid-cols-2'} gap-4">
                    <!-- Calendar 1 -->
                    <div class="calendar-wrapper">
                        <div class="flex items-center justify-between mb-2">
                            <select id="month1" class="month-select"></select>
                            <select id="year1" class="year-select"></select>
                        </div>
                        <div class="calendar-header">
                            <div class="day-label">Sen</div><div class="day-label">Sel</div><div class="day-label">Rab</div><div class="day-label">Kam</div><div class="day-label">Jum</div><div class="day-label">Sab</div><div class="day-label">Min</div>
                        </div>
                        <div id="calendar1" class="calendar-grid"></div>
                    </div>

                    ${calendar2HTML}
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end mt-4 pt-4 border-t border-gray-300">
                    ${applyButtonHTML}
                </div>
            </div>
        `;
    }

    /**
     * Sets up all event listeners.
     */
    setupEventListeners() {
        const dateInput = this.container.querySelector('#dateInput');
        const pickerPanel = this.container.querySelector('#pickerPanel');
        const cancelBtn = this.container.querySelector('#cancelBtn');
        const applyBtn = this.container.querySelector('#applyBtn');
        const month1 = this.container.querySelector('#month1');
        const year1 = this.container.querySelector('#year1');
        const month2 = this.container.querySelector('#month2');
        const year2 = this.container.querySelector('#year2');

        // Populate month selects
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        [month1, month2].filter(el => el).forEach(select => {
            months.forEach((month, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = month;
                select.appendChild(option);
            });
        });
        month1.value = this.currentMonth1;
        if (month2) month2.value = this.currentMonth2;

        // Toggle picker panel
        dateInput.addEventListener('click', (ev) => {
            ev.stopPropagation();
            pickerPanel.classList.toggle('hidden');
        });

        // Close picker when clicking outside
        this.closePickerOutside = (e) => {
            if (!this.container.contains(e.target) && e.target !== dateInput) {
                pickerPanel.classList.add('hidden');
            }
        };
        document.addEventListener('click', this.closePickerOutside);

        // Cancel button (only in date range mode)
        if (cancelBtn) {
            cancelBtn.addEventListener('click', (ev) => {
                ev.stopPropagation();
                pickerPanel.classList.add('hidden');
            });
        }

        // Apply button (only in date range mode)
        if (applyBtn) {
            applyBtn.addEventListener('click', (ev) => {
                ev.stopPropagation();
                if (this.startDate && this.endDate) {
                    this.updateDateInput();
                    pickerPanel.classList.add('hidden');
                    if (typeof this.options.onApply === 'function') {
                        this.options.onApply({
                            startDate: this.startDate,
                            endDate: this.endDate
                        });
                    }
                }
            });
        }

        // Month and year selects
        month1.addEventListener('change', (e) => {
            this.currentMonth1 = parseInt(e.target.value);
            this.renderCalendars();
        });

        year1.addEventListener('change', (e) => {
            this.currentYear1 = parseInt(e.target.value);
            this.renderCalendars();
        });

        if (month2) {
            month2.addEventListener('change', (e) => {
                this.currentMonth2 = parseInt(e.target.value);
                this.renderCalendars();
            });
        }

        if (year2) {
            year2.addEventListener('change', (e) => {
                this.currentYear2 = parseInt(e.target.value);
                this.renderCalendars();
            });
        }
    }

    /**
     * Populates the year select dropdowns.
     */
    populateYearSelects() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 10;
        const endYear = currentYear + 10;
        const yearSelects = this.container.querySelectorAll('.year-select');

        yearSelects.forEach(select => {
            select.innerHTML = ''; // Clear existing options
            for (let year = startYear; year <= endYear; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                select.appendChild(option);
            }
        });

        this.container.querySelector('#year1').value = this.currentYear1;
        const year2Select = this.container.querySelector('#year2');
        if (year2Select) year2Select.value = this.currentYear2;
    }

    /**
     * Renders both calendars.
     */
    renderCalendars() {
        this.renderCalendar(1, this.currentMonth1, this.currentYear1);
        if (!this.options.singleDate) {
            this.renderCalendar(2, this.currentMonth2, this.currentYear2);
        }
    }

    /**
     * Renders a single calendar.
     * @param {number} id - The calendar ID (1 or 2).
     * @param {number} month - The month (0-11).
     * @param {number} year - The year.
     */
    renderCalendar(id, month, year) {
        const calendarGrid = this.container.querySelector(`#calendar${id}`);
        if (!calendarGrid) return;

        calendarGrid.innerHTML = ''; // Clear existing cells

        const firstDayOfMonth = new Date(year, month, 1);
        // Adjust start day to Monday (0=Sunday, 1=Monday, ..., 6=Saturday)
        let startDay = firstDayOfMonth.getDay();
        startDay = startDay === 0 ? 6 : startDay - 1; // Convert Sunday (0) to 6, others -1

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = this.normalizeDate(new Date());

        // Calculate previous month's days to fill the start
        const prevMonth = new Date(year, month, 0);
        const daysInPrevMonth = prevMonth.getDate();
        const prevMonthYear = prevMonth.getFullYear();
        const prevMonthMonth = prevMonth.getMonth();

        // Calculate next month's days to fill the end
        const nextMonthYear = new Date(year, month + 1, 1).getFullYear();
        const nextMonthMonth = new Date(year, month + 1, 1).getMonth();

        // 1. Days from previous month
        for (let i = startDay - 1; i >= 0; i--) {
            const date = new Date(prevMonthYear, prevMonthMonth, daysInPrevMonth - i);
            calendarGrid.appendChild(this.createDateCell(date, true, false));
        }

        // 2. Days of the current month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            calendarGrid.appendChild(this.createDateCell(date, false, date.getTime() === today.getTime()));
        }

        // 3. Days from next month to fill the grid (up to 42 cells)
        const totalCells = calendarGrid.children.length;
        const remainingCells = 42 - totalCells; // Max 6 rows * 7 days = 42 cells

        for (let i = 1; i <= remainingCells; i++) {
            const date = new Date(nextMonthYear, nextMonthMonth, i);
            calendarGrid.appendChild(this.createDateCell(date, true, false));
        }

        // Update month/year selects to reflect the rendered month
        const monthSelect = this.container.querySelector(`#month${id}`);
        const yearSelect = this.container.querySelector(`#year${id}`);
        if (monthSelect) monthSelect.value = month;
        if (yearSelect) yearSelect.value = year;
    }

    /**
     * Creates a single date cell element.
     * @param {Date} date - The date for the cell.
     * @param {boolean} isOtherMonth - True if the date belongs to the previous or next month.
     * @param {boolean} isToday - True if the date is today.
     * @returns {HTMLElement} The date cell element.
     */
    createDateCell(date, isOtherMonth, isToday) {
        const cell = document.createElement('div');
        cell.classList.add('date-cell');
        cell.textContent = date.getDate();
        cell.dataset.date = date.toISOString();

        if (isOtherMonth) {
            cell.classList.add('other-month');
        }

        if (isToday) {
            cell.classList.add('today');
        }

        // Check for disabled dates
        const isDisabled = this.isDateDisabled(date);
        if (isDisabled) {
            cell.classList.add('disabled');
            cell.style.cursor = 'not-allowed';
        } else {
            cell.addEventListener('click', (ev) => {
                ev.stopPropagation();              // cegah bubble ke document
                this.handleDateClick(date, isOtherMonth);
            });

        }

        // Apply selection/range styles
        this.applySelectionStyles(cell, date);

        return cell;
    }

    /**
     * Checks if a date is outside the min/max range.
     * @param {Date} date - The date to check.
     * @returns {boolean} True if the date is disabled.
     */
    isDateDisabled(date) {
        const time = date.getTime();
        if (this.options.minDate && time < this.options.minDate.getTime()) {
            return true;
        }
        if (this.options.maxDate && time > this.options.maxDate.getTime()) {
            return true;
        }
        return false;
    }

    /**
     * Applies selection and range styles to a date cell.
     * @param {HTMLElement} cell - The date cell element.
     * @param {Date} date - The date for the cell.
     */
    applySelectionStyles(cell, date) {
        const time = date.getTime();
        const start = this.startDate ? this.startDate.getTime() : null;
        const end = this.endDate ? this.endDate.getTime() : null;

        cell.classList.remove('selected', 'in-range', 'range-start', 'range-end');

        if (start && time === start) {
            cell.classList.add('selected', 'range-start');
        }
        if (end && time === end) {
            cell.classList.add('selected', 'range-end');
        }
        if (start && end && time > start && time < end) {
            cell.classList.add('in-range');
        }

        // Handle single day selection (start and end are the same)
        if (start && end && start === end && time === start) {
            cell.classList.add('range-start', 'range-end');
        }
    }

    /**
     * Handles a click on a date cell.
     * @param {Date} date - The date that was clicked.
     * @param {boolean} isOtherMonth - True if the date belongs to the previous or next month.
     */
    handleDateClick(date, isOtherMonth) {
        const normalizedDate = this.normalizeDate(date);

        if (isOtherMonth) {
            // Change month/year to the clicked date's month/year
            this.currentMonth1 = normalizedDate.getMonth();
            this.currentYear1 = normalizedDate.getFullYear();
            if (!this.options.singleDate) {
                // For the second calendar, set it to the next month
                let nextMonth = normalizedDate.getMonth() + 1;
                let nextYear = normalizedDate.getFullYear();
                if (nextMonth > 11) {
                    nextMonth = 0;
                    nextYear += 1;
                }
                this.currentMonth2 = nextMonth;
                this.currentYear2 = nextYear;
            }
            this.renderCalendars();
            return;
        }

        if (this.options.singleDate) {
            // Single date mode
            this.startDate = normalizedDate;
            this.endDate = normalizedDate;
            this.updateDateInput();
            this.container.querySelector('#pickerPanel').classList.add('hidden');
            this.renderCalendars(); // Re-render to show selection
            if (typeof this.options.onSelect === 'function') {
                this.options.onSelect({
                    date: this.startDate
                });
            }
        } else {
            // Date range mode
            if (!this.startDate || (this.startDate && this.endDate)) {
                // Start a new range
                this.startDate = normalizedDate;
                this.endDate = null;
            } else if (normalizedDate.getTime() < this.startDate.getTime()) {
                // New date is before start date, make it the new start date
                this.startDate = normalizedDate;
            } else {
                // New date is after start date, make it the end date
                this.endDate = normalizedDate;
            }
            this.renderCalendars();
        }
    }

    /**
     * Updates the text input with the selected date(s).
     */
    updateDateInput() {
        const input = this.container.querySelector('#dateInput');
        const dateFormat = this.options.dateFormat || 'MMM D, YYYY';
        const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

        const formatDate = (date) => {
            if (!date) return '';
            let formatted = dateFormat;

            // Perbaikan: Ganti dari token yang lebih panjang/spesifik ke yang lebih pendek
            // Perbaikan: Gunakan word boundary (\b) atau pastikan urutan penggantian yang benar
            // Menggunakan regex global dengan word boundary untuk memastikan hanya token yang utuh yang diganti
            formatted = formatted.replace(/\bYYYY\b/g, date.getFullYear());
            formatted = formatted.replace(/\bYY\b/g, String(date.getFullYear()).slice(-2));
            formatted = formatted.replace(/\bMMM\b/g, months[date.getMonth()]);
            formatted = formatted.replace(/\bMM\b/g, String(date.getMonth() + 1).padStart(2, '0'));
            formatted = formatted.replace(/\bM\b/g, date.getMonth() + 1);
            formatted = formatted.replace(/\bDD\b/g, String(date.getDate()).padStart(2, '0'));
            formatted = formatted.replace(/\bD\b/g, date.getDate());

            return formatted;
        };

        if (this.options.singleDate) {
            input.value = this.startDate ? formatDate(this.startDate) : '';
        } else {
            if (!this.startDate && !this.endDate) {
                input.value = '';
            } else {
                const start = this.startDate ? formatDate(this.startDate) : 'Start Date';
                const end = this.endDate ? formatDate(this.endDate) : 'End Date';
                input.value = `${start} - ${end}`;
            }
        }
    }

    /**
     * Destroys the date picker instance and cleans up DOM/events.
     */
    destroy() {
        document.removeEventListener('click', this.closePickerOutside);
        this.container.innerHTML = '';
        this.container.classList.remove('date-picker-container');
        // Optionally remove styles if no other DatePicker instances are present
        // const style = document.getElementById('date-picker-styles');
        // if (style) style.remove();
    }
}
