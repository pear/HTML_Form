<?php

// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Stig Bakken <ssb@fast.no>                                   |
// |          Urs Gehrig <urs@circle.ch>                                  |
// |          Daniel Convissor <danielc@php.net>                          |
// +----------------------------------------------------------------------+
//
// $Id$
//
// HTML form utility functions.


// TODO
// * add $class parameter/tag for add*() and *Row() methods which will
//       then get inserted into the <th> and <td> tags.


if (!defined('HTML_FORM_TEXT_SIZE')) {
    define('HTML_FORM_TEXT_SIZE', 20);
}

if (!defined('HTML_FORM_MAX_FILE_SIZE')) {
    define('HTML_FORM_MAX_FILE_SIZE', 1048576); // 1 MB
}

if (!defined('HTML_FORM_PASSWD_SIZE')) {
    define('HTML_FORM_PASSWD_SIZE', 8);
}

if (!defined('HTML_FORM_TEXTAREA_WT')) {
    define('HTML_FORM_TEXTAREA_WT', 40);
}

if (!defined('HTML_FORM_TEXTAREA_HT')) {
    define('HTML_FORM_TEXTAREA_HT', 5);
}

/**
 * HTML form utility functions
 *
 * @category HTML
 * @package  HTML_Form
 * @author   Stig Bakken <ssb@fast.no>
 * @author   Urs Gehrig <urs@circle.ch>
 * @author   Daniel Convissor <danielc@php.net>
 * @version  $Id$
 * @access   public
 */
class HTML_Form
{
    // {{{ properties

    /**
     * ACTION attribute of <form> tag
     * @var string
     */
    var $action;

    /**
     * METHOD attribute of <form> tag
     * @var string
     */
    var $method;

    /**
     * NAME attribute of <form> tag
     * @var string
     */
    var $name;

    /**
     * an array of entries for this form
     * @var array
     */
    var $fields;

    /**
     * DB_storage object, if tied to one
     */
    var $storageObject;

    /**
     * TARGET attribute of <form> tag
     * @var string
     */
    var $target;

    /**
     * ENCTYPE attribute of <form> tag
     * @var string
     */
    var $enctype;

    /**
     * additional attributes for <form> tag
     *
     * @var string
     * @since Property available since Release 1.1.0
     */
    var $attr;


    // }}}
    // {{{ constructor

    /**
     * Constructor
     *
     * @param string $action  the string naming file or URI to which the form
     *                         should be submitted
     * @param string $method  a string indicating the submission method
     *                         ('get' or 'post')
     * @param string $name    a string used in the <form>'s 'name' attribute
     * @param string $target  a string used in the <form>'s 'target' attribute
     * @param string $enctype a string indicating the submission's encoding
     * @param string $attr    a string of additional attributes to be put
     *                         in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     */
    function HTML_Form($action, $method = 'get', $name = '', $target = '',
                       $enctype = '', $attr = '')
    {
        $this->action = $action;
        $this->method = $method;
        $this->name = $name;
        $this->fields = array();
        $this->target = $target;
        $this->enctype = $enctype;
        $this->attr = $attr;
    }


    // ===========  ADD  ===========

    // }}}
    // {{{ addText()

