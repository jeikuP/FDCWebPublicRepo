<!-- View/Elements/dropdown_menu.ctp -->

<div class="dropdown ml-3">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="oi oi-menu"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <?php echo $this->Html->link('<span class="oi oi-person"></span> Profile', array('controller' => 'users', 'action' => 'my_profile'), array('class' => 'dropdown-item', 'escape' => false)); ?>
        <?php echo $this->Html->link('<span class="oi oi-account-logout"></span> Logout', array('controller' => 'users', 'action' => 'logout'), array('class' => 'dropdown-item', 'escape' => false)); ?>
    </div>
</div>

