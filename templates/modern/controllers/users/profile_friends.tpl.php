<?php

    $this->setPageTitle($tab['title'], $profile['nickname']);
    $this->setPageDescription($profile['nickname'].' — '.$tab['title']);

    if($this->controller->listIsAllowed()){
        $this->addBreadcrumb(LANG_USERS, href_to('users'));
    }
    $this->addBreadcrumb($profile['nickname'], $this->href_to($profile['id']));
    $this->addBreadcrumb($tab['title']);

?>

<?php $this->renderChild('profile_header', ['profile' => $profile, 'tabs' => $tabs, 'fields' => $fields]); ?>

<div id="user_content_list"><?php echo $profiles_list_html; ?></div>