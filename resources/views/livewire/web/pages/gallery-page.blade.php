@php
    use App\Helpers\Constants;
    use Illuminate\Support\Str;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
@endphp
<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Fotogalerie</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Heim</a></li>
                <li class="active">Fotogalerie</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->


    @if($artGalleries->isNotEmpty())
        <!-- gallery-area -->
        <div class="gallery-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="site-heading text-center wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                            <span class="site-title-tagline"><i class="fas fa-bring-forward"></i> Galerien</span>
                            <h2 class="site-title">Entdecken Sie unsere <span>Galerie</span></h2>
                            <div class="heading-divider"></div>
                        </div>

                        {{-- FILTER BUTTONS --}}
                        <div class="filter-controls wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                            <ul class="filter-btns">
                                <li class="active" data-filter="*">
                                    <i class="fa-solid fa-layer-group"></i> Alle
                                </li>

                                @foreach($artGalleries as $gallery)
                                    @php
                                        $slug       = $gallery->slug ?? Str::slug($gallery->title);
                                        $iconKey    = $gallery->icon; // e.g. 'phone'
                                        $iconClass  = config('font_awesome.icons')[$iconKey] ?? 'fa-regular fa-image';
                                    @endphp

                                    <li data-filter=".cat-{{ $slug }}">
                                        <i class="{{ $iconClass }}"></i> {{ $gallery->title }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- FILTERABLE GRID --}}
                <div class="row mt-3 filter-box popup-gallery wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                    @foreach($artGalleries as $gallery)
                        @php
                            $slug = $gallery->slug ?? Str::slug($gallery->title);

                            // Load media
                            $images = $gallery->getMedia('images');
                            $videos = $gallery->getMedia('videos');

                            // EXCLUDE video posters that were stored in "images"
                            $posterIds = $videos->map(fn($v) => $v->getCustomProperty('poster_media_id'))
                                  ->filter()
                                  ->values()
                                  ->all();
                            if (!empty($posterIds)) {
                                $images = $images->reject(fn($m) => in_array($m->id, $posterIds, true));
                            }
                        @endphp

                        {{-- Images --}}
                        @foreach($images as $media)
                            <div class="col-md-4 filter-item cat-{{ $slug }}">
                                <div class="gallery-item">
                                    <div class="gallery-img">
                                        <picture>
                                            <source srcset="{{ $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(Constants::RESOLUTION_1280_720) }}" type="image/webp">
                                            <img
                                                src="{{ $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(Constants::RESOLUTION_1280_720) }}"
                                                alt="{{ $gallery->title }}">
                                        </picture>
                                    </div>
                                    <div class="gallery-content">
                                        <a class="popup-img gallery-link" href="{{ $media->getUrl() }}">
                                            <i class="far fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Videos (use poster) --}}
                        @foreach($videos as $video)
                            @php
                                if ($posterId = $video->getCustomProperty('poster_media_id')) {
                                    $posterUrl = Media::find($posterId)?->getUrl(Constants::RESOLUTION_1280_720) ?? '#';
                                } else {
                                    $posterUrl = asset('assets/images/default/video_poster.jpg');
                                }
                            @endphp

                            <div class="col-md-4 filter-item cat-{{ $slug }}">
                                <div class="gallery-video">
                                    <a href="{{ $video->getUrl() }}"
                                       target="_blank"
                                       class="popup-video"
                                       data-type="video"
                                       data-title="{{ $gallery->title }}"
                                    >
                                        <picture>
                                            <source srcset="{{ $posterUrl }}" type="image/webp">
                                            <img src="{{ $posterUrl }}" alt="{{ $gallery->title }} video">
                                        </picture>

                                        <div class="video-icon">
                                            <i class="fa-solid fa-play"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    @endforeach

                    {{-- Optional: empty state --}}
                    @php
                        // recompute emptiness after filtering posters out of images
                        $hasMedia = $artGalleries->some(function($g) {
                          $videos = $g->getMedia('videos');
                          $images = $g->getMedia('images');
                          $posterIds = $videos->map(fn($v) => $v->getCustomProperty('poster_media_id'))
                                              ->filter()->values()->all();
                          if (!empty($posterIds)) {
                            $images = $images->reject(fn($m) => in_array($m->id, $posterIds, true));
                          }
                          return $images->isNotEmpty() || $videos->isNotEmpty();
                        });
                    @endphp

                    @unless($hasMedia)
                        <div class="col-12 text-center text-muted py-5">Keine Medien gefunden.</div>
                    @endunless
                </div>
            </div>
        </div>
        <!-- gallery-area end -->
    @else
        <div class="container py-5">
            <div class="alert alert-info text-center shadow-sm rounded">
                <i class="fa-regular fa-image fa-2x d-block mb-2"></i>
                <h5 class="mb-1">Keine Galerien vorhanden</h5>
                <p class="mb-0">Derzeit sind keine Medien verfügbar. Bitte schauen Sie später noch einmal vorbei.</p>
            </div>
        </div>
    @endif
</div>
