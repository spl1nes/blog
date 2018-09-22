# GIT Pre-Commit Hooks

GIT pre-\* hooks allow for some very useful checks before committing or pushing changes. Of course there are more hooks than just the pre-commit or pre-push hook but I found these two the most helpful as they allow to implement checks before making changes public. Of course many build processes still perform a lot of checks before the merge process on the remote server but as a user I find it very helpful to know in advance if my changes will be accepted or not. Additionally pre-\* hooks help with enforcing a uniform code style and code quality.

> The following implementations are for bash (linux) only.

## Create Pre-Commit Hooks

Pre-\* hooks can be created in the `.git/hooks` directory of your project. For a pre-commit hook just create the file `pre-commit` in this directory and you are good to go with creating your pre-commit checks in here. You may want to consider to create your pre-commit checks in separate files and just link to them in your pre-commit file. This way you can re-use your pre-commit hooks in other projects and structure them in a more easy way. 

What I recommend to to is simply link to a delegator script in your project for all of your pre-commit checks

## Sample Pre-Commit Hook

```bash
#!/bin/bash

strindex() { 
    x="${1%%$2*}"
    [[ "$x" = "$1" ]] && echo -1 || echo "${#x}"
}

orgpath="$(pwd)"
repository="Your repository name here" # Put the name of your repository in here
pos=$(strindex "$orgpath" "$repository")
length=$pos+${#repository}
rootpath=${orgpath:0:length} # The root path is required for absolute path reference in the following scripts

. ${rootpath}/Build/Hooks/delegator.sh # adjust the path to your liking
```

In the delegator scripts you can then call all the different checks you want to perform. Example of the delegator script:

```bash
#!/bin/bash

# Include scripts containing bash functions for checking your code.
. ${rootpath}/Build/Hooks/filename.sh
. ${rootpath}/Build/Hooks/logging.sh
. ${rootpath}/Build/Hooks/syntax.sh
. ${rootpath}/Build/Hooks/tests.sh

git diff --cached --name-only | while read FILE; do
    # Code checks are performed here and on failure should exit with none-zero codes which abort the commit
done
```

I like to structure my code checks in functions and group them by different purposes which is why I put them in different scripts. Instead of checking all files on every commit I usually only check the changed files which is done with this line:

```bash
git diff --cached --name-only | while read FILE; do
```

The advantage of this is that the pre-commit hook is very fast and doesn't slow down the commit process. For this purpose I also don't perform my unit tests on commits. Commits should be done often and a long test of all unit tests discourages developers from committing. From my perspective it makes more sense to perform the unit tests before the `push` is executed and since I checked all the other things on every commit my `pre-push` hook simply contains a single line which runs my unit tests.

## Sample Pre-Commit Checks

### filename.sh

The filename check confirms that a file only contains ASCII characters.

```bash
#!/bin/bash

isValidFileName() {
    if test $(git diff --cached --name-only --diff-filter=A -z "$1" |
            LC_ALL=C tr -d '[ -~]\0' | wc -c) != 0
    then
        echo 0
        return 0
    fi

    echo 1
    return 1
}
```

The check in the delegator looks like this:

```bash
if [[ $(isValidFileName "$FILE") = 0 ]]; then
    echo -e "\e[1;31m\tInvalid file name '$FILE'.\e[0m" >&2
    exit 1
fi
```

### logging.sh

During development you sometimes log variables and values which are not meant for production. In order to find these accidental logs these two functions can be helpful.

```bash
#!/bin/bash

hasPhpLogging() {
    RESULT=$(grep "var_dump(" "$1")
    if [ ! -z $RESULT ]; then
        echo 1
        return 1
    fi

    echo 0
    return 0
}

hasJsLogging() {
    RESULT=$(grep "console.log(" "$1")
    if [ ! -z $RESULT ]; then
        echo 1
        return 1
    fi

    echo 0
    return 0
}
```

The check in the delegator looks like this:

```bash
if [[ "$FILE" =~ ^.+(php)$ ]] && [[ $(hasPhpLogging "$FILE") = 1 ]]; then
    echo -e "\e[1;33m\tWarning, the commit contains a call to var_dump() in '$FILE'. Commit was not aborted, however.\e[0m" >&2
fi

if [[ "$FILE" =~ ^.+(js)$ ]] && [[ $(hasJsLogging "$FILE") = 1 ]]; then
    echo -e "\e[1;33m\tWarning, the commit contains a call to console.log()
fi
```

These checks are only performed on `.php` and `.js` files respectively. Please note that these checks will not abort the commit (no `exit 1` is specified) since I prefer them to only be warnings instead of errors.

### syntax.sh

For php I perform three checks: linting, phpcs and phpmd. For phpcs and phpmd you of course have to install them and create the corresponding configuration files. 

```bash
#!/bin/bash

hasInvalidPhpSyntax() {
    # php lint
    $(php -l "$1" > /dev/null)
    if [[ $? != 0 ]]; then
        echo 1
        return 1
    fi

    # phpcs
    $(php -d memory_limit=4G ${rootpath}/vendor/bin/phpcs --standard="${rootpath}/Build/Config/phpcs.xml" --encoding=utf-8 -n -p "$1" > /dev/null)
    if [[ $? != 0 ]]; then
        echo 2
        return 2
    fi

    # phpmd
    $(php -d memory_limit=4G ${rootpath}/vendor/bin/phpmd "$1" text ${rootpath}/Build/Config/phpmd.xml --exclude *tests* --minimumpriority 1 > /dev/null)
    if [[ $? != 0 ]]; then
        echo 3
        return 3
    fi

    echo 0
    return 0
}
```

