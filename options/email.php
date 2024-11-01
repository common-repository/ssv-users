<?php
namespace mp_ssv_users\options;
use mp_ssv_general\SSV_General;
use mp_ssv_users\SSV_Users;

if (!defined('ABSPATH')) {
    exit;
}

if (SSV_General::isValidPOST(SSV_Users::ADMIN_REFERER_OPTIONS)) {
    if (isset($_POST['reset'])) {
        SSV_Users::resetOptions();
    } else {
        update_option(SSV_Users::OPTION_MEMBER_ADMINS, SSV_General::sanitize($_POST['members_admin'], 'int'));
        update_option(SSV_Users::OPTION_NEW_MEMBER_ADMIN_EMAIL, SSV_General::sanitize($_POST['email_admin_on_registration'], 'boolean'));
        update_option(SSV_Users::OPTION_NEW_MEMBER_REGISTRANT_EMAIL, SSV_General::sanitize($_POST['email_on_registration_status_changed'], 'boolean'));
    }
}
?>
<form method="post" action="#">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Members Admin</th>
            <td>
                <label>
                    <?php
                    $dropdown = wp_dropdown_users(
                        array(
                            'echo' => false,
                            'name' => 'members_admin[]',
                        )
                    );
                    if (is_array(get_option(SSV_Users::OPTION_MEMBER_ADMINS))) {
                        foreach (get_option(SSV_Users::OPTION_MEMBER_ADMINS) as $member_admin) {
                            $dropdown = str_replace(' value=\'' . $member_admin . '\'', ' value="' . $member_admin . '" selected="selected"', $dropdown);
                        }
                    }
                    $usersCount = count(get_users());
                    $size       = $usersCount > 25 ? 25 : $usersCount;
                    echo str_replace('id=', 'multiple="multiple" size="' . $size . '" id=', $dropdown);
                    ?>
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Email Admin</th>
            <td>
                <label>
                    <input type="hidden" name="email_admin_on_registration" value="false"/>
                    <input type="checkbox" name="email_admin_on_registration" value="true" <?= get_option(SSV_Users::OPTION_NEW_MEMBER_ADMIN_EMAIL) ? 'checked' : '' ?> />
                    When someone registers the Members Admin will receive an email.
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Email Registrant</th>
            <td>
                <label>
                    <input type="hidden" name="email_on_registration_status_changed" value="false"/>
                    <input type="checkbox" name="email_on_registration_status_changed" value="true" <?= get_option(SSV_Users::OPTION_NEW_MEMBER_REGISTRANT_EMAIL) ? 'checked' : '' ?>/>
                    When someone registers he/she will receive a confirmation email.
                </label>
            </td>
        </tr>
    </table>
    <?= SSV_General::getFormSecurityFields(SSV_Users::ADMIN_REFERER_OPTIONS); ?>
</form>
