{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{literal}
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: "{/literal}{if isset($thnxwhatsup_number) && !empty($thnxwhatsup_number)}{$thnxwhatsup_number}{else}+1 (800) 123-4567{/if}{literal}",
            company_logo_url: "{/literal}{if isset($thnxwhatsup_image) && !empty($thnxwhatsup_image)}{$thnxwhatsup_image}{/if}{literal}",
            greeting_message: "{/literal}{if isset($thnxwhatsup_message) && !empty($thnxwhatsup_message)}{$thnxwhatsup_message}{else}Hello,how may we help you? Just send us a message now to get assistance.{/if}{literal}",
            call_to_action: "{/literal}{if isset($thnxwhatsup_action) && !empty($thnxwhatsup_action)}{$thnxwhatsup_action}{else}Message us{/if}{literal}",
            position: "{/literal}{if isset($thnxwhatsup_position) && !empty($thnxwhatsup_position)}{$thnxwhatsup_position}{else}right{/if}{literal}",
        };
        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>
{/literal}