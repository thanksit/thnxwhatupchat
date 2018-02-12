<?php
/**
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
*/

class Thnxwhatsupchat extends Module
{
    protected static $setting_fields = array(
        'THNXWHATSUP_NUMBER',
        'THNXWHATSUP_POSITION'
    );
    protected static $setting_fields_langs = array(
        'THNXWHATSUP_MESSAGE',
        'THNXWHATSUP_ACTION'
    );
    public function __construct()
    {
        $this->name = 'thnxwhatsupchat';
        $this->author = 'thanksit.com';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Whatsup Chat Module for prestashop By thanksit.com');
        $this->description = $this->l('Whatsup Chat Module for prestashop By thanksit.com.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (parent::install()) {
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) {
                $this->registerHook('displayBeforeBodyClosingTag');
            } else {
                $this->registerHook('displayfooter');
            }
            return true;
        } else {
            return false;
        }
    }

    public function uninstall()
    {
        foreach (Thnxwhatsupchat::$setting_fields as $field) {
            Configuration::deleteByName($field);
        }
        foreach (Thnxwhatsupchat::$setting_fields_langs as $field_lang) {
            Configuration::deleteByName($field_lang);
        }
        return (parent::uninstall());
    }

    public function getContent()
    {
        $html = '';
        if (Tools::isSubmit('submit'.$this->name)) {
            $languages = Language::getLanguages(false);
            foreach (Thnxwhatsupchat::$setting_fields as $field) {
                Configuration::updateValue($field, Tools::getValue($field));
            }
            foreach (Thnxwhatsupchat::$setting_fields_langs as $field_lang) {
                foreach ($languages as $lang) {
                    Configuration::updateValue($field_lang.'_'.$lang['id_lang'], Tools::getValue($field_lang.'_'.$lang['id_lang']));
                }
            }
            $html = $this->displayConfirmation($this->l('Configuration updated'));
        }

        return $html.$this->renderForm();
    }

    public function preparehookexe($params)
    {
        $id_lang = (int)$this->context->language->id;
        $params = $params;
        $whatsup_logo_url = Context::getContext()->shop->getBaseURL().'modules/'.$this->name.'/views/img/whatsup.png';
        foreach (Thnxwhatsupchat::$setting_fields as $field) {
            $this->smarty->assign(Tools::strtolower($field), Configuration::get($field));
        }
        foreach (Thnxwhatsupchat::$setting_fields_langs as $field_lang) {
            $this->smarty->assign(Tools::strtolower($field_lang), Configuration::get($field_lang.'_'.$id_lang));
        }
        $this->smarty->assign('thnxwhatsup_image', $whatsup_logo_url);
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) {
            return $this->fetch('module:'.$this->name.'/views/templates/front/'.$this->name.'.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/front/'.$this->name.'.tpl');
        }
    }
    public function hookdisplayfooter($params)
    {
        return $this->preparehookexe($params);
    }

    public function hookdisplayBeforeBodyClosingTag($params)
    {
        return $this->preparehookexe($params);
    }
    
    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('WhatsApp'),
                        'name' => 'THNXWHATSUP_NUMBER',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Greeting Message'),
                        'name' => 'THNXWHATSUP_MESSAGE',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Call to Action'),
                        'name' => 'THNXWHATSUP_ACTION',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Partner offers subscribers'),
                        'name' => 'THNXWHATSUP_POSITION',
                        'options' => array(
                            'query' => array(
                                array('id' => 'left', 'name' => $this->l('Left')),
                                array('id' => 'right', 'name' => $this->l('Right')),
                            ),
                            'id' => 'id',
                            'name' => 'name',
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
         ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit'.$this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        foreach (Thnxwhatsupchat::$setting_fields as $field) {
            $helper->tpl_vars['fields_value'][$field] = Tools::getValue($field, Configuration::get($field));
        }
        $languages = Language::getLanguages(false);
        foreach (Thnxwhatsupchat::$setting_fields_langs as $field_lang) {
            foreach ($languages as $lang) {
                $helper->tpl_vars['fields_value'][$field_lang][$lang['id_lang']] = Tools::getValue($field_lang.'_'.$lang['id_lang'], Configuration::get($field_lang.'_'.$lang['id_lang']));
            }
        }
        return $helper->generateForm(array($fields_form));
    }
}
