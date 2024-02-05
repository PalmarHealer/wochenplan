<?php
if (!$keep_pdo) {
    $pdo = null;
}
?>

<nav class="topnav navbar navbar-light sticky-top">
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

            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo $relative_path; ?>/profile/settings">Einstellungen</a>
                <a class="dropdown-item" href="./?logout=true">Abmelden</a>
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
                <img src="<?php echo $relative_path; ?>/img/logo.svg" alt="Logo" class="logo">

            </a>
        </div>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
                <a class="nav-link" href="<?php echo $relative_path; ?>/dashboard">
                    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">Übersicht</span>
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
                    <a class="nav-link pl-3" href="'. $relative_path . '/lessons"><span class="ml-1">Übersicht</span></a>
                    
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
					             <a class="nav-link pl-3" href="'. $relative_path . '/sick/"><span class="ml-1">Übersicht</span></a>
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
							<a class="nav-link pl-3" href="' . $relative_path . '/admin/accounts"><span class="ml-1">Benutzer</span></a>
							
                            <a class="nav-link pl-3" href="' . $relative_path . '/admin/settings"><span class="ml-1">Settings</span></a>
                            
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
        <div class="bottom-navbar-elements btn-box w-100 mt-4 mb-1">
            <div onclick="window.location='https://nauren.de'"                          class="pointer logo-footer-div"><img src="<?php echo $relative_path; ?>/img/nauren.svg"  alt="Nauren" class="logo-footer"></div>
            <div                                                                        class="logo-footer-div"><img src="<?php echo $relative_path; ?>/img/manu.svg" alt="Manu-Logo" class="logo-footer-manu"></div>
            <div                                                                        class="logo-footer-div"><img src="<?php echo $relative_path; ?>/img/phpstorm.svg"  alt="PayPal" class="logo-footer"></div>
            <div onclick="window.location='https://github.com/PalmarHealer/wochenplan'" class="pointer logo-footer-div"><img src="<?php echo $relative_path; ?>/img/github.svg"  alt="GitHub" class="logo-footer"></div>
        </div>
    </nav>
</aside>