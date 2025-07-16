<?php
/* ====================
[BEGIN_COT_EXT]
Code=pagereviews
Name=Page Reviews
Description=Отзывы к статьям
Version=1.0.0
Date=2025-05-10
Author=webitprof
Copyright=Cotonti
Notes=Requires Cotonti Siena v0.9.26 and PHP 8.2
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
Requires_modules=page,users
Recommends_modules=
Recommends_plugins=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
pageall=01:radio::1:Show all reviews on page details
reviews_per_page=02:string::10:Number of reviews per page on the reviews list
blacklistcattree=03:string::system,unvalidated,info:Черный список категорий через запятую
metatitletext=10:string::meta title text:meta title text
metadescrtext=11:string::meta descr text:meta name=description
[END_COT_EXT_CONFIG]
==================== */

/**
 * Page Reviews plugin
 * Filename: pagereviews.setup.php
 * @package pagereviews
 * @version 1.0.0
 * @author webitprof
 * @copyright Copyright (c) 2025
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL.');
?>