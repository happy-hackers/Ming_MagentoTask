<?php 
namespace Zou\Demo\Block\Adminhtml\Form\Element;
use Magento\Framework\Escaper;

class Editor extends \Magento\Framework\Data\Form\Element\Textarea
{
    /**
     * Adminhtml data
     *
     * @var \Magento\Backend\Helper\Data
     */
    protected $_backendData = null;

    /**
     * Module data
     *
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager = null;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Backend\Helper\Data $backendData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Helper\Data $backendData,
        array $data = []
        ) {
            $this->_wysiwygConfig = $wysiwygConfig;
            $this->_layout = $layout;
            $this->_moduleManager = $moduleManager;
            $this->_backendData = $backendData;
            parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
            $this->setType('wysiwyg');
            $this->setExtType('wysiwyg');
    }

    /**
     * @return array
     */
    protected function getButtonTranslations()
    {
        $buttonTranslations = [
            'Insert Image...' => $this->translate('Insert Image...'),
            'Insert Media...' => $this->translate('Insert Media...'),
            'Insert File...' => $this->translate('Insert File...'),
        ];
    
        return $buttonTranslations;
    }
    
    /**
     * @return string
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getElementHtml()
    {
        $js = '
            <script type="text/javascript">
            //<![CDATA[
                openEditorPopup = function(url, name, specs, parent) {
                    if ((typeof popups == "undefined") || popups[name] == undefined || popups[name].closed) {
                        if (typeof popups == "undefined") {
                            popups = new Array();
                        }
                        var opener = (parent != undefined ? parent : window);
                        popups[name] = opener.open(url, name, specs);
                    } else {
                        popups[name].focus();
                    }
                    return popups[name];
                }
    
                closeEditorPopup = function(name) {
                    if ((typeof popups != "undefined") && popups[name] != undefined && !popups[name].closed) {
                        popups[name].close();
                    }
                }
            //]]>
            </script>';
    
            $jsSetupObject = 'wysiwyg' . $this->getHtmlId();
    
            $forceLoad = '';
            //if (!$this->isHidden()) {
                if ($this->getForceLoad()) {
                    $forceLoad = $jsSetupObject . '.setup("exact");';
                } else {
                    $forceLoad = 'jQuery(window).on("load", ' .
                        $jsSetupObject .
                        '.setup.bind(' .
                        $jsSetupObject .
                        ', "exact"));';
                }
            //}
    
            $html = $this->_getButtonsHtml() .
            '<textarea name="' .
            $this->getName() .
            '" title="' .
            $this->getTitle() .
            '" ' .
            $this->_getUiId() .
            ' id="' .
            $this->getHtmlId() .
            '"' .
            ' class="textarea' .
            $this->getClass() .
            '" ' .
            $this->serialize(
                $this->getHtmlAttributes()
                ) .
                ' >' .
                $this->getEscapedValue() .
                '</textarea>' .
                $js .
                '
                <script type="text/javascript">
                //<![CDATA[
                window.tinyMCE_GZ = window.tinyMCE_GZ || {}; window.tinyMCE_GZ.loaded = true;require(["jquery", "mage/translate", "mage/adminhtml/events", "mage/adminhtml/wysiwyg/tiny_mce/setup", "mage/adminhtml/wysiwyg/widget"], function(jQuery){' .
                    "\n" .
                    '  (function($) {$.mage.translate.add(' .
                    \Zend_Json::encode(
                        $this->getButtonTranslations()
                        ) .
                        ')})(jQuery);' .
                        "\n" .
                        $jsSetupObject .
                        ' = new tinyMceWysiwygSetup("' .
                        $this->getHtmlId() .
                        '", ' .
                        \Zend_Json::encode(
                            $this->getConfig()
                            ) .
                            ');' .
                            $forceLoad .
                            '
                    editorFormValidationHandler = ' .
                        $jsSetupObject .
                        '.onFormValidation.bind(' .
                        $jsSetupObject .
                        ');
                    Event.observe("toggle' .
                        $this->getHtmlId() .
                        '", "click", ' .
                        $jsSetupObject .
                        '.toggle.bind(' .
                        $jsSetupObject .
                        '));
                    varienGlobalEvents.attachEventHandler("formSubmit", editorFormValidationHandler);
                    varienGlobalEvents.clearEventHandlers("open_browser_callback");
                    varienGlobalEvents.attachEventHandler("open_browser_callback", ' .
                        $jsSetupObject .
                        '.openFileBrowser);
                //]]>
                });
                </script>';
    
                        $html = $this->_wrapIntoContainer($html);
                        $html .= $this->getAfterElementHtml();
                        return $html;
    }
    
    /**
     * @return mixed
     */
    public function getTheme()
    {
        if (!$this->hasData('theme')) {
            return 'simple';
        }
    
        return $this->_getData('theme');
    }
    
