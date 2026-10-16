<nav class="sidebar">
    <ul class="nav flex-column">
        <?php if($loginstaff[0]['Create']=="1"){ ?>
        <li class="nav-item"><a href="<?php echo SUPPORTURL."create.php";; ?>" id="create"><span class="fa fa-ticket"> </span><span class="title">Create Ticket</span></a></li>
        <? }
        if($_SESSION['acc_type']!="Client"){ ?>
        <li class="nav-item"><a href="<?php echo SUPPORTURL."home.php"; ?>" id="home"><span class="fa fa-folder-open"> </span> <span class="title">Open <span class="badge"><?php echo count($getopen); ?></span></span></a></li>
        <li class="nav-item"><a href="<?php echo SUPPORTURL."answered.php"; ?>" id="Answered"><span class="fa fa-pencil-square-o"></span><span class="title">Answered <span class="badge"><?php echo count($getans); ?></span></span></a></li>
        <li class="nav-item"><a href="<?php echo SUPPORTURL."mytickets.php"; ?>" id="My"><span class="fa fa-ticket"></span> <span class="title">My Tickets <span class="badge"><?php echo count($getmytkt); ?></span></span></a></li>
        <li class="nav-item"><a href="<?php echo SUPPORTURL."overdue.php";; ?>" id="overdue"><span class="fa fa-puzzle-piece"></span><span class="title">Overdue <span class="badge"><?php echo count($getoverdue); ?></span></span></a></li>
        <li class="nav-item"><a href="<?php echo SUPPORTURL."closed.php";; ?>" id="closed"><span class="fa fa-archive"> </span> <span class="title">Closed <span class="badge"><?php echo count($getclosed); ?></span></span></a></li>
        <!--<li><a href="<?php echo SUPPORTURL."guestlist.php";; ?>" id="guestlist">Guest Master</a></li> -->
        <?php } ?>
        <li class="nav-item d-block d-sm-none"><?php if($accesstoadminpanel=="1"){ ?> <a class="nav-link" href="<?php echo ADMINURL; ?>home.php"><span class="fa fa-user" aria-hidden="true"></span> <span class="title">Admin Panel</span></a><? } ?></li>
    </ul>
</nav>