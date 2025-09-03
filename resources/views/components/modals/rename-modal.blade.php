<div class="modal rename-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ $formActionUrl }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $modalId }}Label">Rename</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                  <div class="mb-">
                      <label for="{{ $modalId }}-label-text" class="form-label">Rename <div class="badge text-dark rename-badge">{{ $prevResourceName }}</div></label>
                      <input type="text" name="new_name" class="form-control" id="{{ $modalId }}-label-text" value="{{ $prevResourceName }}">
                  </div>
              </div>
              <div class="modal-footer">

                <button type="submit" class="btn btn-primary mt-2">Save</button>
              </div>
        </form>
      </div>
    </div>
  </div>