    /**
     * Return Editor top Buttons HTML
     *
     * @return string
     */
    protected function _getButtonsHtml()
    {
        $buttonsHtml = '<div id="buttons' . $this->getHtmlId() . '" class="buttons-set">';
        $buttonsHtml .= $this->_getToggleButtonHtml(true);
        $buttonsHtml .= '</div>';
    
        return $buttonsHtml;
    }
    
    /**
     * Return HTML button to toggling WYSIWYG
     *
     * @param bool $visible
     * @return string
     */
    protected function _getToggleButtonHtml($visible = true)
    {
        $html = $this->_getButtonHtml(
            [
                'title' => $this->translate('Show / Hide Editor'),
                'class' => 'action-show-hide',
                'style' => $visible ? '' : 'display:none',
                'id' => 'toggle' . $this->getHtmlId(),
            ]
            );
        return $html;
    }
    
    
    
    /**
     * Convert options by replacing template constructions ( like {{var_name}} )
     * with data from this element object
     *
     * @param array $options
     * @return array
     */
    protected function _prepareOptions($options)
    {
        $preparedOptions = [];
        foreach ($options as $name => $value) {
            if (is_array($value) && isset($value['search']) && isset($value['subject'])) {
                $subject = $value['subject'];
                foreach ($value['search'] as $part) {
                    $subject = str_replace('{{' . $part . '}}', $this->getDataUsingMethod($part), $subject);
                }
                $preparedOptions[$name] = $subject;
            } else {
                $preparedOptions[$name] = $value;
            }
        }
        return $preparedOptions;
    }
    
    /**
     * Return custom button HTML
     *
     * @param array $data Button params
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getButtonHtml($data)
    {
        $html = '<button type="button"';
        $html .= ' class="scalable ' . (isset($data['class']) ? $data['class'] : '') . '"';
        $html .= isset($data['onclick']) ? ' onclick="' . $data['onclick'] . '"' : '';
        $html .= isset($data['style']) ? ' style="' . $data['style'] . '"' : '';
        $html .= isset($data['id']) ? ' id="' . $data['id'] . '"' : '';
        $html .= '>';
        $html .= isset($data['title']) ? '<span><span><span>' . $data['title'] . '</span></span></span>' : '';
        $html .= '</button>';
    
        return $html;
    }
    
    /**
     * Wraps Editor HTML into div if 'use_container' config option is set to true
     * If 'no_display' config option is set to true, the div will be invisible
     *
     * @param string $html HTML code to wrap
     * @return string
     */
    protected function _wrapIntoContainer($html)
    {
        if (!$this->getConfig('use_container')) {
            return '<div class="admin__control-wysiwig">' .$html . '</div>';
        }
    
        $html = '<div id="editor' . $this->getHtmlId() . '"' . ($this->getConfig(
            'no_display'
            ) ? ' style="display:none;"' : '') . ($this->getConfig(
                'container_class'
                ) ? ' class="admin__control-wysiwig ' . $this->getConfig(
                    'container_class'
                    ) . '"' : '') . '>' . $html . '</div>';
    
                    return $html;
    }
    
    /**
     * Editor config retriever
     *
     * @param string $key Config var key
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if (!$this->_getData('config') instanceof \Magento\Framework\DataObject) {
            $config = new \Magento\Framework\DataObject();
            $this->setConfig($config);
        }
        if ($key !== null) {
            return $this->_getData('config')->getData($key);
        }
        return $this->_getData('config');
    }
    
    /**
     * Translate string using defined helper
     *
     * @param string $string String to be translated
     * @return \Magento\Framework\Phrase
     */
    public function translate($string)
    {
        return (string)new \Magento\Framework\Phrase($string);
    }
}

?>