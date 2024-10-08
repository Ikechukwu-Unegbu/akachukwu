<div class="modal fade" id="newcateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Create New Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="categoryForm">
          <!-- Name Input -->
          <div class="mb-3">
            <label for="categoryName" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="categoryName" placeholder="Enter category name" required>
          </div>

          <!-- Type Select Input -->
          <div class="mb-3">
            <label for="categoryType" class="form-label">Category Type</label>
            <select class="form-select" id="categoryType" required>
              <option selected disabled>Choose a type</option>
              <option value="type1">Type 1</option>
              <option value="type2">Type 2</option>
              <option value="type3">Type 3</option>
            </select>
          </div>

          <!-- Description Input -->
          <div class="mb-3">
            <label for="categoryDescription" class="form-label">Description</label>
            <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Enter description" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="categoryForm">Save Category</button>
      </div>
    </div>
  </div>
</div>
