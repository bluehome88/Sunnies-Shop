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

// fashion.jpg
// Lifestyle.jpg
// Entertainment.jpg
// blog.jpg
// blog1.jpg
// blog2.jpg
// blog3.jpg
// blog4.jpg
// blog5.jpg
// blog6.jpg
// large-blog31.jpg
// large-blog2.jpg
// large-blog1.jpg
$tbcmsblog_categories = array(
    array(
        'lang' => array(
            'name' => 'Fashion',
            'link_rewrite' => 'fashion',
            'title' => 'Fashion',
            'description' => 'Fashion',
            'meta_description' => 'Fashion',
            'keyword' => 'Fashion',
        ),
        'notlang' => array(
            'category_group' => 0,
            'category_img' => 'Fashion.jpg',
            'category_type' => 'category',
            'active' => 1,
        ),
    ),
    // array(
    //     'lang' => array(
    //         'name' => 'Lifestyle',
    //         'link_rewrite' => 'lifestyle',
    //         'title' => 'Lifestyle',
    //         'description' => 'Lifestyle',
    //         'meta_description' => 'Lifestyle',
    //         'keyword' => 'Lifestyle',
    //     ),
    //     'notlang' => array(
    //         'category_group' => 0,
    //         'category_img' => 'Lifestyle.jpg',
    //         'category_type' => 'category',
    //         'active' => 1,
    //     ),
    // ),
    // array(
    //     'lang' => array(
    //         'name' => 'Entertainment',
    //         'link_rewrite' => 'entertainment',
    //         'title' => 'Entertainment',
    //         'description' => 'Entertainment',
    //         'meta_description' => 'Entertainment',
    //         'keyword' => 'Entertainment',
    //     ),
    //     'notlang' => array(
    //         'category_group' => 0,
    //         'category_img' => 'Entertainment.jpg',
    //         'category_type' => 'category',
    //         'active' => 1,
    //     ),
    // ),
);
$tbcmsblog_posts = array(
    array
    (
        'notlang' => array
            (
                // 'id_tbcmsposts' => 1
                'post_author' => 0,
                'post_date' => '2016-03-28 11:40:27',
                'comment_status' => 'open',
                'post_password' => '',
                'post_modified' => '2016-04-03 15:11:26',
                'post_parent' => 0,
                'category' => 1,
                'post_type' => 'post',
                'post_format' => 'standrad',
                'post_img' => 'blog-1.jpg',
                'video' => '',
                'audio' => '',
                'gallery' => '',
                'related_products' => '0',
                'comment_count' => 0,
                'active' => 1,
                // 'id_lang' => 1
            ),
        'lang' => array
            (
                'post_title' => 'This is First Post For TemplateBeta',
                'meta_title' => 'This is First Post For TemplateBeta',
                'meta_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting'
                    .' industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,'
                    .' when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'meta_keyword' => 'Lorem,Ipsum,simply,dummy',
                'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation'
                    .' ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep rehenderit. '
                    .'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor incididunt ut '
                    .'labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do '
                    .'eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, '
                    .'consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit. Lorem ipsum dolor sit amet, consectetur '
                    .'adipisicing elit, sed do eiumod tempor incididunt ut labore et dolore magna aliqua. Lorem '
                    .'ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore '
                    .'et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod '
                    .'tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud '
                    .'exercitation ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep'
                    .' rehenderit. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing'
                    .' elit, do eiusmod tempor cididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet,'
                    .' consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit.',
                'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'
                    .' Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer'
                    .' took a galley of type and scrambled it to make a type specimen book.',
                'link_rewrite' => 'this-is-first-post-for-tbcmsblog',
            ),
    ),
    array
    (
        'notlang' => array
            (
                // 'id_tbcmsposts' => 2
                'post_author' => 0,
                'post_date' => '2016-03-28 11:40:27',
                'comment_status' => 'open',
                'post_password' => '',
                'post_modified' => '2016-04-02 10:04:26',
                'post_parent' => 0,
                'category' => 1,
                'post_type' => 'post',
                'post_format' => 'Standrad',
                'post_img' => 'blog-2.jpg',
                'video' => 'https://www.youtube.com/embed/Fn3WTX6pYmE',
                'audio' => '',
                'gallery' => '',
                'related_products' => '0',
                'comment_count' => 0,
                'active' => 1,
                // 'id_lang' => 1
            ),
        'lang' => array
            (
                'post_title' => 'This is Second Post For TemplateBeta',
                'meta_title' => 'This is Second Post For TemplateBeta',
                'meta_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting'
                    .' industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,'
                    .' when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'meta_keyword' => 'Lorem,Ipsum,simply,dummy',
                'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation'
                    .' ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep rehenderit. '
                    .'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor incididunt ut '
                    .'labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do '
                    .'eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, '
                    .'consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit. Lorem ipsum dolor sit amet, consectetur '
                    .'adipisicing elit, sed do eiumod tempor incididunt ut labore et dolore magna aliqua. Lorem '
                    .'ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore '
                    .'et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod '
                    .'tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud '
                    .'exercitation ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep'
                    .' rehenderit. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing'
                    .' elit, do eiusmod tempor cididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet,'
                    .' consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit.',
                'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'
                    .' Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer'
                    .' took a galley of type and scrambled it to make a type specimen book.',
                'link_rewrite' => 'this-is-secound-post-for-tbcmsblog',
            ),
    ),
    array
    (
        'notlang' => array
            (
                // 'id_tbcmsposts' => 3
                'post_author' => 0,
                'post_date' => '2016-03-28 11:40:28',
                'comment_status' => 'open',
                'post_password' => '',
                'post_modified' => '2016-04-02 10:04:33',
                'post_parent' => 0,
                'category' => 1,
                'post_type' => 'post',
                'post_format' => 'Standrad',
                'post_img' => 'blog-3.jpg',
                'video' => '',
                'audio' => '',
                'gallery' => '',
                'related_products' => '0',
                'comment_count' => 0,
                'active' => 1,
                // 'id_lang' => 1
            ),
        'lang' => array
            (
                'post_title' => 'This is Third Post For TemplateBeta',
                'meta_title' => 'This is Third Post For TemplateBeta',
                'meta_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting'
                    .' industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,'
                    .' when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'meta_keyword' => 'Lorem,Ipsum,simply,dummy',
                'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation'
                    .' ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep rehenderit. '
                    .'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor incididunt ut '
                    .'labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do '
                    .'eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, '
                    .'consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit. Lorem ipsum dolor sit amet, consectetur '
                    .'adipisicing elit, sed do eiumod tempor incididunt ut labore et dolore magna aliqua. Lorem '
                    .'ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore '
                    .'et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod '
                    .'tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud '
                    .'exercitation ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep'
                    .' rehenderit. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing'
                    .' elit, do eiusmod tempor cididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet,'
                    .' consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit.',
                'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'
                    .' Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer'
                    .' took a galley of type and scrambled it to make a type specimen book.',
                'link_rewrite' => 'this-is-third-post-for-tbcmsblog',
            ),
    ),
    array
    (
        'notlang' => array
            (
                // 'id_tbcmsposts' => 4
                'post_author' => 0,
                'post_date' => '2016-03-28 11:40:28',
                'comment_status' => 'open',
                'post_password' => '',
                'post_modified' => '2016-04-02 10:04:40',
                'post_parent' => 0,
                'category' => 1,
                'post_type' => 'post',
                'post_format' => 'standrad',
                'post_img' => 'blog-4.jpg',
                'video' => '',
                'audio' => '',
                'gallery' => '',
                'related_products' => '0',
                'comment_count' => 0,
                'active' => 1,
                // 'id_lang' => 1
            ),
        'lang' => array
            (
                'post_title' => 'This is Fourth Post For TemplateBeta',
                'meta_title' => 'This is Fourth Post For TemplateBeta',
                'meta_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting'
                    .' industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,'
                    .' when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'meta_keyword' => 'Lorem,Ipsum,simply,dummy',
                'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation'
                    .' ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep rehenderit. '
                    .'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor incididunt ut '
                    .'labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do '
                    .'eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, '
                    .'consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit. Lorem ipsum dolor sit amet, '
                    .'adipisicing elit, sed do eiumod tempor incididunt ut labore et dolore magna aliqua. Lorem '
                    .'ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore '
                    .'et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod '
                    .'tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud '
                    .'exercitation ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep'
                    .' rehenderit. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor '
                    .'incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur '
                    .' elit, do eiusmod tempor cididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit'
                    .' consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
                    .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
                    .'consequat. Duis aute irure dolor in rep rehenderit.',
                'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'
                    .' Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown '
                    .' took a galley of type and scrambled it to make a type specimen book.',
                'link_rewrite' => 'this-is-fourth-post-for-tbcmsblog',
            ),
    ),
    // array
    // (
    //     'notlang' => array
    //         (
    //             // 'id_tbcmsposts' => 5
    //             'post_author' => 0,
    //             'post_date' => '2016-03-28 11:40:28',
    //             'comment_status' => 'open',
    //             'post_password' => '',
    //             'post_modified' => '2016-04-02 10:04:33',
    //             'post_parent' => 0,
    //             'category' => 1,
    //             'post_type' => 'post',
    //             'post_format' => 'standrad',
    //             'post_img' => 'blog-5.jpg',
    //             'video' => '',
    //             'audio' => '',
    //             'gallery' => '',
    //             'related_products' => '0',
    //             'comment_count' => 0,
    //             'active' => 1,
    //             // 'id_lang' => 1
    //         ),
    //     'lang' => array
    //         (
    //             'post_title' => 'This is Fourth Post For TemplateBeta',
    //             'meta_title' => 'This is Fourth Post For TemplateBeta',
    //             'meta_description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting'
    //                 .' industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,'
    //                 .' when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
    //             'meta_keyword' => 'Lorem,Ipsum,simply,dummy',
    //             'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor '
    //                 .'incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation'
    //                 .' ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep rehenderit. '
    //                 .'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor incididunt ut '
    //                 .'labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do '
    //                 .'eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, '
    //                 .'consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
    //                 .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
    //                 .'consequat. Duis aute irure dolor in rep rehenderit. Lorem ipsum dolor sit amet, consectetur '
    //                 .'adipisicing elit, sed do eiumod tempor incididunt ut labore et dolore magna aliqua. Lorem '
    //                 .'ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore '
    //                 .'et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod '
    //                 .'tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud '
    //                 .'exercitation ullamco laboris nisi ut aliquip commodo consequat. Duis aute irure dolor in rep'
    //                 .' rehenderit. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiumod tempor '
    //                 .'incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur '
    //                 .' elit, do eiusmod tempor cididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit '
    //                 .' consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. '
    //                 .'Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo '
    //                 .'consequat. Duis aute irure dolor in rep rehenderit.',
    //             'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'
    //                 .' Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown '
    //                 .' took a galley of type and scrambled it to make a type specimen book.',
    //             'link_rewrite' => 'this-is-fourth-post-for-tbcmsblog',
    //         ),
    // ),
);

$tbcmsblog_imagetype = array(
    // array(
    //     'notlang' => array(
    //         'name' => 'tiny',
    //         'width' => 50,
    //         'height' => 50,
    //         'id_shop' => 1,
    //         'active' => 1,
    //     ),
    // ),
    array(
        'notlang' => array(
            'name' => 'small',
            'width' => 260,
            'height' => 260,
            'id_shop' => 1,
            'active' => 1,
        ),
    ),
    // array(
    //     'notlang' => array(
    //         'name' => 'home',
    //         'width' =>  370,
    //         'height' => 370,
    //         'id_shop' => 1,
    //         'active' => 1,
    //     ),
    // ),
    array(
        'notlang' => array(
            'name' => 'medium',
            'width' =>  770,
            'height' => 451,
            'id_shop' => 1,
            'active' => 1,
        ),
    ),
    // array(
    //     'notlang' => array(
    //         'name' => 'large',
    //         'width' =>  770,
    //         'height' => 607,
    //         'id_shop' => 1,
    //         'active' => 1,
    //     ),
    // ),
);
