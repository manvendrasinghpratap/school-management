<footer class="tl-footer tl-4-footer">
      <div class="tl-footer-top">
            <div class="container">
                <div class="row gy-5 justify-content-between">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="tl-footer-widget tl-4-footer-widget">
                            <a href="index.html" class="logo tl-footer-widget-title">
                                <img src="{{ asset('frontend/assets/images/logos/logo-3.png') }}" alt="logo" />
                            </a>
                            <p class="tl-4-footer-descr">Maurus herderite egret orca ac incident. Viramas at deque eu ipsum consenter commode egret t dam celestas beget mi.</p>
                            <div class="tl-4-footer-socials">
                                <ul>
                                    <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 order-1 order-sm-2 order-lg-1">
                        <div class="row gy-5">
                            <div class="col-6 col-xxs-12">
                                <div class="tl-footer-widget tl-4-footer-widget">
                                    <h5 class="tl-footer-widget-title tl-4-footer-widget-title">Our Campus</h5>
                                    <ul class="tl-footer-links tl-4-footer-links">
                                        <ul class="tl-footer-links">
                                            <li><a href="{{ url('/#about') }}">About Us</a></li> 
                                            <li><a href="{{ url('/#programs') }}">Courses</a></li>
                                            <li><a href="{{ url('/#admission') }}">Help Centre</a></li>
                                            <li><a href="{{ url('/#blog') }}">News</a></li>
                                            <li><a href="{{ url('/contact') }}">Contact</a></li>
                                        </ul>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6 col-xxs-12">
                                <div class="tl-footer-widget tl-4-footer-widget">
                                    <h5 class="tl-footer-widget-title tl-4-footer-widget-title">
                                        Academics
                                    </h5>
                                    <ul class="tl-footer-links tl-4-footer-links">
                                        <li><a href="#">Programming</a></li>
                                        <li><a href="#">Art &amp; Design</a></li>
                                        <li><a href="#">Business</a></li>
                                        <li><a href="#">Engineering</a></li>
                                        <li><a href="#">Photography</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-12 order-2 order-sm-1 order-lg-2">
                        <div class="tl-footer-widget tl-4-footer-widget">
                            <h5 class="tl-footer-widget-title tl-4-footer-widget-title">
                                Recent Posts
                            </h5>
                            <div class="tl-4-footer-articles">
                                <div class="tl-4-footer-article">
                                    <img src="{{ asset('frontend/assets/images/tl-11/sidebar-article-2.jpg') }}" alt="Article image" class="tl-4-footer-article-img"/>

                                    <div class="tl-4-footer-article-txt">
                                        <span class="tl-4-footer-article-date" >June 16, 2023</span>
                                        <h5 class="tl-4-footer-article-title"><a href="#">Education Renaissance Reviving the Art of Learning.</a></h5>
                                    </div>
                                </div>

                                <div class="tl-4-footer-article">
                                    <img src="{{ asset('frontend/assets/images/tl-11/sidebar-article-3.jpg') }}" alt="Article image" class="tl-4-footer-article-img"/>

                                    <div class="tl-4-footer-article-txt">
                                        <span class="tl-4-footer-article-date">April 18, 2023</span>
                                        <h5 class="tl-4-footer-article-title"><a href="#">Education Trailblazers Innovating the Learning.</a></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tl-footer-bottom tl-4-footer-bottom">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12">
                    <p class="tl-4-copyright-txt m-0 text-center">
                        Copyright © {{ date('Y') }} All Rights Reserved by Jerry
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>