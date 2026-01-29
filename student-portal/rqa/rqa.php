<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>administrator-page/rqa/css/rqa.css">

<div class="card radius-10">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div>
                <h5 class="mb-0">Registry of Qualified Applicants</h5>
            </div>
            <div class="dropdown options ms-auto">
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addNews" onclick="prepareAdd()">
                    <i class='bx bx-plus'></i> Add
                </button>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>File Name</th>
                        <th>Date Posted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="rqaTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addNews" tabindex="-1" aria-labelledby="addNewsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Post RQA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-top border-0 border-4 border-white">
                    <div class="card-body p-5">
                        <form id="rqaForm">
                            <div class="col-md-12 mt-2">
                                <label for="rFile" class="form-label">Upload File</label>
                                <input type="file" class="form-control" id="rFile" name="rFile" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                <small class="text-info" id="fileNote"></small>
                            </div>
                            
                            <div class="col-md-12 mt-2">
                                <label for="fileName" class="form-label">Display Name</label>
                                <input type="text" class="form-control" id="fileName" name="fileName" required>
                            </div>
                            
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" id="submitBtn" class="btn btn-light col-12 mx-auto">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isEditing = false;
let currentEditId = null;

/* Helper to reset modal for "Add" mode */
function prepareAdd() {
    isEditing = false;
    currentEditId = null;
    document.getElementById('rqaForm').reset();
    document.getElementById('modalTitle').textContent = 'Post RQA';
    document.getElementById('submitBtn').textContent = 'Save changes';
    document.getElementById('fileNote').textContent = "";
}

/* FORM SUBMIT */
document.getElementById('rqaForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    
    if (isEditing) {
        formData.append('rId', currentEditId);
    }

    // Note: Ensure your API endpoints are correct
    const url = isEditing ? 'api/edit-rqa.php' : 'api/add.php';

    try {
        const res = await fetch(url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });

        const json = await res.json();

        if (!json.success) {
            alert(json.message || 'Operation failed');
            return;
        }

        alert(json.message);
        bootstrap.Modal.getInstance(document.getElementById('addNews')).hide();
        loadRqa();
    } catch (error) {
        console.error('Submit error:', error);
        alert('An error occurred while submitting');
    }
});

/* ESCAPE HTML */
function escapeHtml(text) {
    return text ? text.replace(/[&<>"']/g, m => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
    }[m])) : '';
}

/* LOAD RQA */
async function loadRqa() {
    try {
        const res = await fetch('api/fetch-rqa.php', { credentials:'same-origin' });
        const json = await res.json();

        if (!json.success) return;

        const tbody = document.getElementById('rqaTableBody');
        tbody.innerHTML = '';

        json.data.forEach((row, i) => {
            const ext = row.rFile.split('.').pop().toLowerCase();
            const isImage = ['jpg','jpeg','png','webp'].includes(ext);

            const filePreview = isImage
                ? `<img src="../assets/rqa-files/${row.rFile}" style="width:50px;height:50px;object-fit:cover" class="rounded">`
                : `<i class="bx bxs-file-pdf text-danger fs-2"></i>`;

            tbody.innerHTML += `
            <tr>
                <td>${i + 1}</td>
                <td>
                    <a href="../assets/rqa-files/${row.rFile}" target="_blank">
                        ${filePreview}
                    </a>
                </td>
                <td>${escapeHtml(row.fileName)}</td>
                <td>${row.dateAdded}</td>
                <td>
                    <a href="api/view-rqa.php?id=${row.rId}" class="text-primary me-2" title="View">
                        <i class="bx bx-show fs-4"></i>
                    </a>
                    <a href="javascript:;" class="text-warning me-2 edit-rqa" data-id="${row.rId}" title="Edit">
                        <i class="bx bx-edit fs-4"></i>
                    </a>
                    <a href="javascript:;" class="text-danger delete-rqa" data-id="${row.rId}" title="Delete">
                        <i class="bx bx-trash fs-4"></i>
                    </a>
                </td>
            </tr>`;
        });
    } catch (err) { console.error("Load error:", err); }
}

/* TABLE ACTIONS (Delegation) */
document.getElementById('rqaTableBody').addEventListener('click', (e) => {
    const editBtn = e.target.closest('.edit-rqa');
    const deleteBtn = e.target.closest('.delete-rqa');

    if (editBtn) {
        openEditModal(editBtn.dataset.id);
    }
    if (deleteBtn) {
        deleteRqa(deleteBtn.dataset.id);
    }
});

/* OPEN EDIT MODAL */
async function openEditModal(id) {
    try {
        // Updated to use fetch-rqa.php instead of fetch-news.php
        const res = await fetch(`api/fetch-rqa.php?id=${id}`, { credentials: 'same-origin' });
        const json = await res.json();

        if (!json.success || !json.data) {
            alert('Failed to load record');
            return;
        }

        const data = Array.isArray(json.data) ? json.data.find(x => x.rId == id) : json.data;

        document.getElementById('fileName').value = data.fileName || '';
        document.getElementById('fileNote').textContent = "Leave blank to keep current file: " + data.rFile;
        
        isEditing = true;
        currentEditId = id;
        document.getElementById('modalTitle').textContent = 'Edit RQA';
        document.getElementById('submitBtn').textContent = 'Update RQA';

        new bootstrap.Modal(document.getElementById('addNews')).show();

    } catch (err) {
        console.error(err);
        alert('Edit error');
    }
}

/* DELETE */
async function deleteRqa(id) {
    if (!confirm('Delete this record?')) return;

    const fd = new FormData();
    fd.append('rId', id); // Changed to rId

    try {
        const res = await fetch('api/delete-rqa.php', {
            method:'POST',
            body:fd,
            credentials:'same-origin'
        });

        const json = await res.json();
        if (json.success) loadRqa();
        else alert(json.message);
    } catch (err) { alert("Delete failed"); }
}

/* INIT */
document.addEventListener('DOMContentLoaded', loadRqa);
</script>