<?php
/**
* 2007-2019 PrestaShop
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
*  @copyright 2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Core\Product\Search\Pagination;

class TbcmsBlogArchiveModuleFrontController extends TbcmsBlogMainModuleFrontController
{
    public $blogpost;
    public $blogcategory;
    public $tbcmserrors = array();
    public $id_identity;
    public $rewrite;
    public function init()
    {
        parent::init();
        $this->rewrite = Tools::getValue('rewrite');
        $subpage_type = Tools::getValue('subpage_type');
        $p = Tools::getValue('page');
        $this->p = isset($p) && !empty($p) ? $p : 1;
        $id_identity = Tools::getValue('id');
        if (!isset($id_identity) || empty($id_identity)) {
            $this->id_identity = (int)TbcmsCategoryClass::getTheId($this->rewrite, $this->page_type);
        } else {
            $this->id_identity = (int)$id_identity;
        }
        if (isset($this->id_identity)
            && !empty($this->id_identity)
            && !TbcmsCategoryClass::CategoryExists($this->id_identity, $this->page_type)) {
            $url = TbcmsBlog::tbcmsBlogLink();
            Tools::redirect($url);
            $this->tbcmserrors[] = Tools::displayError($this->l('Blog Category Not Found.'));
        }
        if ($this->page_type == 'tag') {
            $this->blogpost = TbcmsPostsClass::getTagPosts(
                (int)$this->id_identity,
                (int)$this->p,
                (int)$this->n,
                $subpage_type
            );
        } else {
            $this->blogpost = TbcmsPostsClass::getCategoryPosts(
                (int)$this->id_identity,
                (int)$this->p,
                (int)$this->n,
                $subpage_type
            );
        }
        if ($this->id_identity || Validate::isUnsignedId($this->id_identity)) {
            $this->blogcategory = new TbcmsCategoryClass($this->id_identity);
        }
        $this->nbProducts = (int)TbcmsPostsClass::getCategoryPostsCount((int)$this->id_identity, $subpage_type);
    }
    
    public function initContent()
    {
        parent::initContent();
        // print_r($this->getLayout());
        $id_lang = (int)Context::getContext()->language->id;
        $pagination = $this->getXprtPagination();
        $path = TbcmsCategoryClass::getCategoryPath($this->id_identity, $this->page_type);
        $this->context->smarty->assign('path', $path);
        $this->context->smarty->assign('pagination', $pagination);
        if (isset($this->blogpost) && !empty($this->blogpost)) {
            $this->context->smarty->assign('tbcmsblogpost', $this->blogpost);
        }
        if (isset($this->blogcategory->title[$id_lang]) && !empty($this->blogcategory->title[$id_lang])) {
            $this->context->smarty->assign('meta_title', $this->blogcategory->title[$id_lang]);
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $this->blogcategory->title[$id_lang];
        } else {
            $this->context->smarty->assign(
                'meta_title',
                Configuration::get(TbcmsBlog::$tbcmsblogshortname."meta_title", $id_lang)
            );
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = Configuration::get(
                TbcmsBlog::$tbcmsblogshortname."meta_title",
                $id_lang
            );
        }
        if (isset($this->blogcategory->meta_description[$id_lang])
            && !empty($this->blogcategory->meta_description[$id_lang])) {
            $this->context->smarty->assign('meta_description', $this->blogcategory->meta_description[$id_lang]);
        } else {
            $this->context->smarty->assign(
                'meta_description',
                Configuration::get(TbcmsBlog::$tbcmsblogshortname."meta_description")
            );
        }
        if (isset($this->blogcategory->keyword[$id_lang]) && !empty($this->blogcategory->keyword[$id_lang])) {
            $this->context->smarty->assign('meta_keywords', $this->blogcategory->keyword[$id_lang]);
        } else {
            $this->context->smarty->assign(
                'meta_keywords',
                Configuration::get(TbcmsBlog::$tbcmsblogshortname."meta_keyword")
            );
        }
        if (isset($this->tbcmserrors) && !empty($this->tbcmserrors)) {
            $this->context->smarty->assign('tbcmserrors', $this->tbcmserrors);
        }

        $tpl_prefix = '';
        $template = 'archive.tpl';
        if (!empty($this->page_type)) {
            $template1 = $this->page_type.'-'.'archive.tpl';
            if ($path = $this->getTemplatePath($template1)) {
                $template = $template1;
            } else {
                $template = 'archive.tpl';
            }
        }
        $this->setTemplate($template);
    }
    public function getLayout()
    {
        $entity = 'module-tbcmsblog-archive';
        $layout = $this->context->shop->theme->getLayoutRelativePathForPage($entity);
        if ($overridden_layout = Hook::exec(
            'overrideLayoutTemplate',
            array(
                'default_layout' => $layout,
                'entity' => $entity,
                'locale' => $this->context->language->locale,
                'controller' => $this,
            )
        )) {
            return $overridden_layout;
        }
        if ((int) Tools::getValue('content_only')) {
            $layout = 'layouts/layout-content-only.tpl';
        }
        return $layout;
    }
    public function updateXprtQueryString(array $extraParams = null)
    {
        $uriWithoutParams = explode('?', $_SERVER['REQUEST_URI'])[0];
        $url = Tools::getCurrentUrlProtocolPrefix().$_SERVER['HTTP_HOST'].$uriWithoutParams;
        $params = array();
        parse_str($_SERVER['QUERY_STRING'], $params);

        if (null !== $extraParams) {
            foreach ($extraParams as $key => $value) {
                if (null === $value) {
                    unset($params[$key]);
                } else {
                    $params[$key] = $value;
                }
            }
        }

        ksort($params);

        if (null !== $extraParams) {
            foreach ($params as $key => $param) {
                if (null === $param || '' === $param) {
                    unset($params[$key]);
                }
            }
        } else {
            $params = array();
        }

        $queryString = str_replace('%2F', '/', http_build_query($params));

        return $url.($queryString ? "?$queryString" : '');
    }

    public function getXprtPagination()
    {
        $pagination = new Pagination();
        $pagination
            ->setPage($this->p)
            ->setPagesCount(
                ceil($this->nbProducts / $this->n)
            );
        $totalItems = $this->nbProducts;
        $itemsShownFrom = ($this->n * ($this->p - 1)) + 1;
        $itemsShownTo = $this->n * $this->p;
        $link = array();
        return array(
            'total_items' => $totalItems,
            'items_shown_from' => $itemsShownFrom,
            'items_shown_to' => ($itemsShownTo <= $totalItems) ? $itemsShownTo : $totalItems,
            'pages' => array_map(function ($link) {
                $extraParams = array('page' => $link['page']);
                $uriWithoutParams = explode('?', $_SERVER['REQUEST_URI'])[0];
                $url = Tools::getCurrentUrlProtocolPrefix().$_SERVER['HTTP_HOST'].$uriWithoutParams;
                $params = array();
                parse_str($_SERVER['QUERY_STRING'], $params);

                if (null !== $extraParams) {
                    foreach ($extraParams as $key => $value) {
                        if (null === $value) {
                            unset($params[$key]);
                        } else {
                            $params[$key] = $value;
                        }
                    }
                }

                ksort($params);

                if (null !== $extraParams) {
                    foreach ($params as $key => $param) {
                        if (null === $param || '' === $param) {
                            unset($params[$key]);
                        }
                    }
                } else {
                    $params = array();
                }

                $queryString = str_replace('%2F', '/', http_build_query($params));

                // return $url.($queryString ? "?$queryString" : '');

                $link['url'] = $url.($queryString ? "?$queryString" : '');
                return $link;
            }, $pagination->buildLinks()),
        );
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $blog_title = Configuration::get(TbcmsBlog::$tbcmsblogshortname."meta_title");
        $breadcrumb['links'][] = array(
            'title' => $blog_title,
            'url' => TbcmsBlog::tbcmsBlogLink(),
        );
        $id_lang = (int)$this->context->language->id;

        if (isset($this->blogcategory->title[$id_lang]) && !empty($this->blogcategory->title[$id_lang])) {
            $category_name = $this->blogcategory->title[$id_lang];
        } elseif (isset($this->blogcategory->name[$id_lang]) && !empty($this->blogcategory->name[$id_lang])) {
            $category_name = $this->blogcategory->name[$id_lang];
        } else {
            $category_name = '';
        }
        $params = array();
        $params['id'] = $this->blogcategory->id_tbcmscategory ? $this->blogcategory->id_tbcmscategory : 0;

        if (isset($this->blogcategory->link_rewrite[$id_lang]) && !empty($this->blogcategory->link_rewrite[$id_lang])) {
            $params['rewrite'] = $this->blogcategory->link_rewrite[$id_lang];
        } else {
            $params['rewrite'] = 'category_blog_post';
        }

        $params['page_type'] = 'category';
        $params['subpage_type'] = 'post';
        $category_url = TbcmsBlog::tbcmsBlogCategoryLink($params);
        if (!empty($category_name)) {
            $breadcrumb['links'][] = array(
                'title' => $category_name,
                'url' => $category_url,
            );
        }

        return $breadcrumb;
    }
}
