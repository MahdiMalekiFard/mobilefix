<div>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/images/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Service Single</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home-page') }}">Home</a></li>
                <li class="active">Service Single</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- service-single -->
    <div class="service-single-area py-120">
        <div class="container">
            <div class="service-single-wrapper">
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="service-sidebar">
                            <div class="widget category">
                                <h4 class="widget-title">All Services</h4>
                                <div class="category-list">
                                    <a href="{{ route('service-single-page', ['slug' => 'tablets-ipad-repair']) }}"><i class="far fa-angle-double-right"></i>Tablets & iPad Repair</a>
                                    <a href="{{ route('service-single-page', ['slug' => 'smart-phone-repair']) }}"><i class="far fa-angle-double-right"></i>Smart Phone Repair</a>
                                    <a href="{{ route('service-single-page', ['slug' => 'gadget-repair']) }}"><i class="far fa-angle-double-right"></i>Gadget Repair</a>
                                    <a href="{{ route('service-single-page', ['slug' => 'laptop-desktop-repair']) }}"><i class="far fa-angle-double-right"></i>Laptop & Desktop Repair</a>
                                    <a href="{{ route('service-single-page', ['slug' => 'data-recovery']) }}"><i class="far fa-angle-double-right"></i>Data Recovery</a>
                                    <a href="{{ route('service-single-page', ['slug' => 'hardware-update']) }}"><i class="far fa-angle-double-right"></i>Hardware Update</a>
                                    <a href="{{ route('service-single-page', ['slug' => 'networking-problem']) }}"><i class="far fa-angle-double-right"></i>Networking Problem</a>
                                </div>
                            </div>
                            <div class="widget service-download">
                                <h4 class="widget-title">Download</h4>
                                <a href="#"><i class="far fa-file-pdf"></i> Download Brochure</a>
                                <a href="#"><i class="far fa-file-alt"></i> Download Application</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8">
                        <div class="service-details">
                            <div class="service-details-img mb-30">
                                <img src="{{ asset('assets/images/service/single.jpg') }}" alt="thumb">
                            </div>
                            <div class="service-details">
                                <h3 class="mb-30">Data Recovery</h3>
                                <p class="mb-20">
                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                    doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                                    veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam
                                    voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                    consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque
                                    porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                    velit, sed quia non numquam eius modi tempora incidunt ut labore et.
                                </p>
                                <p class="mb-20">
                                    But I must explain to you how all this mistaken idea of denouncing pleasure and
                                    praising pain was born and I will give you a complete account of the system, and
                                    expound the actual teachings of the great explorer of the truth, the
                                    master-builder of human happiness. No one rejects, dislikes, or avoids pleasure
                                    itself, because it is pleasure, but because those who do not know how to pursue
                                    pleasure rationally encounter consequences that are extremely painful. Nor again
                                    is there anyone who loves or pursues or desires to obtain pain of itself,
                                    because it is pain, but because occasionally circumstances occur in which toil
                                    and pain can procure him some great pleasure. To take a trivial example
                                </p>
                                <div class="row">
                                    <div class="col-md-6 mb-20">
                                        <img src="{{ asset('assets/images/service/01.jpg') }}" alt="">
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <img src="{{ asset('assets/images/service/02.jpg') }}" alt="">
                                    </div>
                                </div>
                                <p class="mb-20">
                                    Power of choice is untrammelled and when nothing prevents our being able to do
                                    what we like best, every pleasure is to be welcomed and every pain avoided. But
                                    in certain circumstances and owing to the claims of duty or the obligations of
                                    business it will frequently occur that pleasures have to be repudiated and
                                    annoyances accepted. The wise man therefore always holds in these matters to
                                    this principle of selection.
                                </p>
                                <div class="my-4">
                                    <div class="mb-3">
                                        <h3 class="mb-3">Our Work Process</h3>
                                        <p>Aliquam facilisis rhoncus nunc, non vestibulum mauris volutpat non.
                                            Vivamus tincidunt accumsan urna, vel aliquet nunc commodo tristique.
                                            Nulla facilisi. Phasellus vel ex nulla. Nunc tristique sapien id mauris
                                            efficitur, porta scelerisque nisl dignissim. Vestibulum ante ipsum
                                            primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed at
                                            mollis tellus. Proin consequat, orci nec bibendum viverra, ante orci
                                            suscipit dolor, et condimentum felis dolor ac lectus.</p>
                                    </div>
                                    <ul class="service-single-list">
                                        <li><i class="far fa-check"></i>Fusce justo risus placerat in risus eget
                                            tincidunt consequat elit.</li>
                                        <li><i class="far fa-check"></i>Nunc fermentum sem sit amet dolor laoreet
                                            placerat.</li>
                                        <li><i class="far fa-check"></i>Nullam rhoncus dictum diam quis ultrices.
                                        </li>
                                        <li><i class="far fa-check"></i>Integer quis lorem est uspendisse eu augue
                                            porta ullamcorper dictum.</li>
                                        <li><i class="far fa-check"></i>Quisque tristique neque arcu ut venenatis
                                            felis malesuada et.</li>
                                    </ul>
                                </div>
                                <div class="my-4">
                                    <h3 class="mb-3">Service Features</h3>
                                    <p>Quisque a nisl id sem sollicitudin volutpat. Cras et commodo quam, vel congue
                                        ligula. Orci varius natoque penatibus et magnis dis parturient montes,
                                        nascetur ridiculus mus. Cras quis venenatis neque. Donec volutpat tellus
                                        lobortis mi ornare eleifend. Fusce eu nisl ut diam ultricies accumsan.
                                        Integer lobortis vestibulum nunc id porta. Curabitur aliquam arcu sed ex
                                        dictum, a facilisis urna porttitor. Fusce et mattis nisl. Sed iaculis libero
                                        consequat justo auctor iaculis. Vestibulum sed ex et magna tristique
                                        bibendum. Sed hendrerit neque nec est suscipit, id faucibus dolor convallis.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- service-single end-->
</div>
