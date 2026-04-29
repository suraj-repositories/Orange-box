<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 g-3 dashboard-template-cards">

    @foreach ($templates as $template)
        <div class="col">
            <div class="template-card d-flex flex-column h-100">
                <img class="card-img-top rounded-top" src="https://placehold.co/600x300" alt="Project Thumbnail">
                <div class="template-meta">
                    <div>
                        <div class="user-title">
                            <img class="proflie-image" src="https://placehold.co/50" alt="">
                            <h2 class="mb-0">Crimson forest of alvins Crimson forest of alvinsCrimson
                                forest of alvinsCrimson forest of alvins</h2>
                        </div>

                        <div class="rating text-muted"><i class="bi bi-star-fill"></i> 4.8</div>
                    </div>
                    <div class="price-badge">$ 30</div>
                </div>
            </div>
        </div>
    @endforeach


</div>
