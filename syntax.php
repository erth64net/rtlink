<?php
/**
 * Plugin rtlink: Links to Request Tracker tickets
 *
 *  Thanks to Stefan Hechenberger for the inspiration through his websvn plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Gregg Berkholtz <gregg@tocici.com>, Tobias <info@hopeconsultants.org>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');



//-----------------------------------CONFIGURE RTLINK ROOT HERE---------
global $rtlink_root_url;
$rtlink_root_url = "https://rt.example.com/";
//----------------------------------------------------------------------



/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_rtlink extends DokuWiki_Syntax_Plugin {

    const ARTICLE = 'article';
    const TICKET = 'ticket';

    /**
     * return some info
     */
    public function getInfo(){
        return array(
            'author' => 'Gregg Berkholtz et al',
            'email'  => 'info@hopeconsultants.org',
            'date'   => '2023-02-17',
            'name'   => 'rtlink Plugin',
            'desc'   => 'Generates links to RT:: Tickets.',
            'url'    => 'https://github.com/Hope-Consultants-International/rtlink',
        );
    }

    /**
     * What kind of syntax are we?
     */
    public function getType(){
        return 'substition';
    }


    /**
     * Where to sort in?
     */
    public function getSort(){
        return 921;
    }


    /**
     * Connect pattern to lexer
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('[rR][tT][0-9]+', $mode, substr(get_class($this), 7));
        $this->Lexer->addSpecialPattern('[rR][tT][aA][0-9]+', $mode, substr(get_class($this), 7));
    }


    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
        preg_match('/([rR][tT][aA]?)([0-9]+)/', $match, $matches);
        if (strcasecmp($matches[1], 'RTA')) {
            return array(self::ARTICLE, $matches[2]);
        } else {
            return array(self::TICKET, $matches[2]);
        }
    }

    /**
     * Create output
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        global $rtlink_root_url;

        if ($mode !== 'xhtml') {
            return true;
        }

        list($type, $id) = $data;
        switch ($type) {
            case self::ARTICLE:
                $renderer->doc .= sprintf('<a href="%sArticles/Article/Display.html?id=%s">RT Article #%s</a>', $rtlink_root_url, $id, $id);
                break;
            case self::TICKET:
                $renderer->doc .= sprintf('<a href="%sTicket/Display.html?id=%s">RT Ticket #%s</a>', $rtlink_root_url, $id, $id);
                break;
        }
        return true;
    }
}
