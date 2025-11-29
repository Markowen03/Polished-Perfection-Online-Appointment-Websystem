<style>
.modal-bg {
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index: 99999;
}

.modal-box {
    background:#fff;
    width:450px;
    padding:20px;
    border-radius:10px;
    animation: fadeIn .25s ease;
}

@keyframes fadeIn {
    from { transform: scale(0.85); opacity:0; }
    to   { transform: scale(1); opacity:1; }
}

.modal-box input,
.modal-box textarea {
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
    margin:7px 0;
}
</style>


<div class="modal-bg" id="updateModal">
    <div class="modal-box">

        <h3>Update Service</h3>

        <form id="updateForm" enctype="multipart/form-data">

            <input type="hidden" id="update_id" name="update_id">

            <label>Service Name</label>
            <input type="text" id="update_name" name="update_name" required>

            <label>Price</label>
            <input type="number" id="update_price" name="update_price" required>

            <label>Description</label>
            <textarea id="update_desc" name="update_desc" required></textarea>

            <label>Current Image</label>
            <img id="update_image_preview" src="" style="width:100%; height:150px; object-fit:cover; border:1px solid #ccc; margin-bottom:10px;">

            <label>Upload New Image (optional)</label>
            <input type="file" name="update_image" accept="image/*">

            <br><br>

            <button type="button" class="btn" onclick="submitUpdate()">Save</button>
            <button type="button" class="delete-btn" onclick="closeModal()">Cancel</button>

        </form>
    </div>
</div>

<script>
function openUpdateModal(id, name, price, desc, image) {
    document.getElementById("update_id").value = id;
    document.getElementById("update_name").value = name;
    document.getElementById("update_price").value = price;
    document.getElementById("update_desc").value = desc;
    document.getElementById("update_image_preview").src = "uploaded_img/" + image;

    document.getElementById("updateModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("updateModal").style.display = "none";
}

function submitUpdate() {
    let formData = new FormData(document.getElementById("updateForm"));

    fetch("admin_update_service.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        Swal.fire({
            icon: "success",
            title: "Service Updated!",
            text: "Changes saved."
        }).then(() => {
            window.location.reload();
        });
    });
}
</script>
