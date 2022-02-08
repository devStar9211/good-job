<?php
App::uses('FormHelper', 'View/Helper');
class CkFormHelper extends FormHelper {

    public function create($model = null, $options = array()) {
        $defaultOptions = array(
            'inputDefaults' => array(
                'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                'div' => array('class' => 'form-group clear'),
                'label' => array('class' => 'control-label col-xs-12 col-sm-2'),
                'between' => '<div class="controls col-xs-12 col-sm-8">',
                'after' => '</div>',
                'error' => array('attributes' => array('wrap' => 'label', 'class' => 'control-label', 'before' => '<i class="fa fa-times-circle-o"></i> ')),
                'class' => 'form-control'
            ),
            'class' => 'form-horizontal',
            'role' => 'form',
        );

        $options = array_merge($defaultOptions, $options);
        return parent::create($model, $options);
    }

    public function submit($caption = null, $options = array()) {
        $defaultOptions = array(
            'class' => 'btn btn-primary',
            'div' =>  'form-group',
            'before' => '<div class="col-xs-12 col-sm-8 col-sm-offset-2">',
            'after' => ' <input type="reset" class="btn btn-default" value="'.__('Cancel').'"></div>',
        );
        $options = array_merge($defaultOptions, $options);
        return parent::submit($caption, $options);
    }

    public function input($fieldName, $options = array()) {
        $this->setEntity($fieldName);
        $options = $this->_parseOptions($options);

        $divOptions = $this->_divOptions($options);
        unset($options['div']);

        if ($options['type'] === 'radio' && isset($options['options'])) {
            $radioOptions = (array)$options['options'];
            unset($options['options']);
        }

        $label = $this->_getLabel($fieldName, $options);
        if ($options['type'] !== 'radio') {
            unset($options['label']);
        }

        $error = $this->_extractOption('error', $options, null);
        unset($options['error']);

        $errorMessage = $this->_extractOption('errorMessage', $options, true);
        unset($options['errorMessage']);

        $selected = $this->_extractOption('selected', $options, null);
        unset($options['selected']);

        if ($options['type'] === 'datetime' || $options['type'] === 'date' || $options['type'] === 'time') {
            $dateFormat = $this->_extractOption('dateFormat', $options, 'MDY');
            $timeFormat = $this->_extractOption('timeFormat', $options, 12);
            unset($options['dateFormat'], $options['timeFormat']);
        }

        $type = $options['type'];
        $out = array('before' => $options['before'], 'label' => $label, 'between' => $options['between'], 'after' => $options['after']);
        $format = $this->_getFormat($options);

        unset($options['type'], $options['before'], $options['between'], $options['after'], $options['format']);

        $out['error'] = null;
        if ($type !== 'hidden' && $error !== false) {
            $errMsg = $this->error($fieldName, $error);
            if ($errMsg) {
                $divOptions = $this->addClass($divOptions, 'has-error');
                if ($errorMessage) {
                    $out['error'] = $errMsg;
                }
            }
        }

        if ($type === 'radio' && isset($out['between'])) {
            $options['between'] = $out['between'];
            $out['between'] = null;
        }
        $out['input'] = $this->_getInput(compact('type', 'fieldName', 'options', 'radioOptions', 'selected', 'dateFormat', 'timeFormat'));

        $output = '';
        foreach ($format as $element) {
            $output .= $out[$element];
        }

        if (!empty($divOptions['tag'])) {
            $tag = $divOptions['tag'];
            unset($divOptions['tag']);
            $output = $this->Html->tag($tag, $output, $divOptions);
        }
        return $output;
    }

    public function error($field, $text = null, $options = array()) {
        $clearfix = '<div class="clear-fix"></div>';
        $defaults = array('wrap' => 'label', 'class' => 'control-label', 'escape' => true, 'before' => '<i class="fa fa-times-circle-o"></i> ');
        $options += $defaults;
        $this->setEntity($field);

        $error = $this->tagIsInvalid();
        if ($error === false) {
            return null;
        }
        if (is_array($text)) {
            if (isset($text['attributes']) && is_array($text['attributes'])) {
                $options = array_merge($options, $text['attributes']);
                unset($text['attributes']);
            }
            $tmp = array();
            foreach ($error as &$e) {
                if (isset($text[$e])) {
                    $tmp[] = $text[$e];
                } else {
                    $tmp[] = $e;
                }
            }
            $text = $tmp;
        }

        if ($text !== null) {
            $error = $text;
        }
        if (is_array($error)) {
            foreach ($error as &$e) {
                if (is_numeric($e)) {
                    $e = __d('cake', 'Error in field %s', Inflector::humanize($this->field()));
                }
            }
        }
        if ($options['escape']) {
            $error = h($error);
            unset($options['escape']);
        }
        if (is_array($error)) {
            if (count($error) > 1) {
                $listParams = array();
                if (isset($options['listOptions'])) {
                    if (is_string($options['listOptions'])) {
                        $listParams[] = $options['listOptions'];
                    } else {
                        if (isset($options['listOptions']['itemOptions'])) {
                            $listParams[] = $options['listOptions']['itemOptions'];
                            unset($options['listOptions']['itemOptions']);
                        } else {
                            $listParams[] = array();
                        }
                        if (isset($options['listOptions']['tag'])) {
                            $listParams[] = $options['listOptions']['tag'];
                            unset($options['listOptions']['tag']);
                        }
                        array_unshift($listParams, $options['listOptions']);
                    }
                    unset($options['listOptions']);
                }
                array_unshift($listParams, $error);
                $error = call_user_func_array(array($this->Html, 'nestedList'), $listParams);
            } else {
                $error = array_pop($error);
            }
        }
        if ($options['wrap']) {
            $tag = is_string($options['wrap']) ? $options['wrap'] : 'div';
            unset($options['wrap']);
            $error = '<i class="fa fa-times-circle-o"></i> ' . $error;
            return $clearfix.$this->Html->tag($tag, $error, $options);
        }
        return $clearfix.$error;
    }

}