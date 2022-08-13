# Json Template Validator

In many projects I use json files because they are very convenient to work with due to their simplicity and how verbose they are. In software development you either have to rely on a correct strucutre or perform checks (existence, correctness) while you try to access the different elements. This validator implements a solution to create json templates which can be used in order to validate json files.

This validator can become especially usefull during testing for larger projects where similar json structures are used over and over again. By using this validator it's possible to test the correctness and completeness of json files by compring them to a pre-defined template. Examples can be configuration/setting files or any other project depending json structures. 

In my case I use this for module `info.json` files where I describe a specific module. Since there are many modules and therefor many `info.json` files; this helps me to ensure that all files follow the same template even if I make changes to the structure of these json files.

## The Json Template Standard Example

### Example: Template

```json
{
    "name": {
        "id": "^[1-9]\\d*",
        "internal": "[a-zA-Z0-9]+",
        "external": "[a-zA-Z0-9]+"
    },
    "category": "[a-zA-Z0-9]+",
    "version": "([0-9]+\\.){2}[0-9]+",
    "requirements": {
        ".*": ".*"
    },
    "creator": {
        "name": ".+",
        "website": ".*"
    },
    "description": ".+",
    "directory": "[a-zA-Z0-9]+",
    "dependencies": {
        ".*": ".*"
    },
    "providing": {
        ".*": ".*"
    },
    "load": [
        {
            "pid": [
                ".*"
            ],
            "type": "^[1-9]\\d*",
            "for": "^([1-9]\\d*)|([a-zA-Z0-9]+)",
            "from": "[a-zA-Z0-9]+",
            "file": "[a-zA-Z0-9]*"
        }
    ]
}
```

### Example: Json File Which Matches Template

```json
{
    "name": {
        "id": 1001100000,
        "internal": "Tasks",
        "external": "Tasks"
    },
    "category": "Tools",
    "version": "1.0.0",
    "requirements": {
        "phpOMS": "1.0.0",
        "phpOMS-db": "1.0.0"
    },
    "creator": {
        "name": "Orange Management",
        "website": "www.spl1nes.com"
    },
    "description": "Tasks module.",
    "directory": "Tasks",
    "dependencies": {
        "Admin": "1.0.0",
        "Calendar": "1.0.0",
        "Media": "1.0.0",
        "Editor": "1.0.0"
    },
    "providing": {
        "Navigation": "*"
    },
    "load": [
        {
            "pid": [
                "/backend/task"
            ],
            "type": 4,
            "for": 0,
            "from": "Tasks",
            "file": "Tasks"
        },
        {
            "pid": [
                "/backend"
            ],
            "type": 4,
            "from": "Tasks",
            "for": "Navigation",
            "file": "Tasks"
        },
        {
            "pid": [
                "/backend"
            ],
            "type": 5,
            "from": "Tasks",
            "for": "Navigation",
            "file": "Navigation"
        }
    ]
}

```


## The Code

```php
<?php
abstract class JsonTemplateValidator
{

    public static function validateTemplate(array $template, array $source, bool $perfect = false) : bool
    {
        $templatePaths = self::createAllViablePaths($template, '');
        $sourcePaths   = self::createAllViablePaths($source, '');

        $isComplete = self::isCompleteSource($templatePaths, $sourcePaths);
        if (!$isComplete) {
            return false;
        }

        if ($perfect) {
            // No additional elements compared to the json template are allowed
            $perfectFit = self::hasTemplateDefinition($templatePaths, $sourcePaths);
            if (!$perfectFit) {
                return false;
            }
        }

        $isValid = self::isValidSource($templatePaths, $sourcePaths);
        if (!$isValid) {
            return false;
        }

        return true;
    }

    private static function createAllViablePaths(array $arr, string $path = '') : array
    {
        $paths = [];
        foreach ($arr as $key => $value) {
            $tempPath = $path . '/' . $key;

            if (\is_array($value)) {
                $paths += self::createAllViablePaths($value, $tempPath);
            } else {
                $paths[$tempPath] = $value;
            }
        }

        return $paths;
    }

    private static function hasTemplateDefinition(array $template, array $source) : bool
    {
        $completePaths = [];
        foreach ($template as $key => $value) {
            $key                 = \str_replace('/0', '/.*', $key);
            $completePaths[$key] = $value;
        }

        foreach ($source as $sPath => $sValue) {
            $hasDefinition = false;

            foreach ($completePaths as $tPath => $tValue) {
                if ($tPath === $sPath
                    || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1
                ) {
                    $hasDefinition = true;
                    break;
                }
            }

            if (!$hasDefinition) {
                return false;
            }
        }

        return true;
    }

    private static function isCompleteSource(array $template, array $source) : bool
    {
        $completePaths = [];
        foreach ($template as $key => $value) {
            $key = \str_replace('/0', '/.*', $key);

            if (\stripos($key, '/.*') !== false) {
                continue;
            }

            $completePaths[$key] = $value;
        }

        foreach ($completePaths as $tPath => $tValue) {
            $sourceIsComplete = false;

            foreach ($source as $sPath => $sValue) {
                if ($tPath === $sPath
                    || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1
                ) {
                    unset($completePaths[$tPath]);
                    break;
                }
            }
        }

        return count($completePaths) === 0;
    }

    private static function isValidSource(array $template, array $source) : bool
    {
        $validPaths = [];
        foreach ($template as $key => $value) {
            $key              = \str_replace('/0', '/\d*', $key);
            $validPaths[$key] = $value;
        }

        foreach ($source as $sPath => $sValue) {
            $sourceIsValid = false;
            $foundPath     = false;

            foreach ($validPaths as $tPath => $tValue) {
                if (!$foundPath
                    && ($tPath === $sPath
                        || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1)
                ) {
                    $foundPath = true;
                }

                if (($tPath === $sPath
                        || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1)
                    && ($tValue === $sValue
                        || \preg_match('~' . ((string) $tValue) . '~', (string) $sValue) === 1)
                ) {
                    $sourceIsValid = true;
                    break;
                }
            }

            if (!$sourceIsValid && $foundPath) {
                return false;
            }
        }

        return true;
    }
}

```
