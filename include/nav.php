<?php
if (!$keep_pdo) {
    $pdo = null;
}
?>

<nav class="topnav navbar navbar-light">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar" href="./?mode=dark">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
                <i class="fe fe-sun fe-16"></i>
            </a>
        </li><!--
      <li class="nav-item">
         <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
         <span class="fe fe-grid fe-16"></span>
         </a>
      </li>
      <li class="nav-item nav-notif">
         <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-notif">
         <span class="fe fe-bell fe-16"></span>
         <span class="dot dot-md bg-success"></span>
         </a>
      </li> -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class='fe fe-user fe-16'></span>
                <style>
                    #navbarDropdownMenuLink {
                        margin-top: 7px;
                    }
                </style>
                <!-- <span class="avatar avatar-sm mt-2">
                <img src="./assets/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle">
                </span> -->

            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo $relative_path; ?>/profile/settings">Settings</a>
                <a class="dropdown-item" href="./?logout=true">Logout</a>
            </div>
        </li>
    </ul>
</nav>

<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">
        <!-- nav bar -->
        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?php echo $relative_path; ?>/dashboard">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="38" viewBox="0,0,36,38">
                    <g transform="translate(-222,-161)">
                        <g data-paper-data="{&quot;isPaintingLayer&quot;:true}" fill="none" fill-rule="nonzero" stroke="none" stroke-width="0.5" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" style="mix-blend-mode: normal">
                            <image x="444" y="322" transform="scale(0.5,0.5)" width="72" height="76" xlink:href="data:image/png;base64,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" data-paper-data="{&quot;expanded&quot;:[&quot;Raster&quot;,{&quot;applyMatrix&quot;:false,&quot;matrix&quot;:[1,0,0,1,502,358],&quot;crossOrigin&quot;:&quot;&quot;,&quot;source&quot;:&quot;data:image/png;base64,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&quot;}]}"/>
                        </g>
                    </g>
                </svg>

            </a>
        </div>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
                <a class="nav-link" href="<?php echo $relative_path; ?>/dashboard">
                    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">Dashboard</span>
                </a>
            </li>
        </ul><?php

if ($permission_level >= $create_lessons) {
    echo '
        <p class="text-muted nav-heading mt-4 mb-1">
            <span>Apps</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">


            <li class="nav-item dropdown"> <!-- Lessons -->
                <a href="#lessons" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-calendar fe-16"></i>
                    <span class="ml-3 item-text">Angebote</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="lessons">
                    <a class="nav-link pl-3" href="'. $relative_path . '/lessons"><span class="ml-1">Overview</span></a>
                    
							<a class="nav-link pl-3" href="' . $relative_path . '/lessons/details"><span class="ml-1">Angebot erstellen</span></a>
						
                </ul>';
        }

                if ($permission_level >= $create_lessons) {
                    echo '
			              <ul class="navbar-nav flex-fill w-100 mb-2">
				             <li class="nav-item w-100">
					         <a href="#sick" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-user fe-16"></i>
                    <span class="ml-3 item-text">Krank</span>
                </a>
					         <ul class="collapse list-unstyled pl-4 w-100" id="sick">
					             <a class="nav-link pl-3" href="'. $relative_path . '/sick/"><span class="ml-1">Overview</span></a>
                                 <a class="nav-link pl-3" href="' . $relative_path . '/sick/edit/"><span class="ml-1">Krankmeldung erstellen</span></a>                   
                             </ul>
                          </ul>';
                }?>
            </li>


            <?php

            if ($permission_level >= $manage_other_users) {
                echo '
					<p class="text-muted nav-heading mt-4 mb-1">
						<span>Admin</span>
					</p>

					<li class="nav-item dropdown">
						<a href="#admin" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
							<i class="fe fe-unlock fe-16"></i>
							<span class="ml-3 item-text">Administration</span>
						</a>
						<ul class="collapse list-unstyled pl-4 w-100" id="admin">
							<a class="nav-link pl-3" href="' . $relative_path . '/admin/accounts"><span class="ml-1">Accounts</span></a>
							' .
                            //<a class="nav-link pl-3" href="' . $relative_path . '/admin/settings"><span class="ml-1">Settings</span></a>
                            '
						</ul>
					</li>



				';
            }
            ?>



        </ul>
        <!--
          <p class="text-muted nav-heading mt-4 mb-1">
            <span>Support</span>
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a href="#support" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-compass fe-16"></i>
                <span class="ml-3 item-text">Support</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="support">
                <a class="nav-link pl-3" href="<?php echo $relative_path; ?>/support"><span class="ml-1">Home</span></a>
                <a class="nav-link pl-3" href="<?php echo $relative_path; ?>/support-tickets.html"><span class="ml-1">Tickets</span></a>
                <a class="nav-link pl-3" href="<?php echo $relative_path; ?>/support-faqs.html"><span class="ml-1">FAQs</span></a>
              </ul>
            </li>
          </ul> -->

    </nav>
</aside>