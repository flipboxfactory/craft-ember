<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipbox/ember/blob/master/LICENSE
 * @link       https://github.com/flipbox/ember
 */

namespace flipbox\ember\models;

use craft\events\ModelEvent as ModelSaveEvent;
use flipbox\ember\traits\AuditAttributes;
use yii\base\ModelEvent as ModelDeleteEvent;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
abstract class Model extends \yii\base\Model
{
    use AuditAttributes;

    /**
     * @event ModelEvent an event. You may set
     * [[ModelEvent::isValid]] to be false to stop the save.
     */
    const EVENT_BEFORE_SAVE = 'beforeSave';

    /**
     * @event ModelEvent an event. You may set
     * [[ModelEvent::isValid]] to be false to stop the save.
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @event ModelEvent an event. You may set
     * [[ModelEvent::isValid]] to be false to stop the deletion.
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';

    /**
     * @event ModelEvent an event. You may set
     * [[ModelEvent::isValid]] to be false to stop the deletion.
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            $this->auditRules()
        );
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            $this->auditAttributes()
        );
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return array_merge(
            parent::fields(),
            $this->auditFields()
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            $this->auditAttributeLabels()
        );
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete(ModelDeleteEvent $event): bool
    {
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
        return $event->isValid;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete(ModelDeleteEvent $event): bool
    {
        $this->trigger(self::EVENT_AFTER_DELETE, $event);
        return $event->isValid;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave(ModelSaveEvent $event): bool
    {
        $this->trigger(self::EVENT_BEFORE_SAVE, $event);
        return $event->isValid;
    }

    /**
     * @inheritdoc
     */
    public function afterSave(ModelSaveEvent $event): bool
    {
        $this->trigger(self::EVENT_AFTER_SAVE, $event);
        return $event->isValid;
    }
}