    /**
     * Adds a text input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addText($name, $title, $default = '',
                     $size = HTML_FORM_TEXT_SIZE, $maxlength = 0,
                     $attr = '')
    {
        $this->fields[] = array('text', $name, $title, $default, $size,
                                $maxlength, $attr);
    }

    // }}}
    // {{{ addPassword()

    /**
     * Adds a password input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addPassword($name, $title, $default = '',
                         $size = HTML_FORM_PASSWD_SIZE,
                         $maxlength = 0, $attr = '')
    {
        $this->fields[] = array('password', $name, $title, $default, $size,
                                $maxlength, $attr);
    }

    // }}}
    // {{{ addCheckbox()

    /**
     * Adds a checkbox input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addCheckbox($name, $title, $default = false, $attr = '')
    {
        $this->fields[] = array('checkbox', $name, $title, $default, $attr);
    }

    // }}}
    // {{{ addTextarea()

    /**
     * Adds a textarea input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $width     an integer saying how many characters wide
     *                           the item should be
     * @param int    $height    an integer saying how many rows tall the
     *                           item should be
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addTextarea($name, $title, $default = '',
                         $width = HTML_FORM_TEXTAREA_WT,
                         $height = HTML_FORM_TEXTAREA_HT, $maxlength = 0,
                         $attr = '')
    {
        $this->fields[] = array('textarea', $name, $title, $default, $width,
                                $height, $maxlength, $attr);
    }

    // }}}
    // {{{ addSubmit()

    /**
     * Adds a submit button to the list of fields to be processed by display()
     *
     * @param string $name      a string used in the 'name' attribute
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addSubmit($name = 'submit', $title = 'Submit Changes',
                       $attr = '')
    {
        $this->fields[] = array('submit', $name, $title, $attr);
    }

    // }}}
    // {{{ addReset()

    /**
     * Adds a reset button to the list of fields to be processed by display()
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addReset($title = 'Discard Changes', $attr = '')
    {
        $this->fields[] = array('reset', $title, $attr);
    }

    // }}}
    // {{{ addSelect()

    /**
     * Adds a select list to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param array  $entries   an array containing the <options> to be listed.
     *                           The array's keys become the option values and
     *                           the array's values become the visible text.
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer saying how many rows should be
     * @param string $blank     if this string is present, an <option> will be
     *                           added to the top of the list that will contain
     *                           the given text in the visible portion and an
     *                           empty string as the value
     * @param bool   $multiple  a bool saying if multiple choices are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addSelect($name, $title, $entries, $default = '', $size = 1,
                       $blank = '', $multiple = false, $attr = '')
    {
        $this->fields[] = array('select', $name, $title, $entries, $default,
                                $size, $blank, $multiple, $attr);
    }

    // }}}
    // {{{ addRadio()

    /**
     * Adds a radio input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param string $value     the string used for the item's value
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addRadio($name, $title, $value, $default = false, $attr = '')
    {
        $this->fields[] = array('radio', $name, $title, $value, $default,
                                $attr);
    }

    // }}}
    // {{{ addImage()

    /**
     * Adds an image input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param string $src       the string denoting the path to the image.
     *                           Can be a relative path or full URI.
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addImage($name, $title, $src, $attr = '')
    {
        $this->fields[] = array('image', $name, $title, $src, $attr);
    }

    // }}}
    // {{{ addHidden()

    /**
     * Adds a hiden input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $value     the string used for the item's value
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addHidden($name, $value, $attr = '')
    {
        $this->fields[] = array('hidden', $name, $value, $attr);
    }

    // }}}
    // {{{ addBlank()

    /**
     * Adds a blank row to the list of fields to be processed by display()
     *
     * @param int    $i         the number of rows to create.  Ignored if
     *                           $title is used.
     * @param string $title     a string to be used as the label for the row
     *
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addBlank($i, $title = '')
    {
        $this->fields[] = array('blank', $i, $title);
    }

    // }}}
    // {{{ addFile

    /**
     * Adds a file upload input to the list of fields to be processed by display()
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param int    $maxsize   an integer determining how large (in bytes) a
     *                           submitted file can be.
     * @param int    $size      an integer used in the 'size' attribute
     * @param string $accept    a string saying which MIME types are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addFile($name, $title, $maxsize = HTML_FORM_MAX_FILE_SIZE,
                     $size = HTML_FORM_TEXT_SIZE, $accept = '', $attr = '')
    {
        $this->enctype = "multipart/form-data";
        $this->fields[] = array('file', $name, $title, $maxsize, $size,
                                $accept, $attr);
    }

    // }}}
    // {{{ addPlaintext()

    /**
     * Adds a row of text to the list of fields to be processed by display()
     *
     * @param string $title     the string used as the label
     * @param string $text      a string to be displayed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @see HTML_Form::display()
     */
    function addPlaintext($title, $text = '&nbsp;', $attr = '')
    {
        $this->fields[] = array('plaintext', $title, $text, $attr);
    }


    // ===========  DISPLAY  ===========

    // }}}
    // {{{ start()

    /**
     * Prints the opening tags for the form and table
     *
     * NOTE: can NOT be called statically.
     *
     * @param bool $multipartformdata  a bool indicating if the form should
     *                                  be submitted in multipart format
     * @return void
     *
     * @access public
     */
    function start($multipartformdata = false)
    {
        print $this->returnStart($multipartformdata);
    }

    // }}}
    // {{{ end()

    /**
     * Prints the ending tags for the table and form
     *
     * NOTE: can NOT be called statically.
     *
     * @return void
     *
     * @access public
     */
    function end()
    {
        print $this->returnEnd();
    }

    // }}}
    // {{{ displayText()

