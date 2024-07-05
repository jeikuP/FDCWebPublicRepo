<!-- user_info.ctp -->
<?php
// Fetch logged-in user's details
$loggedInUser = $this->Session->read('Auth');
$userName = $loggedInUser['User']['name'];

if ($loggedInUser) {
    $profilePic = isset($loggedInUser['User']['profile_pic']) ? $this->Html->image('/' . $loggedInUser['User']['profile_pic'], ['class' => 'rounded-circle', 'width' => '40', 'height' => '40']) : '<div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;"><span class="oi oi-person" style="font-size: 20px;"></span></div>';
} else {
    $profilePic = '<div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;"><span class="oi oi-person" style="font-size: 20px;"></span></div>';
}
?>
<div class="d-flex align-items-center">
    
    <?php echo $profilePic; ?>
    <span class="ml-2"><?php echo $userName; ?></span>
</div>