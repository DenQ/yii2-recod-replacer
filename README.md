# yii2-recod-replacer
Record replacer a component for safe add new records or replacing that already exists

# Install
```sh
    composer require "denq/yii2-record-replacer"
```

# Configure
```php
    'components' => [
        'RecordReplacer' => [
            'class' => 'RecordReplacer\RecordReplacer',
        ],
        ...
    ]
```

# Usage
```php
    Yii::$app->RecordReplacer->Run($model, $fields, $primary)
```

* $model
    * type: \yii\db\ActiveRecord
    * description: your active record model
    * required: true


* $fields
    * type: array
    * description: array with fields and values
    * required: true


* $primary
    * type: array
    * description: searchable fields
    * required: false
    * default value: []


# Example
```php
    Yii::$app->RecordReplacer->Run(new CustomModel, [
        'field_1' => 'val 1',
        'field_2' => 'val 2',
        ...
        'field_n' => 'val n',
    ], [
        'field_n'
    ]);
```

