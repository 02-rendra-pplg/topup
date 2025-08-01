<div class="container my-4">
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/Baner.jpeg') }}" class="d-block w-100 rounded" alt="Banner 1" style="max-height: 300px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/Baner1.jpg') }}" class="d-block w-100 rounded" alt="Banner 2" style="max-height: 300px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/Baner2.png') }}" class="d-block w-100 rounded" alt="Banner 3" style="max-height: 300px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/Baner3.jpeg') }}" class="d-block w-100 rounded" alt="Banner 4" style="max-height: 300px; object-fit: cover;">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="3"></button>
        </div>
    </div>
</div>
