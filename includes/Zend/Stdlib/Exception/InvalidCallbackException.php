<?php
/**
 * Zend Framework Modified for PHP-Nuke Titanium
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend for PHP-Nuke Titanium
 * @package    Zend_Stdlib
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

require_once NUKE_ZEND_DIR.'Stdlib/Exception.php';

/**
 * Invalid callback exception
 *
 * @category   Zend for PHP-Nuke Titanium
 * @package    Zend_Stdlib
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Stdlib_Exception_InvalidCallbackException
    extends DomainException
    implements Zend_Stdlib_Exception
{
}
