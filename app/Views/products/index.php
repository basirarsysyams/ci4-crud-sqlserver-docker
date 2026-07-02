<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="toolbar">
        <h2 style="margin:0;">Daftar Produk</h2>
        <button type="button" class="btn btn-primary" id="btn-add">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Produk
        </button>
    </div>

    <table id="products-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th width="150">Harga</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Form (untuk Create & Edit) -->
<div class="modal-overlay" id="product-modal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Produk</h3>
            <button type="button" class="modal-close" id="modal-close">&times;</button>
        </div>
        <form id="product-form">
            <div class="modal-body">
                <input type="hidden" name="id" id="product-id">

                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" name="name" id="product-name" class="form-control"
                        placeholder="Contoh: Kopi Arabika Premium" required>
                    <div class="form-error" id="error-name"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select name="category_id" id="product-category" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error" id="error-category_id"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span class="required">*</span></label>
                    <input type="number" name="price" id="product-price" class="form-control" placeholder="0" min="1"
                        required>
                    <div class="form-error" id="error-price"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn-cancel">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span id="btn-submit-text">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let table;
        let currentEditId = null;

        // ============================================
        // INISIALISASI DATATABLES (SERVER-SIDE)
        // ============================================
        table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('products/ajax-list') ?>',
                type: 'POST',
                error: function(xhr, error, thrown) {
                    console.error('DataTables Ajax Error:', error);
                    console.error('Server Response:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat data',
                        html: 'Terjadi kesalahan pada server. Buka Console (F12) untuk detail.',
                        footer: '<small>Status: ' + xhr.status + ' ' + xhr.statusText +
                            '</small>'
                    });
                }
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'name',
                    className: 'product-name'
                },
                {
                    data: 'category_name',
                    render: function(data) {
                        return data ? '<span class="badge">' + data + '</span>' :
                            '<span style="color:#9ca3af;">-</span>';
                    }
                },
                {
                    data: 'price_formatted',
                    className: 'price-cell'
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                        <div class="action-buttons">
                            <button type="button" class="btn btn-sm btn-edit btn-edit-product" data-id="${row.id}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-delete btn-delete-product" data-id="${row.id}" data-name="${row.name}">
                                Hapus
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            order: [
                [1, 'asc']
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ produk",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                processing: "Memuat...",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "→",
                    previous: "←"
                }
            }
        });

        // ============================================
        // MODAL HANDLERS
        // ============================================
        function openModal(isEdit = false) {
            $('#product-modal').addClass('active');
            $('#modal-title').text(isEdit ? 'Edit Produk' : 'Tambah Produk');
            $('#btn-submit-text').text(isEdit ? 'Update' : 'Simpan');
            clearErrors();
        }

        function closeModal() {
            $('#product-modal').removeClass('active');
            $('#product-form')[0].reset();
            $('#product-id').val('');
            currentEditId = null;
            clearErrors();
        }

        function clearErrors() {
            $('.form-error').removeClass('active').text('');
        }

        function showErrors(errors) {
            clearErrors();
            for (const [field, message] of Object.entries(errors)) {
                $(`#error-${field}`).addClass('active').text(message);
            }
        }

        // ============================================
        // TAMBAH PRODUK
        // ============================================
        $('#btn-add').click(function() {
            currentEditId = null;
            openModal(false);
        });

        // ============================================
        // EDIT PRODUK (AJAX GET)
        // ============================================
        $(document).on('click', '.btn-edit-product', function() {
            const id = $(this).data('id');
            currentEditId = id;

            $.ajax({
                url: `<?= base_url('products/ajax-get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const p = response.data;
                        $('#product-id').val(p.id);
                        $('#product-name').val(p.name);
                        $('#product-category').val(p.category_id);
                        $('#product-price').val(p.price);
                        openModal(true);
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memuat data produk', 'error');
                }
            });
        });

        // ============================================
        // SUBMIT FORM (CREATE / UPDATE via AJAX)
        // ============================================
        $('#product-form').submit(function(e) {
            e.preventDefault();
            clearErrors();

            const formData = $(this).serialize();
            const url = currentEditId ?
                `<?= base_url('products/ajax-update') ?>/${currentEditId}` :
                `<?= base_url('products/ajax-store') ?>`;

            $('#btn-submit').prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        closeModal();
                        table.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        if (response.errors) {
                            showErrors(response.errors);
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error',
                        'Terjadi kesalahan jaringan atau server menolak request (Status: ' +
                        xhr.status + ')', 'error');
                },
                complete: function() {
                    $('#btn-submit').prop('disabled', false).html(`
                    <span id="btn-submit-text">${currentEditId ? 'Update' : 'Simpan'}</span>
                `);
                }
            });
        });

        // ============================================
        // HAPUS PRODUK (AJAX DELETE dengan SweetAlert2)
        // ============================================
        $(document).on('click', '.btn-delete-product', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `Produk "${name}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('products/ajax-delete') ?>/${id}`,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal menghapus produk', 'error');
                        }
                    });
                }
            });
        });

        // ============================================
        // CLOSE MODAL
        // ============================================
        $('#modal-close, #btn-cancel').click(closeModal);
        $('#product-modal').click(function(e) {
            if (e.target === this) closeModal();
        });
    });
</script>
<?= $this->endSection() ?>