    /**
     * Prints a text input element
     *
     * @param string $name      the string used in the 'name' attribute
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayText($name, $default = '', $size = HTML_FORM_TEXT_SIZE,
                         $maxlength = 0, $attr = '')
    {
        print HTML_Form::returnText($name, $default, $size, $maxlength, $attr);
    }

    // }}}
    // {{{ displayTextRow()

    /**
     * Prints a text input element inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayTextRow($name, $title, $default = '',
                            $size = HTML_FORM_TEXT_SIZE, $maxlength = 0,
                            $attr = '')
    {
        print HTML_Form::returnTextRow($name, $title, $default, $size,
                                       $maxlength, $attr);
    }

    // }}}
    // {{{ displayPassword()

    /**
     * Prints a password input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayPassword($name, $default = '',
                             $size = HTML_FORM_PASSWD_SIZE,
                             $maxlength = 0, $attr = '')
    {
        print HTML_Form::returnPassword($name, $default, $size, $maxlength,
                                        $attr);
    }

    // }}}
    // {{{ displayPasswordRow()

    /**
     * Prints a password input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayPasswordRow($name, $title, $default = '',
                                $size = HTML_FORM_PASSWD_SIZE,
                                $maxlength = 0, $attr = '')
    {
        print HTML_Form::returnPasswordRow($name, $title, $default,
                                           $size, $maxlength, $attr);
    }

    // }}}
    // {{{ displayCheckbox()

    /**
     * Prints a checkbox input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayCheckbox($name, $default = false, $attr = '')
    {
        print HTML_Form::returnCheckbox($name, $default, $attr);
    }

    // }}}
    // {{{ displayCheckboxRow()

    /**
     * Prints a checkbox input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayCheckboxRow($name, $title, $default = false, $attr = '')
    {
        print HTML_Form::returnCheckboxRow($name, $title, $default, $attr);
    }

    // }}}
    // {{{ displayTextarea()

    /**
     * Prints a textarea input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param mixed  $default   a default value for the element
     * @param int    $width     an integer saying how many characters wide
     *                           the item should be
     * @param int    $height    an integer saying how many rows tall the
     *                           item should be
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayTextarea($name, $default = '', $width = 40,
                             $height = 5, $maxlength  = '', $attr = '')
    {
        print HTML_Form::returnTextarea($name, $default, $width, $height,
                                        $maxlength, $attr);
    }

    // }}}
    // {{{ displayTextareaRow()

    /**
     * Prints a textarea input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $width     an integer saying how many characters wide
     *                           the item should be
     * @param int    $height    an integer saying how many rows tall the
     *                           item should be
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayTextareaRow($name, $title, $default = '', $width = 40,
                                $height = 5, $maxlength = 0, $attr = '')
    {
        print HTML_Form::returnTextareaRow($name, $title, $default, $width,
                                           $height, $maxlength, $attr);
    }

    // }}}
    // {{{ displaySubmit()

    /**
     * Prints a submit button
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $name      a string used in the 'name' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displaySubmit($title = 'Submit Changes', $name = 'submit',
                           $attr = '')
    {
        print HTML_Form::returnSubmit($title, $name, $attr);
    }

    // }}}
    // {{{ displaySubmitRow()

    /**
     * Prints a submit button inside a table row
     *
     * @param string $name      a string used in the 'name' attribute
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displaySubmitRow($name = 'submit', $title = 'Submit Changes',
                              $attr = '')
    {
        print HTML_Form::returnSubmitRow($name, $title, $attr);
    }

    // }}}
    // {{{ displayReset()

    /**
     * Prints a reset button
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayReset($title = 'Clear contents', $attr = '')
    {
        print HTML_Form::returnReset($title, $attr);
    }

    // }}}
    // {{{ displayResetRow()

    /**
     * Prints a reset button inside a table row
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayResetRow($title = 'Clear contents', $attr = '')
    {
        print HTML_Form::returnResetRow($title, $attr);
    }

    // }}}
    // {{{ displaySelect()

    /**
     * Prints a select list
     *
     * @param string $name      the string used in the 'name' attribute
     * @param array  $entries   an array containing the <options> to be listed.
     *                           The array's keys become the option values and
     *                           the array's values become the visible text.
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer saying how many rows should be
     * @param string $blank     if this string is present, an <option> will be
     *                           added to the top of the list that will contain
     *                           the given text in the visible portion and an
     *                           empty string as the value
     * @param bool   $multiple  a bool saying if multiple choices are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displaySelect($name, $entries, $default = '', $size = 1,
                           $blank = '', $multiple = false, $attr = '')
    {
        print HTML_Form::returnSelect($name, $entries, $default, $size,
                                      $blank, $multiple, $attr);
    }

    // }}}
    // {{{ displaySelectRow()

    /**
     * Prints a select list inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param array  $entries   an array containing the <options> to be listed.
     *                           The array's keys become the option values and
     *                           the array's values become the visible text.
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer saying how many rows should be
     * @param string $blank     if this string is present, an <option> will be
     *                           added to the top of the list that will contain
     *                           the given text in the visible portion and an
     *                           empty string as the value
     * @param bool   $multiple  a bool saying if multiple choices are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displaySelectRow($name, $title, $entries, $default = '',
                              $size = 1, $blank = '', $multiple = false,
                              $attr = '')
    {
        print HTML_Form::returnSelectRow($name, $title, $entries, $default,
                                         $size, $blank, $multiple, $attr);
    }

    // }}}
    // {{{ displayImage()

    /**
     * Prints an image input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $src       the string denoting the path to the image.
     *                           Can be a relative path or full URI.
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function displayImage($name, $src, $attr = '')
    {
        print HTML_Form::returnImage($name, $src, $attr);
    }

    // }}}
    // {{{ displayImageRow()

    /**
     * Prints an image input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param string $src       the string denoting the path to the image.
     *                           Can be a relative path or full URI.
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function displayImageRow($name, $title, $src, $attr = '')
    {
        print HTML_Form::returnImageRow($name, $title, $src, $attr);
    }

    // }}}
    // {{{ displayHidden()

    /**
     * Prints a hiden input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $value     the string used for the item's value
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayHidden($name, $value, $attr = '')
    {
        print HTML_Form::returnHidden($name, $value, $attr);
    }

    // }}}

    // assuming that $default is the 'checked' attribut of the radio tag

    // {{{ displayRadio()

    /**
     * Prints a radio input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $value     the string used for the item's value
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayRadio($name, $value, $default = false, $attr = '')
    {
        print HTML_Form::returnRadio($name, $value, $default, $attr);
    }

    // }}}
    // {{{ displayRadioRow()

    /**
     * Prints a radio input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param string $value     the string used for the item's value
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayRadioRow($name, $title, $value, $default = false,
                             $attr = '')
    {
        print HTML_Form::returnRadioRow($name, $title, $value, $default,
                                        $attr);
    }

    // }}}
    // {{{ displayBlank()

    /**
     * Prints &nbsp;
     *
     * @return void
     *
     * @access public
     * @static
     */
    function displayBlank()
    {
        print HTML_Form::returnBlank();
    }

