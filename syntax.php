<?php
/**
 * Plugin rtlink: Links to Request Tracker tickets
 *
 *  Thanks to Stefan Hechenberger for the inspiration through his websvn plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Gregg Berkholtz <gregg@tocici.com>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');



//-----------------------------------CONFIGURE RTLINK ROOT HERE---------
global $rtlink_root_url;
$rtlink_root_url = "http://rt.tocici.com/";
//----------------------------------------------------------------------



/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_rtlink extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Gregg Berkholtz',
            'email'  => 'gregg@tocici.com',
            'date'   => '2012-04-25',
            'name'   => 'rtlink Plugin',
            'desc'   => 'Generates links to RT:: Tickets.',
            'url'    => 'http://wiki.splitbrain.org/plugin:rtlink',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }


    /**
     * Where to sort in?
     */
    function getSort(){
        return 921;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern('[rR][tT][0-9]+',$mode, substr(get_class($this),7));
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        $match = html_entity_decode(substr($match, 2));
        return array($match);
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        global $rtlink_root_url;
        list($ticket) = $data;
        $url = $rtlink_root_url."Ticket/Display.html?id=$ticket";
        if($mode == 'xhtml'){
                $renderer->doc .= "<a href=\"".$url."\">RT$ticket</a>";
           }
        return true;
        //return false;
    }
}
