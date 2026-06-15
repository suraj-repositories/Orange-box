  <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-4 g-3 file-manager-files">

      @foreach ($files as $file)
          <div class="col">
              <div class="file-card d-flex flex-column h-100">

                  <div class="file-img-box">
                      <input type="checkbox" class="form-check select-checkbox">


                      @if (str_starts_with($file->mime_type, 'image/'))
                          <img class="card-img-top rounded-top object-fit-contain" src="{{ $file->file_url }}"
                              alt="{{ $file->file_name }}"
                              onerror="this.onerror=null;this.src='http://localhost:8000/assets/images/defaults/placeholder-600x400.svg';">
                      @else
                          <i class="bx {{ $file->extension_icon }} file-icon"></i>
                      @endif

                      <div class="dropdown">
                          <a class="dropdown-toggle center-content text-dark btn border btn-sm px-1" type="button"
                              data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded fs-5"></i>
                          </a>

                          <ul class="dropdown-menu dropdown-menu-lg-end" style="">
                              <li><a class="dropdown-item"
                                      href="http://localhost:8000/u/user@123/folder-factory/testing/files">
                                      <i class="bx bx-info-circle me-1"></i>
                                      File info
                                  </a>
                              </li>
                              <li><a class="dropdown-item"
                                      href="http://localhost:8000/u/user@123/folder-factory/testing/files">
                                      <i class="bx bx-share me-1"></i>
                                      Share
                                  </a>
                              </li>
                              <li><a class="dropdown-item"
                                      href="http://localhost:8000/u/user@123/folder-factory/testing/files">
                                      <i class="bx bx-show-alt me-1"></i>
                                      View Source
                                  </a>
                              </li>
                              <li><a class="dropdown-item"
                                      href="http://localhost:8000/u/user@123/folder-factory/testing/files">
                                      <i class="bx bx-copy me-1"></i>
                                      Copy File
                                  </a>
                              </li>
                              <li><a class="dropdown-item"
                                      href="http://localhost:8000/u/user@123/folder-factory/testing/files">
                                     <i class='bx bx-log-in me-1' ></i>
                                      Move File
                                  </a>
                              </li>
                              <li><a class="dropdown-item"
                                      href="http://localhost:8000/u/user@123/folder-factory/testing/files">
                                     <i class='bx bxs-star me-1' ></i>
                                     Make Favourite
                                  </a>
                              </li>
                              <li><button class="dropdown-item edit-form-factory-btn" data-ob-folder-factory-id="2"
                                      data-ob-folder-factory-name="testing" data-ob-folder-factory-icon="3">
                                      <i class="bx bx-edit me-1"></i>
                                      Edit
                                  </button>
                              </li>
                              <li>
                                  <button class="dropdown-item text-danger bg-light-danger delete-folder-button"
                                      data-folder-factory-id="2"><i class="bx bx-trash me-1"></i>
                                      Delete
                                    </button>
                              </li>

                          </ul>
                      </div>
                  </div>

                  <div class="file-meta">
                      <div>
                          <div class="user-title">
                              <i class="bx bx-star fs-4"></i>
                              <div>
                                  <h2 class="mb-0">
                                      {{ $file->file_name }}
                                  </h2>
                                  <small class="file-size text-muted">
                                      {{ $file->formatted_file_size }} | {{ $file->extension }}
                                  </small>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
          </div>
      @endforeach

  </div>