    // }}}
    // {{{ displayBlankRow()

    /**
     * Prints a blank row in the table
     *
     * @param int    $i         the number of rows to create.  Ignored if
     *                           $title is used.
     * @param string $title     a string to be used as the label for the row
     *
     * @return void
     *
     * @access public
     * @static
     */
    function displayBlankRow($i, $title= '')
    {
        print HTML_Form::returnBlankRow($i, $title);
    }

    // }}}
    // {{{ displayFile()

    /**
     * Prints a file upload input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param int    $maxsize   an integer determining how large (in bytes) a
     *                           submitted file can be.
     * @param int    $size      an integer used in the 'size' attribute
     * @param string $accept    a string saying which MIME types are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayFile($name, $maxsize = HTML_FORM_MAX_FILE_SIZE,
                         $size = HTML_FORM_TEXT_SIZE, $accept = '',
                         $attr = '')
    {
        print HTML_Form::returnFile($name, $maxsize, $size, $accept, $attr);
    }

    // }}}
    // {{{ displayFileRow()

    /**
     * Prints a file upload input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param int    $maxsize   an integer determining how large (in bytes) a
     *                           submitted file can be.
     * @param int    $size      an integer used in the 'size' attribute
     * @param string $accept    a string saying which MIME types are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayFileRow($name, $title, $maxsize = HTML_FORM_MAX_FILE_SIZE,
                            $size = HTML_FORM_TEXT_SIZE, $accept = '',
                            $attr = '')
    {
        print HTML_Form::returnFileRow($name, $title, $maxsize,
                                       $size, $accept, $attr);
    }

    // }}}
    // {{{ displayPlaintext()

    /**
     * Prints the text provided
     *
     * @param string $text      a string to be displayed
     *
     * @return void
     *
     * @access public
     * @static
     */
    function displayPlaintext($text = '&nbsp;')
    {
        print $text;
    }

    // }}}
    // {{{ displayPlaintextRow()

    /**
     * Prints the text provided inside a table row
     *
     * @param string $title     the string used as the label
     * @param string $text      a string to be displayed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return void
     *
     * @access public
     * @static
     */
    function displayPlaintextRow($title, $text = '&nbsp;', $attr = '')
    {
        print HTML_Form::returnPlaintextRow($title, $text, $attr);
    }


    // ===========  RETURN  ===========

    // }}}
    // {{{ returnText()

