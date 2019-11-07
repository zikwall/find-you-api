# Find you API

### Installation

```json
{
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/zikwall/find-you-api.git"
        }
    ],
    "require": {
        "zikwall/find-you-api": "dev-master"
    }
}
```

### Configuration

#### Web & console application config

```php
'modules' => [
      'findyouapi' => [
            'class' => \zikwall\findyouapi\Module::class,
            'handleUrl' => '',
            'securityToken' => '',
            'imageUploadPath' => '',
            'responseHeaders' => []
      ],
],