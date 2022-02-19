<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?php echo lang('HOME'); ?></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?php echo lang('CATEGORIES'); ?></a></li>
        <li><a href="items.php"><?php echo lang('ITEMS'); ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['user']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="members.php?do=edit&userId=<?php echo $_SESSION['ID'] ?>"><?php echo lang('EDIT_PROFILE'); ?></a></li>
            <li><a href="#"><?php echo lang('SETTINGS'); ?></a></li>
            <li><a href="logout.php"><?php echo lang('LOGOUT'); ?></a></li>
            <!-- <li role="separator" class="divider"></li> -->
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>