    /**
     * Produce a string containing a text input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnText($name, $default = '', $size = HTML_FORM_TEXT_SIZE,
                        $maxlength = 0, $attr = '')
    {
        $str  = '<input type="text" name="' . $name . '" ';
        $str .= 'size="' . $size . '" value="' . $default . '" ';
        if ($maxlength) {
            $str .= 'maxlength="' . $maxlength. '" ';
        }
        return $str . $attr . "/>\n";
    }

    // }}}
    // {{{ returnTextRow()

    /**
     * Produce a string containing a text input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnTextRow($name, $title, $default = '',
                           $size = HTML_FORM_TEXT_SIZE, $maxlength = 0,
                           $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\">$title:</th>\n";
        $str .= "  <td>\n   ";
        $str .= HTML_Form::returnText($name, $default, $size, $maxlength,
                                      $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnPassword()

    /**
     * Produce a string containing a password input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnPassword($name, $default = '',
                            $size = HTML_FORM_PASSWD_SIZE,
                            $maxlength = 0, $attr = '')
    {
        $str  = '<input type="password" name="' . $name . '" ';
        $str .= 'size="' . $size . '" value="' . $default . '" ';
        if ($maxlength) {
            $str .= 'maxlength="' . $maxlength. '" ';
        }
        return $str . $attr . "/>\n";
    }

    // }}}
    // {{{ returnPasswordRow()

    /**
     * Produce a string containing a password input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer used in the 'size' attribute
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnPasswordRow($name, $title, $default = '',
                               $size = HTML_FORM_PASSWD_SIZE,
                               $maxlength = 0, $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\">$title:</th>\n";
        $str .= "  <td>\n   ";
        $str .= HTML_Form::returnPassword($name, $default, $size,
                                          $maxlength, $attr);
        $str .= "   repeat: ";
        $str .= HTML_Form::returnPassword($name.'2', $default, $size,
                                          $maxlength, $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnCheckbox()

    /**
     * Produce a string containing a checkbox input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnCheckbox($name, $default = false, $attr = '')
    {
        $str = "<input type=\"checkbox\" name=\"$name\"";
        if ($default && $default !== 'off') {
            $str .= ' checked="checked"';
        }
        return $str . ' ' . $attr . "/>\n";
    }

    // }}}
    // {{{ returnCheckboxRow()

    /**
     * Produce a string containing a checkbox input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnCheckboxRow($name, $title, $default = false, $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\">$title:</th>\n";
        $str .= "  <td>\n   ";
        $str .= HTML_Form::returnCheckbox($name, $default, $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnTextarea()

    /**
     * Produce a string containing a textarea input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param mixed  $default   a default value for the element
     * @param int    $width     an integer saying how many characters wide
     *                           the item should be
     * @param int    $height    an integer saying how many rows tall the
     *                           item should be
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnTextarea($name, $default = '', $width = 40, $height = 5,
                            $maxlength = 0, $attr = '')
    {
        $str  = '<textarea name="' . $name . '" cols="' . $width . '"';
        $str .= ' rows="' . $height . '" ';
        if ($maxlength) {
            $str .= 'maxlength="' . $maxlength. '" ';
        }
        $str .=  $attr . '>';
        $str .= $default;
        $str .= "</textarea>\n";

        return $str;
    }

    // }}}
    // {{{ returnTextareaRow()

    /**
     * Produce a string containing a textarea input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param mixed  $default   a default value for the element
     * @param int    $width     an integer saying how many characters wide
     *                           the item should be
     * @param int    $height    an integer saying how many rows tall the
     *                           item should be
     * @param int    $maxlength an integer used in the 'maxlength' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnTextareaRow($name, $title, $default = '', $width = 40,
                               $height = 5, $maxlength = 0, $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\">$title:</th>\n";
        $str .= "  <td>\n   ";
        $str .= HTML_Form::returnTextarea($name, $default, $width, $height,
                                      $maxlength, $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnSubmit()

    /**
     * Produce a string containing a submit button
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $name      a string used in the 'name' attribute
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnSubmit($title = 'Submit Changes', $name = 'submit',
                          $attr = '')
    {
        return '<input type="submit" name="' . $name . '"'
               . ' value="' . $title . '" ' . $attr . "/>\n";
    }

    // }}}
    // {{{ returnSubmitRow()

    /**
     * Produce a string containing a submit button inside a table row
     *
     * @param string $name      a string used in the 'name' attribute
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnSubmitRow($name = 'submit', $title = 'Submit Changes',
                             $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <td>&nbsp;</td>\n";
        $str .= "  <td>\n   ";
        $str .= HTML_Form::returnSubmit($title, $name, $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnReset()

    /**
     * Produce a string containing a reset button
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnReset($title = 'Clear contents', $attr = '')
    {
        return '<input type="reset"'
               . ' value="' . $title . '" ' . $attr . "/>\n";
    }

    // }}}
    // {{{ returnResetRow()

    /**
     * Produce a string containing a reset button inside a table row
     *
     * NOTE: Unusual parameter order.
     *
     * @param string $title     a string that appears on the button
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnResetRow($title = 'Clear contents', $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <td>&nbsp;</td>\n";
        $str .= "  <td>\n   ";
        $str .= HTML_Form::returnReset($title, $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnSelect()

    /**
     * Produce a string containing a select list
     *
     * @param string $name      the string used in the 'name' attribute
     * @param array  $entries   an array containing the <options> to be listed.
     *                           The array's keys become the option values and
     *                           the array's values become the visible text.
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer saying how many rows should be
     * @param string $blank     if this string is present, an <option> will be
     *                           added to the top of the list that will contain
     *                           the given text in the visible portion and an
     *                           empty string as the value
     * @param bool   $multiple  a bool saying if multiple choices are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnSelect($name, $entries, $default = '', $size = 1,
                          $blank = '', $multiple = false, $attr = '')
    {
        if ($multiple && substr($name, -2) != "[]") {
            $name .= "[]";
        }
        $str = "   <select name=\"$name\"";
        if ($size) {
            $str .= " size=\"$size\"";
        }
        if ($multiple) {
            $str .= " multiple=\"multiple\"";
        }
        $str .= ' ' . $attr . ">\n";
        if ($blank) {
            $str .= "    <option value=\"\">$blank</option>\n";
        }

        foreach ($entries as $val => $text) {
            $str .= '    <option ';
                if ($default) {
                    if ($multiple && is_array($default)) {
                        if ((is_string(key($default)) && $default[$val]) ||
                            (is_int(key($default)) && in_array($val, $default))) {
                            $str .= 'selected="selected" ';
                        }
                    } elseif ($default == $val) {
                        $str .= 'selected="selected" ';
                    }
                }
            $str .= "value=\"$val\">$text</option>\n";
        }
        $str .= "   </select>\n";

        return $str;
    }

    // }}}
    // {{{ returnSelectRow()

    /**
     * Produce a string containing a select list inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param array  $entries   an array containing the <options> to be listed.
     *                           The array's keys become the option values and
     *                           the array's values become the visible text.
     * @param mixed  $default   a default value for the element
     * @param int    $size      an integer saying how many rows should be
     * @param string $blank     if this string is present, an <option> will be
     *                           added to the top of the list that will contain
     *                           the given text in the visible portion and an
     *                           empty string as the value
     * @param bool   $multiple  a bool saying if multiple choices are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnSelectRow($name, $title, $entries, $default = '', $size = 1,
                             $blank = '', $multiple = false, $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\">$title:</th>\n";
        $str .= "  <td>\n";
        $str .= HTML_Form::returnSelect($name, $entries, $default, $size,
                                        $blank, $multiple, $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnRadio()

    /**
     * Produce a string containing a radio input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $value     the string used for the item's value
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function returnRadio($name, $value, $default = false, $attr = '')
    {
        return '<input type="radio" name="' . $name . '"' .
               ' value="' . $value . '"' .
               ($default ? ' checked="checked"' : '') .
               ' ' . $attr . "/>\n";
    }

    // }}}
    // {{{ returnRadioRow()

    /**
     * Produce a string containing a radio input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param string $value     the string used for the item's value
     * @param bool   $default   a bool indicating if item should be checked
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function returnRadioRow($name, $title, $value, $default = false,
                            $attr = '')
    {
        return " <tr>\n" .
               "  <th align=\"right\">$title:</th>\n" .
               "  <td>\n   " .
               HTML_Form::returnRadio($name, $value, $default, $attr) .
               "  </td>\n" .
               " </tr>\n";
    }

    // }}}
    // {{{ returnImage()

    /**
     * Produce a string containing an image input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $src       the string denoting the path to the image.
     *                           Can be a relative path or full URI.
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function returnImage($name, $src, $attr = '')
    {
        return '<input type="image" name="' . $name . '"' .
               ' src="' . $src . '" ' . $attr . "/>\n";
    }

    // }}}
    // {{{ returnImageRow()

    /**
     * Produce a string containing an image input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param string $src       the string denoting the path to the image.
     *                           Can be a relative path or full URI.
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function returnImageRow($name, $title, $src, $attr = '')
    {
        return " <tr>\n" .
               "  <th align=\"right\">$title:</th>\n" .
               "  <td>\n   " .
               HTML_Form::returnImage($name, $src, $attr) .
               "  </td>\n" .
               " </tr>\n";
    }

    // }}}
    // {{{ returnHidden()

    /**
     * Produce a string containing a hiden input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $value     the string used for the item's value
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnHidden($name, $value, $attr = '')
    {
        return '<input type="hidden" name="' . $name . '"'
               . ' value="' . $value . '" ' . $attr . "/>\n";
    }

    // }}}
    // {{{ returnBlank()

    /**
     * Produce a string containing &nbsp;
     *
     * @return string
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function returnBlank()
    {
        return '&nbsp;';
    }

    // }}}
    // {{{ returnBlankRow()

    /**
     * Produce a string containing a blank row in the table
     *
     * @param int    $i         the number of rows to create.  Ignored if
     *                           $title is used.
     * @param string $title     a string to be used as the label for the row
     *
     * @return string
     *
     * @access public
     * @static
     * @since Method available since Release 1.1.0
     */
    function returnBlankRow($i, $title= '')
    {
        if (!$title) {
            $str = '';
            for ($j = 0; $j < $i; $j++) {
                $str .= " <tr>\n";
                $str .= "  <th align=\"right\">&nbsp;</th>\n";
                $str .= '  <td>' . HTML_Form::returnBlank() . "</td>\n";
                $str .= " </tr>\n";
            }
            return $str;
        } else {
            return " <tr>\n" .
                   "  <th align=\"right\">$title:</th>\n" .
                   '  <td>' . HTML_Form::returnBlank() . "</td>\n" .
                   " </tr>\n";
        }
    }

