<div class="tl-4-header red-clr">
      <div class="tl-8-top-header">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <ul class="tl-8-top-header-contacts">
                <li><a href="tel:12356877787"><i class="fa-solid fa-phone"></i> (88) 123 568 777 87</a></li>
                <li><a href="mailto:info@xyz-text.com"><i class="fa-solid fa-envelope"></i> info@xyz-text.com</a></li>
                <li><i class="fa-solid fa-location-dot"></i> New Jersey 07052, USA</li>
              </ul>
            </div>
            <div class="col-lg-4">
              <ul class="tl-8-top-header-socials">
                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="tl-4-bottom-header">
        <div class="container">
          <div class="row justify-content-between g-0 align-items-center">
            <div class="col-xl-2 col-lg-2">
              <div class="row align-items-center">
                <div class="col-lg-12 col-6">
                  <div class="logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('frontend/assets/images/logos/logo-3-light.png') }}" alt="KIDBA"/></a>
                  </div>
                </div>
                <div class="d-lg-none d-flex justify-content-end col-6">
                  <button class="tl-hamburger navbar-toggler"><i class="icofont-navigation-menu"></i></button>
                </div>
              </div>
            </div>

            <div class="col-xxl-5 col-6">
              <div class="tl-nav-menu tl-4-nav-menu">
                <ul class="justify-content-center d-none d-lg-flex">
                  <li class="tl-nav-item tl-dropdown">
                    <a href="{{ url('/') }}" role="button">Home <i class="fa-regular fa-angle-down"></i></a>
                    <ul class="tl-submenu">
                      <li><a href="{{ url('/') }}">Home</a></li>
                      <!--
                      <li><a href="{{ url('index-2') }}">Kindergarten</a></li>
                      <li><a href="{{ url('index-3') }}">School</a></li>
                      <li><a href="{{ url('index-4') }}">College</a></li>
                      <li><a href="{{ url('index-5') }}">University</a></li>
                      <li><a href="{{ url('index-6') }}">Magazine</a></li>
                      <li><a href="{{ url('index-7') }}">Dance School</a></li>
                      <li><a href="{{ url('index-8') }}">Driving School</a></li>
                      <li><a href="{{ url('index-9') }}">Music School</a></li>
                      <li><a href="{{ url('index-10') }}">University Two</a></li>
                      <li><a href="{{ url('index-11') }}">Magazine Two</a></li>
                      <li><a href="{{ url('index-12') }}">Live Class</a></li>
                      <li><a href="{{ url('index-13') }}">Online Course</a></li>
                      <li><a href="{{ url('index-14') }}">Language Club</a></li>
                      <li><a href="{{ url('index-15') }}">Cooking Academy</a></li>
                      <li><a href="{{ url('index-16') }}">Online School</a></li>
                      <li><a href="{{ url('index-17') }}">Marketplace</a></li>
                      <li><a href="{{ url('index-18') }}">Art School</a></li>
                      <li><a href="{{ url('index-19') }}">LMS Instructor</a></li>
                      -->
                    </ul>
                  </li>
                  <li class="tl-nav-item tl-dropdown"> <a href="#" role="button"> Courses <i class="fa-regular fa-angle-down"></i> </a>
                    <ul class="tl-submenu">
                      <li><a href="{{ url('/') }}">Course One</a></li>
                      <!--
                      <li><a href="{{ url('/') }}">Course Two</a></li>
                      <li><a href="{{ url('/') }}">Course Three</a></li>
                      <li><a href="{{ url('/') }}">Course Four</a></li>
                      <li><a href="{{ url('/') }}">Course Details</a></li>
                      -->
                    </ul>
                  </li>
                  <li class="tl-nav-item tl-dropdown"> <a href="#" role="button"> Pages <i class="fa-regular fa-angle-down"></i> </a>
                    <ul class="tl-submenu">
                      <li class="tl-dropdown-2"> <a href="#">About <i class="fa-regular fa-angle-right"></i></a>
                        <ul class="tl-submenu tl-submenu-2">
                          <li><a href="{{ url('/') }}">About One</a></li>
                          <!--
                          <li><a href="{{ url('/') }}">About Two</a></li>
                          <li><a href="{{ url('/') }}">About Three</a></li>
                          <li><a href="{{ url('/') }}">About Four</a></li>
                          <li><a href="{{ url('/') }}">About Five</a></li>
                          <li><a href="{{ url('/') }}">About Six</a></li>
                          <li><a href="{{ url('/') }}">About Seven</a></li>
                          <li><a href="{{ url('/') }}">About Eight</a></li>
                          -->
                        </ul>
                      </li>
                      <li class="tl-dropdown-2"> <a href="#">Staff <i class="fa-regular fa-angle-right"></i></a>
                        <ul class="tl-submenu tl-submenu-2">
                          <!--
                          <li><a href="staff.html">Staff One</a></li>
                          <li><a href="staff-2.html">Staff Two</a></li>
                          <li><a href="staff-3.html">Staff Three</a></li>
                          <li><a href="staff-4.html">Staff Four</a></li>
                          <li><a href="staff-5.html">Staff Five</a></li>
                          <li><a href="staff-6.html">Staff Six</a></li>
                          -->
                        </ul>
                      </li>
                      <li><a href="{{ url('/') }}">Staff Profile</a></li>
                      <!--
                      <li class="tl-dropdown-2">
                        <a href="#"
                          >Event <i class="fa-regular fa-angle-right"></i
                        ></a>

                        <ul class="tl-submenu tl-submenu-2">
                          <li><a href="event.html">Event One</a></li>
                          <li><a href="event-2.html">Event Two</a></li>
                          <li><a href="event-3.html">Event Three</a></li>
                        </ul>
                      </li>
                      <li><a href="event-details.html">Event Details</a></li>
                      -->
                    </ul>
                  </li>
                 <!--
                  <li class="tl-nav-item tl-dropdown">
                    <a href="#" role="button">
                      Blog <i class="fa-regular fa-angle-down"></i>
                    </a>
                    <ul class="tl-submenu">
                      <li class="tl-dropdown-2">
                        <a href="#"
                          >Blog <i class="fa-regular fa-angle-right"></i
                        ></a>

                        <ul class="tl-submenu tl-submenu-2">
                          <li><a href="blog.html">Blog One</a></li>
                          <li><a href="blog-2.html">Blog Two</a></li>
                          <li><a href="blog-3.html">Blog Three</a></li>
                          <li><a href="blog-4.html">Blog Four</a></li>
                          <li><a href="blog-5.html">Blog Five</a></li>
                          <li><a href="blog-6.html">Blog Six</a></li>
                          <li><a href="blog-7.html">Blog Seven</a></li>
                        </ul>
                      </li>
                      <li><a href="blog-details.html">Blog Details</a></li>
                    </ul>
                  </li>
                  -->
                  <li class="tl-nav-item tl-dropdown"><a href="#" role="button">Contact <i class="fa-regular fa-angle-down"></i></a>
                    <ul class="tl-submenu">
                      <li><a href="{{ url('/') }}">Contact One</a></li>
                      <!--
                      <li><a href="contact-2.html">Contact Two</a></li>
                      -->
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-xxl-1 d-xxl-block d-none"></div>
            <div class="col-4 d-lg-block d-none">
              <div class="tl-header-actions d-flex justify-content-end">
                <form action="#" class="tl-nav-search-form tl-4-nav-search-form">
                  <input type="search" name="Search" class="tl-nav-search" placeholder="Search items" />
                  <button class="tl-searh-btn"><i class="fa-light fa-magnifying-glass"></i></button>
                </form>
                @auth
                  <form method="POST" action="{{ route('logout') }}" class="d-inline"> 
                      @csrf
                      <button type="submit" class="tl-def-btn tl-4-def-btn border-0 bg-transparent"><i class="fa-light fa-right-from-bracket"></i> Logout</button>
                  </form>
                @else
                  <a href="#" class="tl-def-btn tl-4-def-btn" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-light fa-user"></i> Login</a>
                @endauth
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>