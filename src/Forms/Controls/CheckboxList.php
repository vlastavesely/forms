<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Forms\Controls;

use Nette;
use Nette\Utils\Html;


/**
 * Set of checkboxes.
 *
 * @property-read Html $separatorPrototype
 * @property-read Html $containerPrototype
 * @property-read Html $itemLabelPrototype
 */
class CheckboxList extends MultiChoiceControl
{
	/** @var Html  separator element template */
	protected $separator;

	/** @var Html  container element template */
	protected $container;

	/** @var Html  item label template */
	protected $itemLabel;


	/**
	 * @param  string|object
	 */
	public function __construct($label = NULL, array $items = NULL)
	{
		parent::__construct($label, $items);
		$this->control->type = 'checkbox';
		$this->container = Html::el();
		$this->separator = Html::el('br');
		$this->itemLabel = Html::el('label');
		$this->setOption('type', 'checkbox');
	}


	/**
	 * Generates control's HTML element.
	 */
	public function getControl(): Html
	{
		$input = parent::getControl();
		$items = $this->getItems();
		reset($items);

		return $this->container->setHtml(
			Nette\Forms\Helpers::createInputList(
				$this->translate($items),
				array_merge($input->attrs, [
					'id' => NULL,
					'checked?' => $this->value,
					'disabled:' => $this->disabled,
					'required' => NULL,
					'data-nette-rules:' => [key($items) => $input->attrs['data-nette-rules']],
				]),
				$this->itemLabel->attrs,
				$this->separator
			)
		);
	}


	/**
	 * Generates label's HTML element.
	 * @param  string|object
	 */
	public function getLabel($caption = NULL): Html
	{
		return parent::getLabel($caption)->for(NULL);
	}


	public function getControlPart($key = NULL): Html
	{
		$key = key([(string) $key => NULL]);
		return parent::getControl()->addAttributes([
			'id' => $this->getHtmlId() . '-' . $key,
			'checked' => in_array($key, (array) $this->value, TRUE),
			'disabled' => is_array($this->disabled) ? isset($this->disabled[$key]) : $this->disabled,
			'required' => NULL,
			'value' => $key,
		]);
	}


	public function getLabelPart($key = NULL): Html
	{
		$itemLabel = clone $this->itemLabel;
		return func_num_args()
			? $itemLabel->setText($this->translate($this->items[$key]))->for($this->getHtmlId() . '-' . $key)
			: $this->getLabel();
	}


	/**
	 * Returns separator HTML element template.
	 */
	public function getSeparatorPrototype(): Html
	{
		return $this->separator;
	}


	/**
	 * Returns container HTML element template.
	 */
	public function getContainerPrototype(): Html
	{
		return $this->container;
	}


	/**
	 * Returns item label HTML element template.
	 */
	public function getItemLabelPrototype(): Html
	{
		return $this->itemLabel;
	}

}
