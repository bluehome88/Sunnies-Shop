{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{strip}
<div id="tb-email-call-wrappre">
  <div id="contact-email">
    {* [1][/1] is for a HTML tag. *}
    

    {* [1][/1] is for a HTML tag. *}
    {if $contact_infos.email}
      {l
        s='[1]%email%[/1]'
        sprintf=[
          '[1]' => '<a href="mailto:'|cat:$contact_infos.email|cat:'" class="dropdown">',
          '[/1]' => '</a>',
          '%email%' => $contact_infos.email
        ]
        d='Shop.Theme.Global'
      }
    {/if}

  </div>
    <div class="tb-call-wrapper">
      {if $contact_infos.phone}
        {l
          s='[1]%phone%[/1]'
          sprintf=[
            '[1]' => '<span>',
            '[/1]' => '</span>',
            '%phone%' => $contact_infos.phone
          ]
          d='Shop.Theme.Global'
        }
      {/if}
    </div>
 
</div>
{/strip}