    // }}}
    // {{{ returnFile()

    /**
     * Produce a string containing a file upload input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param int    $maxsize   an integer determining how large (in bytes) a
     *                           submitted file can be.
     * @param int    $size      an integer used in the 'size' attribute
     * @param string $accept    a string saying which MIME types are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnFile($name = 'userfile',
                        $maxsize = HTML_FORM_MAX_FILE_SIZE,
                        $size = HTML_FORM_TEXT_SIZE,
                        $accept = '', $attr = '')
    {
        $str  = '   <input type="hidden" name="MAX_FILE_SIZE" value="';
        $str .= $maxsize . "\" />\n";
        $str .= '   <input type="file" name="' . $name . '"';
        $str .= ' size="' . $size . '" ';
        if ($accept) {
            $str .= 'accept="' . $accept . '" ';
        }
        return $str . $attr . "/>\n";
    }

    // }}}
    // {{{ returnFileRow()

    /**
     * Produce a string containing a file upload input inside a table row
     *
     * @param string $name      the string used in the 'name' attribute
     * @param string $title     the string used as the label
     * @param int    $maxsize   an integer determining how large (in bytes) a
     *                           submitted file can be.
     * @param int    $size      an integer used in the 'size' attribute
     * @param string $accept    a string saying which MIME types are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnFileRow($name, $title, $maxsize = HTML_FORM_MAX_FILE_SIZE,
                           $size = HTML_FORM_TEXT_SIZE,
                           $accept = '', $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\">$title:</th>\n";
        $str .= "  <td>\n";
        $str .= HTML_Form::returnFile($name, $maxsize, $size, $accept,
                                      $attr);
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ returnMultipleFiles()

    /**
     * Produce a string containing a file upload input
     *
     * @param string $name      the string used in the 'name' attribute
     * @param int    $maxsize   an integer determining how large (in bytes) a
     *                           submitted file can be.
     * @param int    $files     an integer of how many file inputs to show
     * @param int    $size      an integer used in the 'size' attribute
     * @param string $accept    a string saying which MIME types are allowed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnMultipleFiles($name = 'userfile[]',
                                 $maxsize = HTML_FORM_MAX_FILE_SIZE,
                                 $files = 3,
                                 $size = HTML_FORM_TEXT_SIZE,
                                 $accept = '', $attr = '')
    {
        $str  = '<input type="hidden" name="MAX_FILE_SIZE" value="';
        $str .= $maxsize . "\" />\n";

        for($i=0; $i < $files; $i++) {
            $str .= '<input type="file" name="' . $name . '"';
            $str .= ' size="' . $size . '" ';
            if ($accept) {
                $str .= 'accept="' . $accept . '" ';
            }
            $str .= $attr . "/><br />\n";
        }
        return $str;
    }

    // }}}
    // {{{ returnStart()

    /**
     * Produces a string containing the opening tags for the form and table
     *
     * NOTE: can NOT be called statically.
     *
     * @param bool $multipartformdata  a bool indicating if the form should
     *                                  be submitted in multipart format
     * @return string
     *
     * @access public
     */
    function returnStart($multipartformdata = false)
    {
        $str = "<form action=\"" . $this->action . "\" method=\"$this->method\"";
        if ($this->name) {
            $str .= " name=\"$this->name\"";
        }
        if ($this->target) {
            $str .= " target=\"$this->target\"";
        }
        if ($this->enctype) {
            $str .= " enctype=\"$this->enctype\"";
        }
        if ($multipartformdata) {
            $str .= " enctype=\"multipart/form-data\"";
        }

        return $str . ' ' . $this->attr . ">\n";
    }

