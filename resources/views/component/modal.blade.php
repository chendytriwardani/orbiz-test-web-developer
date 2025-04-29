  <!-- Modal -->
  <div class="modal fade" id="productModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- form --}}
            <form id="productBook" action="#" method="post" >
                @csrf
                <input type="hidden" id="id" name="id">
                <div class="mb-3">
                  <label for="title" class="col-form-label">Title books:</label>
                  <input type="text" name="title" class="form-control" id="title">
                </div>
                <div class="mb-3">
                  <label for="author" class="col-form-label">author:</label>
                  <textarea class="form-control" name="author" id="author"></textarea>
                </div>
                <div class="mb-3">
                    <label for="genre" class="col-form-label">genre:</label>
                    <input class="form-control" name="genre" id="genre"></input>
                  </div>
                  <div class="mb-3">
                    
                    <label for="vote_count" class="col-form-label">vote_count:</label>
                    <input class="form-control" name="vote_count" id="vote_count"></input>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btnSubmit"></button>
                  </div>
              </form>
        </div>
      </div>
    </div>
  </div>