In the delegator the following code has to be implemented:

```bash
if [[ "$FILE" =~ ^.+(php)$ ]]; then
    PHP_SYNTAX=$(hasInvalidPhpSyntax "$FILE")

    if [[ $PHP_SYNTAX = 1 ]]; then
        echo -e "\e[1;31m\tPhp linting error.\e[0m" >&2
        exit 1
    fi

    if [[ $PHP_SYNTAX = 2 ]]; then
        echo -e "\e[1;31m\tCode Sniffer error.\e[0m" >&2
        exit 1
    fi

    if [[ $PHP_SYNTAX = 3 ]]; then
        echo -e "\e[1;31m\tMess Detector error.\e[0m" >&2
        exit 1
    fi
fi
```

For my bash scripts I use bash to validate them.

```bash
isValidBashScript() {
    bash -n "$1" 1> /dev/null
    if [ $? -ne 0 ]; then
        echo 0
        return 0
    fi

    echo 1
    return 1
}
```

In the delegator the following code has to be implemented:

```bash
if [[ "$FILE" =~ ^.+(sh)$ ]] && [[ $(isValidBashScript "$FILE") = 0 ]]; then
    echo -e "\e[1;31m\tBash linting error in '$FILE'.\e[0m" >&2
    exit 1
fi
```

Some additional general checks I perform are that a line doesn't end with a whitespace and no tabs are used.

```bash
hasInvalidBasicSyntax() {
    # Check whitespace end of line in code
    if [[ -n $(grep -P ' $' "$1") ]]; then
        echo 1
        return 1
    fi

    # Check for tabs
    if [[ -n $(grep -P '\t' "$1") ]]; then
        echo 2
        return 2
    fi

    echo 0
    return 0
}
```

In the delegator the following code has to be implemented:

```bash
if [[ "$FILE" =~ ^.+(sh|js|php|json|css)$ ]]; then
    GEN_SYNTAX=$(hasInvalidBasicSyntax "$FILE")

    if [[ $GEN_SYNTAX = 1 ]]; then
        echo -e "\e[1;31m\tFound whitespace at end of line in $FILE.\e[0m" >&2
        grep -P ' $' $FILE >&2
        exit 1
    fi

    if [[ $GEN_SYNTAX = 2 ]]; then
        echo -e "\e[1;31m\tFound tab instead of whitespace $FILE.\e[0m" >&2
        grep -P '\t' $FILE >&2
        exit 1
    fi
fi
```

### tests.sh

As described above unit tests may take too long to run for a simple commit. Static analysis on the other hand on single files can be performed very quick which allows us to run at least them on a commit basis.

```bash
#!/bin/bash

isPhanTestSuccessful() {
    php -d memory_limit=4G ${rootpath}/vendor/bin/phan -k ${rootpath}/Build/Config/phan.php -f "$1"
    if [ $? -ne 0 ]; then
        echo 0
        return 0
    fi

    echo 1
    return 1
}

isPhpStanTestSuccessful() {
    php -d memory_limit=4G ${rootpath}/vendor/bin/phpstan analyse --autoload-file=${rootpath}/phpOMS/Autoloader.php -l 7 -c ${rootpath}/Build/Config/phpstan.neon "$1"
    if [ $? -ne 0 ]; then
        echo 0
        return 0
    fi

    echo 1
    return 1
}
```

In the delegator the following code has to be implemented:

```bash
if [[ "$FILE" =~ ^.+(php)$ ]] && [[ $(isPhanTestSuccessful "$FILE") = 0 ]]; then
    echo -e "\e[1;31m\tPhan error.\e[0m" >&2
    exit 1
fi

if [[ "$FILE" =~ ^.+(php)$ ]] && [[ $(isPhpStanTestSuccessful "$FILE") = 0 ]]; then
    echo -e "\e[1;31m\tPhp stan error.\e[0m" >&2
    exit 1
fi
```

## Additional Pre-Commit Checks

Additional pre-commit checks could be:

* Spell checking for commit messages
* Check if html tags are implemented correctly (e.g. images must have a alt attribute, inline styles are not allowed etc..)
* JavaScript linting
* CSS linting
* Project specific checks (e.g. no hard coded localization, only allow svg and png images, every model needs to have a unit test, ...)

### Some Snippets For Html

The following snippets can be used to check correct html tag implementation.

Alt attribute for images is required:

```bash
if [[ -n $(grep -P '(\<img)((?!.*?alt=).)*(>)' "$1") ]]; then
    echo 1
    return 1
fi
```

Input elements must have a type attribute:

```bash
if [[ -n $(grep -P '(<input)((?!.*?type=).)*(>)' "$1") ]]; then
    echo 1
    return 1
fi
```

Inline CSS is invalid:

```bash
if [[ -n $(grep -P '(style=)' "$1") ]]; then
    echo 1
    return 1
fi
```