    // }}}
    // {{{ returnEnd()

    /**
     * Produces a string containing the opening tags for the form and table
     *
     * NOTE: can NOT be called statically.
     *
     * @return string
     *
     * @access public
     */
    function returnEnd()
    {
        $fields = array();
        foreach ($this->fields as $i => $data) {
            switch ($data[0]) {
                case 'reset':
                case 'blank':
                    continue 2;
            }
            $fields[$data[1]] = true;
        }
        $ret = HTML_Form::returnHidden("_fields", implode(":", array_keys($fields)));
        $ret .= "</form>\n\n";
        return $ret;
    }

    // }}}
    // {{{ returnPlaintext()

    /**
     * Produce a string containing the text provided
     *
     * @param string $text      a string to be displayed
     *
     * @return string
     *
     * @access public
     * @static
     */
    function returnPlaintext($text = '&nbsp;')
    {
        return $text;
    }

    // }}}
    // {{{ returnPlaintextRow()

    /**
     * Produce a string containing the text provided inside a table row
     *
     * @param string $title     the string used as the label
     * @param string $text      a string to be displayed
     * @param string $attr      a string of additional attributes to be put
     *                           in the element (example: 'id="foo"')
     * @return string
     *
     * @access public
     * @static
     */
    function returnPlaintextRow($title, $text = '&nbsp;', $attr = '')
    {
        $str  = " <tr>\n";
        $str .= "  <th align=\"right\" valign=\"top\">$title:</th>\n";
        $str .= "  <td>\n  ";
        $str .= HTML_Form::returnPlaintext($text) . "\n";
        $str .= "  </td>\n";
        $str .= " </tr>\n";

        return $str;
    }

