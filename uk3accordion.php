<?php defined('_JEXEC') or die;
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.uk3accordion
 * @copyright   Copyright (C) 2019 Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Filesystem\Path;

class PlgContentUk3accordion extends CMSPlugin
{
    public function onContentPrepare($context, &$article, &$params, $page = 0)
    {
        if ($context == 'com_finder.indexer' || !preg_match('/{accordion\s(.*)}/s', $article->text)) {
            return false;
        }

        $vars = [
            'accordion_class', 'title_class', 'content_class',
            'active', 'multiple', 'collapsible', 'animation', 'duration', 'transition',
        ];

        foreach ($vars as $var) {
            $$var = $this->params->get($var);
        }

        $layout = Path::clean(PluginHelper::getLayoutPath('content', 'uk3accordion'));
        $layout = pathinfo($layout, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($layout, PATHINFO_FILENAME);

        $accordion_class = trim($accordion_class) ? ' ' . trim($accordion_class) : '';
        $title_class = trim($title_class) ? ' ' . trim($title_class) : '';
        $content_class = trim($content_class) ? ' ' . trim($content_class) : '';

        $accordion_params = [];
        if ((int)$active > 0) $accordion_params[] = 'active:' . $active;
        if ($multiple) $accordion_params[] = 'multiple:true';
        if (!$collapsible && !$multiple) $accordion_params[] = 'collapsible:false';
        if (!$animation) {
            $accordion_params[] = 'animation:false';
        } else {
            if ($duration > 0 && (int)$duration != 200) $accordion_params[] = 'duration:' . $duration;
            if ($transition != 'ease') $accordion_params[] = 'transition:' . $transition;
        }
        $accordion_params = $accordion_params ? '="' . implode(';', $accordion_params) . '"' : '';

        $accordion = [];
        $matches = [];

        if (preg_match_all('/{accordion\s(.*)}{accordion\s(.*)}|{accordion\s(.*)}|{\/accordion}/', $article->text, $matches, PREG_PATTERN_ORDER) > 0) {
            $article->text = preg_replace('|<[^>]+>{accordion\s(.*)}</[^>]+>|U', '{accordion \\1}', $article->text);
            $article->text = preg_replace('|<(.*)>{accordion\s(.*)}|U', '{accordion \\2}<\\1>', $article->text);
            $article->text = preg_replace('|{accordion\s(.*)}</(.*)>|U', '</\\2>{accordion \\1}', $article->text);
            $article->text = preg_replace('|<[^>]+>{/accordion}</[^>]+>|U', '{/accordion}', $article->text);
            $article->text = preg_replace('|<(.*)>{/accordion}|U', '{/accordion}<\\1>', $article->text);
            $article->text = preg_replace('|{/accordion}</(.*)>|U', '</\\1>{/accordion}', $article->text);
            $step = 1;
            foreach ($matches[0] as $match) {
                if ($step == 1 && $match != '{/accordion}') {
                    $accordion[] = 1;
                    $step = 2;
                } elseif ($match == '{/accordion}') {
                    $accordion[] = 3;
                    $step = 1;
                } elseif (preg_match('/{accordion\s(.*)}{accordion\s(.*)}/', $match)) {
                    $accordion[] = 2;
                    $accordion[] = 1;
                    $step = 2;
                } else {
                    $accordion[] = 2;
                }
            }
        }

        if ($matches) {
            $accordionCount = 0;
            foreach ($matches[0] as $match) {
                if ($accordion[$accordionCount] < 3) {
                    $title = preg_replace('|{accordion\s(.*)}|U', '\\1', $match);
                    $match = '/' . preg_quote($match) . '/U';
                    ob_start();
                    include $layout . ($accordion[$accordionCount] < 2 ? '_start.php' : '_li_end.php');
                    include $layout . '_li_start.php';
                    $accordion_content = ob_get_clean();
                } elseif ($accordion[$accordionCount] == 3) {
                    $match = '/{\/accordion}/U';
                    ob_start();
                    include $layout . '_li_end.php';
                    include $layout . '_end.php';
                    $accordion_content = ob_get_clean();
                }
                $article->text = preg_replace($match, $accordion_content, $article->text, 1);
                $accordionCount++;
            }
        }
    }
}
