<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

    <p style="max-width:55em;"><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "GitHub", "Client ID", "Client secret"); ?></p>

    <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'GitHub App'); ?></h2>

    <ol>
        <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://github.com/settings/developers/" target="_blank">https://github.com/settings/developers/</a>'); ?></li>
        <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'GitHub'); ?></li>
        <li><?php printf(__('Make sure the <b>%1$s</b> tab is selected and click on the <b>%2$s</b> button.', 'nextend-facebook-connect'), 'OAuth Apps', 'Register a new application'); ?></li>
        <li><?php _e('Enter a name into the <b>Application name</b> field. Users will see this name, when they authorize your app at the OAuth consent screen!', 'nextend-facebook-connect'); ?></li>
        <li><?php printf(__('Fill <b>Homepage URL</b> with the url of your homepage, probably: <b>%s</b>', 'nextend-facebook-connect'), str_replace(parse_url(site_url(), PHP_URL_PATH), "", site_url())); ?></li>
        <li><?php _e('In the <b>Description</b> field you should explain what this App will be used for.', 'nextend-facebook-connect') ?></li>
        <li><?php printf(__('Add the following URL to the <b>Authorization callback URL</b> field: <b>%s</b>', 'nextend-facebook-connect'), $provider->getLoginUrl()) ?></li>
        <li><?php _e('Click the <b>Register application</b> button.', 'nextend-facebook-connect') ?></li>
        <li><?php _e('Find the necessary <b>Client ID</b> and <b>Client Secret</b> at the middle of the page. These will be needed in the plugin\'s settings!', 'nextend-facebook-connect') ?></li>
    </ol>

    <p><?php printf(__('<b>Important note:</b> The email address is only retrievable, if there is a public email address set at the %1$s profile page%2$s!', 'nextend-facebook-connect'), '<a href="https://github.com/settings/profile" target="_blank">', '</a>'); ?></p>

    <a href="<?php echo $this->getUrl('settings'); ?>"
       class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'GitHub App'); ?></a>

</div>