    // }}}
    // {{{ display()

    /**
     * Prints a complete form with all fields you specified via
     * the add*() methods
     *
     * NOTE: can NOT be called statically.
     *
     * @return void
     *
     * @access public
     */
    function display()
    {
        $arrname = '_' . strtoupper($this->method);
        if (isset($$arrname)) {
            $arr =& $$arrname;
        } else {
            $arrname = 'HTTP' . $arrname . '_VARS';
            if (isset($GLOBALS[$arrname])) {
                $arr =& $GLOBALS[$arrname];
            } else {
                $arr = array();
            }
        }

        $this->start();
        print "<table>\n";
        reset($this->fields);
        $hidden = array();
        foreach ($this->fields as $i => $data) {
            switch ($data[0]) {
                case "hidden":
                    $hidden[] = $i;
                    $defind = 1;
                    continue 2;
                case "reset":
                    $params = 2;
                    $defind = 2;
                    break;
                case "submit":
                    $params = 3;
                    $defind = 3;
                    break;
                case "blank":
                    $params = 2;
                    $defind = 1;
                    break;
                case "image":
                    $params = 4;
                    $defind = 1;
                    break;
                case "checkbox":
                    $params = 4;
                    $defind = 2;
                    break;
                case "file":  //new
                case "text":
                    $params = 6;
                    $defind = 4;
                    break;
                case "password":
                    $params = 6;
                    $defind = 3;
                    break;
                case "radio":
                    $params = 5;
                    $defind = 2;
                    break;
                case "textarea":
                    $params = 7;
                    $defind = 4;
                    break;
                case "select":
                    $params = 8;
                    $defind = 5;
                    break;
                case "plaintext":
                    $params = 3;
                    $defind = 2;
                    break;
                default:
                    // unknown field type
                    continue 2;
            }
            $str = '$this->display'.ucfirst($data[0])."Row(";
            for ($i = 1;$i <= $params;$i++) {
                if ($i == $defind && $data[$defind] === null && isset($arr[$data[1]])) {
                    $str .= "\$arr['$data[1]']";
                } else {
                    $str .= '$'."data[$i]";
                }
                if ($i < $params) $str .= ', ';
            }
            $str .= ');';
            eval($str);
        }
        print "</table>\n";
        for ($i = 0;$i < sizeof($hidden);$i++) {
            $this->displayHidden($this->fields[$hidden[$i]][1],
                                 $this->fields[$hidden[$i]][2],
                                 $this->fields[$hidden[$i]][3]);
        }
        $this->end();
    }

    // }}}
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */

?>
